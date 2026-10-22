<?php
/**
 * One city guide, e.g. /blog/hospital-management-software-hyderabad.
 * Included by blog-post.php with $slug set (variables use $cp/$citySlug because head.php reuses $p/$slug); content from partials/city-data.php.
 */
require_once __DIR__ . '/city-data.php';
require_once __DIR__ . '/seo.php';

['product' => $product, 'city' => $cityKey] = city_pages()[$slug];
$cp     = CITY_PRODUCTS[$product];
$c     = CITIES[$cityKey];
$cname = $c['name'];
$short = PRODUCTS[$product]['short'];
$isHome = $cityKey === HOME_CITY;
$fill  = static fn(string $t): string => city_fill($t, $cityKey);

$citySlug = $slug;  // head.php reuses $slug and $p in its nav loop
$url      = blog_url($citySlug, true);
$h1       = $fill($cp['h1']);
$dateText = date('j F Y', strtotime(CITY_PUBLISHED));
$areas    = implode(', ', array_slice($c['areas'], 0, -1)) . ' and ' . end($c['areas']);

// City FAQs first (they carry the local search terms), then the product's own.
$support = $isHome
    ? "Yes. Our offices are in Hyderabad — Banjara Hills and Abids — so our team can visit your {$cp['audience']} when needed. Setup and training are free, and support is a call or WhatsApp away 24×7."
    : "Yes. Our team sets HealthO Pro {$short} up for you and trains your staff for free, wherever you are in {$cname} — from {$c['areas'][0]} to " . end($c['areas']) . ". After go-live, support is a call or WhatsApp away 24×7.";
$faqs = [
    "What is the best " . strtolower($cp['keyword']) . " in {$cname}?" =>
        "The best choice is the one your team will actually use every day. HealthO Pro {$short} is cloud-based, set up and trained for free, and used by 1,200+ healthcare providers across India and 7 other countries since 2011. Book a free demo to see it working with your own setup in {$cname}.",
    "How much does " . strtolower($cp['keyword']) . " cost in {$cname}?" => $cp['pricing'] . ' See the pricing page for current plans.',
    "Do you provide setup and support in {$cname}?" => $support,
] + $cp['faqs'];
if ($product === 'hims' && $c['scheme'] !== '') {
    $faqs["Can it handle {$c['scheme']} and TPA patients?"] =
        "Yes. HIMS supports cash, credit, insurance, package and TPA billing, so hospitals in {$cname} can bill {$c['scheme']}, corporate and self-paying patients from the same system, with claim papers coming from the patient record.";
}

$page        = 'blog';
$title       = $fill($cp['title']) . ' | HealthO Pro';
$description = $fill($cp['desc']);
$canonical   = $url;
$crumbs      = ['Blog' => blog_url('', true), $h1 => $url];
$og_type     = 'article';
$cover       = blog_image($citySlug);
$place       = str_contains($cname, $c['state']) ? $cname : $cname . ', ' . $c['state'];
$coverAlt    = 'Best ' . $cp['keyword'] . ' in ' . $place . ' — HealthO Pro ' . $short;
$og_image    = blog_image($citySlug, 'share', true);
$og_image_alt = $coverAlt;
$schema      = [
    [
        '@type'            => 'BlogPosting',
        '@id'              => $url . '#article',
        'headline'         => $fill($cp['title']),
        'description'      => $description,
        'url'              => $url,
        'mainEntityOfPage' => ['@id' => $url . '#webpage'],
        'datePublished'    => CITY_PUBLISHED,
        'dateModified'     => CITY_PUBLISHED,
        'author'           => ['@type' => 'Organization', 'name' => BRAND_NAME . ' Team', 'url' => SITE_URL . '/'],
        'publisher'        => ['@id' => ORG_ID],
        'image'            => $og_image ? [
            '@type'      => 'ImageObject',
            'url'        => $og_image,
            'width'      => 1200,
            'height'     => 672,
            'caption'    => $coverAlt,
            'contentLocation' => $place,
        ] : OG_IMAGE,
        'articleSection'   => $cp['keyword'],
        'keywords'         => implode(', ', [
            $cp['keyword'] . ' in ' . $cname,
            'best ' . strtolower($cp['keyword']) . ' ' . $cname,
            $short . ' software ' . $cname,
            $cp['keyword'] . ' ' . $c['state'],
        ]),
        'contentLocation'  => ['@type' => 'City', 'name' => $cname, 'containedInPlace' => ['@type' => 'State', 'name' => $c['state']]],
        'isPartOf'         => ['@id' => blog_url('', true) . '#blog'],
        'inLanguage'       => 'en',
    ],
    [
        '@type'       => 'Service',
        '@id'         => $url . '#service',
        'name'        => 'HealthO Pro ' . $short . ' — ' . $cp['keyword'] . ' in ' . $cname,
        'serviceType' => $cp['keyword'],
        'provider'    => ['@id' => ORG_ID],
        'areaServed'  => ['@type' => 'City', 'name' => $cname],
        'audience'    => ['@type' => 'Audience', 'audienceType' => ucfirst($cp['audience'])],
        'url'         => SITE_URL . '/' . $product,
    ],
];

// Links: other products in this city, this product in nearby cities.
$sameCity = array_filter(array_keys(CITY_PRODUCTS), static fn($k) => $k !== $product);
$nearby   = array_filter($c['near'], static fn($k) => isset(CITIES[$k]));
$guides   = array_intersect_key(blog_posts(), array_flip($cp['guides']));

$demo_interest = PRODUCTS[$product]['short'];  // pre-selects the product in the demo popup
require __DIR__ . '/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/blog">Blog</a><span>›</span><?= h($h1) ?></div>
    <h1><?= h($cp['keyword']) ?> in <span class="text-grad"><?= h($cname) ?></span></h1>
    <p><?= h($fill($cp['short'])) ?></p>
    <div class="bl-meta">
      <a class="bl-tag" href="/<?= h($product) ?>"><?= h($short) ?> · <?= h(PRODUCTS[$product]['long']) ?></a>
      <span><time datetime="<?= h(CITY_PUBLISHED) ?>"><?= h($dateText) ?></time></span>
      <span><?= h($cname) ?>, <?= h($c['state']) ?></span>
    </div>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="bl-layout">
      <article class="bl-article">
<?php if ($cover): ?>
        <figure class="bl-figure">
          <img class="bl-cover" src="<?= h($cover) ?>" alt="<?= h($coverAlt) ?>" title="<?= h($cp['keyword']) ?> in <?= h($cname) ?>" width="1200" height="672" fetchpriority="high" decoding="async">
        </figure>
<?php endif; ?>
        <p class="bl-lede"><?= h($fill($cp['intro'])) ?></p>

        <h2><?= h(ucfirst($cp['audience'])) ?> in <?= h($cname) ?></h2>
        <p><?= h($c['about']) ?></p>
        <p><?= h($c['angle'][$product]) ?></p>

        <h2>Why <?= h($cp['audience']) ?> in <?= h($cname) ?> choose HealthO Pro <?= h($short) ?></h2>
        <div class="bl-points">
<?php foreach ($cp['points'] as [$heading, $text]): ?>
          <div class="bl-point"><h3><?= h($heading) ?></h3><p><?= h($text) ?></p></div>
<?php endforeach; ?>
        </div>

        <h2>Setup, training and support in <?= h($cname) ?></h2>
        <ul class="checks">
<?php if ($isHome): ?>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Local team — our offices are in Banjara Hills and Abids, Hyderabad.</span></li>
<?php endif; ?>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Free setup and staff training — most <?= h($cp['audience']) ?> go live in a few days.</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><?= $product === 'ris' ? 'Nothing extra to buy — one Windows PC at your centre connects to the ultrasound machine.' : 'Cloud-based: open it from any browser, at any branch, anywhere in ' . h($cname) . '.' ?></span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Support 24×7 by phone and WhatsApp.</span></li>
<?php if ($product === 'hims' && $c['scheme'] !== ''): ?>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Insurance, package and TPA billing for <?= h($c['scheme']) ?>, corporate and cash patients.</span></li>
<?php endif; ?>
        </ul>

        <h2>Areas we serve in <?= h($cname) ?></h2>
        <p>HealthO Pro <?= h($short) ?> works for <?= h($cp['audience']) ?> anywhere in <?= h($cname) ?> and nearby, including <?= h($areas) ?>. Single centres and multi-branch groups across the city can run on the same system.</p>

        <h2>Pricing</h2>
        <p><?= h($cp['pricing']) ?> <a href="/pricing">See current plans</a>.</p>

<?php if ($guides): ?>
        <h2>Features to explore</h2>
        <div class="bl-for">
<?php foreach ($guides as $g): ?>
          <a href="<?= h(blog_url($g['slug'])) ?>"><?= h($g['name']) ?></a>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </article>

      <aside class="bl-aside">
        <div class="bl-box bl-box--cta">
          <h2>Free <?= h($short) ?> demo in <?= h($cname) ?></h2>
          <p>See HealthO Pro working with your own setup — free, no obligation.</p>
          <a href="/contact" data-demo class="btn btn-primary">Book a Free Demo</a>
        </div>
        <div class="bl-box">
          <h2>More software in <?= h($cname) ?></h2>
          <div class="bl-links">
<?php foreach ($sameCity as $k): ?>
            <a href="<?= h(blog_url(city_slug($k, $cityKey))) ?>"><?= h(CITY_PRODUCTS[$k]['keyword']) ?></a>
<?php endforeach; ?>
          </div>
        </div>
<?php if ($nearby): ?>
        <div class="bl-box">
          <h2><?= h($cp['keyword']) ?> nearby</h2>
          <div class="bl-links">
<?php foreach ($nearby as $k): ?>
            <a href="<?= h(blog_url(city_slug($product, $k))) ?>"><?= h(CITIES[$k]['name']) ?></a>
<?php endforeach; ?>
          </div>
        </div>
<?php endif; ?>
        <div class="bl-box">
          <h2>Explore</h2>
          <div class="bl-links">
            <a href="/<?= h($product) ?>">HealthO Pro <?= h($short) ?></a>
            <a href="/pricing">Pricing</a>
            <a href="/our-clients">Our clients</a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<?php faq_section($faqs, $cp['keyword'] . ' in ' . $cname . ' — FAQs'); ?>
<?php require __DIR__ . '/foot.php';
