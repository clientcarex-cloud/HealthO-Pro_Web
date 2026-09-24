<?php
/**
 * Blog — every feature guide, newest set first, with search and category
 * filters. All cards are server-rendered, so crawlers see every post; the
 * filters only hide and show them (js/blog.js).
 */
require __DIR__ . '/partials/blog-data.php';
require_once __DIR__ . '/partials/seo.php';

$posts = blog_posts();

$page        = 'blog';
$title       = 'Blog — Healthcare Software Guides & Features | HealthO Pro';
$description = 'Guides to every HealthO Pro feature — billing, appointments, lab, radiology, hospital, AI, HR and more — for hospitals, labs, clinics and imaging centers.';
$page_type   = 'CollectionPage';

$items = [];
$i = 0;
foreach ($posts as $slug => $post) {
    $items[] = ['@type' => 'ListItem', 'position' => ++$i, 'url' => blog_url($slug, true), 'name' => $post['title']];
}
$schema = [
    [
        '@type'     => 'Blog',
        '@id'       => blog_url('', true) . '#blog',
        'name'      => 'HealthO Pro Blog',
        'url'       => blog_url('', true),
        'publisher' => ['@id' => ORG_ID],
        'inLanguage' => 'en',
    ],
    ['@type' => 'ItemList', 'name' => 'HealthO Pro feature guides', 'numberOfItems' => count($items), 'itemListElement' => $items],
];
$foot_scripts = ['js/blog.js'];

require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Blog</div>
    <h1>The HealthO Pro <span class="text-grad">Blog</span></h1>
    <p>Practical guides to the <?= count($posts) ?> features that run hospitals, laboratories, clinics and radiology centers on HealthO Pro.</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="bl-filters" id="blFilters">
      <label class="sr-only" for="blSearch">Search articles</label>
      <input class="bl-search" id="blSearch" type="search" placeholder="Search features, e.g. billing, WhatsApp, QC…" autocomplete="off">
      <div class="bl-chips" role="group" aria-label="Filter by category">
        <button type="button" class="bl-chip" data-cat="" aria-pressed="true">All</button>
<?php foreach (blog_by_category() as $cat => $group): ?>
        <button type="button" class="bl-chip" data-cat="<?= h($cat) ?>" aria-pressed="false"><?= h(FEATURE_CATEGORIES[$cat][0]) ?></button>
<?php endforeach; ?>
      </div>
    </div>
    <p class="bl-count" id="blCount" aria-live="polite"><?= count($posts) ?> articles</p>

    <div class="bl-grid" id="blGrid">
<?php foreach ($posts as $slug => $post): ?>
      <a class="bl-card" href="<?= h(blog_url($slug)) ?>" data-cat="<?= h($post['cat']) ?>" data-text="<?= h(mb_strtolower($post['name'] . ' ' . $post['title'] . ' ' . $post['short'] . ' ' . FEATURE_CATEGORIES[$post['cat']][0])) ?>">
<?php if ($img = blog_image($slug, 'sm')): ?>
        <img class="bl-card-img" src="<?= h($img) ?>" alt="<?= h($post['name']) ?> — HealthO Pro" width="640" height="358" loading="lazy" decoding="async">
<?php endif; ?>
        <span class="bl-tag"><?= blog_icon($post['cat']) ?><?= h(FEATURE_CATEGORIES[$post['cat']][0]) ?></span>
        <h2><?= h($post['title']) ?></h2>
        <p><?= h($post['short']) ?></p>
        <span class="bl-card-foot"><span><?= blog_read_minutes($post) ?> min read</span><span class="bl-more">Read <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span></span>
      </a>
<?php endforeach; ?>
    </div>
    <p class="bl-empty" id="blEmpty" hidden>No articles match your search. <a href="/features">Browse all features</a>.</p>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="section">
  <div class="container center">
    <span class="eyebrow">See it live</span>
    <h2 class="h-sec">Want to see these features in action?</h2>
    <p class="lead" style="margin:16px auto 28px;max-width:620px;">Book a free demo configured for your hospital, lab, clinic or imaging center.</p>
    <a href="/contact" data-demo class="btn btn-primary btn-lg">Book a Free Demo</a>
    <a href="/features" class="btn btn-outline btn-lg" style="margin-left:8px;">All Features</a>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
