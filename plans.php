<?php
/**
 * plans.php — secret-safe pricing proxy for the HealthO Pro marketing site.
 *
 * The browser fetches this endpoint (same-origin, reachable as "/plans" thanks to the
 * .htaccess .php rewrite). It calls the authenticated SaaS API server-side using the API
 * key stored in .env, so the key never reaches the client. The raw SaaS package list is
 * transformed into the view-model the pricing cards expect, grouped by HIMS / LIMS / CIMS,
 * with the yearly and 6-month variants of each tier paired into a single card.
 *
 * Pricing is therefore set ONLY in the SaaS admin — this site is a pure mirror.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');

/* ------------------------------------------------------------------ helpers */

/** Minimal .env reader (the site has no framework — mirror contact.php's plain style). */
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

    // Fallback when curl is unavailable
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

/** Turn a module system name into a readable label (fallback when no custom name exists). */
function ho_prettify($name)
{
    $name = str_replace(['_', '-'], ' ', (string) $name);
    return ucwords(trim($name));
}

/** Length of a package's billing cycle in months, from the SaaS invoice metadata. */
function ho_period_months($invoice)
{
    $inv = (array) $invoice;
    $recurring = $inv['recurring'] ?? null;

    if ($recurring === 'custom') {
        $type  = $inv['repeat_type_custom'] ?? 'month';
        $every = (int) ($inv['repeat_every_custom'] ?? 1);
        switch ($type) {
            case 'year':  return $every * 12;
            case 'month': return $every;
            case 'week':  return max(1, (int) round($every / 4));
            case 'day':   return max(1, (int) round($every / 30));
            default:      return $every;
        }
    }

    // Non-custom recurring is expressed as a number of months (1, 3, 6, 12, ...)
    return (int) $recurring;
}

/** Map a plan group name to one of the website product keys, or null if not HIMS/LIMS/CIMS. */
function ho_group_key($group_name)
{
    $n = strtolower((string) $group_name);
    foreach (['hims', 'lims', 'cims'] as $k) {
        if (strpos($n, $k) !== false) {
            return $k;
        }
    }
    return null;
}

/** Stable tier key (e.g. "startup") by stripping group/period qualifiers from the package name. */
function ho_tier_key($name, $group_label)
{
    $s = ' ' . strtolower((string) $name) . ' ';
    $s = str_replace(strtolower($group_label), ' ', $s);
    $remove = [
        'yearly', 'annually', 'annual', 'per year', 'six months', 'six month',
        '6 months', '6 month', '6-month', 'half yearly', 'half-yearly',
        'semi annual', 'semi-annual', 'semiannual', 'monthly', 'quarterly',
        'plan', '(', ')', '/', '-',
    ];
    foreach ($remove as $r) {
        $s = str_replace($r, ' ', $s);
    }
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s);
}

/** Emit JSON and stop. */
function ho_emit(array $payload)
{
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Pure transform: SaaS package list (+ module name map) -> pricing view-model.
 * Kept IO-free so it can be unit-tested in isolation.
 *
 * @param array  $plans       Decoded /saas/api/plans response (list of packages)
 * @param array  $modulesMap  system_name => friendly label
 * @param string $currency    Currency symbol
 * @return array              Payload with ok/currency/generated_at/groups
 */
function ho_build_plans(array $plans, array $modulesMap, $currency)
{
    // groupKey => tierKey => card scaffold
    $buckets = [];

    foreach ($plans as $pkg) {
        // Package status is stored as '1' (active) / '0' (inactive); the API docs' "active"
        // string is only illustrative. Accept both forms.
        $status_val = (string) ($pkg['status'] ?? '1');
        $is_active  = ($status_val === '1' || strtolower($status_val) === 'active');
        $is_private = !empty($pkg['is_private']) && (string) $pkg['is_private'] !== '0';
        if (!$is_active || $is_private) {
            continue;
        }

        $group_key = ho_group_key($pkg['plan_group_name'] ?? '');
        if ($group_key === null) {
            continue; // not one of HIMS/LIMS/CIMS (RIS and ungrouped stay manual)
        }

        $meta   = (array) ($pkg['metadata'] ?? []);
        $months = ho_period_months($meta['invoice'] ?? []);
        if ($months >= 12) {
            $bk = 'year';
        } elseif ($months >= 5 && $months <= 7) {
            $bk = 'half';
        } else {
            continue; // only the yearly + 6-month tabs are supported on the site
        }

        $group_label = strtoupper($group_key);
        $tier_key    = ho_tier_key($pkg['name'] ?? '', $group_label);
        if ($tier_key === '') {
            $tier_key = strtolower(trim((string) ($pkg['name'] ?? 'plan')));
        }

        // Base/included seats and per-user price (drives the calculator).
        $limits     = (array) ($meta['limitations'] ?? []);
        $unit_price = (array) ($meta['limitations_unit_price'] ?? []);
        $base       = isset($limits['staff']) ? (int) $limits['staff'] : 0;
        if ($base <= 0) {
            $base = 1; // -1 (unlimited) or unset -> single base seat
        }
        $per_user = isset($unit_price['staff']) ? (float) $unit_price['staff'] : 0.0;
        if ($per_user <= 0) {
            $price    = (float) ($pkg['price'] ?? 0);
            $per_user = $base > 0 ? $price / $base : $price;
        }
        // The unit price is charged per billing cycle; cards show a per-user-per-MONTH rate,
        // so normalise by the cycle length (e.g. a yearly ₹4788/user -> ₹399/user/mo).
        if ($months > 0) {
            $per_user = $per_user / $months;
        }

        // Feature list for the card. Prefer the API's display-ready "features" array:
        // it already uses the public/namesake name instead of the technical module id,
        // is sorted by the admin's order, and has hidden modules removed. Each item may
        // carry a description (shown as a tooltip on the site). Fall back to mapping raw
        // module ids when talking to an older API that has no "features" field.
        $features = [];
        if (!empty($pkg['features']) && is_array($pkg['features'])) {
            foreach ($pkg['features'] as $f) {
                if (is_array($f)) {
                    $name = trim((string) ($f['name'] ?? ''));
                    $desc = trim((string) ($f['description'] ?? ''));
                } else {
                    $name = trim((string) $f);
                    $desc = '';
                }
                if ($name !== '') {
                    $features[] = $desc !== '' ? ['name' => $name, 'desc' => $desc] : ['name' => $name];
                }
            }
        } else {
            foreach ((array) ($pkg['modules'] ?? []) as $m) {
                if ($m === '' || $m === null) {
                    continue; // skip empty module ids
                }
                $label = $modulesMap[$m] ?? ho_prettify($m);
                if ($label !== '') {
                    $features[] = ['name' => $label];
                }
            }
        }

        if (!isset($buckets[$group_key][$tier_key])) {
            $buckets[$group_key][$tier_key] = [
                'tier'        => ucwords($tier_key),
                'desc'        => '',
                'base_users'  => $base,
                'features'    => [],
                'priority'    => 0,
                'price_year'  => null,
                'price_half'  => null,
                'slug_year'   => null,
                'slug_half'   => null,
                'signup_year' => null,
                'signup_half' => null,
            ];
        }
        $card = &$buckets[$group_key][$tier_key];

        $card['price_' . $bk]  = (int) round($per_user);
        $card['slug_' . $bk]   = $pkg['slug'] ?? null;
        $card['signup_' . $bk] = $pkg['signup_url'] ?? null;

        // Shared fields: prefer the yearly package, otherwise take whatever is first.
        if ($bk === 'year' || $card['desc'] === '') {
            $card['desc']       = (string) ($pkg['description'] ?? $card['desc']);
            $card['base_users'] = $base;
            $card['features']   = $features;
            $card['priority']   = (int) ($meta['priority'] ?? 0);
            $card['tier']       = ucwords($tier_key);
        }
        unset($card);
    }

    $groups = [];
    foreach (['hims', 'lims', 'cims'] as $gk) {
        if (empty($buckets[$gk])) {
            continue;
        }

        $cards = array_values($buckets[$gk]);

        // Fill a missing billing period from the other so the toggle never shows blanks.
        foreach ($cards as &$c) {
            if ($c['price_year'] === null) {
                $c['price_year']  = $c['price_half'];
                $c['slug_year']   = $c['slug_half'];
                $c['signup_year'] = $c['signup_half'];
            }
            if ($c['price_half'] === null) {
                $c['price_half']  = $c['price_year'];
                $c['slug_half']   = $c['slug_year'];
                $c['signup_half'] = $c['signup_year'];
            }
        }
        unset($c);

        // Cheapest first; highlight the middle tier as "featured" (matches the original design).
        usort($cards, function ($a, $b) {
            return ($a['price_year'] ?? 0) <=> ($b['price_year'] ?? 0);
        });
        $n = count($cards);
        foreach ($cards as $i => &$c) {
            $c['featured'] = ($n >= 3) ? ($i === (int) floor(($n - 1) / 2)) : ($n === 2 ? $i === 1 : false);
        }
        unset($c);

        $groups[$gk] = [
            'label' => strtoupper($gk),
            'plans' => $cards,
        ];
    }

    return [
        'ok'           => true,
        'currency'     => $currency,
        'generated_at' => gmdate('c'),
        'groups'       => empty($groups) ? new stdClass() : $groups,
    ];
}

// When included by a test harness, expose only the functions above.
if (defined('HO_PLANS_LIB_ONLY')) {
    return;
}

/* --------------------------------------------------------------- config/cache */

$env        = ho_load_env(__DIR__ . '/.env');
$apiUrl     = $env['HEALTHO_API_URL']     ?? '';
$apiKey     = $env['HEALTHO_API_KEY']     ?? '';
$modulesUrl = $env['HEALTHO_MODULES_URL'] ?? '';
$currency   = $env['HEALTHO_CURRENCY']    ?? '₹';
$ttl        = (int) ($env['HEALTHO_PLANS_CACHE_TTL'] ?? 600);

if ($apiUrl === '' || $apiKey === '') {
    http_response_code(500);
    ho_emit(['ok' => false, 'error' => 'API not configured', 'groups' => new stdClass()]);
}

$cacheFile = sys_get_temp_dir() . '/healtho_plans_' . md5($apiUrl . '|' . $apiKey) . '.json';
$force     = isset($_GET['refresh']);

// Serve fresh cache without hitting the API.
if (!$force && is_readable($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
    echo file_get_contents($cacheFile);
    exit;
}

/* ------------------------------------------------------------------ fetch API */

$authHeader = ['Authorization: ' . $apiKey, 'Accept: application/json'];

list($plans, $status, $error) = ho_get_json($apiUrl, $authHeader);

// On upstream failure, fall back to stale cache if we have one.
if (!is_array($plans) || (isset($plans['error']))) {
    if (is_readable($cacheFile)) {
        echo file_get_contents($cacheFile);
        exit;
    }
    http_response_code(502);
    ho_emit([
        'ok'     => false,
        'error'  => $error ?: ($plans['error'] ?? 'Unable to load plans'),
        'groups' => new stdClass(),
    ]);
}

// Friendly feature names (best-effort; failure just falls back to prettified module ids).
$modulesMap = [];
if ($modulesUrl !== '') {
    list($modules, , ) = ho_get_json($modulesUrl, $authHeader);
    if (is_array($modules)) {
        foreach ($modules as $sys => $m) {
            $modulesMap[$sys] = (is_array($m) && !empty($m['custom_name'])) ? $m['custom_name'] : ho_prettify($sys);
        }
    }
}

/* --------------------------------------------------------------- transform */

$out     = ho_build_plans($plans, $modulesMap, $currency);
$encoded = json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Best-effort cache write (ignore failures on read-only filesystems).
@file_put_contents($cacheFile, $encoded, LOCK_EX);

echo $encoded;
