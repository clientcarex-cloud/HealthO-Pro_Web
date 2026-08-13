<?php
/**
 * partners.php — secret-safe channel-partner proxy for the HealthO Pro marketing site.
 *
 * Mirrors plans.php: the browser fetches this same-origin endpoint ("/partners.php"
 * — note the explicit .php, since "/partners" is the marketing page), and PHP
 * calls the authenticated CCX Partners API on the SaaS master server-side using the
 * key stored in .env, so the key never reaches the client.
 *
 * Actions:
 *   /partners.php?action=directory          → cached public partner directory
 *   /partners.php?action=verify&code=&t=    → live certificate verification (no cache)
 */

header('Content-Type: application/json; charset=utf-8');

/* ------------------------------------------------------------------ helpers */

/** Minimal .env reader (mirror plans.php's plain style). */
function ho_load_env($path)
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

/** GET a URL and json_decode the body. Returns [array|null $json, int $status, string|null $error]. */
function ho_get_json($url, array $headers = [])
{
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
        if ($code >= 400) {
            $msg = is_array($json) && isset($json['error']) ? $json['error'] : ('HTTP ' . $code);
            return [null, $code, $msg];
        }
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

/** Emit JSON and stop. */
function ho_emit(array $payload, $status = 200)
{
    if ($status !== 200) {
        http_response_code($status);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// When included by partners-debug.php, expose only the functions above.
if (defined('HO_PARTNERS_LIB_ONLY')) {
    return;
}

/* --------------------------------------------------------------- config */

$env     = ho_load_env(__DIR__ . '/../.env');
$apiBase = rtrim($env['CCX_PARTNERS_API_URL'] ?? '', '/'); // e.g. https://healtho.pro/ccx_partners_api
$apiKey  = $env['CCX_PARTNERS_API_KEY'] ?? '';
$ttl     = (int) ($env['CCX_PARTNERS_CACHE_TTL'] ?? 600);

if ($apiBase === '' || $apiKey === '') {
    ho_emit(['ok' => false, 'error' => 'API not configured'], 500);
}

$authHeader = ['Authorization: ' . $apiKey, 'Accept: application/json'];
$action     = $_GET['action'] ?? 'directory';

/* ------------------------------------------------------------------ verify */

if ($action === 'verify') {
    $code  = trim((string) ($_GET['code'] ?? ''));
    $token = trim((string) ($_GET['t'] ?? ''));

    if ($code === '' || !preg_match('/^[A-Za-z0-9\-]{4,40}$/', $code)) {
        ho_emit(['ok' => false, 'error' => 'Invalid certificate code'], 400);
    }
    if ($token !== '' && !preg_match('/^[a-f0-9]{10,64}$/i', $token)) {
        $token = '';
    }

    header('Cache-Control: no-store');

    $url = $apiBase . '/verify?code=' . rawurlencode($code) . ($token !== '' ? '&t=' . rawurlencode($token) : '');
    list($json, $status, $error) = ho_get_json($url, $authHeader);

    if (!is_array($json)) {
        ho_emit(['ok' => false, 'error' => $error ?: 'Verification service unavailable'], 502);
    }

    ho_emit($json);
}

/* --------------------------------------------------------------- directory */

header('Cache-Control: public, max-age=300');

$cacheFile = sys_get_temp_dir() . '/healtho_partners_' . md5($apiBase . '|' . $apiKey) . '.json';
$force     = isset($_GET['refresh']);

if (!$force && is_readable($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
    echo file_get_contents($cacheFile);
    exit;
}

list($json, $status, $error) = ho_get_json($apiBase . '/directory', $authHeader);

if (!is_array($json) || empty($json['ok'])) {
    // On upstream failure, fall back to stale cache if we have one.
    if (is_readable($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
    ho_emit(['ok' => false, 'error' => $error ?: ($json['error'] ?? 'Unable to load partners'), 'partners' => []], 502);
}

$encoded = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@file_put_contents($cacheFile, $encoded, LOCK_EX);

echo $encoded;
