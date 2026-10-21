<?php
$page        = 'our-clients';
$title       = 'Our Clients — Hospitals, Labs & Clinics on HealthO Pro';
$description = 'Meet the hospitals, diagnostic labs, imaging centers and clinics that run on HealthO Pro. 1,200+ healthcare providers across 8 countries trust our HIMS, LIMS, CIMS and RIS.';
$page_type   = 'CollectionPage';
require_once __DIR__ . '/partials/clients-data.php';
require __DIR__ . '/partials/head.php';

[$rowA, $rowB] = client_featured_rows();
$counts = array_count_values(array_column(CLIENTS, 'cat'));
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero clients-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Our Clients</div>
    <h1>The healthcare brands that <span class="text-grad">run on HealthO Pro</span></h1>
    <p>Hospitals, diagnostic labs, imaging centers and clinics rely on us every day to serve their patients faster and run their operations with confidence.</p>
    <div class="hero-chips">
      <span class="hero-chip"><strong>1,200+</strong> healthcare clients</span>
      <span class="hero-chip"><strong>8</strong> countries</span>
      <span class="hero-chip"><strong>13+</strong> years of trust</span>
    </div>
  </div>
  <div style="position:relative;z-index:2;">
    <?= client_marquee($rowA, false, 42) ?>
    <?= client_marquee($rowB, true, 46) ?>
  </div>
</header>

<!-- ===== CLIENT WALL ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Client Wall</span>
      <h2 class="h-sec">Proud to power <span class="text-grad">every kind of care</span></h2>
      <p class="lead">A glimpse of the providers who trust HealthO Pro — from multi-speciality hospitals to neighbourhood diagnostic centres.</p>
    </div>

    <div class="client-filters reveal" role="tablist" aria-label="Filter clients by type">
      <button class="client-filter is-active" data-filter="all" role="tab" aria-selected="true">All <span class="cnt"><?= count(CLIENTS) ?></span></button>
<?php foreach (CLIENT_CATEGORIES as $key => $label): if (empty($counts[$key])) continue; ?>
      <button class="client-filter" data-filter="<?= $key ?>" role="tab" aria-selected="false"><?= h($label) ?> <span class="cnt"><?= $counts[$key] ?></span></button>
<?php endforeach; ?>
    </div>

    <div class="client-grid" id="clientGrid">
<?php foreach (CLIENTS as $i => $c): ?>
      <article class="client-card reveal<?= ['', ' d1', ' d2', ' d3'][$i % 4] ?>" data-cat="<?= $c['cat'] ?>">
        <div class="client-logo"><?= client_logo_img($c, 10500, 180, 96) ?></div>
        <div class="client-name"><?= h($c['name']) ?></div>
        <span class="client-cat c-<?= $c['cat'] ?>"><?= h(CLIENT_CATEGORIES[$c['cat']]) ?></span>
      </article>
<?php endforeach; ?>
      <a href="contact" data-demo class="client-card client-join reveal">
        <span class="plus">+</span>
        <span class="client-name">Your logo here</span>
        <small>Join 1,200+ providers</small>
      </a>
    </div>
  </div>
</section>

<!-- ===== STATS ===== -->
<section class="section section--navy" style="padding:64px 0;">
  <div class="container">
    <div class="stats">
      <div class="stat reveal"><div class="n"><span data-count="1200" data-suffix="+">1,200+</span></div><div class="l">Healthcare clients</div></div>
      <div class="stat reveal d1"><div class="n"><span data-count="8" data-suffix="">8</span></div><div class="l">Countries served</div></div>
      <div class="stat reveal d2"><div class="n"><span data-count="13" data-suffix="+">13+</span></div><div class="l">Years in healthcare IT</div></div>
      <div class="stat reveal d3"><div class="n"><span data-count="99.9" data-suffix="%">99.9%</span></div><div class="l">Platform uptime</div></div>
    </div>
  </div>
</section>

<!-- ===== FEATURED QUOTE ===== -->
<section class="section">
  <div class="container">
    <div class="cta-band reveal" style="background:var(--grad-hero);">
      <div class="stars" style="justify-content:center;color:var(--amber);font-size:1.4rem;margin-bottom:18px;">★★★★★</div>
      <p style="font-size:clamp(1.2rem,2.4vw,1.6rem);font-weight:600;line-height:1.6;max-width:820px;margin:0 auto;">"HealthO Pro didn't just give us software — they gave us a partner who understands healthcare."</p>
      <div style="margin-top:28px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="contact" data-demo class="btn btn-primary btn-lg">Book a Free Demo</a>
        <a href="testimonials" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,.4);">Read success stories</a>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var btns = document.querySelectorAll('.client-filter');
  var cards = document.querySelectorAll('#clientGrid .client-card[data-cat]');
  btns.forEach(function (b) {
    b.addEventListener('click', function () {
      var f = b.getAttribute('data-filter');
      btns.forEach(function (x) { var on = x === b; x.classList.toggle('is-active', on); x.setAttribute('aria-selected', on); });
      cards.forEach(function (c) {
        var show = f === 'all' || c.getAttribute('data-cat') === f;
        c.classList.toggle('is-hidden', !show);
        if (show) c.classList.add('in');
      });
    });
  });
})();
</script>
<?php require __DIR__ . '/partials/foot.php';
