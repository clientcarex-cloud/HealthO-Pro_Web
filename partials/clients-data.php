<?php
/**
 * Client logos shown on /our-clients and the home page "Trusted by" strip.
 * Images live in assets/images/clients/{slug}.webp (trimmed, white background).
 * w/h are the image's pixel size; client_logo_size() uses them to balance logos visually.
 * 'featured' => true puts a logo in the home page marquee.
 */

const CLIENT_CATEGORIES = [
    'hospitals'   => 'Hospitals',
    'diagnostics' => 'Diagnostics & Labs',
    'imaging'     => 'Imaging & Scanning',
    'clinics'     => 'Clinics & Polyclinics',
];

const CLIENTS = [
    ['slug' => 'alfa-diagnostic-centre', 'name' => 'ALFA Diagnostic Centre', 'cat' => 'diagnostics', 'w' => 469, 'h' => 160, 'featured' => true],
    ['slug' => 'aprocare-diagnostics', 'name' => 'Aprocare Diagnostics', 'cat' => 'diagnostics', 'w' => 243, 'h' => 160, 'featured' => false],
    ['slug' => 'ark-digital-diagnostics', 'name' => 'ARK Digital Diagnostics', 'cat' => 'diagnostics', 'w' => 444, 'h' => 160, 'featured' => true],
    ['slug' => 'alekhya-scanning-centre', 'name' => 'Alekhya Scanning Centre', 'cat' => 'imaging', 'w' => 172, 'h' => 160, 'featured' => false],
    ['slug' => 'celabs-diagnostics', 'name' => 'Celabs Diagnostics', 'cat' => 'diagnostics', 'w' => 398, 'h' => 160, 'featured' => true],
    ['slug' => 'fatimah-diagnostics', 'name' => 'Fatimah Diagnostics', 'cat' => 'diagnostics', 'w' => 520, 'h' => 155, 'featured' => true],
    ['slug' => 'fatima-hospitals', 'name' => 'Fatima Hospitals', 'cat' => 'hospitals', 'w' => 114, 'h' => 160, 'featured' => false],
    ['slug' => 'haq-diagnostics', 'name' => 'HAQ Diagnostics', 'cat' => 'imaging', 'w' => 520, 'h' => 128, 'featured' => true],
    ['slug' => 'iris-hospital', 'name' => 'IRIS Hospital', 'cat' => 'hospitals', 'w' => 170, 'h' => 160, 'featured' => false],
    ['slug' => 'iris-diagnostics', 'name' => 'IRIS Diagnostics', 'cat' => 'diagnostics', 'w' => 345, 'h' => 160, 'featured' => true],
    ['slug' => 'lenus-diagnostics', 'name' => 'Lenus Diagnostics', 'cat' => 'diagnostics', 'w' => 478, 'h' => 160, 'featured' => true],
    ['slug' => 'lifeline-diagnostics', 'name' => 'Lifeline Diagnostics', 'cat' => 'diagnostics', 'w' => 190, 'h' => 160, 'featured' => false],
    ['slug' => 'maa-diagnostics', 'name' => 'MAA Diagnostics', 'cat' => 'diagnostics', 'w' => 317, 'h' => 160, 'featured' => true],
    ['slug' => 'max-polyclinic', 'name' => 'MAX Polyclinic & Diagnostic Center', 'cat' => 'clinics', 'w' => 407, 'h' => 160, 'featured' => true],
    ['slug' => 'new-medicure', 'name' => 'New Medicure', 'cat' => 'diagnostics', 'w' => 134, 'h' => 160, 'featured' => false],
    ['slug' => 'mediwell-diagnostic-centre', 'name' => 'Mediwell Diagnostic Centre', 'cat' => 'diagnostics', 'w' => 377, 'h' => 160, 'featured' => true],
    ['slug' => 'muslim-maternity-childrens-hospital', 'name' => 'Muslim Maternity & Children\'s Hospital', 'cat' => 'hospitals', 'w' => 154, 'h' => 160, 'featured' => false],
    ['slug' => 'new-life-hospital', 'name' => 'New Life Hospital', 'cat' => 'hospitals', 'w' => 219, 'h' => 195, 'featured' => true],
    ['slug' => 'padmasri-diagnostics', 'name' => 'Padmasri Diagnostics', 'cat' => 'diagnostics', 'w' => 161, 'h' => 160, 'featured' => true],
    ['slug' => 'surya-diagnostics', 'name' => 'Surya Diagnostics', 'cat' => 'diagnostics', 'w' => 161, 'h' => 160, 'featured' => false],
    ['slug' => 'sv-diagnostic-centre', 'name' => 'SV Diagnostic Centre', 'cat' => 'diagnostics', 'w' => 320, 'h' => 320, 'featured' => true],
    ['slug' => 'swaraj-healthcare', 'name' => 'Swaraj Healthcare', 'cat' => 'clinics', 'w' => 229, 'h' => 160, 'featured' => false],
    ['slug' => 'sri-balaji-diagnostics', 'name' => 'Sri Balaji Diagnostics', 'cat' => 'diagnostics', 'w' => 164, 'h' => 160, 'featured' => false],
    ['slug' => 'suman-diagnostics', 'name' => 'Suman Diagnostics', 'cat' => 'diagnostics', 'w' => 148, 'h' => 160, 'featured' => false],
    ['slug' => 'ug-center', 'name' => 'UG Center', 'cat' => 'diagnostics', 'w' => 346, 'h' => 160, 'featured' => false],
    ['slug' => 'ultravision-eye-care-hospital', 'name' => 'UltraVision Eye Care Hospital', 'cat' => 'hospitals', 'w' => 286, 'h' => 180, 'featured' => true],
    ['slug' => 'urooj-hospital', 'name' => 'Urooj Hospital', 'cat' => 'hospitals', 'w' => 302, 'h' => 280, 'featured' => true],
    ['slug' => 'verma-diagnostics', 'name' => 'Verma Diagnostics', 'cat' => 'diagnostics', 'w' => 265, 'h' => 232, 'featured' => true],
    ['slug' => 'crystal-dental-clinic', 'name' => 'Crystal Dental Implant Clinic & Diagnostics', 'cat' => 'clinics', 'w' => 151, 'h' => 160, 'featured' => false],
    ['slug' => 'ultrasound-diagnostics', 'name' => 'Ultrasound Diagnostics', 'cat' => 'imaging', 'w' => 160, 'h' => 160, 'featured' => false],
];

/**
 * Display size for a logo so wide wordmarks and square badges carry the same visual weight:
 * every logo gets roughly the same area, capped to the box it sits in.
 *
 * @return array{0:int,1:int} [width, height] in CSS px
 */
function client_logo_size(array $c, int $area = 9000, int $maxW = 170, int $maxH = 84): array
{
    $ratio = $c['w'] / $c['h'];
    $w = sqrt($area * $ratio);
    $h = $w / $ratio;
    $k = min(1, $maxW / $w, $maxH / $h);
    return [(int) round($w * $k), (int) round($h * $k)];
}

/** One <img> for a client logo, sized by client_logo_size(). */
function client_logo_img(array $c, int $area = 9000, int $maxW = 170, int $maxH = 84): string
{
    [$w, $h] = client_logo_size($c, $area, $maxW, $maxH);
    return '<img src="/assets/images/clients/' . h($c['slug']) . '.webp" alt="' . h($c['name']) . ' logo"'
        . ' width="' . $w . '" height="' . $h . '" loading="lazy" decoding="async">';
}


/**
 * Endless scrolling row of logo chips. The list is printed twice (the copy hidden
 * from screen readers) so the CSS animation can loop seamlessly.
 */
function client_marquee(array $list, bool $reverse = false, int $seconds = 48, bool $compact = false): string
{
    $chips = '';
    foreach ($list as $c) {
        $img = $compact ? client_logo_img($c, 6400, 140, 60) : client_logo_img($c, 9600, 164, 80);
        $chips .= '<div class="logo-chip" title="' . h($c['name']) . '">' . $img . '</div>';
    }
    $copy = str_replace('<div class="logo-chip"', '<div class="logo-chip" aria-hidden="true"', $chips);
    $copy = preg_replace('/alt="[^"]*"/', 'alt=""', $copy);
    return '<div class="logo-marquee' . ($reverse ? ' reverse' : '') . ($compact ? ' is-compact' : '') . '" style="--speed:' . $seconds . 's">'
        . '<div class="logo-track">' . $chips . $copy . '</div></div>';
}

/** Featured clients split into two rows for the marquee. */
function client_featured_rows(): array
{
    $f = array_values(array_filter(CLIENTS, static fn($c) => $c['featured']));
    $half = (int) ceil(count($f) / 2);
    return [array_slice($f, 0, $half), array_slice($f, $half)];
}


/* ---------- Product landing pages (HIMS / LIMS / CIMS / RIS) ---------- */

/** Which client types to show first on each product page, and how to name them. */
const CLIENT_PRODUCT_FOCUS = [
    'hims' => ['hospitals',   'hospitals & nursing homes'],
    'lims' => ['diagnostics', 'diagnostic labs'],
    'cims' => ['clinics',     'clinics & polyclinics'],
    'ris'  => ['imaging',     'imaging & scanning centres'],
];

/** Only the clients of the product's own type (featured first). */
function clients_for(string $product, int $limit = 14): array
{
    $cat  = CLIENT_PRODUCT_FOCUS[$product][0] ?? '';
    $list = array_values(array_filter(CLIENTS, static fn($c) => $c['cat'] === $cat));
    usort($list, static fn($a, $b) => $b['featured'] <=> $a['featured']);
    return array_slice($list, 0, $limit);
}

/** Logos in a still, centred row — used when there are too few to scroll without visibly repeating. */
function client_logo_row(array $list): string
{
    $chips = '';
    foreach ($list as $c) {
        $chips .= '<div class="logo-chip" title="' . h($c['name']) . '">' . client_logo_img($c, 6400, 140, 60) . '</div>';
    }
    return '<div class="logo-row">' . $chips . '</div>';
}

/** Slim "Trusted by" logo band placed straight under a product page hero. */
function client_trust_strip(string $product): string
{
    $who = CLIENT_PRODUCT_FOCUS[$product][1] ?? 'healthcare providers';
    return '<section class="trust-strip" aria-label="Some of our clients">'
        . '<div class="container"><p class="trust-title">Trusted by <b>1,200+ ' . h($who) . '</b> across 8 countries</p></div>'
        . (count($list = clients_for($product, 14)) >= 6 ? client_marquee($list, false, 40, true) : client_logo_row($list))
        . '<div class="container"><a class="trust-link" href="/our-clients">See all our clients <span aria-hidden="true">→</span></a></div>'
        . '</section>';
}

/** Small "join these providers" badge for the demo form column. */
function client_proof(string $product): string
{
    $list = clients_for($product, 30);
    // Round badges only suit compact marks; wide wordmarks go in the sentence instead.
    usort($list, static fn($a, $b) => ($a['w'] / $a['h'] > 1.5) <=> ($b['w'] / $b['h'] > 1.5));
    $badges = array_slice($list, 0, 5);
    $named  = clients_for($product, 2);
    $who    = CLIENT_PRODUCT_FOCUS[$product][1] ?? 'healthcare providers';
    $html = '<div class="client-proof"><div class="proof-logos">';
    foreach ($badges as $c) {
        $html .= '<span title="' . h($c['name']) . '">' . client_logo_img($c, 1300, 40, 40) . '</span>';
    }
    $html .= '</div><p>Join <b>' . h($named[0]['name'] ?? '') . '</b>' . (isset($named[1]) ? ', <b>' . h($named[1]['name']) . '</b>' : '')
        . ' and <b>1,200+ ' . h($who) . '</b> already on HealthO Pro.</p></div>';
    return $html;
}
