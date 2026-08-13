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
    // Period qualifiers are stripped so the same tier billed on different invoice
    // periods (e.g. "Startup", "Startup Half", "Startup Quaterly") collapses to one
    // card whose Quarterly/Half-Yearly/Yearly prices come from each variant.
    // Multi-word phrases are listed before the single words they contain.
    $remove = [
        'yearly', 'annually', 'annual', 'per year', 'six months', 'six month',
        '6 months', '6 month', '6-month', 'half yearly', 'half-yearly',
        'semi annual', 'semi-annual', 'semiannual', 'monthly',
        'four months', 'four month', '4 months', '4 month', '4-month',
        'three months', 'three month', '3 months', '3 month', '3-month',
        'quarterly', 'quaterly', 'quarter', 'quater', 'half',
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

/* ------------------------------------------------------------------- offers */

/**
 * Price after a sale offer is applied.
 *
 * The SaaS sends the offer definition (percent or flat) rather than a finished price so
 * the same discount can be applied to the per-user figure the cards actually display.
 *
 * @param array $offer  Offer payload from the SaaS API
 * @param float $amount List price
 * @return float
 */
function ho_offer_apply(array $offer, $amount)
{
    $amount = (float) $amount;
    if ($amount <= 0) {
        return 0.0;
    }

    $value  = (float) ($offer['discount_value'] ?? 0);
    $saving = (($offer['discount_type'] ?? 'percent') === 'flat') ? $value : $amount * ($value / 100);
    $saving = max(0.0, min($amount, $saving));

    return $amount - $saving;
}

/**
 * Trim a SaaS offer payload down to what the pricing page renders, and translate its
 * plan groups into the website's product keys (hims/lims/cims).
 *
 * @param array $offer
 * @return array
 */
function ho_offer_view(array $offer)
{
    $group_keys = [];
    foreach ((array) ($offer['groups'] ?? []) as $group_name) {
        $key = ho_group_key($group_name);
        if ($key !== null) {
            $group_keys[] = $key;
        }
    }

    return [
        'id'             => (int) ($offer['id'] ?? 0),
        'badge'          => (string) ($offer['badge'] ?? 'Limited Time'),
        'headline'       => (string) ($offer['headline'] ?? ''),
        'subtext'        => (string) ($offer['subtext'] ?? ''),
        'discount_type'  => (string) ($offer['discount_type'] ?? 'percent'),
        'discount_value' => (float) ($offer['discount_value'] ?? 0),
        'discount_label' => (string) ($offer['discount_label'] ?? ''),
        'coupon_code'    => (string) ($offer['coupon_code'] ?? ''),
        'urgency_note'   => (string) ($offer['urgency_note'] ?? ''),
        'seats_total'    => isset($offer['seats_total']) ? $offer['seats_total'] : null,
        'seats_left'     => isset($offer['seats_left']) ? $offer['seats_left'] : null,
        'show_banner'    => !empty($offer['show_banner']),
        'show_countdown' => !empty($offer['show_countdown']),
        'theme'          => (string) ($offer['theme'] ?? 'amber'),
        'priority'       => (int) ($offer['priority'] ?? 0),
        'applies_to'     => (string) ($offer['applies_to'] ?? 'all'),
        'cycles'         => array_values((array) ($offer['cycles'] ?? [])),
        'group_keys'     => array_values(array_unique($group_keys)),
        'starts_ts'      => isset($offer['starts_ts']) ? (int) $offer['starts_ts'] : null,
        'ends_ts'        => isset($offer['ends_ts']) ? (int) $offer['ends_ts'] : null,
        'ends_at'        => $offer['ends_at'] ?? null,
    ];
}

/**
 * Pure transform: SaaS package list (+ module name map) -> pricing view-model.
 * Kept IO-free so it can be unit-tested in isolation.
 *
 * @param array  $plans       Decoded /saas/api/plans response (list of packages)
 * @param array  $modulesMap  system_name => friendly label
 * @param string $currency    Currency symbol
 * @param array  $offersFeed  Optional decoded /saas/api/offers response
 * @return array              Payload with ok/currency/generated_at/groups/offers
 */
function ho_build_plans(array $plans, array $modulesMap, $currency, array $offersFeed = [])
{
    // groupKey => tierKey => card scaffold
    $buckets = [];

    // Every sale offer seen, keyed by id: from the dedicated offers feed (which can also
    // announce promotions that discount nothing) and from the per-package offer objects.
    $offers_seen = [];
    foreach ((array) ($offersFeed['offers'] ?? []) as $o) {
        if (is_array($o) && !empty($o['id'])) {
            $offers_seen[(int) $o['id']] = ho_offer_view($o);
        }
    }

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
            $bk = 'year';        // Yearly (12 months)
        } elseif ($months >= 5 && $months <= 7) {
            $bk = 'half';        // Half-Yearly (6 months)
        } elseif ($months >= 3 && $months <= 4) {
            $bk = 'quarter';     // Quarterly (4 months)
        } else {
            continue; // only the quarterly / half-yearly / yearly tabs are supported on the site
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
        // $per_user is the price charged per USER for the WHOLE billing cycle (the figure
        // set in the SaaS admin, e.g. ₹12000/user/year, ₹6000/user/6-months, ₹4000/user/quarter).
        // It is kept as-is so each tab shows that tier's real per-cycle price; the front-end
        // derives the monthly-equivalent and billed totals from it.

        // Feature list for the card, as a flat array of label STRINGS (kept as strings
        // so any version of the front-end renders them — emitting objects can show up
        // as "[object Object]" on an older cached script). Prefer the API's display-ready
        // "features" array: it already uses the public/namesake name instead of the
        // technical module id, is sorted by the admin's order, and has hidden modules
        // removed. Fall back to mapping raw module ids for an older API with no "features".
        // NOTE: the fallback is keyed on the "features" KEY being absent, not on it being
        // empty. A plan may legitimately return an empty list (all modules hidden, or the
        // plan set to show only custom features) and must then show nothing — falling back
        // to the raw module ids would put back exactly what the admin hid.
        $features = [];
        if (array_key_exists('features', $pkg) && is_array($pkg['features'])) {
            foreach ($pkg['features'] as $f) {
                $label = is_array($f) ? trim((string) ($f['name'] ?? '')) : trim((string) $f);
                if ($label === '') {
                    continue;
                }
                // A highlighted line is emitted as an object so the card can emphasise
                // it; everything else stays a plain string.
                $features[] = (is_array($f) && !empty($f['highlight']))
                    ? ['name' => $label, 'highlight' => true]
                    : $label;
            }
        } else {
            foreach ((array) ($pkg['modules'] ?? []) as $m) {
                if ($m === '' || $m === null) {
                    continue; // skip empty module ids
                }
                $label = $modulesMap[$m] ?? ho_prettify($m);
                if ($label !== '') {
                    $features[] = $label;
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
                'price_year'    => null,
                'price_half'    => null,
                'price_quarter' => null,
                // Undiscounted per-user price of each cycle (equals price_* when no offer runs)
                'list_year'     => null,
                'list_half'     => null,
                'list_quarter'  => null,
                // Offer id applied to each cycle, or null
                'offer_year'    => null,
                'offer_half'    => null,
                'offer_quarter' => null,
                'offer'         => null,
                // True when the cycle has its own package (false = copied from another cycle,
                // so it must not be used as a "you save vs shorter cycle" baseline).
                'native_year'    => false,
                'native_half'    => false,
                'native_quarter' => false,
                'slug_year'     => null,
                'slug_half'     => null,
                'slug_quarter'  => null,
                'signup_year'    => null,
                'signup_half'    => null,
                'signup_quarter' => null,
            ];
        }
        $card = &$buckets[$group_key][$tier_key];

        // A running sale offer discounts the per-user price of THIS package (the SaaS has
        // already checked the offer's group/plan/cycle scope), while the untouched list
        // price is kept so the card can strike it through.
        // A PERCENTAGE offer scales the per-user price, exactly as the SaaS scales the
        // invoice. A FLAT offer is a fixed amount off the invoice total, so it must NOT be
        // subtracted from the per-user rate (that would multiply it by the seat count and
        // promise a bigger discount than billing gives); the calculator takes it off the
        // cycle total instead.
        $offer = (isset($pkg['offer']) && is_array($pkg['offer'])) ? $pkg['offer'] : null;
        if ($offer !== null && !empty($offer['id'])) {
            $offer_view = ho_offer_view($offer);
            $offers_seen[(int) $offer['id']] = $offer_view;
            $card['offer_' . $bk] = (int) $offer['id'];
            $card['price_' . $bk] = ($offer_view['discount_type'] === 'flat')
                ? (int) round($per_user)
                : (int) round(ho_offer_apply($offer, $per_user));
        } else {
            $card['price_' . $bk] = (int) round($per_user);
        }
        $card['list_' . $bk]   = (int) round($per_user);
        $card['native_' . $bk] = true;
        $card['slug_' . $bk]   = $pkg['slug'] ?? null;
        $card['signup_' . $bk] = $pkg['signup_url'] ?? null;

        // Shared fields: the yearly package wins, otherwise the first cycle seen fills
        // them in. Tracked with an explicit source marker rather than "description is
        // still empty" — a tier whose yearly package has no description would otherwise
        // let a 6-month/quarterly package overwrite the yearly feature list.
        $shared_src = $card['_shared_src'] ?? '';
        if ($bk === 'year' || $shared_src === '') {
            $card['desc']         = (string) ($pkg['description'] ?? $card['desc']);
            $card['base_users']   = $base;
            $card['features']     = $features;
            $card['priority']     = (int) ($meta['priority'] ?? 0);
            $card['tier']         = ucwords($tier_key);
            $card['_shared_src']  = ($bk === 'year') ? 'year' : 'other';
        }
        unset($card);
    }

    $groups = [];
    foreach (['hims', 'lims', 'cims'] as $gk) {
        if (empty($buckets[$gk])) {
            continue;
        }

        $cards = array_values($buckets[$gk]);

        // Internal bookkeeping key, never part of the payload.
        foreach ($cards as &$c) {
            unset($c['_shared_src']);
        }
        unset($c);

        // Fill any missing billing period from whichever one exists so no tab shows blanks.
        // Preference order for the source: yearly, then half-yearly, then quarterly.
        // These copies are placeholders only: `native_<cycle>` stays false for them and the
        // front-end never sells a non-native cycle — it hides that card, and hides the cycle
        // button entirely once no plan on the panel has its own active package for it.
        foreach ($cards as &$c) {
            $fallback = 'year';
            foreach (['year', 'half', 'quarter'] as $bk) {
                if ($c['price_' . $bk] !== null) {
                    $fallback = $bk;
                    break;
                }
            }
            foreach (['year', 'half', 'quarter'] as $bk) {
                if ($c['price_' . $bk] === null) {
                    $c['price_' . $bk]  = $c['price_' . $fallback];
                    $c['list_' . $bk]   = $c['list_' . $fallback];
                    $c['offer_' . $bk]  = $c['offer_' . $fallback];
                    $c['slug_' . $bk]   = $c['slug_' . $fallback];
                    $c['signup_' . $bk] = $c['signup_' . $fallback];
                }
            }

            // Card-level offer: the yearly one wins, else whichever cycle carries one.
            foreach (['year', 'half', 'quarter'] as $bk) {
                $oid = $c['offer_' . $bk];
                if ($oid !== null && isset($offers_seen[$oid])) {
                    $c['offer'] = $offers_seen[$oid];
                    break;
                }
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

    // Banner offers, strongest first. The front-end picks the one matching the active
    // product tab (an offer with no group_keys applies to every product).
    $banner_offers = [];
    foreach ($offers_seen as $offer_view) {
        if (!empty($offer_view['show_banner'])) {
            $banner_offers[] = $offer_view;
        }
    }
    usort($banner_offers, function ($a, $b) {
        return ($b['priority'] ?? 0) <=> ($a['priority'] ?? 0);
    });

    // Earliest moment the payload stops being accurate (an offer ending), so a cached
    // response is not served past the end of a sale.
    $next_change_ts = null;
    foreach ($offers_seen as $offer_view) {
        if (!empty($offer_view['ends_ts'])) {
            $next_change_ts = $next_change_ts === null
                ? (int) $offer_view['ends_ts']
                : min($next_change_ts, (int) $offer_view['ends_ts']);
        }
    }

    return [
        'ok'             => true,
        'currency'       => $currency,
        'generated_at'   => gmdate('c'),
        // Server clock, re-stamped on every response (even cached ones) so countdowns
        // stay correct on browsers with a skewed clock.
        'now_ts'         => time(),
        'next_change_ts' => $next_change_ts,
        'offers'         => $banner_offers,
        'groups'         => empty($groups) ? new stdClass() : $groups,
    ];
}

// When included by a test harness, expose only the functions above.
if (defined('HO_PLANS_LIB_ONLY')) {
    return;
}

/* --------------------------------------------------------------- config/cache */

$env        = ho_load_env(__DIR__ . '/../.env');
$apiUrl     = $env['HEALTHO_API_URL']     ?? '';
$apiKey     = $env['HEALTHO_API_KEY']     ?? '';
$modulesUrl = $env['HEALTHO_MODULES_URL'] ?? '';
$currency   = $env['HEALTHO_CURRENCY']    ?? '₹';
$ttl        = (int) ($env['HEALTHO_PLANS_CACHE_TTL'] ?? 600);

// Sale offers endpoint. Defaults to the plans URL with the trailing segment swapped, so
// no new .env entry is needed; set HEALTHO_OFFERS_URL to override.
$offersUrl = $env['HEALTHO_OFFERS_URL'] ?? preg_replace('~/plans/?$~', '/offers', $apiUrl);

if ($apiUrl === '' || $apiKey === '') {
    http_response_code(500);
    ho_emit(['ok' => false, 'error' => 'API not configured', 'groups' => new stdClass()]);
}

$cacheFile = sys_get_temp_dir() . '/healtho_plans_' . md5($apiUrl . '|' . $apiKey) . '.json';
$force     = isset($_GET['refresh']);

/**
 * Echo a cached response, re-stamping the server clock and refusing it once a sale
 * inside it has ended. Returns false when the cache must be rebuilt.
 *
 * @param string $file
 * @param bool   $strip_expired When true (last-resort fallback while the API is down),
 *                              expired offers are removed and list pricing restored
 *                              instead of rejecting the cache outright.
 * @return bool
 */
function ho_emit_cached($file, $strip_expired = false)
{
    $raw = @file_get_contents($file);
    if ($raw === false || $raw === '') {
        return false;
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return false;
    }

    // A cached payload must not outlive the offer it advertises.
    $now = time();
    if (!empty($data['next_change_ts']) && (int) $data['next_change_ts'] <= $now) {
        if (!$strip_expired) {
            return false;
        }
        $data = ho_strip_expired_offers($data, $now);
    }

    $data['now_ts'] = $now;
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Remove offers that have ended from a payload and roll the affected cards back to
 * their list prices.
 *
 * @param array $data
 * @param int   $now
 * @return array
 */
function ho_strip_expired_offers(array $data, $now)
{
    $expired = [];
    foreach ((array) ($data['offers'] ?? []) as $offer) {
        if (!empty($offer['ends_ts']) && (int) $offer['ends_ts'] <= $now) {
            $expired[(int) $offer['id']] = true;
        }
    }

    $data['offers'] = array_values(array_filter((array) ($data['offers'] ?? []), function ($offer) use ($now) {
        return empty($offer['ends_ts']) || (int) $offer['ends_ts'] > $now;
    }));

    foreach ((array) ($data['groups'] ?? []) as $gk => $group) {
        foreach ((array) ($group['plans'] ?? []) as $i => $card) {
            foreach (['year', 'half', 'quarter'] as $bk) {
                $oid = $card['offer_' . $bk] ?? null;
                if ($oid !== null && isset($expired[(int) $oid])) {
                    $card['offer_' . $bk] = null;
                    if (isset($card['list_' . $bk])) {
                        $card['price_' . $bk] = $card['list_' . $bk];
                    }
                }
            }
            if (!empty($card['offer']['ends_ts']) && (int) $card['offer']['ends_ts'] <= $now) {
                $card['offer'] = null;
            }
            $data['groups'][$gk]['plans'][$i] = $card;
        }
    }

    $data['next_change_ts'] = null;

    return $data;
}

// Serve fresh cache without hitting the API.
if (!$force && is_readable($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
    ho_emit_cached($cacheFile);
}

/* ------------------------------------------------------------------ fetch API */

$authHeader = ['Authorization: ' . $apiKey, 'Accept: application/json'];

list($plans, $status, $error) = ho_get_json($apiUrl, $authHeader);

// On upstream failure, fall back to stale cache if we have one.
if (!is_array($plans) || (isset($plans['error']))) {
    if (is_readable($cacheFile)) {
        ho_emit_cached($cacheFile, true);
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

// Sale offers (best-effort). Each package already carries the offer that discounts it,
// so a failure here — including an API key without the "offers" permission — only costs
// the announcement banner for promotions that discount nothing.
$offersFeed = [];
if ($offersUrl !== '' && $offersUrl !== $apiUrl) {
    list($offersResponse, , ) = ho_get_json($offersUrl, $authHeader);
    if (is_array($offersResponse) && !empty($offersResponse['offers'])) {
        $offersFeed = $offersResponse;
    }
}

/* --------------------------------------------------------------- transform */

$out     = ho_build_plans($plans, $modulesMap, $currency, $offersFeed);
$encoded = json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// Best-effort cache write (ignore failures on read-only filesystems).
@file_put_contents($cacheFile, $encoded, LOCK_EX);

echo $encoded;
