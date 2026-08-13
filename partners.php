<?php
$page        = 'partners';
$title       = 'Channel Partners | HealthO Pro';
$description = 'Meet HealthO Pro\'s authorized channel partners, resellers and distributors across countries and states. Every partner holds a verifiable partnership certificate — verify any certificate instantly by QR code.';
$head_extra  = <<<'HTML'
<style>
/* ── Partners page ── */
.pt-filters { display:flex; gap:12px; flex-wrap:wrap; align-items:center; justify-content:center; margin-bottom:38px; }
.pt-filters input, .pt-filters select {
  padding:11px 16px; border:1.5px solid var(--line); border-radius:12px; background:var(--surface);
  color:var(--ink); font:inherit; font-size:.95rem; min-width:190px; outline:none; transition:border-color .2s;
}
.pt-filters input:focus, .pt-filters select:focus { border-color: var(--cyan); }
.pt-filters input { min-width:250px; }
.pt-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:22px; }
.pt-card {
  background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:26px 24px;
  display:flex; flex-direction:column; gap:14px; transition:transform .25s, box-shadow .25s;
}
.pt-card:hover { transform:translateY(-4px); box-shadow:0 14px 34px rgba(18,43,92,.10); }
.pt-card-head { display:flex; align-items:center; gap:14px; }
.pt-logo {
  width:54px; height:54px; border-radius:12px; border:1px solid var(--line-soft); background:#fff;
  object-fit:contain; flex:0 0 54px;
}
.pt-logo-ph {
  display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.3rem;
  color:#fff; background:var(--grad-brand);
}
.pt-name { font-weight:700; color:var(--ink); font-size:1.05rem; line-height:1.3; }
.pt-loc { color:var(--text-soft); font-size:.86rem; display:flex; align-items:center; gap:5px; margin-top:2px; }
.pt-loc svg { width:13px; height:13px; flex:0 0 13px; }
.pt-about { color:var(--text); font-size:.9rem; line-height:1.55; margin:0; }
.pt-meta { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-top:auto; }
.pt-badge {
  display:inline-flex; align-items:center; gap:5px; font-size:.74rem; font-weight:700;
  padding:4px 11px; border-radius:99px; background:var(--green-tint); color:var(--green-dark);
}
.pt-badge svg { width:12px; height:12px; }
.pt-chip { font-size:.74rem; font-weight:600; padding:4px 11px; border-radius:99px; background:var(--cyan-tint); color:var(--cyan-dark); }
.pt-chip.since { background:var(--navy-tint); color:var(--navy); }
.pt-link { font-size:.85rem; font-weight:600; color:var(--cyan-dark); text-decoration:none; margin-left:auto; }
.pt-link:hover { text-decoration:underline; }
.pt-state { text-align:center; color:var(--text-soft); padding:60px 0; }
.pt-count { text-align:center; color:var(--text-mute); font-size:.88rem; margin-bottom:26px; }
</style>
HTML;
$foot_scripts = ['js/partners.js'];
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Channel Partners</div>
    <h1>Our authorized <span class="text-grad">channel partners</span></h1>
    <p>A growing network of certified partners, resellers and distributors bringing HealthO Pro to healthcare providers across countries and states. Every partner listed here holds a verifiable partnership certificate.</p>
  </div>
</header>

<!-- ===== VERIFY BAND ===== -->
<section class="section--navy" style="padding:34px 0;">
  <div class="container" style="display:flex; gap:18px; align-items:center; justify-content:center; flex-wrap:wrap;">
    <span style="color:#c7d3ea; font-size:.95rem;">Have a partnership certificate to check?</span>
    <a href="verify-partner" class="btn btn-primary">Verify a Certificate</a>
  </div>
</section>

<!-- ===== DIRECTORY ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">Partner Directory</span><h2 class="h-sec">Find a partner near you</h2></div>

    <div class="pt-filters">
      <input type="search" id="ptSearch" placeholder="Search partner name or city…" aria-label="Search partners">
      <select id="ptCountry" aria-label="Filter by country"><option value="">All Countries</option></select>
      <select id="ptState" aria-label="Filter by state"><option value="">All States</option></select>
    </div>
    <div class="pt-count" id="ptCount"></div>

    <div class="pt-state" id="ptLoading">Loading partners…</div>
    <div class="pt-grid" id="ptGrid" hidden></div>
    <div class="pt-state" id="ptEmpty" hidden>No partners match your filters yet. Try clearing the search or choosing another region.</div>
  </div>
</section>

<!-- ===== BECOME A PARTNER CTA ===== -->
<section class="section">
  <div class="container">
    <div class="cta-band reveal" style="background:var(--grad-hero);">
      <div>
        <h3>Want to become a HealthO Pro partner?</h3>
        <p>Join our partner network and grow with the leading healthcare software suite — HIMS, LIMS, CIMS and RIS/RIMS.</p>
      </div>
      <a href="contact" class="btn btn-white btn-lg">Partner With Us</a>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
