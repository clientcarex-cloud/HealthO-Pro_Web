<?php
$page        = 'pricing';
$title       = 'Pricing — HIMS, LIMS, CIMS & RIS/RIMS Plans | HealthO Pro';
$description = 'Transparent HealthO Pro pricing. Choose the billing cycle that suits you for HIMS, LIMS and CIMS (Startup, Business, Enterprise plans), or pay-as-you-go RIS/RIMS at just ₹5 per patient.';
require __DIR__ . '/partials/user-stepper.php';
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Pricing</div>
    <h1>Simple, transparent <span class="text-grad">pricing</span></h1>
    <p>Flexible plans for every healthcare provider — choose the billing cycle that suits you and save. Choose your product below.</p>
  </div>
</header>

<!-- ===== PRICING ===== -->
<section class="section section--soft section--bar">

  <!-- ===== SALE OFFER BANNER — filled by script.js from the SaaS "Sale Offer" module ===== -->
  <div class="container">
    <div class="offer-mount" id="offerBanner"></div>
  </div>

  <!-- ===== SMART CONTROL BAR — product + team size + billing cycle in one sticky row ===== -->
  <div class="pricing-bar" id="pricingBar">
    <div class="container">
      <div class="pb-inner">

        <!-- Product -->
        <div class="product-tabs pb-products">
          <a href="#tab-hims" id="tab-hims" class="product-tab active" data-product="hims"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/></svg>HIMS</a>
          <a href="#tab-lims" id="tab-lims" class="product-tab" data-product="lims"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6M10 3v6l-4 8a2 2 0 0 0 2 3h8a2 2 0 0 0 2-3l-4-8V3"/><path d="M7 14h10"/></svg>LIMS</a>
          <a href="#tab-cims" id="tab-cims" class="product-tab" data-product="cims"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M12 8v8M8 12h8"/></svg>CIMS</a>
          <a href="#tab-ris" id="tab-ris" class="product-tab" data-product="ris"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 3a9 9 0 0 0 0 18M3 12h18"/></svg>RIS / RIMS</a>
        </div>

        <span class="pb-div"></span>

        <!-- Team size -->
        <div class="pb-users">
          <?php user_stepper(); user_slider(); ?>
        </div>

        <span class="pb-div"></span>

        <!-- Billing cycle -->
        <div class="billing-toggle pb-cycle">
          <button data-cycle="quarter">Quarterly</button>
          <button data-cycle="half">Half-Yearly</button>
          <button data-cycle="year" class="active">Yearly<span class="save-badge">Save 20%</span></button>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <!-- ===== HIMS PANEL ===== -->
    <div class="price-panel active" id="panel-hims">
      <div class="dynamic-plans" data-group="hims"></div>
    </div>

    <!-- ===== LIMS PANEL ===== -->
    <div class="price-panel" id="panel-lims">
      <div class="dynamic-plans" data-group="lims"></div>
    </div>

    <!-- ===== CIMS PANEL ===== -->
    <div class="price-panel" id="panel-cims">
      <div class="dynamic-plans" data-group="cims"></div>
    </div>

    <!-- ===== RIS PANEL ===== -->
    <div class="price-panel" id="panel-ris">
      <div id="plan-ris-payg" class="payg">
        <div>
          <span class="eyebrow">Pay-As-You-Go</span>
          <h3 class="h-sec" style="margin-bottom:14px;"><a href="#plan-ris-payg" style="color:inherit;text-decoration:none;">RIS / RIMS — billed per patient</a></h3>
          <p style="color:var(--text-soft);margin-bottom:22px;">Get the complete Radiology Information System with no fixed license fees. You only pay for the patients you process — pricing that scales naturally with your imaging volume.</p>
          <ul class="checks">
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>All core RIS/RIMS features included</span></li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Scheduling, DICOM &amp; templated reporting</span></li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Free onboarding &amp; 24×7 support</span></li>
            <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>No long-term lock-in</span></li>
          </ul>
          <a href="contact" class="btn btn-primary" style="margin-top:26px;">Get Started</a>
        </div>
        <div class="payg-price">
          <div style="font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.7);">Pay-As-You-Go</div>
          <div class="big">₹5</div>
          <div class="sub">per patient</div>
        </div>
      </div>
    </div>


  </div>
</section>

    <!-- ===== INCLUDED IN EVERY PLAN ===== -->
<section class="section">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">Every Plan Includes</span><h2 class="h-sec">More value, built in</h2></div>
    <div class="grid grid-4">
      <div class="feat reveal"><span class="feat-ic bg-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10Z"/></svg></span><div><h4>Cloud hosting</h4><p>Secure, reliable &amp; always available.</p></div></div>
      <div class="feat reveal d1"><span class="feat-ic bg-cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/></svg></span><div><h4>Data security</h4><p>Encryption, backups &amp; audit logs.</p></div></div>
      <div class="feat reveal d2"><span class="feat-ic bg-navy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></span><div><h4>Onboarding</h4><p>Guided setup &amp; data migration.</p></div></div>
      <div class="feat reveal d3"><span class="feat-ic bg-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg></span><div><h4>Free updates</h4><p>Continuous improvements at no cost.</p></div></div>
    </div>
  </div>
</section>

    <!-- ===== FAQ ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">FAQ</span><h2 class="h-sec">Pricing questions, answered</h2></div>
    <div class="faq">
      <div class="faq-item reveal"><button class="faq-q">Can I switch between billing cycles?<span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner">Yes. You can pick any billing cycle shown above and switch at renewal. Yearly billing gives the best value with up to 20% savings.</div></div></div>
      <div class="faq-item reveal"><button class="faq-q">Is there a setup or onboarding fee?<span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner">Standard onboarding, configuration and data migration are included. Complex enterprise migrations may be scoped separately — your account manager will confirm upfront.</div></div></div>
      <div class="faq-item reveal"><button class="faq-q">How does RIS/RIMS pay-as-you-go billing work?<span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner">You're billed a simple ₹5 per patient processed through the system. There are no fixed license fees, so your cost scales directly with your imaging volume.</div></div></div>
      <div class="faq-item reveal"><button class="faq-q">Can I use multiple products together?<span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner">Absolutely. HIMS, LIMS, CIMS and RIS/RIMS are built to work together. Combine modules and we'll provide a bundled quote tailored to your organization.</div></div></div>
      <div class="faq-item reveal"><button class="faq-q">Do you offer a free demo before purchase?<span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner">Yes — book a free, no-obligation demo and our team will walk you through the platform configured for your use case.</div></div></div>
    </div>
  </div>
</section>

    <!-- ===== CTA ===== -->
<section class="section">
  <div class="container">
    <div class="cta-band reveal">
      <h2 class="h-sec">Get a custom quote in minutes</h2>
      <p>Tell us about your organization and we'll recommend the right plan and pricing for you.</p>
      <div class="cta-actions">
        <a href="contact" class="btn btn-primary btn-lg">Talk to Sales</a>
        <a href="tel:+919700730044" class="btn btn-ghost-light btn-lg">Call +91 97007 30044</a>
      </div>
    </div>
  </div>
</section>

    
<?php require __DIR__ . '/partials/foot.php';
