<?php
/**
 * One feature guide — /blog/{slug} (rewritten to blog-post.php?s={slug}).
 * Content comes from partials/features/*.php via partials/blog-data.php.
 */
require __DIR__ . '/partials/blog-data.php';
require_once __DIR__ . '/partials/seo.php';

$slug  = trim((string) ($_GET['s'] ?? ''));
$posts = blog_posts();
$post  = $posts[$slug] ?? null;

if (!$post) {
    http_response_code(404);
    $page        = 'blog';
    $title       = 'Article not found | HealthO Pro Blog';
    $description = 'This article could not be found. Browse all HealthO Pro feature guides on the blog.';
    $canonical   = blog_url('', true);
    $robots      = 'noindex, follow';
    require __DIR__ . '/partials/head.php';
    ?>
    <header class="page-hero">
      <div class="hero-glow glow-1"></div>
      <div class="container">
        <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/blog">Blog</a><span>›</span>Not found</div>
        <h1>This article <span class="text-grad">could not be found</span></h1>
        <p>It may have moved. Every feature guide is listed on the blog.</p>
        <div class="hero-actions hero-actions--center">
          <a href="/blog" class="btn btn-primary btn-lg">Go to the blog</a>
        </div>
      </div>
    </header>
    <?php
    require __DIR__ . '/partials/foot.php';
    exit;
}

$cat      = $post['cat'];
$catName  = FEATURE_CATEGORIES[$cat][0];
$url      = blog_url($slug, true);
$minutes  = blog_read_minutes($post);
$dateText = date('j F Y', strtotime(BLOG_PUBLISHED));

// Previous / next across the whole blog, in the same order as the index.
$slugs = array_keys($posts);
$at    = array_search($slug, $slugs, true);
$prev  = $at > 0 ? $posts[$slugs[$at - 1]] : null;
$next  = $at < count($slugs) - 1 ? $posts[$slugs[$at + 1]] : null;

// Related: the rest of this category.
$related = array_filter(blog_by_category()[$cat], static fn($p) => $p['slug'] !== $slug);

$page        = 'blog';
$title       = $post['title'] . ' | HealthO Pro';
$description = $post['desc'];
$canonical   = $url;
$crumbs      = ['Blog' => blog_url('', true), $post['name'] => $url];
$og_type     = 'article';
$faqs        = $post['faqs'];
$schema      = [[
    '@type'            => 'BlogPosting',
    '@id'              => $url . '#article',
    'headline'         => $post['title'],
    'description'      => $post['desc'],
    'url'              => $url,
    'mainEntityOfPage' => ['@id' => $url . '#webpage'],
    'datePublished'    => BLOG_PUBLISHED,
    'dateModified'     => BLOG_PUBLISHED,
    'author'           => ['@type' => 'Organization', 'name' => BRAND_NAME . ' Team', 'url' => SITE_URL . '/'],
    'publisher'        => ['@id' => ORG_ID],
    'image'            => OG_IMAGE,
    'articleSection'   => $catName,
    'keywords'         => implode(', ', [$post['name'], $catName, 'HealthO Pro', 'healthcare software']),
    'about'            => ['@type' => 'Thing', 'name' => $post['name']],
    'isPartOf'         => ['@id' => blog_url('', true) . '#blog'],
    'inLanguage'       => 'en',
]];

require __DIR__ . '/partials/head.php';
$arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/blog">Blog</a><span>›</span><?= h($post['name']) ?></div>
    <h1><?= h($post['title']) ?></h1>
    <p><?= h($post['short']) ?></p>
    <div class="bl-meta">
      <a class="bl-tag" href="/features#<?= h($cat) ?>"><?= blog_icon($cat) ?><?= h($catName) ?></a>
      <span><time datetime="<?= h(BLOG_PUBLISHED) ?>"><?= h($dateText) ?></time></span>
      <span><?= $minutes ?> min read</span>
    </div>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="bl-layout">
      <article class="bl-article">
        <p class="bl-lede"><?= h($post['intro']) ?></p>

        <h2>What <?= h($post['name']) ?> does</h2>
        <div class="bl-points">
<?php foreach ($post['points'] as [$heading, $text]): ?>
          <div class="bl-point"><h3><?= h($heading) ?></h3><p><?= h($text) ?></p></div>
<?php endforeach; ?>
        </div>

        <h2>Why it matters</h2>
        <ul class="checks">
<?php foreach ($post['benefits'] as $benefit): ?>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><?= h($benefit) ?></span></li>
<?php endforeach; ?>
        </ul>

        <h2>Built for</h2>
        <div class="bl-for">
<?php foreach ($post['for'] as $product): ?>
          <a href="/<?= h($product) ?>"><?= h(FEATURE_AUDIENCE[$product]) ?> · <?= h(PRODUCTS[$product]['short']) ?></a>
<?php endforeach; ?>
        </div>

        <nav class="bl-pager" aria-label="More articles">
<?php if ($prev): ?>
          <a href="<?= h(blog_url($prev['slug'])) ?>" rel="prev"><small>Previous</small><strong><?= h($prev['name']) ?></strong></a>
<?php endif; ?>
<?php if ($next): ?>
          <a class="next" href="<?= h(blog_url($next['slug'])) ?>" rel="next"><small>Next</small><strong><?= h($next['name']) ?></strong></a>
<?php endif; ?>
        </nav>
      </article>

      <aside class="bl-aside">
        <div class="bl-box bl-box--cta">
          <h2>See <?= h($post['name']) ?> live</h2>
          <p>Book a free, no-obligation demo configured for your facility.</p>
          <a href="/contact" data-demo class="btn btn-primary">Book a Free Demo</a>
        </div>
<?php if ($related): ?>
        <div class="bl-box">
          <h2>More in <?= h($catName) ?></h2>
          <div class="bl-links">
<?php foreach ($related as $r): ?>
            <a href="<?= h(blog_url($r['slug'])) ?>"><?= h($r['name']) ?></a>
<?php endforeach; ?>
          </div>
        </div>
<?php endif; ?>
        <div class="bl-box">
          <h2>Explore</h2>
          <div class="bl-links">
            <a href="/features">All <?= count($posts) ?> features</a>
            <a href="/blog">All articles</a>
            <a href="/pricing">Pricing</a>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<?php faq_section($faqs, $post['name'] . ' — FAQs'); ?>
<?php require __DIR__ . '/partials/foot.php';
