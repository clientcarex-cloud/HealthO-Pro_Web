<?php
/**
 * careers.php — secret-safe careers proxy for the HealthO Pro marketing site.
 *
 * Mirrors api/partners.php: the browser talks to this same-origin endpoint and
 * PHP calls the authenticated Careers API on the CRM server-side using the key
 * in .env, so the key never reaches the client. The CRM is the single source of
 * truth for every opening — nothing about a job is hard-coded on this website.
 *
 * Read actions (cached):
 *   /api/careers.php?action=jobs            → published openings + filter facets
 *   /api/careers.php?action=job&slug=…      → one opening, with its apply form
 *   /api/careers.php?action=meta            → departments / types for filters
 *
 * Write actions (never cached):
 *   POST /api/careers.php?action=apply      → application intake (multipart CV)
 *   POST /api/careers.php?action=subscribe  → job alerts
 *   POST /api/careers.php?action=track      → job detail view counter
 *
 * careers.php and career.php include this file with HO_CAREERS_LIB_ONLY defined
 * to render server-side (and get real HTML into Google) instead of fetching.
 */

/* ------------------------------------------------------------------ config */

/** Minimal .env reader (same plain style as plans.php / partners.php). */
function ho_careers_env($path)
{
    $vars = [];
    if (!is_readable($path)) {
        return $vars;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        $key = trim(substr($line, 0, $pos));
        $val = trim(substr($line, $pos + 1));
        if (strlen($val) >= 2
            && (($val[0] === '"' && substr($val, -1) === '"')
                || ($val[0] === "'" && substr($val, -1) === "'"))) {
            $val = substr($val, 1, -1);
        }
        $vars[$key] = $val;
    }

    return $vars;
}

function ho_careers_config()
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $env = ho_careers_env(__DIR__ . '/../.env');

    $config = [
        'base' => rtrim($env['CAREERS_API_URL'] ?? '', '/'),   // e.g. https://crm.healtho.pro/careers/careers_api
        'key'  => $env['CAREERS_API_KEY'] ?? '',
        'ttl'  => (int) ($env['CAREERS_CACHE_TTL'] ?? 300),
    ];

    return $config;
}

function ho_careers_configured()
{
    $config = ho_careers_config();

    return $config['base'] !== '' && $config['key'] !== '';
}

/* ---------------------------------------------------------------- transport */

/** GET a URL and json_decode the body. Returns [array|null, int status, string|null error]. */
function ho_careers_get($url)
{
    $config  = ho_careers_config();
    $headers = ['Authorization: ' . $config['key'], 'Accept: application/json'];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return [null, $code, $err ?: 'request failed'];
        }

        $json = json_decode($body, true);

        return [$json, $code, $json === null ? 'invalid JSON response' : null];
    }

    $ctx = stream_context_create([
        'http' => ['method' => 'GET', 'header' => implode("\r\n", $headers), 'timeout' => 12, 'ignore_errors' => true],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);

    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) {
        return [null, 0, 'request failed'];
    }

    $json = json_decode($body, true);

    return [$json, 200, $json === null ? 'invalid JSON response' : null];
}

/**
 * POST a form (optionally multipart with an uploaded file) upstream.
 * cURL only — a CV upload has no sane stream-wrapper equivalent.
 */
function ho_careers_post($endpoint, array $fields, ?array $file = null)
{
    $config = ho_careers_config();

    if (!function_exists('curl_init')) {
        return [null, 0, 'This server cannot forward the application (cURL unavailable).'];
    }

    if ($file !== null && is_uploaded_file($file['tmp_name'])) {
        $fields['resume'] = new CURLFile(
            $file['tmp_name'],
            $file['type'] ?: 'application/octet-stream',
            $file['name']
        );
    }

    $ch = curl_init($config['base'] . '/' . $endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $fields,
        CURLOPT_HTTPHEADER     => [
            'Authorization: ' . $config['key'],
            'Accept: application/json',
            // The CRM stores this against the application; without it every
            // candidate would look like they applied from the web server.
            'X-Forwarded-For: ' . ho_careers_visitor_ip(),
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        return [null, $code, $err ?: 'request failed'];
    }

    return [json_decode($body, true), $code, null];
}

function ho_careers_visitor_ip()
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (empty($_SERVER[$key])) {
            continue;
        }
        $candidate = trim(explode(',', (string) $_SERVER[$key])[0]);
        if (filter_var($candidate, FILTER_VALIDATE_IP)) {
            return $candidate;
        }
    }

    return '';
}

/* ------------------------------------------------------------------- cache */

function ho_careers_cache_file($key)
{
    $config = ho_careers_config();

    return sys_get_temp_dir() . '/healtho_careers_' . md5($config['base'] . '|' . $config['key'] . '|' . $key) . '.json';
}

/**
 * Cached upstream GET. On an upstream failure a stale cache is served rather
 * than an empty careers page — a recruiter's openings staying visible during a
 * CRM restart matters more than the data being seconds-fresh.
 */
function ho_careers_fetch($endpoint, array $params = [], $force = false)
{
    if (!ho_careers_configured()) {
        return ['ok' => false, 'error' => 'The careers service is not configured yet.'];
    }

    $config = ho_careers_config();
    $query  = $params ? '?' . http_build_query($params) : '';
    $cache  = ho_careers_cache_file($endpoint . $query);

    if (!$force && is_readable($cache) && (time() - filemtime($cache) < $config['ttl'])) {
        $cached = json_decode(file_get_contents($cache), true);
        if (is_array($cached)) {
            return $cached;
        }
    }

    list($json, $status, $error) = ho_careers_get($config['base'] . '/' . $endpoint . $query);

    if (!is_array($json) || empty($json['ok'])) {
        if (is_readable($cache)) {
            $cached = json_decode(file_get_contents($cache), true);
            if (is_array($cached)) {
                return $cached;
            }
        }

        return ['ok' => false, 'error' => $error ?: ($json['error'] ?? 'Openings are being updated — please check back shortly.')];
    }

    @file_put_contents($cache, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);

    return $json;
}

/** Convenience wrappers used by careers.php and career.php. */
function ho_careers_jobs($force = false)
{
    return ho_careers_fetch('jobs', [], $force);
}

function ho_careers_job($slug, $track = false)
{
    if (!ho_careers_configured()) {
        return ['ok' => false, 'found' => false, 'error' => 'The careers service is not configured yet.'];
    }

    // The detail page is cached, so the view counter is bumped by a separate
    // uncached call rather than as a side effect of the cached read.
    $data = ho_careers_fetch('job', ['slug' => $slug]);

    if ($track && !empty($data['found'])) {
        ho_careers_post('track', ['slug' => $slug]);
    }

    return $data;
}

// Included by careers.php / career.php for server-side rendering: expose only
// the functions above, and never emit JSON.
if (defined('HO_CAREERS_LIB_ONLY')) {
    return;
}

/* ------------------------------------------------------------- HTTP surface */

header('Content-Type: application/json; charset=utf-8');

function ho_careers_emit(array $payload, $status = 200)
{
    if ($status !== 200) {
        http_response_code($status);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if (!ho_careers_configured()) {
    ho_careers_emit(['ok' => false, 'error' => 'Careers API not configured', 'jobs' => []], 500);
}

$action = $_GET['action'] ?? 'jobs';
$isPost = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

/* ── Application intake ── */

if ($action === 'apply') {
    header('Cache-Control: no-store');

    if (!$isPost) {
        ho_careers_emit(['ok' => false, 'error' => 'Invalid request.'], 405);
    }

    $fields = [];
    foreach ($_POST as $key => $value) {
        // Screening answers arrive as q_<id>, possibly as checkbox arrays.
        if (is_array($value)) {
            $fields[$key] = implode(', ', array_map('strval', $value));
        } else {
            $fields[$key] = (string) $value;
        }
    }

    $fields['source']     = $fields['source'] ?? 'website';
    $fields['user_agent'] = substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    $fields['visitor_ip'] = ho_careers_visitor_ip();

    $file = (isset($_FILES['resume']) && !empty($_FILES['resume']['name'])) ? $_FILES['resume'] : null;

    if ($file !== null && !empty($file['error'])) {
        ho_careers_emit(['ok' => false, 'error' => 'Your file did not upload completely. Please try again.'], 422);
    }

    list($json, $status, $error) = ho_careers_post('apply', $fields, $file);

    if (!is_array($json)) {
        // The transport error names our infrastructure, so it goes to the log,
        // never to the candidate.
        error_log('careers apply failed: ' . ($error ?: 'HTTP ' . $status));
        ho_careers_emit(['ok' => false, 'error' => 'We could not submit your application right now. Please try again in a moment, or email your CV to sales@healtho.pro.'], 502);
    }

    ho_careers_emit($json, $status >= 400 ? $status : 200);
}

/* ── Job alerts ── */

if ($action === 'subscribe') {
    header('Cache-Control: no-store');

    if (!$isPost) {
        ho_careers_emit(['ok' => false, 'error' => 'Invalid request.'], 405);
    }

    list($json, $status, $error) = ho_careers_post('subscribe', [
        'email'       => (string) ($_POST['email'] ?? ''),
        'name'        => (string) ($_POST['name'] ?? ''),
        'departments' => (string) ($_POST['departments'] ?? ''),
        'job_types'   => (string) ($_POST['job_types'] ?? ''),
        'company_website' => (string) ($_POST['company_website'] ?? ''),
    ]);

    if (!is_array($json)) {
        error_log('careers subscribe failed: ' . ($error ?: 'HTTP ' . $status));
        ho_careers_emit(['ok' => false, 'error' => 'Could not save your subscription right now. Please try again shortly.'], 502);
    }

    ho_careers_emit($json, $status >= 400 ? $status : 200);
}

/* ── View tracking ── */

if ($action === 'track') {
    header('Cache-Control: no-store');
    ho_careers_post('track', ['slug' => (string) ($_POST['slug'] ?? $_GET['slug'] ?? '')]);
    ho_careers_emit(['ok' => true]);
}

/* ── Reads ── */

header('Cache-Control: public, max-age=120');

$force = isset($_GET['refresh']);

if ($action === 'job') {
    $slug = trim((string) ($_GET['slug'] ?? ''));

    if ($slug === '' || !preg_match('/^[a-z0-9\-]{2,191}$/i', $slug)) {
        ho_careers_emit(['ok' => false, 'error' => 'Invalid job reference'], 400);
    }

    ho_careers_emit(ho_careers_fetch('job', ['slug' => $slug], $force));
}

if ($action === 'meta') {
    ho_careers_emit(ho_careers_fetch('meta', [], $force));
}

ho_careers_emit(ho_careers_fetch('jobs', [], $force));
