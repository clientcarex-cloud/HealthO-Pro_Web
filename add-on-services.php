<?php
$page        = 'add-on-services';
$title       = 'Add-on Services — SMS, WhatsApp, Email & AI Call Credits | HealthO Pro';
$description = 'Live prices for HealthO Pro messaging credits: Transactional and Promotional SMS, official WhatsApp, Email and AI Call plans, plus one-time DLT and WhatsApp API setup. Simple, prepaid, GST shown.';

require __DIR__ . '/partials/addons-data.php';
$catalog = ho_addons_catalog(isset($_GET['refresh']));

$faqs = [
    'What is the difference between Transactional and Promotional messages?' =>
        'Transactional messages are service updates a patient expects from you: OTPs, report ready, bills, payment receipts and appointment reminders. Promotional messages market your hospital: offers, health camps, check-up packages and festival wishes. For SMS, Transactional messages are delivered any time, even to DND numbers; Promotional SMS go out in daytime only and do not reach DND numbers.',
    'Which one should my clinic or hospital buy?' =>
        'Almost every clinic, lab and hospital needs Transactional credits, because they carry the everyday patient updates. Add Promotional credits only if you plan to run campaigns or send offers.',
    'Do credits expire?' =>
        'Yes. Each plan is valid for the number of days shown on its card (for example 30, 120 or 365 days). Longer plans give more days and a lower price per credit.',
    'Are the prices inclusive of GST?' =>
        'The plan price and the per-credit rate are before tax. Each card also shows the GST and the total you pay including it.',
    'Do I need the DLT or WhatsApp API setup?' =>
        'DLT registration is required once by Indian telecom rules before any SMS can be sent. The WhatsApp API setup is needed once to send official WhatsApp messages from your own number. If you already have them, you can skip the setup and just buy credits.',
    'How do I buy or recharge credits?' =>
        'Log in to HealthO Pro and open Communication Hub → Recharge Credits. Pick your plans, add them to the cart and pay online. Credits are added to your account right after payment.',
];

$crumbs     = ['Solutions' => 'https://healtho.pro/solutions', 'Add-on Services' => 'https://healtho.pro/add-on-services'];
$head_extra = '<style>' . file_get_contents(__DIR__ . '/partials/addons-style.css') . '</style>';

require __DIR__ . '/partials/head.php';

$login = 'https://healtho.pro/login';

$icons = [
    'sms'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>',
    'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35M12 21.5a9.5 9.5 0 0 1-4.84-1.32l-.35-.21-3.59.94.96-3.5-.23-.36A9.5 9.5 0 1 1 12 21.5M12 2a11.5 11.5 0 0 0-9.86 17.4L1 23l3.7-1.12A11.5 11.5 0 1 0 12 2"/></svg>',
    'email'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg>',
    'aicall'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>',
];
$tick = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>';

/** One plan card. */
$card = static function (array $c, string $cta, string $href) use ($tick): void { ?>
        <article class="ao-card<?= $c['best'] ? ' is-best' : '' ?>">
          <?php if ($c['best']): ?><span class="ao-best">Best value</span><?php endif; ?>
          <span class="ao-cycle"><?= h($c['cycle']) ?></span>
          <h4 class="ao-name"><?= h($c['name']) ?></h4>
          <div class="ao-rate">
            <?php if ($c['was_rate']): ?><s><?= h($c['was_rate']) ?></s><?php endif; ?>
            <strong><?= h($c['rate']) ?></strong><span>/ credit</span>
          </div>
          <div class="ao-row"><span><?= h($c['credits']) ?> credits</span><b><?= h($c['pay']) ?></b></div>
          <div class="ao-pay">
            <div><span class="ao-pay-l">You pay once</span><span class="ao-pay-a"><?= h($c['pay']) ?></span></div>
            <?php if ($c['tax']): ?><div class="ao-tax"><span><?= h($c['tax']) ?></span><span><?= h($c['total']) ?> in total</span></div><?php endif; ?>
          </div>
          <?php if ($c['save']): ?><div class="ao-save">You save <?= h($c['save']) ?></div><?php endif; ?>
          <ul class="ao-feats">
            <li><span class="ao-ck"><?= $tick ?></span>Valid for <?= (int) $c['days'] ?> days</li>
            <?php if ($c['per_day']): ?><li><span class="ao-ck"><?= $tick ?></span>About <?= h($c['per_day']) ?> credits a day</li><?php endif; ?>
            <?php if ($c['offer']): ?><li><span class="ao-ck"><?= $tick ?></span><?= h($c['offer']) ?></li><?php endif; ?>
          </ul>
          <a href="<?= h($href) ?>" class="btn <?= $c['best'] ? 'btn-primary' : 'btn-navy' ?> btn-block ao-cta"><?= h($cta) ?></a>
        </article>
<?php };
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/solutions">Solutions</a><span>›</span>Add-on Services</div>
    <h1>Reach every patient with <span class="text-grad">messaging credits</span></h1>
    <p>SMS, WhatsApp, Email and AI Call credits for your HealthO Pro account. Prepaid, no lock-in, and the prices below are always live.</p>
  </div>
</header>

<!-- ===== HOW TO CHOOSE ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">How to choose</span><h2 class="h-sec">Pick a plan in 3 easy steps</h2></div>

    <ol class="ao-steps">
      <li class="reveal"><span class="ao-num">1</span><div><h4>Pick the channel</h4><p><?= h(implode(', ', array_column($catalog['channels'], 'label')) ?: 'SMS, WhatsApp, Email or AI Call') ?>. Buy only the ones you use.</p></div></li>
      <li class="reveal d1"><span class="ao-num">2</span><div><h4>Pick the message type</h4><p><strong>Transactional</strong> for patient updates, <strong>Promotional</strong> for marketing. Most clinics need Transactional.</p></div></li>
      <li class="reveal d2"><span class="ao-num">3</span><div><h4>Pick how long</h4><p>Monthly, Quarterly or Yearly. Longer plans cost less per credit. Credits stay valid for the plan's days.</p></div></li>
    </ol>

    <div class="ao-types">
<?php foreach (ADDONS_TYPES as $key => $t): ?>
      <div class="ao-type reveal" style="--tc: <?= h($t['colour']) ?>">
        <div class="ao-type-head"><span class="ao-tag"><?= h($t['tag']) ?></span><h3><?= h($t['title']) ?></h3></div>
        <p class="ao-type-lead"><?= h($t['lead']) ?></p>
        <ul>
          <li><span class="ao-ck"><?= $tick ?></span><span><strong>For example:</strong> <?= h($t['eg']) ?></span></li>
          <li><span class="ao-ck"><?= $tick ?></span><span><strong>SMS:</strong> <?= h($t['sms']) ?></span></li>
          <li><span class="ao-ck"><?= $tick ?></span><span><strong>WhatsApp:</strong> <?= h($t['wa']) ?></span></li>
        </ul>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== LIVE PLANS ===== -->
<section class="section" id="plans">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">Live pricing</span><h2 class="h-sec">Credit plans</h2><p class="lead">Every price on this page comes straight from HealthO Pro, so it always matches what you see in the app.</p></div>

<?php if (!$catalog['channels'] && !$catalog['setup']): ?>
    <div class="ao-empty">
      <h3>Prices are being updated</h3>
      <p>Please check back in a few minutes, or call us on <a href="tel:<?= str_replace(' ', '', PHONE_SALES) ?>"><?= PHONE_SALES ?></a> for the latest plans.</p>
    </div>
<?php else: ?>
    <div class="ao-pdf">
      <button type="button" class="pb-brochure" id="aoPdf" aria-label="Download the messaging credits pricing as a PDF">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        <span>Download Pricing PDF</span><span class="pb-brochure-tag">PDF</span>
      </button>
    </div>

    <nav class="ao-jump" aria-label="Jump to a channel">
<?php foreach ($catalog['channels'] as $ch): ?>
      <a href="#<?= h($ch['slug']) ?>" style="--cc: <?= h($ch['colour']) ?>"><span class="ao-jump-ic"><?= $icons[$ch['icon']] ?></span><?= h($ch['label']) ?></a>
<?php endforeach; ?>
<?php if ($catalog['setup']): ?>
      <a href="#setup" style="--cc: #EA580C"><span class="ao-jump-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76Z"/></svg></span>One-time setup</a>
<?php endif; ?>
    </nav>

<?php foreach ($catalog['channels'] as $ch): ?>
    <div class="ao-channel" id="<?= h($ch['slug']) ?>">
      <div class="ao-ch-head" style="--cc: <?= h($ch['colour']) ?>">
        <span class="ao-ch-ic"><?= $icons[$ch['icon']] ?></span>
        <h3><?= h($ch['label']) ?></h3>
        <span class="ao-ch-count"><?= (int) $ch['count'] ?> plan<?= $ch['count'] > 1 ? 's' : '' ?></span>
      </div>
<?php if ($ch['parts']): foreach ($ch['parts'] as $part): ?>
      <div class="ao-part" style="--tc: <?= h($part['colour']) ?>">
        <span class="ao-tag"><?= h($part['tag']) ?></span><p><?= h($part['desc']) ?></p>
      </div>
      <div class="ao-grid">
<?php foreach ($part['cards'] as $c) { $card($c, 'Buy now', $login); } ?>
      </div>
<?php endforeach; else: ?>
      <div class="ao-grid">
<?php foreach ($ch['cards'] as $c) { $card($c, 'Buy now', $login); } ?>
      </div>
<?php endif; ?>
    </div>
<?php endforeach; ?>

<?php if ($catalog['setup']): ?>
    <div class="ao-channel ao-setup" id="setup">
      <div class="ao-ch-head" style="--cc: #EA580C">
        <span class="ao-ch-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76Z"/></svg></span>
        <h3>One-time setup services</h3>
        <span class="ao-ch-count">Pay once · not a recharge</span>
      </div>
      <p class="ao-setup-intro">New to SMS or official WhatsApp? Our team does the paperwork for you. You pay for this only once; if you already have it, skip it and just buy credits.</p>
      <div class="ao-grid">
<?php foreach ($catalog['setup'] as $s): ?>
        <article class="ao-card ao-card--setup">
          <span class="ao-cycle">One-time · <?= h($s['channel']) ?></span>
          <h4 class="ao-name"><?= h($s['name']) ?></h4>
          <div class="ao-rate"><?php if ($s['was']): ?><s><?= h($s['was']) ?></s><?php endif; ?><strong><?= h($s['pay']) ?></strong><span>one-time</span></div>
          <p class="ao-who"><?= h($s['who']) ?></p>
          <div class="ao-pay">
            <div><span class="ao-pay-l">You pay once</span><span class="ao-pay-a"><?= h($s['pay']) ?></span></div>
            <?php if ($s['tax']): ?><div class="ao-tax"><span><?= h($s['tax']) ?></span><span><?= h($s['total']) ?> in total</span></div><?php endif; ?>
          </div>
          <?php if ($s['save']): ?><div class="ao-save">You save <?= h($s['save']) ?></div><?php endif; ?>
          <ul class="ao-feats">
<?php foreach ($s['features'] as $f): ?>
            <li><span class="ao-ck"><?= $tick ?></span><?= h($f) ?></li>
<?php endforeach; ?>
          </ul>
          <a href="<?= h($login) ?>" class="btn btn-navy btn-block ao-cta">Get it now</a>
        </article>
<?php endforeach; ?>
      </div>
    </div>
<?php endif; ?>

    <p class="ao-note">Plan prices and per-credit rates are before tax; GST is shown on each card. Already a customer? Recharge any time from <strong>Communication Hub → Recharge Credits</strong> inside HealthO Pro.</p>
<?php endif; ?>
  </div>
</section>

<!-- ===== FAQ ===== -->
<?php faq_section($faqs, 'Messaging credits, answered', 'section section--soft'); ?>

<!-- ===== CTA ===== -->
<section class="section">
  <div class="container">
    <div class="cta-band reveal">
      <h2 class="h-sec">Not sure which plan fits?</h2>
      <p>Tell us how many patients you message each month and we'll suggest the right mix of credits.</p>
      <div class="cta-actions">
        <a href="/contact" class="btn btn-primary btn-lg">Talk to Sales</a>
        <a href="tel:<?= str_replace(' ', '', PHONE_SALES) ?>" class="btn btn-ghost-light btn-lg">Call <?= PHONE_SALES ?></a>
      </div>
    </div>
  </div>
</section>

<?php if ($catalog['channels'] || $catalog['setup']): ?>
<script>
/* Pricing PDF: the live plans above, drawn by js/credits-brochure.js (the same
   renderer as the in-app "Download Pricing PDF"). Loaded on the first click. */
(function () {
  var btn = document.getElementById('aoPdf');
  if (!btn) return;
  var data = <?= json_encode(ho_addons_brochure($catalog), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
  var label = btn.querySelector('span');
  var idle = label.textContent;

  function loadRenderer() {
    if (window.HealthOCreditsBrochure) return Promise.resolve();
    return new Promise(function (resolve, reject) {
      var s = document.createElement('script');
      s.src = '/js/credits-brochure.js?v=<?= ASSET_VER ?>';
      s.onload = function () { window.HealthOCreditsBrochure ? resolve() : reject(new Error('empty')); };
      s.onerror = reject;
      document.head.appendChild(s);
    });
  }

  function say(text, ms) {
    label.textContent = text;
    if (ms) setTimeout(function () { label.textContent = idle; }, ms);
  }

  btn.addEventListener('click', function () {
    if (btn.disabled) return;
    btn.disabled = true;
    say('Preparing PDF…');
    data.prepared = new Date().toLocaleString('en-IN', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: '2-digit' });
    data.meta = [data.meta[0], ['Prepared ', data.prepared]];
    loadRenderer()
      .then(function () { return window.HealthOCreditsBrochure.download(data); })
      .then(function () { say('Downloaded ✓', 2500); })
      .catch(function () { say('Could not prepare — try again', 3500); })
      .then(function () { btn.disabled = false; });
    if (window.gtag) gtag('event', 'download_addons_pdf');
  });
})();
</script>
<?php endif; ?>
<?php require __DIR__ . '/partials/foot.php';
