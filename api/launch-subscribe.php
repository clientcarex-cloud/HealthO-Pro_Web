<?php
/**
 * Launch page sign-up → Omni Messaging Contacts.
 *
 * The visitor's details go to the CRM server-side (API key from .env), into
 * the group LAUNCH_GROUP with launch tags + custom fields, so the team can
 * send the launch-day campaign from Omni Messaging. On success a signed cookie
 * is set; it unlocks the reveal on the launch page from 24 Oct, 11:00 AM IST.
 */
require __DIR__ . '/../partials/launch.php';

date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function out(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    out(405, ['success' => false, 'message' => 'Method not allowed.']);
}

$in = $_POST;
if (!$in) {
    $json = json_decode((string) file_get_contents('php://input'), true);
    $in   = is_array($json) ? $json : [];
}
$val = static fn(string $k, int $max = 150): string => mb_substr(trim(strip_tags((string) ($in[$k] ?? ''))), 0, $max);

// Honeypot: bots fill every field. Pretend it worked.
if ($val('website') !== '') {
    out(200, ['success' => true, 'message' => "You're on the list."]);
}

// Light rate limit: 6 sign-ups per IP per 10 minutes.
$ip    = $_SERVER['REMOTE_ADDR'] ?? '0';
$rl    = launch_cache_path('rl_' . substr(hash('sha256', $ip), 0, 16));
$hits  = is_file($rl) ? array_filter((array) json_decode((string) file_get_contents($rl), true), fn($t) => $t > time() - 600) : [];
if (count($hits) >= 6) {
    out(429, ['success' => false, 'message' => 'Too many attempts. Please try again in a few minutes.']);
}
$hits[] = time();
@file_put_contents($rl, json_encode(array_values($hits)));

$name     = $val('name', 120);
$cc       = preg_replace('/\D+/', '', $val('country_code', 6)) ?: '91';
$phone    = preg_replace('/\D+/', '', $val('phone', 20));
$email    = strtolower($val('email', 150));
$org      = $val('organisation', 150);
$role     = $val('role', 60);
$city     = $val('city', 80);
$volume   = $val('volume', 60);
$mods     = array_values(array_filter(array_map(fn($m) => mb_substr(trim(strip_tags((string) $m)), 0, 30), (array) ($in['modalities'] ?? []))));
$consent  = !empty($in['consent']);

if ($name === '' || mb_strlen($name) < 2) {
    out(422, ['success' => false, 'field' => 'name', 'message' => 'Please enter your name.']);
}
// Local number may be typed with its country code already
if (strlen($phone) > 10 && strpos($phone, $cc) === 0 && strlen($phone) - strlen($cc) >= 7) {
    $phone = substr($phone, strlen($cc));
}
$phone = ltrim($phone, '0');
if (strlen($phone) < 7 || strlen($phone) > 12 || ($cc === '91' && !preg_match('/^[6-9]\d{9}$/', $phone))) {
    out(422, ['success' => false, 'field' => 'phone', 'message' => 'Please enter a valid mobile number.']);
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    out(422, ['success' => false, 'field' => 'email', 'message' => 'Please enter a valid e-mail address.']);
}
if (!$consent) {
    out(422, ['success' => false, 'field' => 'consent', 'message' => 'Please allow us to send you the launch invite.']);
}

$full_phone = $cc . $phone;

// Lead source (captured on the page)
$src = [];
foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'referrer', 'landing_page'] as $k) {
    $v = $val($k, 250);
    if ($v !== '') {
        $src[$k] = $v;
    }
}

$tags = array_merge([LAUNCH_TAG, 'Website'], $role !== '' ? [$role] : [], $mods);
$custom = array_filter([
    'role'          => $role,
    'modalities'    => implode(', ', $mods),
    'monthly_scans' => $volume,
    'subscribed_on' => date('d M Y, h:i A') . ' IST',
] + $src, fn($v) => $v !== '');

$payload = [
    'name'          => $name,
    'phone'         => $full_phone,
    'company'       => $org,
    'city'          => $city,
    'tags'          => $tags,
    'groups'        => [LAUNCH_GROUP],
    'custom_fields' => $custom,
    'notes'         => 'Signed up on the launch page: ' . SITE_URL . '/' . LAUNCH_SLUG,
    'on_duplicate'  => 'update',
];
if ($email !== '') {
    $payload['email'] = $email;
}

[$code, $res] = launch_omni('POST', 'create', $payload);

if (!in_array($code, [200, 201], true) || empty($res['success'])) {
    // An address already used by another contact (phone and e-mail belong to two
    // different people in the CRM) — retry once without the e-mail.
    if ($code === 409 && $email !== '') {
        unset($payload['email']);
        $payload['custom_fields']['email_given'] = $email;
        [$code, $res] = launch_omni('POST', 'create', $payload);
    }
}

if (!in_array($code, [200, 201], true) || empty($res['success'])) {
    error_log('Launch subscribe → Omni Contacts failed (HTTP ' . $code . '): ' . json_encode($res));
    out(502, ['success' => false, 'message' => 'We could not save your details just now. Please try again, or WhatsApp us on ' . PHONE_SALES . '.']);
}

// Greeting name: "Dr Ravi Kumar" → "Dr. Ravi", "Anita Sharma" → "Anita"
$words = preg_split('/\s+/', $name);
$first = $words[0];
if (count($words) > 1 && preg_match('/^(dr|mr|mrs|ms|miss|prof|sri|smt)\.?$/i', $first)) {
    $first = ucfirst(strtolower(rtrim($first, '.'))) . '. ' . $words[1];
}
launch_set_cookie(launch_phone_key($full_phone), $first);
@unlink(launch_cache_path('count'));

out(200, [
    'success'  => true,
    'returning' => ($res['action'] ?? '') === 'updated',
    'name'     => $first,
    'live'     => launch_is_live(),
    'message'  => "You're on the list.",
]);
