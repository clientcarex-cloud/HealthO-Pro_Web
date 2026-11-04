<?php
/**
 * Live prices for the Add-on Services page (SMS, WhatsApp, Email, AI Call credits
 * and the one-time setup services).
 *
 * Source: the CRM's keyless public endpoint that the in-app "Recharge Credits"
 * window also uses, so the website and the app always show the same prices.
 * Prices are set ONLY in the CRM (ccx_msgs pricing) — this page is a mirror.
 *
 * The response is cached for ADDONS_CACHE_TTL seconds. If the CRM cannot be
 * reached, the last good copy is used instead of an empty page.
 * Add ?refresh=1 to the page URL to rebuild the cache.
 */

require_once __DIR__ . '/site.php';

const ADDONS_PLANS_URL = 'https://healtho.pro/ccx_msgs/ccx_msgs_public/get_pricing_plans';
const ADDONS_CACHE_TTL = 600;
const ADDONS_CURRENCY  = '₹';

/** Channels shown, in order — the same four as the in-app Recharge window. */
const ADDONS_CHANNELS = [
    'sms'      => ['label' => 'SMS',      'colour' => '#2563EB', 'icon' => 'sms'],
    'whatsapp' => ['label' => 'WhatsApp', 'colour' => '#16A34A', 'icon' => 'whatsapp'],
    'email'    => ['label' => 'Email',    'colour' => '#D97706', 'icon' => 'email'],
    'aicall'   => ['label' => 'AI Call',  'colour' => '#9333EA', 'icon' => 'aicall'],
];

/** Plain-words meaning of the two message types. */
const ADDONS_TYPES = [
    'transactional' => [
        'tag'    => 'Transactional',
        'title'  => 'Service updates',
        'colour' => '#0F7A66',
        'lead'   => 'Information the patient expects from you.',
        'eg'     => 'OTPs, report ready, bills and payment receipts, appointment reminders.',
        'sms'    => 'Delivered any time, even to DND numbers.',
        'wa'     => 'Sent as WhatsApp Utility templates.',
    ],
    'promotional' => [
        'tag'    => 'Promotional',
        'title'  => 'Marketing',
        'colour' => '#7C3AED',
        'lead'   => 'Messages that promote your hospital.',
        'eg'     => 'Offers, health camps, check-up packages, festival wishes.',
        'sms'    => 'Daytime only, and not delivered to DND numbers.',
        'wa'     => 'Sent as WhatsApp Marketing templates.',
    ],
];

/** One-time setup services, keyed by the plan's message_subtype. */
const ADDONS_SETUP = [
    'dlt_setup' => [
        'channel'  => 'SMS',
        'summary'  => 'DLT registration',
        'who'      => 'Needed once before any SMS can be sent in India.',
        'features' => ['Entity + sender ID registration', 'Template approvals', 'Done for you by our team'],
    ],
    'wa_setup' => [
        'channel'  => 'WhatsApp',
        'summary'  => 'API setup + Blue Tick',
        'who'      => 'For the official WhatsApp Business API on your own number.',
        'features' => ['Official API + number onboarding', 'Display name and templates', 'Blue Tick (verified badge) process'],
    ],
];

/* ------------------------------------------------------------------ fetch */

function ho_addons_get($url)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_CONNECTTIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false || $code >= 400) {
            return null;
        }
    } else {
        $ctx  = stream_context_create(['http' => ['timeout' => 12, 'ignore_errors' => true]]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body === false) {
            return null;
        }
    }

    $json = json_decode($body, true);

    return is_array($json) && !empty($json['success']) && isset($json['plans']) && is_array($json['plans'])
        ? $json['plans']
        : null;
}

/** Raw plan rows from the CRM, cached; stale cache on failure; [] when nothing is available. */
function ho_addons_plans($force = false)
{
    $cache = sys_get_temp_dir() . '/healtho_addons_' . md5(ADDONS_PLANS_URL) . '.json';

    if (!$force && is_readable($cache) && (time() - filemtime($cache) < ADDONS_CACHE_TTL)) {
        $cached = json_decode((string) file_get_contents($cache), true);
        if (is_array($cached)) {
            return $cached;
        }
    }

    $plans = ho_addons_get(ADDONS_PLANS_URL);

    if ($plans === null) {
        $cached = is_readable($cache) ? json_decode((string) file_get_contents($cache), true) : null;
        return is_array($cached) ? $cached : [];
    }

    @file_put_contents($cache, json_encode($plans, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);

    return $plans;
}

/* ----------------------------------------------------------------- format */

/** Indian digit grouping: 110000 → 1,10,000. Decimals only when they are not zero (or $dec forces them). */
function ho_inr($amount, $dec = null)
{
    $amount = (float) $amount;
    if ($dec === null) {
        $dec = abs($amount - round($amount)) < 0.005 ? 0 : 2;
    }
    $fixed = number_format(abs($amount), $dec, '.', '');
    $parts = explode('.', $fixed);
    $int   = $parts[0];
    if (strlen($int) > 3) {
        $last = substr($int, -3);
        $rest = substr($int, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        $int  = $rest . ',' . $last;
    }

    return ($amount < 0 ? '-' : '') . ADDONS_CURRENCY . $int . (isset($parts[1]) ? '.' . $parts[1] : '');
}

/** Indian grouping for plain counts (credits). */
function ho_count($n)
{
    return substr(ho_inr((int) $n, 0), strlen(ADDONS_CURRENCY));
}

/* ------------------------------------------------------------------ shape */

/** One price card's view-model. */
function ho_addons_card(array $p)
{
    $cycles = ['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly'];

    $price = (float) ($p['price'] ?? 0);
    $disc  = (float) ($p['discount_percent'] ?? 0);
    $fin   = $price - $price * $disc / 100;
    $taxP  = (float) ($p['tax_percent'] ?? 0);
    $count = (int) ($p['message_count'] ?? 0);
    $days  = (int) ($p['expiry_days'] ?? 0);

    // Two decimals, same as the app. A small discount can round to the same
    // figure, and then a struck-through twin is just noise.
    $rate    = ho_inr($count ? $fin / $count : 0, 2);
    $wasRate = $disc > 0 && $count ? ho_inr($price / $count, 2) : $rate;

    return [
        'name'     => (string) ($p['plan_name'] ?? ''),
        'cycle'    => $cycles[$p['billing_cycle'] ?? 'monthly'] ?? 'Monthly',
        'rate'     => $rate,
        'was_rate' => $wasRate !== $rate ? $wasRate : '',
        'credits'  => ho_count($count),
        'days'     => $days,
        'per_day'  => $count && $days ? ho_count((int) round($count / $days)) : '',
        'was'      => $disc > 0 ? ho_inr($price) : '',
        'pay'      => ho_inr($fin),
        'tax'      => $taxP > 0 ? '+ ' . rtrim(rtrim(number_format($taxP, 2), '0'), '.') . '% ' . ($p['tax_name'] ?: 'tax') : '',
        'total'    => $taxP > 0 ? ho_inr($fin + $fin * $taxP / 100) : '',
        'save'     => $disc > 0 ? ho_inr($price - $fin) . ' (' . rtrim(rtrim(number_format($disc, 2), '0'), '.') . '% off)' : '',
        'disc'     => $disc,
        'offer'    => trim((string) ($p['offer_description'] ?? '')),
        'best'     => false,
    ];
}

/** Marks the deepest discount in a set as Best Value — the app's rule. */
function ho_addons_best(array $cards)
{
    $best = -1;
    $bestDisc = 0;
    foreach ($cards as $i => $c) {
        if ($c['disc'] > $bestDisc) {
            $bestDisc = $c['disc'];
            $best = $i;
        }
    }
    if (count($cards) > 1 && $best >= 0) {
        $cards[$best]['best'] = true;
    }

    return $cards;
}

/**
 * Everything the page renders:
 *   channels[]  label, colour, icon, slug, count, parts[] (type meta + cards) or cards[]
 *   setup[]     one-time services
 *   split       true when any channel sells both message types
 */
function ho_addons_catalog($force = false)
{
    $rows  = ho_addons_plans($force);
    $order = ['monthly' => 1, 'quarterly' => 2, 'yearly' => 3];

    $active = array_values(array_filter($rows, static function ($p) {
        return !isset($p['active']) || (string) $p['active'] === '1';
    }));

    $out = ['channels' => [], 'setup' => [], 'split' => false];

    foreach (ADDONS_CHANNELS as $type => $meta) {
        $list = [];
        foreach ($active as $p) {
            if (($p['message_type'] ?? '') !== $type) {
                continue;
            }
            $sub = $p['message_subtype'] ?: 'promotional';
            if (isset(ADDONS_SETUP[$sub])) {
                $card = ho_addons_card($p);
                $out['setup'][] = $card + ADDONS_SETUP[$sub] + ['subtype' => $sub];
                continue;
            }
            $p['message_subtype'] = $sub;
            $list[] = $p;
        }
        if (!$list) {
            continue;
        }

        usort($list, static function ($a, $b) use ($order) {
            return (($order[$a['billing_cycle'] ?? 'monthly'] ?? 9) <=> ($order[$b['billing_cycle'] ?? 'monthly'] ?? 9))
                ?: ((float) $a['price'] <=> (float) $b['price']);
        });

        $subs = array_unique(array_column($list, 'message_subtype'));
        $both = in_array('transactional', $subs, true) && in_array('promotional', $subs, true);

        $channel = $meta + [
            'slug'  => $type,
            'count' => count($list),
            'parts' => [],
            'cards' => [],
        ];

        if ($both) {
            $out['split'] = true;
            // Transactional first: it is what almost every clinic needs.
            foreach (['transactional', 'promotional'] as $st) {
                $set = array_values(array_filter($list, static function ($p) use ($st) {
                    return $p['message_subtype'] === $st;
                }));
                $t = ADDONS_TYPES[$st];
                $note = $type === 'sms' ? $t['sms'] : ($type === 'whatsapp' ? $t['wa'] : '');
                $channel['parts'][] = [
                    'key'    => $st,
                    'tag'    => $t['tag'],
                    'colour' => $t['colour'],
                    'desc'   => trim($t['title'] . ': ' . rtrim($t['eg'], '.') . '. ' . $note),
                    'cards'  => ho_addons_best(array_map('ho_addons_card', $set)),
                ];
            }
        } else {
            $channel['cards'] = ho_addons_best(array_map('ho_addons_card', $list));
        }

        $out['channels'][] = $channel;
    }

    return $out;
}

/* ------------------------------------------------------------- PDF data */

/**
 * The live catalog in the shape js/credits-brochure.js draws (the same shape
 * the CRM's "Download Pricing PDF" builds), so the website PDF matches the
 * in-app one card for card. "Prepared" is stamped in the browser at click time.
 */
function ho_addons_brochure(array $catalog)
{
    $link = 'https://healtho.pro/login';

    $card = static function (array $c, bool $split) use ($link) {
        $features = [['text' => 'Valid for ' . (int) $c['days'] . ' days']];
        if ($c['per_day'] !== '') {
            $features[] = ['text' => 'About ' . $c['per_day'] . ' credits a day'];
        }

        return [
            'name'     => $c['name'],
            'kicker'   => $c['cycle'],
            'best'     => (bool) $c['best'],
            'was'      => $c['was_rate'],
            'amount'   => $c['rate'],
            'unit'     => '/ credit',
            'sumLeft'  => $c['credits'] . ' credits',
            'sumRight' => $c['pay'],
            'payLabel' => 'You pay once · ' . (int) $c['days'] . ' days',
            'pay'      => $c['pay'],
            'taxLeft'  => $c['tax'],
            'taxRight' => $c['total'] !== '' ? $c['total'] . ' in total' : '',
            'savings'  => $c['save'] !== '' ? 'You save ' . $c['save'] : '',
            'features' => $features,
            'cta'      => 'Recharge now  →',
            'link'     => $link,
        ];
    };

    $groups = [];
    foreach ($catalog['channels'] as $ch) {
        $g = [
            'label'  => $ch['label'],
            'colour' => $ch['colour'],
            'count'  => $ch['count'] . ' plan' . ($ch['count'] > 1 ? 's' : ''),
        ];
        if ($ch['parts']) {
            $g['parts'] = array_map(static function ($part) use ($card) {
                return [
                    'tag'    => strtoupper($part['tag']),
                    'colour' => $part['colour'],
                    'desc'   => $part['desc'],
                    'plans'  => array_map(static function ($c) use ($card) { return $card($c, true); }, $part['cards']),
                ];
            }, $ch['parts']);
        } else {
            $g['plans'] = array_map(static function ($c) use ($card) { return $card($c, false); }, $ch['cards']);
        }
        $groups[] = $g;
    }
    $labels = array_column($groups, 'label');

    if ($catalog['setup']) {
        $groups[] = [
            'label'  => 'One-Time Setup Services',
            'colour' => '#EA580C',
            'count'  => 'Pay once · not a recharge',
            'setup'  => true,
            'plans'  => array_map(static function ($s) use ($link) {
                return [
                    'name'     => $s['name'],
                    'kicker'   => 'One-time · ' . $s['channel'],
                    'tint'     => true,
                    'best'     => false,
                    'was'      => $s['was'],
                    'amount'   => $s['pay'],
                    'unit'     => 'one-time',
                    'sumLeft'  => $s['summary'],
                    'sumRight' => $s['pay'],
                    'payLabel' => 'You pay once',
                    'pay'      => $s['pay'],
                    'taxLeft'  => $s['tax'],
                    'taxRight' => $s['total'] !== '' ? $s['total'] . ' in total' : '',
                    'savings'  => $s['save'] !== '' ? 'You save ' . $s['save'] : '',
                    'features' => array_map(static function ($t) { return ['text' => $t]; }, $s['features']),
                    'cta'      => 'Get it now  →',
                    'link'     => $link,
                ];
            }, $catalog['setup']),
        ];
    }

    $steps = [['title' => 'Pick the channel', 'text' => implode(', ', $labels) . '. Buy only the ones you use.']];
    if ($catalog['split']) {
        $steps[] = ['title' => 'Pick the message type', 'text' => 'Transactional for patient updates, Promotional for marketing. Most clinics need Transactional; add Promotional only if you run campaigns.'];
    }
    $steps[] = ['title' => 'Pick how long', 'text' => "Monthly, Quarterly or Yearly. Longer plans cost less per credit. Credits expire after the plan's days."];
    if ($catalog['setup']) {
        $steps[] = ['title' => 'New to SMS / WhatsApp?', 'text' => 'Add the one-time setup at the end of this sheet. It is paid only once.'];
    }

    $types = [];
    if ($catalog['split']) {
        foreach (ADDONS_TYPES as $t) {
            $types[] = [
                'tag'    => strtoupper($t['tag']),
                'title'  => $t['title'],
                'colour' => $t['colour'],
                'lines'  => [
                    ['text' => rtrim($t['lead'], '.'), 'bold' => true],
                    ['text' => rtrim($t['eg'], '.')],
                    ['text' => 'SMS: ' . lcfirst(rtrim($t['sms'], '.'))],
                ],
            ];
        }
    }

    return [
        'assets'      => [
            'jspdf' => '/js/vendor/jspdf.umd.min.js?v=' . ASSET_VER,
            'fonts' => '/js/vendor/pjs-fonts.js?v=' . ASSET_VER,
            'logo'  => '/assets/images/logo.png',
        ],
        'product'     => 'Messaging Credits',
        'productLong' => implode(' · ', $labels) . ' recharge plans',
        'meta'        => [['Channels ', (string) count($labels)]],
        'guide'       => ['title' => 'How to choose a plan', 'steps' => $steps, 'types' => $types],
        'groups'      => $groups,
        'site'        => SITE_URL,
        'phone'       => PHONE_SALES,
        'email'       => 'sales@healtho.pro',
        'wa'          => WHATSAPP_URL,
        'note'        => 'Amounts are exclusive of tax unless shown as "in total"; the per-credit rate is before tax. '
            . 'Existing customers can buy any plan from Communication Hub → Recharge Credits in HealthO Pro. '
            . 'Live prices: ' . SITE_URL . '/add-on-services',
    ];
}

/* ------------------------------------------------ WhatsApp copy text */
/*
 * Ready-to-paste WhatsApp messages for the sales team ("Copy for WhatsApp"
 * buttons). WhatsApp formatting: *bold*, _italic_, ~strike~. Built from the
 * same live card data as the page, so the text always quotes current prices.
 */

const ADDONS_PAGE_URL = SITE_URL . '/add-on-services';
const ADDONS_BUY_URL  = 'https://healtho.pro/login';

/** "+ 18% GST = ₹1,504.50" or '' when the plan has no tax. */
function ho_wa_tax(array $c)
{
    return $c['tax'] !== '' ? ' ' . $c['tax'] . ' = ' . $c['total'] : '';
}

/** One plan, written to be sent on its own. */
function ho_wa_plan(array $c, string $channel, string $type, string $slug)
{
    $title = $c['name'] . ($c['name'] !== $c['cycle'] ? ' (' . $c['cycle'] . ')' : '');
    $lines = [
        '*' . $title . '*' . ($c['best'] ? ' ⭐ Best value' : ''),
        '_HealthO Pro · ' . $channel . ($type !== '' ? ' · ' . $type : '') . '_',
        '',
        '💬 Rate: *' . $c['rate'] . '/credit*' . ($c['was_rate'] !== '' ? ' ~' . $c['was_rate'] . '~' : ''),
        '📦 Credits: ' . $c['credits'],
        '💰 You pay: *' . $c['pay'] . '*' . ho_wa_tax($c),
    ];
    if ($c['save'] !== '') {
        $lines[] = '🎉 You save ' . $c['save'];
    }
    $lines[] = '⏳ Valid for ' . (int) $c['days'] . ' days' . ($c['per_day'] !== '' ? ' (about ' . $c['per_day'] . ' credits a day)' : '');
    if ($c['offer'] !== '') {
        $lines[] = '🎁 ' . $c['offer'];
    }
    $lines[] = '';
    $lines[] = '🛒 Buy now: ' . ADDONS_BUY_URL;
    $lines[] = '📄 All ' . $channel . ' plans: ' . ADDONS_PAGE_URL . '#' . $slug;

    return implode("\n", $lines);
}

/** One line per plan inside a channel summary. */
function ho_wa_line(array $c)
{
    return '• ' . $c['cycle'] . ': ' . $c['credits'] . ' credits — *' . $c['pay'] . '* (' . $c['rate'] . '/credit) · '
        . (int) $c['days'] . ' days' . ($c['save'] !== '' ? ' · ' . preg_replace('/^.*\((.*)\)$/', '$1', $c['save']) : '')
        . ($c['best'] ? ' ⭐' : '');
}

/** All plans of one channel, grouped by message type. */
function ho_wa_channel(array $ch)
{
    $lines = ['*HealthO Pro — ' . $ch['label'] . ' credit plans*', '_Live prices · ' . date('j M Y') . '_', ''];

    if ($ch['parts']) {
        foreach ($ch['parts'] as $part) {
            $t = ADDONS_TYPES[$part['key']];
            $lines[] = '*' . strtoupper($part['tag']) . '* — ' . $t['title'] . ' (' . rtrim($t['eg'], '.') . ')';
            foreach ($part['cards'] as $c) {
                $lines[] = ho_wa_line($c);
            }
            $lines[] = '';
        }
    } else {
        foreach ($ch['cards'] as $c) {
            $lines[] = ho_wa_line($c);
        }
        $lines[] = '';
    }

    $lines[] = '_Prices are before 18% GST. Longer plans cost less per credit. ⭐ = best value._';
    if ($ch['slug'] === 'sms') {
        $lines[] = '_New to SMS? One-time DLT setup is needed once — see the link._';
    }
    $lines[] = '';
    $lines[] = '📄 Details: ' . ADDONS_PAGE_URL . '#' . $ch['slug'];
    $lines[] = '📞 ' . PHONE_SALES;

    return implode("\n", $lines);
}

/** One one-time setup service. */
function ho_wa_setup(array $s)
{
    $lines = [
        '*' . $s['name'] . '*',
        '_HealthO Pro · One-time setup · ' . $s['channel'] . '_',
        '',
        $s['who'],
        '',
        '💰 One-time fee: *' . $s['pay'] . '*' . ($s['was'] !== '' ? ' ~' . $s['was'] . '~' : '') . ho_wa_tax($s),
    ];
    foreach ($s['features'] as $f) {
        $lines[] = '✅ ' . $f;
    }
    $lines[] = '';
    $lines[] = 'Paid only once — it is not a recharge.';
    $lines[] = '🛒 Get it: ' . ADDONS_BUY_URL;
    $lines[] = '📄 Details: ' . ADDONS_PAGE_URL . '#setup';

    return implode("\n", $lines);
}

/** All one-time setup services together. */
function ho_wa_setup_all(array $setup)
{
    $lines = ['*HealthO Pro — One-time setup services*', '_Pay once · not a recharge_', ''];
    foreach ($setup as $s) {
        $lines[] = '• *' . $s['name'] . '* (' . $s['channel'] . '): *' . $s['pay'] . '*' . ho_wa_tax($s);
        $lines[] = '   ' . $s['who'];
    }
    $lines[] = '';
    $lines[] = '📄 Details: ' . ADDONS_PAGE_URL . '#setup';
    $lines[] = '📞 ' . PHONE_SALES;

    return implode("\n", $lines);
}
