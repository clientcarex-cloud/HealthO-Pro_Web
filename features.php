<?php
/**
 * Features — every HealthO Pro feature, grouped by category, each linking to
 * its guide on the blog. Built from partials/blog-data.php.
 */
require __DIR__ . '/partials/blog-data.php';
require_once __DIR__ . '/partials/seo.php';

$posts  = blog_posts();
$groups = blog_by_category();

$page        = 'features';
$title       = 'All ' . count($posts) . ' Features — Healthcare Software Modules | HealthO Pro';
$description = 'Explore ' . count($posts) . ' HealthO Pro features for hospitals, labs, clinics and radiology — billing, appointments, LIS, AI, HR and more — each with a guide.';
$page_type   = 'CollectionPage';

$items = [];
$i = 0;
foreach ($posts as $slug => $post) {
    $items[] = ['@type' => 'ListItem', 'position' => ++$i, 'url' => blog_url($slug, true), 'name' => $post['name']];
}
$schema = [[
    '@type'           => 'ItemList',
    'name'            => 'HealthO Pro features',
    'numberOfItems'   => count($items),
    'itemListElement' => $items,
]];

require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Features</div>
    <h1><?= count($posts) ?> features. <span class="text-grad">One connected platform.</span></h1>
    <p>Everything HealthO Pro does for hospitals, laboratories, clinics and radiology centers — pick any feature to read its guide.</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <nav class="ft-jump" aria-label="Feature categories" style="margin-bottom:56px;">
<?php foreach ($groups as $cat => $group): ?>
      <a class="bl-chip" href="#<?= h($cat) ?>"><?= h(FEATURE_CATEGORIES[$cat][0]) ?> (<?= count($group) ?>)</a>
<?php endforeach; ?>
    </nav>

<?php foreach ($groups as $cat => $group): ?>
    <div class="ft-cat" id="<?= h($cat) ?>">
      <div class="ft-head">
        <span class="ft-ic"><?= blog_icon($cat) ?></span>
        <div>
          <h2><?= h(FEATURE_CATEGORIES[$cat][0]) ?></h2>
          <p><?= h(FEATURE_CATEGORIES[$cat][1]) ?></p>
        </div>
      </div>
      <div class="ft-grid">
<?php foreach ($group as $slug => $post): ?>
        <a class="ft-card" href="<?= h(blog_url($slug)) ?>">
          <h3><?= h($post['name']) ?></h3>
          <p><?= h($post['short']) ?></p>
          <span class="bl-more">Read the guide <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
        </a>
<?php endforeach; ?>
      </div>
    </div>
<?php endforeach; ?>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="section">
  <div class="container center">
    <span class="eyebrow">Get Started</span>
    <h2 class="h-sec">See the features that matter to you</h2>
    <p class="lead" style="margin:16px auto 28px;max-width:620px;">Book a free demo and we’ll walk you through the modules your hospital, lab, clinic or imaging center needs.</p>
    <a href="/contact" data-demo class="btn btn-primary btn-lg">Book a Free Demo</a>
    <a href="/pricing" class="btn btn-outline btn-lg" style="margin-left:8px;">View Pricing</a>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
