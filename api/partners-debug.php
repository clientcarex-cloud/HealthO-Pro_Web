<?php
/**
 * partners-debug.php — TEMPORARY diagnostics for the Channel Partners integration.
 *
 * Shows exactly what the CCX Partners API returns to the proxy, so we can tell
 * whether a blank directory / "Verification unavailable" is a config, routing,
 * auth or cache problem. It NEVER prints the API key.
 *
 * Open:  https://healtho.pro/partners-debug.php?t=ho-debug-2026
 * Optionally test a real certificate: &code=CCXP-2026-00001
 *
 * DELETE THIS FILE once the issue is resolved.
 */

$DEBUG_TOKEN = 'ho-debug-2026';

if (($_GET['t'] ?? '') !== $DEBUG_TOKEN) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Forbidden.\nOpen this page with ?t=" . $DEBUG_TOKEN . "\n";
    exit;
}

// Reuse the proxy's helpers (ho_load_env, ho_get_json).
define('HO_PARTNERS_LIB_ONLY', 1);
require __DIR__ . '/partners.php';

header('Content-Type: text/html; charset=utf-8');

function dbg_e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function dbg_pre($data) {
    echo '<pre style="background:#0b1020;color:#cfe3ff;padding:12px;border-radius:8px;overflow:auto;max-height:380px;font-size:12px;line-height:1.5;">';
    echo dbg_e(is_string($data) ? $data : json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo '</pre>';
}
function dbg_redact($secret) {
    $secret = (string)$secret;
    $len = strlen($secret);
    if ($len === 0) return '(empty)';
    if ($len <= 6) return str_repeat('*', $len) . " (len $len)";
    return substr($secret, 0, 3) . str_repeat('*', max(0, $len - 6)) . substr($secret, -3) . " (len $len)";
}
function dbg_badge($ok, $okText = 'OK', $badText = 'PROBLEM') {
    $bg = $ok ? '#0f9d58' : '#d93025';
    return '<span style="background:' . $bg . ';color:#fff;padding:2px 8px;border-radius:10px;font-size:12px;font-weight:700;">' . dbg_e($ok ? $okText : $badText) . '</span>';
}
/** Raw GET keeping the body even on HTTP errors — shows the 404 HTML etc. */
function dbg_fetch_raw($url, array $headers = []) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 6,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => false,
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $ct   = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $ms   = (int) round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000);
    $err  = curl_error($ch);
    curl_close($ch);
    return [$body === false ? '' : $body, $code, $ct, $ms, $err];
}
function dbg_endpoint($title, $url, $headers) {
    echo '<h2>' . dbg_e($title) . '</h2>';
    echo '<p style="font-size:13px;color:#374151;">GET <code>' . dbg_e($url) . '</code></p>';
    list($body, $code, $ct, $ms, $err) = dbg_fetch_raw($url, $headers);
    $json   = json_decode($body, true);
    $isJson = is_array($json);
    $ok     = $isJson && $code === 200 && !empty($json['ok']);

    echo '<p>HTTP status: <b>' . dbg_e($code ?: 'no response') . '</b> &nbsp; content-type: <code>' . dbg_e($ct ?: '?') . '</code>'
        . ' &nbsp; time: ' . dbg_e($ms) . ' ms &nbsp; ' . dbg_badge($ok, 'WORKING', 'FAILED') . '</p>';
    if ($err)  echo '<p style="color:#b91c1c;font-weight:700;">cURL error: ' . dbg_e($err) . '</p>';

    if ($isJson) {
        dbg_pre($json);
    } else {
        echo '<p style="color:#b91c1c;font-weight:700;">Response is NOT JSON — the URL is reaching a web page, not the API (usually a wrong CCX_PARTNERS_API_URL). First 800 chars:</p>';
        dbg_pre(substr($body, 0, 800));
    }

    // Targeted hints
    if ($code === 401) {
        echo '<p style="color:#b45309;font-weight:700;">401 Unauthorized → routing is CORRECT, but the API key does not match. '
            . 'Copy the key from CRM → CCX Partners → Settings → Website API Key into CCX_PARTNERS_API_KEY in .env on THIS server.</p>';
    } elseif ($code === 404 || !$isJson) {
        echo '<p style="color:#b45309;font-weight:700;">Wrong URL → CCX_PARTNERS_API_URL must be exactly the value shown in CRM → CCX Partners → Settings '
            . '(format: https://healtho.pro/ccx_partners/ccx_partners_api — note the ccx_partners/ prefix).</p>';
    }
    return $ok;
}

echo '<!doctype html><meta name="robots" content="noindex"><meta charset="utf-8">';
echo '<title>Partners debug</title>';
echo '<div style="font-family:system-ui,Segoe UI,Arial,sans-serif;max-width:1000px;margin:24px auto;padding:0 16px;color:#1f2937;">';
echo '<h1 style="margin:0 0 4px;">Channel Partners debug</h1>';
echo '<p style="color:#b91c1c;font-weight:600;margin:0 0 20px;">Temporary diagnostics — delete partners-debug.php when done.</p>';

/* ------------------------------------------------ 1) Config */
$env     = ho_load_env(__DIR__ . '/.env');
$apiBase = rtrim($env['CCX_PARTNERS_API_URL'] ?? '', '/');
$apiKey  = $env['CCX_PARTNERS_API_KEY'] ?? '';
$ttl     = (int) ($env['CCX_PARTNERS_CACHE_TTL'] ?? 600);

echo '<h2>1. Configuration (.env)</h2>';
echo '<table style="border-collapse:collapse;width:100%;font-size:14px;">';
$rows = [
    ['CCX_PARTNERS_API_URL', $apiBase ?: '(empty)', $apiBase !== ''],
    ['CCX_PARTNERS_API_KEY', dbg_redact($apiKey), $apiKey !== ''],
    ['CCX_PARTNERS_CACHE_TTL', $ttl, true],
    ['curl extension', function_exists('curl_init') ? 'available' : 'MISSING', function_exists('curl_init')],
];
foreach ($rows as $r) {
    echo '<tr><td style="padding:6px 10px;border:1px solid #e5e7eb;font-weight:600;">' . dbg_e($r[0]) . '</td>'
        . '<td style="padding:6px 10px;border:1px solid #e5e7eb;">' . dbg_e($r[1]) . '</td>'
        . '<td style="padding:6px 10px;border:1px solid #e5e7eb;">' . dbg_badge($r[2], 'set', 'MISSING') . '</td></tr>';
}
echo '</table>';

if ($apiBase !== '' && strpos($apiBase, '/ccx_partners/ccx_partners_api') === false) {
    echo '<p style="color:#b45309;font-weight:700;">⚠ The URL does not contain <code>/ccx_partners/ccx_partners_api</code> — on this deployment the API '
        . 'routes as module/controller, so the value should look like <code>https://healtho.pro/ccx_partners/ccx_partners_api</code>.</p>';
}

if ($apiBase === '' || $apiKey === '') {
    echo '<p style="color:#b91c1c;font-weight:700;">URL or key missing in .env on this server — the proxy answers "API not configured". Fix .env first, then reload this page.</p>';
    echo '</div>';
    exit;
}

$authHeader = ['Authorization: ' . $apiKey, 'Accept: application/json'];

/* ------------------------------------------------ 2) Directory endpoint */
$dirOk = dbg_endpoint('2. Live API — partner directory', $apiBase . '/directory', $authHeader);

/* ------------------------------------------------ 3) Verify endpoint */
$code     = trim((string) ($_GET['code'] ?? 'CCXP-2026-00001'));
$verifyOk = dbg_endpoint('3. Live API — verify certificate "' . $code . '"', $apiBase . '/verify?code=' . rawurlencode($code), $authHeader);
echo '<p style="font-size:13px;color:#374151;">Test another number: add <code>&code=CCXP-YYYY-NNNNN</code> to this page URL.</p>';

/* ------------------------------------------------ 4) What the browser gets from the proxy */
echo '<h2>4. Same-origin proxy check (what the browser actually calls)</h2>';
$self = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/') . '/partners.php?action=directory&refresh=1';
dbg_endpoint('4a. partners.php?action=directory&refresh=1', $self, []);

/* ------------------------------------------------ 5) Cache */
$cacheFile = sys_get_temp_dir() . '/healtho_partners_' . md5($apiBase . '|' . $apiKey) . '.json';
echo '<h2>5. Proxy cache</h2>';
if (is_readable($cacheFile)) {
    $age = time() - filemtime($cacheFile);
    echo '<p>Cache file: <code>' . dbg_e($cacheFile) . '</code><br>Age: <b>' . dbg_e($age) . 's</b> (TTL ' . dbg_e($ttl) . 's) — '
        . ($age < $ttl ? 'currently served from cache' : 'expired, next request refetches') . '</p>';
    dbg_pre(file_get_contents($cacheFile));
    echo '<p><a href="partners.php?action=directory&refresh=1" target="_blank">→ Force-refresh the proxy cache</a></p>';
} else {
    echo '<p>No cache file yet (' . dbg_e($cacheFile) . ') — nothing stale to worry about.</p>';
}

/* ------------------------------------------------ Verdict */
echo '<h2>Verdict</h2><ul style="font-size:15px;line-height:1.7;">';
echo '<li>Directory API: ' . dbg_badge($dirOk) . '</li>';
echo '<li>Verify API: ' . dbg_badge($verifyOk) . '</li>';
echo '</ul>';
echo '<p style="font-size:14px;color:#374151;">When both show WORKING here but the pages still look empty, it is browser cache — hard-reload '
    . '<code>/partners</code> and <code>/verify-partner</code>. Delete this file once everything is green.</p>';

echo '</div>';
