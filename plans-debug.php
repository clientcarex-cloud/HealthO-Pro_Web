<?php
/**
 * plans-debug.php — TEMPORARY diagnostics for the pricing/plans integration.
 *
 * Shows exactly what the SaaS API returns and what the proxy transform produces,
 * so we can tell whether "features not showing" is an API data problem or a
 * rendering/cache problem. It NEVER prints the API key.
 *
 * Open:  https://healtho.pro/plans-debug.php?t=ho-debug-2026
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

// Reuse the proxy's helpers (ho_load_env, ho_get_json, ho_build_plans, ho_prettify).
define('HO_PLANS_LIB_ONLY', 1);
require __DIR__ . '/plans.php';

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

echo '<!doctype html><meta name="robots" content="noindex"><meta charset="utf-8">';
echo '<title>Plans debug</title>';
echo '<div style="font-family:system-ui,Segoe UI,Arial,sans-serif;max-width:1000px;margin:24px auto;padding:0 16px;color:#1f2937;">';
echo '<h1 style="margin:0 0 4px;">Plans / Features debug</h1>';
echo '<p style="color:#b91c1c;font-weight:600;margin:0 0 20px;">Temporary diagnostics — delete plans-debug.php when done.</p>';

/* ------------------------------------------------ 1) Config */
$env        = ho_load_env(__DIR__ . '/.env');
$apiUrl     = $env['HEALTHO_API_URL']     ?? '';
$apiKey     = $env['HEALTHO_API_KEY']     ?? '';
$modulesUrl = $env['HEALTHO_MODULES_URL'] ?? '';
$currency   = $env['HEALTHO_CURRENCY']    ?? '₹';
$ttl        = (int) ($env['HEALTHO_PLANS_CACHE_TTL'] ?? 600);

echo '<h2>1. Configuration (.env)</h2>';
echo '<table style="border-collapse:collapse;width:100%;font-size:14px;">';
$rows = [
    ['HEALTHO_API_URL', $apiUrl ?: '(empty)', $apiUrl !== ''],
    ['HEALTHO_API_KEY', dbg_redact($apiKey), $apiKey !== ''],
    ['HEALTHO_MODULES_URL', $modulesUrl ?: '(empty)', $modulesUrl !== ''],
    ['HEALTHO_CURRENCY', $currency, true],
    ['HEALTHO_PLANS_CACHE_TTL', $ttl, true],
];
foreach ($rows as $r) {
    echo '<tr><td style="padding:6px 10px;border:1px solid #e5e7eb;font-weight:600;">' . dbg_e($r[0]) . '</td>'
        . '<td style="padding:6px 10px;border:1px solid #e5e7eb;">' . dbg_e($r[1]) . '</td>'
        . '<td style="padding:6px 10px;border:1px solid #e5e7eb;">' . dbg_badge($r[2], 'set', 'MISSING') . '</td></tr>';
}
echo '</table>';

if ($apiUrl === '' || $apiKey === '') {
    echo '<p style="color:#b91c1c;font-weight:700;">API URL or key missing in .env — the site cannot load plans. Fix .env first.</p>';
    echo '</div>';
    exit;
}

$authHeader = ['Authorization: ' . $apiKey, 'Accept: application/json'];

/* ------------------------------------------------ 2) Raw plans API */
echo '<h2>2. Live API call — ' . dbg_e($apiUrl) . '</h2>';
$t0 = microtime(true);
list($plans, $status, $error) = ho_get_json($apiUrl, $authHeader);
$ms = round((microtime(true) - $t0) * 1000);

echo '<p>HTTP status: <b>' . dbg_e($status) . '</b> &nbsp; time: ' . dbg_e($ms) . ' ms &nbsp; '
    . dbg_badge(is_array($plans) && !$error, 'reachable', 'FAILED') . '</p>';
if ($error) {
    echo '<p style="color:#b91c1c;font-weight:700;">Error: ' . dbg_e($error) . '</p>';
}

// Locate the actual list of packages (API may wrap it in data/plans).
$list = null;
if (is_array($plans)) {
    if (isset($plans[0])) {
        $list = $plans;
    } elseif (isset($plans['data']) && is_array($plans['data'])) {
        $list = $plans['data'];
    } elseif (isset($plans['plans']) && is_array($plans['plans'])) {
        $list = $plans['plans'];
    }
}

if (!is_array($list)) {
    echo '<p style="color:#b91c1c;font-weight:700;">Could not find a package list in the response. Raw response below:</p>';
    dbg_pre($plans);
    echo '</div>';
    exit;
}

echo '<p>Packages returned: <b>' . count($list) . '</b></p>';

/* ------------------------------------------------ 3) Per-package modules/features summary */
echo '<h2>3. Per-package: modules vs features</h2>';
$anyFeatures = false;
$anyModules  = false;
echo '<table style="border-collapse:collapse;width:100%;font-size:13px;">';
echo '<tr style="background:#f3f4f6;">'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">id</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">name</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">group</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">status / private</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">modules (count)</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">features[] present?</th>'
    . '<th style="padding:6px 8px;border:1px solid #e5e7eb;text-align:left;">feature names</th>'
    . '</tr>';
foreach ($list as $pkg) {
    $pkg      = (array) $pkg;
    $mods     = (array) ($pkg['modules'] ?? []);
    $mods     = array_values(array_filter($mods, function ($m) { return $m !== '' && $m !== null; }));
    $hasFeat  = array_key_exists('features', $pkg) && is_array($pkg['features']);
    $feats    = $hasFeat ? $pkg['features'] : [];
    if (!empty($mods))  $anyModules = true;
    if (!empty($feats)) $anyFeatures = true;

    $featNames = [];
    foreach ((array) $feats as $f) {
        $featNames[] = is_array($f) ? (string) ($f['name'] ?? '(no name)') : (string) $f;
    }

    echo '<tr>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e($pkg['id'] ?? '') . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e($pkg['name'] ?? '') . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e($pkg['plan_group_name'] ?? '(none)') . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e(($pkg['status'] ?? '?') . ' / ' . ($pkg['is_private'] ?? '?')) . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e(count($mods)) . ': ' . dbg_e(implode(', ', $mods)) . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . ($hasFeat ? dbg_badge(true, 'yes (' . count($feats) . ')') : dbg_badge(false, 'no', 'no')) . '</td>'
        . '<td style="padding:6px 8px;border:1px solid #e5e7eb;">' . dbg_e(implode(', ', $featNames)) . '</td>'
        . '</tr>';
}
echo '</table>';

/* ------------------------------------------------ 4) Modules endpoint */
echo '<h2>4. Modules endpoint — ' . dbg_e($modulesUrl ?: '(not configured)') . '</h2>';
$modulesMap = [];
if ($modulesUrl !== '') {
    list($modules, $mStatus, $mErr) = ho_get_json($modulesUrl, $authHeader);
    echo '<p>HTTP status: <b>' . dbg_e($mStatus) . '</b> ' . dbg_badge(is_array($modules) && !$mErr, 'OK', 'FAILED')
        . ($mErr ? ' &nbsp; error: ' . dbg_e($mErr) : '') . '</p>';
    if (is_array($modules)) {
        foreach ($modules as $sys => $m) {
            $modulesMap[$sys] = (is_array($m) && !empty($m['custom_name'])) ? $m['custom_name'] : ho_prettify($sys);
        }
        echo '<p>Modules in catalog: <b>' . count($modulesMap) . '</b></p>';
        dbg_pre(array_slice($modulesMap, 0, 40, true));
    }
} else {
    echo '<p>(HEALTHO_MODULES_URL not set — fallback friendly names use prettified ids.)</p>';
}

/* ------------------------------------------------ 5) Transform output (what the website gets) */
echo '<h2>5. Transformed output (what the cards receive)</h2>';
$out = ho_build_plans($list, $modulesMap, $currency);
foreach (['hims', 'lims', 'cims'] as $gk) {
    $g = $out['groups'][$gk] ?? null;
    echo '<h3 style="margin:14px 0 4px;">' . strtoupper($gk) . '</h3>';
    if (!$g || empty($g['plans'])) {
        echo '<p style="color:#b91c1c;">No cards built for this group.</p>';
        continue;
    }
    foreach ($g['plans'] as $card) {
        $feats = $card['features'] ?? [];
        echo '<div style="border:1px solid #e5e7eb;border-radius:8px;padding:8px 12px;margin:6px 0;">'
            . '<b>' . dbg_e($card['tier'] ?? '') . '</b> — ₹' . dbg_e($card['price_year'] ?? '?') . '/yr-rate, ₹' . dbg_e($card['price_half'] ?? '?') . '/half-rate'
            . ' &nbsp; features: <b>' . count($feats) . '</b>'
            . '<br><span style="color:#374151;font-size:13px;">' . dbg_e(implode(' • ', array_map(function ($f) { return is_array($f) ? ($f['name'] ?? '') : $f; }, $feats))) . '</span>'
            . '</div>';
    }
}

/* ------------------------------------------------ 6) Cache info */
$cacheFile = sys_get_temp_dir() . '/healtho_plans_' . md5($apiUrl . '|' . $apiKey) . '.json';
echo '<h2>6. Proxy cache</h2>';
if (is_readable($cacheFile)) {
    $age = time() - filemtime($cacheFile);
    echo '<p>Cache file exists: <code>' . dbg_e($cacheFile) . '</code><br>Age: <b>' . dbg_e($age) . 's</b> (TTL ' . dbg_e($ttl) . 's) — '
        . ($age < $ttl ? 'still served from cache' : 'expired, will refetch') . '</p>';
    echo '<p>This is what <code>plans.php</code> currently serves to the browser (cached):</p>';
    dbg_pre(file_get_contents($cacheFile));
    echo '<p><a href="plans.php?refresh=1" target="_blank">→ Force-refresh the proxy cache (plans.php?refresh=1)</a></p>';
} else {
    echo '<p>No cache file yet (' . dbg_e($cacheFile) . ').</p>';
}

/* ------------------------------------------------ Verdict */
echo '<h2>Verdict</h2><ul style="font-size:15px;line-height:1.7;">';
echo '<li>API reachable: ' . dbg_badge(is_array($plans) && !$error) . '</li>';
echo '<li>Packages have <code>modules</code> data: ' . dbg_badge($anyModules) . '</li>';
echo '<li>Packages include new <code>features[]</code> (CRM deployed): ' . dbg_badge($anyFeatures, 'yes', 'NO — using fallback') . '</li>';
echo '</ul>';
echo '<p style="font-size:14px;color:#374151;">If <b>features[] = NO</b> but <b>modules = OK</b>, the website falls back to module names (still shows names). '
    . 'If section 5 shows feature names but the live cards are blank, it is a browser/CDN cache of <code>script.js</code> or the proxy cache — hard-reload and use <code>plans.php?refresh=1</code>.</p>';

echo '</div>';
