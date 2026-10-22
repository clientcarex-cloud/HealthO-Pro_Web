<?php
/**
 * HealthO Pro for Ultrasound Reporting — launch (24 Oct 2026, 11:00 AM IST).
 *
 * Shared by the launch page and api/launch-subscribe.php:
 *   - the reveal moment
 *   - the signed "subscribed" cookie that unlocks the reveal
 *   - the Omni Contacts API client (key lives in .env, never in the browser)
 */
require_once __DIR__ . '/site.php';

const LAUNCH_SLUG        = 'HealthO-Pro-Launch-24-oct-2026';
const LAUNCH_AT_DEFAULT  = '2026-10-24T11:00:00+05:30';
const LAUNCH_GROUP       = 'HealthO Pro Launch — 24 Oct 2026';
const LAUNCH_TAG         = 'Launch 24 Oct 2026';
const LAUNCH_COOKIE      = 'ho_launch_access';
/** Seat counter shows only once the list has this many people. */
const LAUNCH_COUNT_MIN   = 25;

/** .env as an array (cached). */
function launch_env(): array
{
    static $env = null;
    if ($env !== null) {
        return $env;
    }
    $env  = [];
    $file = __DIR__ . '/../.env';
    if (is_readable($file)) {
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            $env[$k] = trim($v, "\"'");
        }
    }
    return $env;
}

/** Unix time of the reveal. LAUNCH_AT in .env overrides it (testing only). */
function launch_ts(): int
{
    $over = launch_env()['LAUNCH_AT'] ?? '';
    $ts   = $over !== '' ? strtotime($over) : false;
    return $ts ?: strtotime(LAUNCH_AT_DEFAULT);
}

function launch_is_live(): bool
{
    return time() >= launch_ts();
}

function launch_secret(): string
{
    $env = launch_env();
    return ($env['LAUNCH_COOKIE_SECRET'] ?? '') ?: hash('sha256', 'ho-launch|' . ($env['OMNI_CONTACTS_API_KEY'] ?? ''));
}

/** Last 10 digits — the same matching rule Omni Contacts uses. */
function launch_phone_key(string $phone): string
{
    $d = preg_replace('/\D+/', '', $phone);
    return strlen($d) > 10 ? substr($d, -10) : $d;
}

/** Signed cookie value for a subscriber. */
function launch_cookie_value(string $phone_key, string $first_name): string
{
    $name    = mb_substr(preg_replace('/[|]/', '', $first_name), 0, 40);
    $payload = $phone_key . '|' . time() . '|' . base64_encode($name);
    return base64_encode($payload . '|' . hash_hmac('sha256', $payload, launch_secret()));
}

/** Subscriber from the cookie, or null. ['phone_key', 'name'] */
function launch_subscriber(): ?array
{
    $raw = $_COOKIE[LAUNCH_COOKIE] ?? '';
    if ($raw === '') {
        return null;
    }
    $dec   = base64_decode($raw, true);
    $parts = $dec !== false ? explode('|', $dec) : [];
    if (count($parts) !== 4) {
        return null;
    }
    [$key, $ts, $name, $sig] = $parts;
    if (!hash_equals(hash_hmac('sha256', "$key|$ts|$name", launch_secret()), $sig)) {
        return null;
    }
    return ['phone_key' => $key, 'name' => (string) base64_decode($name)];
}

function launch_set_cookie(string $phone_key, string $first_name): void
{
    setcookie(LAUNCH_COOKIE, launch_cookie_value($phone_key, $first_name), [
        'expires'  => time() + 86400 * 365,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/**
 * Call the Omni Contacts API. Returns [http code, decoded body|null].
 * $method GET sends $data as the query string; POST sends JSON.
 */
function launch_omni(string $method, string $endpoint, array $data = []): array
{
    $env = launch_env();
    $key = $env['OMNI_CONTACTS_API_KEY'] ?? '';
    $url = rtrim(($env['OMNI_CONTACTS_API_URL'] ?? '') ?: 'https://healtho.pro/sms_wa_email/contacts_api/', '/') . '/' . ltrim($endpoint, '/');
    if ($key === '' || !function_exists('curl_init')) {
        return [0, null];
    }
    if ($method === 'GET' && $data) {
        $url .= '?' . http_build_query($data);
    }
    $ch = curl_init($url);
    $opts = [
        CURLOPT_HTTPHEADER     => ['X-Api-Key: ' . $key, 'Accept: application/json', 'Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, $body !== false ? json_decode((string) $body, true) : null];
}

/** Cache file in the system temp dir. */
function launch_cache_path(string $name): string
{
    return rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'ho_launch_' . preg_replace('/[^a-z0-9_]/', '', $name) . '.json';
}

/**
 * How many people are on the launch list (subscribed members of the launch
 * group). Cached for 2 minutes; null when the CRM cannot be reached.
 */
function launch_subscriber_count(bool $fresh = false): ?int
{
    $cache = launch_cache_path('count');
    if (!$fresh && is_file($cache) && filemtime($cache) > time() - 120) {
        $c = json_decode((string) file_get_contents($cache), true);
        return isset($c['n']) ? (int) $c['n'] : null;
    }
    [$code, $res] = launch_omni('GET', 'groups');
    if ($code !== 200 || empty($res['success'])) {
        return null;
    }
    $n = 0;
    foreach ($res['groups'] as $g) {
        if ($g['name'] === LAUNCH_GROUP) {
            $n = (int) $g['subscribed'];
        }
    }
    @file_put_contents($cache, json_encode(['n' => $n]));
    return $n;
}
