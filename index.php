<?php
$page        = '';
$title       = 'HealthO Pro — Empowering Healthcare Providers | HIMS, LIMS, CIMS & RIS Software';
$description = 'HealthO Pro by Healthocare Private Limited delivers cloud-enabled HIMS, LIMS, CIMS and RIS/RIMS software for hospitals, labs, clinics and radiology centers across India, the GCC, Egypt and Canada. Serving healthcare providers since 2011.';
$head_extra  = <<<'HTML'
<meta name="keywords" content="HIMS, LIMS, CIMS, RIS, RIMS, hospital management software, laboratory information system, clinic management, radiology information system, healthcare software India">
<meta name="author" content="Healthocare Private Limited">
<meta property="og:type" content="website">
<meta property="og:title" content="HealthO Pro — Empowering Healthcare Providers">
<meta property="og:description" content="Cloud-enabled HIMS, LIMS, CIMS and RIS/RIMS software for hospitals, labs, clinics and radiology centers. Trusted across 8 countries since 2011.">
<meta property="og:image" content="https://healtho.pro/assets/images/logo.png">
<meta name="twitter:card" content="summary_large_image">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Healthocare Private Limited",
  "alternateName": "HealthO Pro",
  "url": "https://healtho.pro",
  "logo": "https://healtho.pro/assets/images/logo.png",
  "slogan": "Empowering Healthcare Providers",
  "foundingDate": "2011",
  "email": "sales@healtho.pro",
  "telephone": "+91-97007-30044",
  "areaServed": ["IN", "AE", "QA", "SA", "BH", "KW", "EG", "CA"],
  "sameAs": []
}
</script>
HTML;
require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';
?>
<!-- ===== HERO ===== -->
<header class="hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="hero-grid">
      <div class="hero-copy">
        <span class="eyebrow award reveal">India's Leading Healthcare Software</span>
        <h1 class="reveal d1">Empowering Healthcare Providers with <span class="text-grad">Smart, Connected</span> Software</h1>
        <p class="reveal d2">From hospitals and laboratories to clinics and radiology centers — HealthO Pro unifies your operations on one secure, cloud-enabled platform. Trusted across 8 countries since 2011.</p>
        <div class="hero-actions reveal d3">
          <a href="contact" data-demo class="btn btn-primary btn-lg">Book a Free Demo
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="solutions" class="btn btn-ghost-light btn-lg">Explore Solutions</a>
        </div>
        <div class="hero-trust reveal d4">
          <div><div class="num"><span data-count="13" data-suffix="+">0</span></div><div class="lbl">Years of Expertise</div></div>
          <div><div class="num"><span data-count="8" data-suffix="">0</span></div><div class="lbl">Countries Served</div></div>
          <div><div class="num"><span data-count="1200" data-suffix="+">0</span></div><div class="lbl">Healthcare Clients</div></div>
        </div>
      </div>
      <div class="hero-visual reveal d2">
        <div class="hero-card">
          <img src="assets/images/hero-doctors-machines.webp" alt="HealthO Pro healthcare management dashboard" loading="eager" width="640" height="480" style="object-fit: cover;">
        </div>
        <div class="hero-float float-tl">
          <span class="fi bg-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg></span>
          <span><span class="ft">HIPAA-ready</span><span class="fs">Secure &amp; compliant</span></span>
        </div>
        <div class="hero-float float-br">
          <span class="fi bg-cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/></svg></span>
          <span><span class="ft">+38% efficiency</span><span class="fs">Faster workflows</span></span>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- ===== TRUST STRIP ===== -->
<section class="section" style="padding:48px 0;">
  <div class="container">
    <p class="center" style="color:var(--text-mute);font-weight:600;font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;margin-bottom:26px;">Trusted by healthcare organizations across the globe</p>
    <div class="logos-strip reveal">
      <span class="lg"><img class="flag-png" src="assets/images/flags/in.png" alt="" aria-hidden="true" width="160" height="107" loading="lazy" decoding="async"> India</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/ae.png" alt="" aria-hidden="true" width="160" height="80" loading="lazy" decoding="async"> UAE</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/qa.png" alt="" aria-hidden="true" width="160" height="63" loading="lazy" decoding="async"> Qatar</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/sa.png" alt="" aria-hidden="true" width="160" height="107" loading="lazy" decoding="async"> Saudi Arabia</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/bh.png" alt="" aria-hidden="true" width="160" height="96" loading="lazy" decoding="async"> Bahrain</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/kw.png" alt="" aria-hidden="true" width="160" height="80" loading="lazy" decoding="async"> Kuwait</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/eg.png" alt="" aria-hidden="true" width="160" height="107" loading="lazy" decoding="async"> Egypt</span>
      <span class="lg"><img class="flag-png" src="assets/images/flags/ca.png" alt="" aria-hidden="true" width="160" height="80" loading="lazy" decoding="async"> Canada</span>
    </div>
  </div>
</section>

<!-- ===== SOLUTIONS OVERVIEW ===== -->
<section class="section section--soft" id="solutions">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Our Solutions</span>
      <h2 class="h-sec">One platform for every <span class="text-grad">healthcare workflow</span></h2>
      <p class="lead">Purpose-built, modular systems for hospitals, laboratories, clinics and radiology centers — scalable, secure and cloud-enabled.</p>
    </div>
    <div class="grid grid-4">
      <article class="card sol-card reveal">
        <span class="sol-ic sol-ic-img"><img src="assets/images/icon-hims.webp" alt="HIMS" width="72" height="72" loading="lazy" decoding="async"></span>
        <h3 class="h-card">HIMS</h3>
        <p>Hospital Information Management System — OPD/IPD, billing, pharmacy, EMR and bed management unified end-to-end.</p>
        <a href="hims" class="sol-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </article>
      <article class="card sol-card reveal d1">
        <span class="sol-ic sol-ic-img"><img src="assets/images/icon-lims.webp" alt="LIMS" width="72" height="72" loading="lazy" decoding="async"></span>
        <h3 class="h-card">LIMS</h3>
        <p>Laboratory Information Management System — sample tracking, analyzer integration and instant, accurate reporting.</p>
        <a href="lims" class="sol-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </article>
      <article class="card sol-card reveal d2">
        <span class="sol-ic sol-ic-img"><img src="assets/images/icon-cims.webp" alt="CIMS" width="72" height="72" loading="lazy" decoding="async"></span>
        <h3 class="h-card">CIMS</h3>
        <p>Clinic Information Management System — appointments, e-prescriptions and patient records for modern clinics.</p>
        <a href="cims" class="sol-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </article>
      <article class="card sol-card reveal d3">
        <span class="sol-ic sol-ic-img"><img src="assets/images/icon-ris.webp" alt="RIS / RIMS" width="72" height="72" loading="lazy" decoding="async"></span>
        <h3 class="h-card">RIS / RIMS</h3>
        <p>Radiology Information System — scheduling, DICOM-ready reporting and a flexible pay-as-you-go model.</p>
        <a href="ris" class="sol-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </article>
    </div>
  </div>
</section>

<!-- ===== KEY HIGHLIGHTS ===== -->
<section class="section">
  <div class="container">
    <div class="split">
      <div class="split-copy reveal">
        <span class="eyebrow">Why HealthO Pro</span>
        <h2 class="h-sec">Built for healthcare. Trusted by providers.</h2>
        <p class="lead" style="margin:16px 0 28px;">We combine deep domain expertise with modern, secure technology so your teams can focus on what matters most — patient care.</p>
        <ul class="checks">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><strong>India's leading provider</strong> of healthcare management software.</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><strong>Trusted across 8 countries</strong> by hospitals, labs and clinics.</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><strong>End-to-end coverage</strong> for hospitals, laboratories, clinics and radiology.</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span><strong>Scalable, secure &amp; cloud-enabled</strong> — grows with your organization.</span></li>
        </ul>
        <a href="solutions" class="btn btn-navy" style="margin-top:30px;">Discover the platform</a>
      </div>
      <div class="split-media reveal d2">
        <div class="grid grid-2" style="gap:18px;">
          <div class="card" style="padding:26px;"><span class="feat-ic bg-cyan" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></span><h4>Secure by design</h4><p>Role-based access, audit trails and encrypted data.</p></div>
          <div class="card" style="padding:26px;margin-top:28px;"><span class="feat-ic bg-green" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10Z"/></svg></span><h4>Cloud-enabled</h4><p>Access anytime, anywhere with reliable uptime.</p></div>
          <div class="card" style="padding:26px;"><span class="feat-ic bg-navy" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/></svg></span><h4>Scalable</h4><p>From single clinics to multi-site hospital chains.</p></div>
          <div class="card" style="padding:26px;margin-top:28px;"><span class="feat-ic bg-amber" style="margin-bottom:16px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg></span><h4>24×7 support</h4><p>Dedicated assistance whenever you need it.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== JOURNEY TIMELINE ===== -->
<section class="section section--tint">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Our Journey</span>
      <h2 class="h-sec">Serving healthcare since <span class="text-grad">2011</span></h2>
      <p class="lead">Healthocare Private Limited has evolved with the industry — from local IT roots to a global, preventive-healthcare vision.</p>
    </div>
    <div class="timeline">
      <div class="tl-item reveal"><span class="tl-dot"></span><div class="tl-card"><div class="tl-card-head"><span class="tl-year tl-year--inline">2011</span><img src="assets/images/health-spark-logo.webp" alt="Health Spark" class="tl-logo" width="106" height="34" loading="lazy" decoding="async"></div><h4>Started as Hallys IT</h4><p>Began operations delivering technology solutions to the healthcare industry.</p></div></div>
      <div class="tl-item reveal"><span class="tl-dot"></span><div class="tl-card"><div class="tl-card-head"><span class="tl-year tl-year--inline">2015</span><img src="assets/images/health-spark-logo.webp" alt="Health Spark" class="tl-logo" width="106" height="34" loading="lazy" decoding="async"></div><h4>Expanded as IncraSoft Private Limited</h4><p>Scaled our products and team to serve a growing base of healthcare providers.</p></div></div>
      <div class="tl-item reveal"><span class="tl-dot"></span><div class="tl-card"><div class="tl-card-head"><span class="tl-year tl-year--inline">2023</span><img src="assets/images/logo.webp" alt="HealthO Pro" class="tl-logo" width="160" height="34" loading="lazy" decoding="async"></div><h4>Rebranded as Healthocare Private Limited</h4><p>A global vision focused on preventive healthcare and empowering healthcare providers worldwide.</p></div></div>
    </div>
  </div>
</section>

<!-- ===== GLOBAL PRESENCE ===== -->
<section class="section">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Global Presence</span>
      <h2 class="h-sec">Serving providers across <span class="text-grad">8 countries</span></h2>
      <p class="lead">A trusted partner to hospitals, laboratories, clinics and radiology centers worldwide.</p>
    </div>
    <div class="presence-grid">
      <div class="country reveal"><span class="flag"><img class="flag-png" src="assets/images/flags/in.png" alt="India flag" width="160" height="107" loading="lazy" decoding="async"></span><span><span class="cn">India</span><span class="cr">Headquarters</span></span></div>
      <div class="country reveal d1"><span class="flag"><img class="flag-png" src="assets/images/flags/ae.png" alt="UAE flag" width="160" height="80" loading="lazy" decoding="async"></span><span><span class="cn">United Arab Emirates</span><span class="cr">Dubai</span></span></div>
      <div class="country reveal d2"><span class="flag"><img class="flag-png" src="assets/images/flags/qa.png" alt="Qatar flag" width="160" height="63" loading="lazy" decoding="async"></span><span><span class="cn">Qatar</span><span class="cr">GCC region</span></span></div>
      <div class="country reveal d3"><span class="flag"><img class="flag-png" src="assets/images/flags/sa.png" alt="Saudi Arabia flag" width="160" height="107" loading="lazy" decoding="async"></span><span><span class="cn">Saudi Arabia</span><span class="cr">GCC region</span></span></div>
      <div class="country reveal"><span class="flag"><img class="flag-png" src="assets/images/flags/bh.png" alt="Bahrain flag" width="160" height="96" loading="lazy" decoding="async"></span><span><span class="cn">Bahrain</span><span class="cr">GCC region</span></span></div>
      <div class="country reveal d1"><span class="flag"><img class="flag-png" src="assets/images/flags/kw.png" alt="Kuwait flag" width="160" height="80" loading="lazy" decoding="async"></span><span><span class="cn">Kuwait</span><span class="cr">GCC region</span></span></div>
      <div class="country reveal d2"><span class="flag"><img class="flag-png" src="assets/images/flags/eg.png" alt="Egypt flag" width="160" height="107" loading="lazy" decoding="async"></span><span><span class="cn">Egypt</span><span class="cr">North Africa</span></span></div>
      <div class="country reveal d3"><span class="flag"><img class="flag-png" src="assets/images/flags/ca.png" alt="Canada flag" width="160" height="80" loading="lazy" decoding="async"></span><span><span class="cn">Canada</span><span class="cr">North America</span></span></div>
    </div>
  </div>
</section>

<!-- ===== STATS BAND ===== -->
<section class="section section--navy">
  <div class="container">
    <div class="stats">
      <div class="stat reveal"><div class="n"><span data-count="13" data-suffix="+">0</span></div><div class="l">Years in healthcare IT</div></div>
      <div class="stat reveal d1"><div class="n"><span data-count="1200" data-suffix="+">0</span></div><div class="l">Healthcare clients</div></div>
      <div class="stat reveal d2"><div class="n"><span data-count="8" data-suffix="">0</span></div><div class="l">Countries served</div></div>
      <div class="stat reveal d3"><div class="n"><span data-count="99.9" data-suffix="%">0</span></div><div class="l">Platform uptime</div></div>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS PREVIEW ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Client Success</span>
      <h2 class="h-sec">Loved by healthcare teams</h2>
      <p class="lead">Real results from hospitals, laboratories and clinics that run on HealthO Pro.</p>
    </div>
    <div class="grid grid-3">
      <article class="tcard reveal">
        <div class="stars">★★★★★</div>
        <blockquote>"HealthO Pro's HIMS unified our OPD, IPD and pharmacy in one place. Billing errors dropped and patient turnaround improved dramatically."</blockquote>
        <div class="tperson"><span class="tavatar bg-navy">RA</span><span><span class="tn">Dr. Rakesh Anand</span><span class="tr">Medical Director, City Care Hospital</span></span></div>
      </article>
      <article class="tcard reveal d1">
        <div class="stars">★★★★★</div>
        <blockquote>"The LIMS analyzer integration is flawless. Our reporting time fell by half and our clients trust the accuracy completely."</blockquote>
        <div class="tperson"><span class="tavatar bg-cyan">SM</span><span><span class="tn">Sara Mansoor</span><span class="tr">Lab Owner, MedLab Diagnostics, Dubai</span></span></div>
      </article>
      <article class="tcard reveal d2">
        <div class="stars">★★★★★</div>
        <blockquote>"As a growing clinic chain, CIMS scaled with us effortlessly. Appointments and e-prescriptions are now a breeze."</blockquote>
        <div class="tperson"><span class="tavatar bg-green">PV</span><span><span class="tn">Dr. Priya Verma</span><span class="tr">Founder, WellCare Clinics</span></span></div>
      </article>
    </div>
    <div class="center" style="margin-top:44px;">
      <a href="testimonials" class="btn btn-outline">Read all success stories</a>
    </div>
  </div>
</section>

<!-- ===== LEAD-GEN FORM ===== -->
<section class="section" id="demo">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">Get Started</span>
        <h2 class="h-sec">See HealthO Pro in action</h2>
        <p class="lead" style="margin:16px 0 28px;">Book a personalized demo and discover how our software can streamline your hospital, lab, clinic or radiology center.</p>
        <ul class="checks">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Tailored walkthrough for your organization</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Transparent pricing &amp; flexible plans</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>No obligation — response within one business day</span></li>
        </ul>
        <?php contact_strip(true); ?>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Request a free demo</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">Fill in the form and our team will reach out shortly.</p>
        <?php lead_form(); ?>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
