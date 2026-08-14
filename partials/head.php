<?php
/**
 * Shared document head + navbar.
 *
 * Pages set these before including this file:
 *   $page        slug used for the canonical URL and the active nav item ('' = home)
 *   $title       <title> text
 *   $description meta description
 * Optional:
 *   $canonical   full URL, defaults to https://healtho.pro/$page
 *   $head_extra  raw HTML appended inside <head> (og tags, JSON-LD, …)
 */
require_once __DIR__ . '/site.php';

$page        = $page        ?? '';
$title       = $title       ?? 'HealthO Pro — Empowering Healthcare Providers';
$description = $description ?? '';
$canonical   = $canonical   ?? SITE_URL . '/' . $page;
$head_extra  = $head_extra  ?? '';

// Product pages keep the Solutions tab lit.
$nav_active = in_array($page, ['solutions', 'hims', 'lims', 'cims', 'ris'], true) ? 'solutions' : $page;
$active = static fn(string $slug): string => $slug === $nav_active ? ' active' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($title) ?></title>
<meta name="description" content="<?= h($description) ?>">
<meta name="theme-color" content="#122B5C">
<link rel="canonical" href="<?= h($canonical) ?>">
<link rel="icon" type="image/png" href="/assets/images/favicon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="<?= FONT_CSS ?>">
<link rel="stylesheet" href="<?= FONT_CSS ?>" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="<?= FONT_CSS ?>"></noscript>
<link rel="stylesheet" href="/styles.css?v=<?= ASSET_VER ?>">
<link rel="preload" as="image" href="/assets/images/logo.webp">
<?= $head_extra ?>

<link rel="dns-prefetch" href="https://www.googletagmanager.com">
<link rel="dns-prefetch" href="https://www.clarity.ms">
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= GA_ID ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= GA_ID ?>');
  /* Clarity is a session recorder — hold it back until the page is interactive. */
  addEventListener('load', function () {
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "<?= CLARITY_ID ?>");
  });
</script>
</head>
<body>
<div class="preloader" id="preloader"><div class="spinner"></div></div>

<!-- ===== NAVBAR ===== -->
<nav class="navbar" id="navbar">
  <div class="container nav-inner">
    <a href="/" class="nav-logo" aria-label="HealthO Pro home">
      <img src="/assets/images/logo.webp" alt="HealthO Pro — Empowering Healthcare Providers" width="187" height="40" fetchpriority="high">
    </a>
    <ul class="nav-menu" id="navMenu">
      <li class="nav-item"><a href="/" class="nav-link<?= $active('') ?>">Home</a></li>
      <li class="nav-item has-drop">
        <a href="/solutions" class="nav-link<?= $active('solutions') ?>">Solutions</a>
        <div class="dropdown">
<?php foreach (PRODUCTS as $slug => $p): ?>
          <a href="/<?= $slug ?>" class="drop-item">
            <span class="drop-ic drop-ic-img"><img src="/assets/images/icon-<?= $slug ?>.webp" alt="" width="38" height="38" loading="lazy" decoding="async"></span>
            <span><span class="drop-tt"><?= h($p['short']) ?></span><span class="drop-ds"><?= h($p['long']) ?></span></span>
          </a>
<?php endforeach; ?>
        </div>
      </li>
      <li class="nav-item"><a href="/pricing" class="nav-link<?= $active('pricing') ?>">Pricing</a></li>
      <li class="nav-item"><a href="/testimonials" class="nav-link<?= $active('testimonials') ?>">Testimonials</a></li>
      <li class="nav-item"><a href="/careers" class="nav-link<?= $active('careers') ?>">Careers</a></li>
      <li class="nav-item"><a href="/contact" class="nav-link<?= $active('contact') ?>">Contact</a></li>
      <li><a href="/contact" class="btn btn-primary mobile-cta" style="display:none;">Get a Demo</a></li>
    </ul>
    <div class="nav-actions">
      <a href="/contact" class="btn btn-primary nav-cta">Get a Demo</a>
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>
