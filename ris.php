<?php
$page        = 'ris';
$title       = 'RIS / RIMS — Radiology Information System | HealthO Pro';
$description = 'HealthO Pro RIS/RIMS: a complete Radiology Information System with scheduling, modality worklists, DICOM-ready reporting and a flexible pay-as-you-go model at just ₹5 per patient.';
require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="solutions">Solutions</a><span>›</span>RIS / RIMS</div>
    <h1>RIS / RIMS — <span class="text-grad">Radiology Information</span> System</h1>
    <p>End-to-end radiology workflow management with DICOM-ready reporting — and a pay-as-you-go model that scales with your volume.</p>
    <div class="hero-actions hero-actions--center">
      <a href="#demo" class="btn btn-primary btn-lg">Book a Demo</a>
      <a href="#payg" class="btn btn-ghost-light btn-lg">See Pricing</a>
    </div>
  </div>
</header>

<!-- ===== OVERVIEW SPLIT ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="split rev">
      <div class="reveal">
        <span class="eyebrow">For Radiology &amp; Imaging Centers</span>
        <h2 class="h-sec">From scan request to signed report</h2>
        <p class="lead" style="margin:16px 0 24px;">HealthO Pro RIS/RIMS connects front-desk, modalities and radiologists on one platform — speeding up reporting and keeping every study traceable.</p>
        <ul class="checks">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Patient scheduling &amp; modality worklists</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>DICOM-ready, PACS-friendly reporting</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Templated reports &amp; radiologist e-sign</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Digital report &amp; image sharing with patients</span></li>
        </ul>
      </div>
      <div class="split-media reveal d1"><div class="media-frame"><img src="assets/images/ris.webp" alt="HealthO Pro RIS/RIMS radiology dashboard" width="1024" height="1024" loading="lazy" decoding="async"></div></div>
    </div>
  </div>
</section>

<!-- ===== FEATURE GRID ===== -->
<section class="section">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Capabilities</span>
      <h2 class="h-sec">Complete radiology workflow</h2>
      <p class="lead">For standalone imaging centers and hospital radiology departments alike.</p>
    </div>
    <div class="grid grid-3">
      <div class="card reveal"><span class="feat-ic bg-violet" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></span><h4>Scheduling</h4><p>Slot-based appointments and modality-wise worklists.</p></div>
      <div class="card reveal d1"><span class="feat-ic bg-cyan" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18M3 12h18"/></svg></span><h4>Modality &amp; DICOM</h4><p>Integrate CT, MRI, X-Ray, USG with PACS and DICOM viewers.</p></div>
      <div class="card reveal d2"><span class="feat-ic bg-navy" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6"/></svg></span><h4>Reporting</h4><p>Structured templates, voice-to-text and radiologist e-signature.</p></div>
      <div class="card reveal"><span class="feat-ic bg-green" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg></span><h4>Patient Delivery</h4><p>Share reports &amp; images via link, WhatsApp and patient portal.</p></div>
      <div class="card reveal d1"><span class="feat-ic bg-amber" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></span><h4>Billing</h4><p>Scan-wise billing, referral tracking and package management.</p></div>
      <div class="card reveal d2"><span class="feat-ic bg-navy" style="margin-bottom:18px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/></svg></span><h4>Analytics</h4><p>Volume, TAT and revenue dashboards by modality.</p></div>
    </div>
  </div>
</section>

<!-- ===== PAY AS YOU GO ===== -->
<section class="section section--tint" id="payg">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Flexible Pricing</span>
      <h2 class="h-sec">Pay only for what you use</h2>
      <p class="lead">No heavy upfront cost. RIS/RIMS runs on a simple pay-as-you-go model — perfect for growing imaging centers.</p>
    </div>
    <div class="payg reveal">
      <div>
        <h3 class="h-card" style="margin-bottom:14px;">Pay-As-You-Go Model</h3>
        <p style="color:var(--text-soft);margin-bottom:22px;">Get the full RIS/RIMS platform with zero licensing complexity. You're billed per patient processed — that's it.</p>
        <ul class="checks">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>No fixed monthly license fees</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>All core RIS/RIMS features included</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Scales automatically with your volume</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Free onboarding &amp; 24×7 support</span></li>
        </ul>
        <a href="#demo" class="btn btn-navy" style="margin-top:26px;">Get Started</a>
      </div>
      <div class="payg-price">
        <div style="font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.7);">Starting at</div>
        <div class="big">₹5</div>
        <div class="sub">per patient</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== LEAD-GEN ===== -->
<section class="section section--soft" id="demo">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">Get Started</span>
        <h2 class="h-sec">Modernize your imaging center</h2>
        <p class="lead" style="margin:16px 0 28px;">Book a free RIS/RIMS demo and start with our flexible ₹5-per-patient model.</p>
        <?php contact_strip(); ?>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Request a RIS/RIMS demo</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">We'll respond within one business day.</p>
        <?php lead_form([
          'hidden'   => ['interest' => 'RIS / RIMS'],
          'aside'    => form_select('orgType', 'Monthly Scan Volume', ['Under 500 scans/month', '500–2000 scans/month', '2000–5000 scans/month', '5000+ scans/month']),
          'interest' => '',
        ]); ?>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
