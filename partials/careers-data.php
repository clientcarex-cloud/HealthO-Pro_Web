<?php
/**
 * careers-data.php — the openings, read from the CRM's embed endpoint.
 *
 * There is no careers API and no API key anywhere on this website. The CRM's
 * embeddable widget endpoint (careers/careers_embed) serves exactly the same
 * published openings without a credential, which is what the paste-anywhere
 * snippet uses, and it is what this file reads too.
 *
 * Why read it server-side at all when the snippet exists: Google Jobs and the
 * JobPosting / ItemList structured data must find the openings in the HTML.
 * The widget renders after JavaScript, which a crawler may never run. So the
 * pages render the openings themselves and the widget stays the fallback.
 *
 * Used by careers.php (listing) and career.php (one opening). It is a library,
 * not an endpoint: it lives in /partials, which the web server blocks.
 *
 *   ho_careers_jobs()          → published openings + filter facets
 *   ho_careers_job($slug)      → one opening, with its apply form + questions
 *
 * Applications, job alerts and view tracking are posted by the browser straight
 * to the same widget endpoint — see js/career.js and js/careers.js.
 */

require_once __DIR__ . '/site.php';   // CAREERS_EMBED_BASE

/** How long an openings response is reused before the CRM is asked again. */
const CAREERS_CACHE_TTL = 300;

/* ---------------------------------------------------------------- transport */

/** GET a URL and json_decode the body. Returns [array|null, string|null error]. */
function ho_careers_get($url)
{
    $headers = ['Accept: application/json'];

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
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            return [null, $err ?: 'request failed'];
        }

        $json = json_decode($body, true);

        return [$json, $json === null ? 'invalid JSON response' : null];
    }

    $ctx = stream_context_create([
        'http' => ['method' => 'GET', 'header' => implode("\r\n", $headers), 'timeout' => 12, 'ignore_errors' => true],
        'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true],
    ]);

    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) {
        return [null, 'request failed'];
    }

    $json = json_decode($body, true);

    return [$json, $json === null ? 'invalid JSON response' : null];
}

/* ------------------------------------------------------------------- cache */

function ho_careers_cache_file($key)
{
    return sys_get_temp_dir() . '/healtho_careers_' . md5(CAREERS_EMBED_BASE . '|' . $key) . '.json';
}

/**
 * Cached read of the widget endpoint. On a failure a stale cache is served
 * rather than an empty careers page — a recruiter's openings staying visible
 * during a CRM restart matters more than the data being seconds-fresh.
 */
function ho_careers_fetch(array $params = [], $force = false)
{
    $query = $params ? '?' . http_build_query($params) : '';
    $cache = ho_careers_cache_file($query);

    if (!$force && is_readable($cache) && (time() - filemtime($cache) < CAREERS_CACHE_TTL)) {
        $cached = json_decode(file_get_contents($cache), true);
        if (is_array($cached)) {
            return $cached;
        }
    }

    list($json, $error) = ho_careers_get(CAREERS_EMBED_BASE . '/data' . $query);

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

/* ------------------------------------------------------------------- reads */

/** Every published opening, with the facets present in them. */
function ho_careers_jobs($force = false)
{
    $data = ho_careers_fetch([], $force);

    if (empty($data['ok'])) {
        return $data;
    }

    $jobs   = isset($data['jobs']) && is_array($data['jobs']) ? $data['jobs'] : [];
    $facets = isset($data['facets']) && is_array($data['facets']) ? $data['facets'] : [];

    // Work modes are the one facet the endpoint leaves out; rebuild them from
    // the openings rather than dropping the filter.
    $modes = [];
    foreach ($jobs as $job) {
        if (!empty($job['work_mode'])) {
            $modes[$job['work_mode']] = $job['work_mode_label'] ?? $job['work_mode'];
        }
    }

    $facets += ['departments' => [], 'locations' => [], 'types' => []];
    $facets['work_modes'] = $modes;
    $data['facets']       = $facets;

    // The endpoint does not report the job-alert switch. The alerts form posts
    // to that same endpoint, which refuses politely when alerts are off, so the
    // form is shown and the CRM stays the one that decides.
    $data += ['alerts_enabled' => true];

    return $data;
}

/** One opening by slug, with its screening questions and form configuration. */
function ho_careers_job($slug, $track = false)
{
    $slug = trim((string) $slug);

    if ($slug === '') {
        return ['ok' => false, 'found' => false, 'error' => 'Missing job reference'];
    }

    $data = ho_careers_fetch(['slug' => $slug]);

    // The detail page is cached, so the view counter is bumped separately
    // rather than as a side effect of a cached read.
    if ($track && !empty($data['found'])) {
        ho_careers_track($slug);
    }

    return $data + ['found' => false, 'related' => []];
}

/** Fire-and-forget view counter. Never allowed to delay the page. */
function ho_careers_track($slug)
{
    if (!function_exists('curl_init')) {
        return;
    }

    $ch = curl_init(CAREERS_EMBED_BASE . '/track');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => ['slug' => (string) $slug],
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            // Without this every view would be counted against the web server.
            'X-Forwarded-For: ' . ho_careers_visitor_ip(),
        ],
        CURLOPT_TIMEOUT        => 4,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    curl_exec($ch);
    curl_close($ch);
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
