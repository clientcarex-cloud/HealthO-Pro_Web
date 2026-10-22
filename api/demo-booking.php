<?php
/**
 * demo-booking.php — the demo popup's bridge to the CRM booking page (Pro Sales).
 *
 *   GET  /api/demo-booking.php?from=YYYY-MM-DD&to=YYYY-MM-DD
 *        → { ok, page: {...}, slots: { "2026-10-23": [ {time,label,start,end}, … ] } }
 *   POST /api/demo-booking.php  name, email, phone, slot, company, notes, q0..q9
 *        → { ok, meeting: {...} }  or  { ok:false, error, slot_gone }
 *
 * Calls the CRM's JSON booking API (/book/json/{slug}) server-side, so the
 * browser only ever talks to this site. The CRM re-checks the slot, links the
 * booking to the lead the popup's first step created (matched by e-mail /
 * phone) and sends the calendar invite.
 *
 * Config (.env, both optional):
 *   DEMO_BOOKING_URL   CRM base URL of the booking page, default https://healtho.pro/book
 *   DEMO_BOOKING_SLUG  booking page slug, default "demo"
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function db_env(): array
{
    static $env = null;
    if ($env !== null) return $env;
    $env = [];
    $file = __DIR__ . '/../.env';
    if (is_readable($file)) {
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if ($line[0] === '#' || strpos($line, '=') === false) continue;
            [$k, $v] = array_map('trim', explode('=', $line, 2));
            $env[$k] = trim($v, "\"'");
        }
    }
    return $env;
}

function db_out(array $payload, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/** One request to the CRM. Returns the decoded JSON, or null when it is unreachable. */
function db_call(string $url, ?array $post = null, array $headers = []): ?array
{
    if (!function_exists('curl_init')) return null;
    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER     => array_merge(['Accept: application/json'], $headers),
        CURLOPT_USERAGENT      => 'HealthO-Pro-Website/1.0',
    ];
    if ($post !== null) {
        $opts[CURLOPT_POST]       = true;
        $opts[CURLOPT_POSTFIELDS] = http_build_query($post);
    }
    curl_setopt_array($ch, $opts);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = is_string($body) ? json_decode($body, true) : null;
    if (!is_array($json)) {
        error_log("Demo booking: CRM call failed (HTTP $code) $url: " . substr((string) $body, 0, 200));
        return null;
    }
    return $json;
}

/**
 * Per-IP limits on booking attempts, kept in small files in the temp dir.
 * Bookings are the expensive thing (a host's time + an invite), so they get a
 * tight cap; plain attempts get a looser one to stop scripted hammering.
 *
 * @param bool $record_booking  true after a successful booking
 * @return string  '' when allowed, else the message to show
 */
function db_rate_limit(bool $record_booking = false): string
{
    $ip  = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $dir = sys_get_temp_dir() . '/ho_demo_rl';
    if ($ip === '' || (!is_dir($dir) && !@mkdir($dir, 0700, true))) return '';
    $file = $dir . '/' . sha1('ho-demo|' . $ip) . '.json';
    $now  = time();

    $fh = @fopen($file, 'c+');
    if (!$fh) return '';
    flock($fh, LOCK_EX);
    $log = json_decode(stream_get_contents($fh) ?: '', true) ?: [];
    $tries = array_values(array_filter($log['tries'] ?? [], function ($t) use ($now) { return $t > $now - 3600; }));
    $books = array_values(array_filter($log['books'] ?? [], function ($t) use ($now) { return $t > $now - 86400; }));

    $msg = '';
    if ($record_booking) {
        $books[] = $now;
    } elseif (count($books) >= 2) {
        $msg = 'You have already booked demos from this connection today. Please use the link in your invite e-mail to reschedule, or call us.';
    } elseif (count($tries) >= 12) {
        $msg = 'Too many attempts. Please wait a while and try again.';
    } else {
        $tries[] = $now;
    }

    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode(['tries' => $tries, 'books' => $books]));
    flock($fh, LOCK_UN);
    fclose($fh);

    // Occasionally sweep files nobody has touched for a day.
    if (mt_rand(1, 50) === 1) {
        foreach ((array) glob($dir . '/*.json') as $f) {
            if (@filemtime($f) < $now - 86400) @unlink($f);
        }
    }
    return $msg;
}

$env  = db_env();
$base = rtrim(getenv('DEMO_BOOKING_URL') ?: ($env['DEMO_BOOKING_URL'] ?? '') ?: 'https://healtho.pro/book', '/');
$slug = rawurlencode(getenv('DEMO_BOOKING_SLUG') ?: ($env['DEMO_BOOKING_SLUG'] ?? '') ?: 'demo');

$isDate = function ($s) { return is_string($s) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $s) && strtotime($s); };

/* ---------------------------------------------------------------- slots */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $from = $isDate($_GET['from'] ?? '') ? $_GET['from'] : date('Y-m-d');
    $to   = $isDate($_GET['to'] ?? '') ? $_GET['to'] : date('Y-m-d', strtotime($from . ' +13 days'));
    $qs   = '?' . http_build_query(['from' => $from, 'to' => $to]);

    $res = db_call("$base/json/$slug$qs");

    // Older CRM build without /book/json: fall back to the plain slot feed.
    if ($res === null || (!isset($res['page']) && empty($res['ok']))) {
        $res = db_call("$base/slots/$slug$qs");
    }
    if ($res === null || empty($res['ok'])) {
        db_out(['ok' => false, 'error' => 'Booking is unavailable right now.'], 502);
    }

    // The browser never needs to know which staff member holds a slot.
    $slots = [];
    foreach ((array) ($res['slots'] ?? []) as $date => $list) {
        foreach ((array) $list as $s) {
            $slots[$date][] = ['time' => $s['time'] ?? '', 'label' => $s['label'] ?? '', 'start' => $s['start'] ?? '', 'end' => $s['end'] ?? ''];
        }
    }
    db_out(['ok' => true, 'page' => $res['page'] ?? null, 'slots' => (object) $slots]);
}

/* ---------------------------------------------------------------- book */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    db_out(['ok' => false, 'error' => 'Method not allowed.'], 405);
}

if (($limited = db_rate_limit()) !== '') {
    db_out(['ok' => false, 'rate_limited' => true, 'error' => $limited], 429);
}

$clean = function ($k, $max = 300) {
    return isset($_POST[$k]) ? mb_substr(trim(strip_tags((string) $_POST[$k])), 0, $max) : '';
};

$post = [
    'name'    => $clean('name', 120),
    'email'   => $clean('email', 160),
    'phone'   => $clean('phone', 30),
    'company' => $clean('company', 160),
    'notes'   => $clean('notes', 2000),
    'slot'    => $clean('slot', 25),
    'website' => $clean('website', 100), // honeypot, checked by the CRM
];
for ($i = 0; $i < 10; $i++) {
    if (isset($_POST["q$i"])) $post["q$i"] = $clean("q$i", 1000);
}

if ($post['name'] === '' || !filter_var($post['email'], FILTER_VALIDATE_EMAIL)
    || strlen(preg_replace('/\D+/', '', $post['phone'])) < 7) {
    db_out(['ok' => false, 'error' => 'Please go back and check your name, e-mail and mobile number.'], 400);
}
if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $post['slot'])) {
    db_out(['ok' => false, 'error' => 'Please choose a time slot.'], 400);
}

// Book onto the exact lead step 1 created. The id arrives signed by
// api/contact.php; the API key proves to the CRM that this call is ours.
$apiKey  = $env['PRO_LEADS_API_KEY'] ?? '';
$headers = [];
if ($apiKey !== '') {
    $headers[] = 'X-Api-Key: ' . $apiKey;
    $ref = explode('.', (string) ($_POST['lead_ref'] ?? ''), 2);
    if (count($ref) === 2 && ctype_digit($ref[0])
        && hash_equals(substr(hash_hmac('sha256', 'lead:' . $ref[0], $apiKey), 0, 32), $ref[1])) {
        $post['lead_id'] = (int) $ref[0];
    }
}

$res = db_call("$base/json/$slug", $post, $headers);
if ($res === null) {
    db_out(['ok' => false, 'error' => 'We could not reach our booking system. Please try again in a moment.'], 502);
}
if (!empty($res['ok'])) db_rate_limit(true);
db_out($res, empty($res['ok']) ? 422 : 200);
