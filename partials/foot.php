<?php
/**
 * Shared footer, floating actions and script tags — closes the document.
 *
 * Optional, set by the page before including this file:
 *   $foot_scripts  array of extra script src paths loaded after script.js
 */
require_once __DIR__ . '/site.php';
require_once __DIR__ . '/seo.php';
require_once __DIR__ . '/lead-form.php';

$foot_scripts = $foot_scripts ?? [];

// On a product page (or a page that sets $demo_interest) the popup pre-selects that product.
$demo_interest = $demo_interest ?? (PRODUCTS[$page ?? '']['short'] ?? '');
?>
<!-- Demo popup — script.js opens this on any [data-demo] click.
     Step 1 captures the lead (api/contact.php → CRM), step 2 books a live slot
     on the CRM demo calendar (api/demo-booking.php), step 3 confirms. -->
<div class="demo-modal" role="dialog" aria-modal="true" aria-labelledby="dmTitle" data-page-interest="<?= h($demo_interest) ?>">
  <div class="demo-modal__dialog dm" data-step="1">
    <button type="button" class="demo-modal__close" aria-label="Close">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <div class="dm-top">
      <span class="dm-eyebrow"><span class="dm-live"></span>Free · 30-min live demo</span>
      <h3 class="dm-title" id="dmTitle">Book your free demo</h3>
      <p class="dm-sub" data-sub>Tell us about you, then pick a time that suits you.</p>
      <ol class="dm-steps" aria-label="Progress">
        <li data-dot="1"><span>1</span>Details</li>
        <li data-dot="2"><span>2</span>Pick a time</li>
        <li data-dot="3"><span>3</span>Confirmed</li>
      </ol>
    </div>

    <!-- Step 1: who are you -->
    <form class="dm-pane dm-pane--1" data-pane="1" data-geo novalidate autocomplete="on">
      <div class="form-status"></div>
      <input type="hidden" name="source" value="Demo popup">
      <input type="hidden" name="interest" value="">
      <input type="hidden" name="message" value="Requested a demo from the website popup.">
      <div class="dm-grid">
        <div class="field dm-full">
          <label for="dmName">Full name <span class="req">*</span></label>
          <input id="dmName" type="text" name="fullName" placeholder="Dr. Anjali Rao" autocomplete="name" required>
          <span class="err-msg">Please enter your name.</span>
        </div>
        <div class="field">
          <label for="dmPhone">Mobile number <span class="req">*</span></label>
          <input id="dmPhone" type="tel" name="phone" placeholder="98765 43210" inputmode="tel" autocomplete="tel" required>
          <span class="err-msg">Enter a valid mobile number.</span>
        </div>
        <div class="field">
          <label for="dmEmail">Work email <span class="req">*</span></label>
          <input id="dmEmail" type="email" name="email" placeholder="you@hospital.com" inputmode="email" autocomplete="email" required>
          <span class="err-msg">Enter a valid email — the invite goes here.</span>
        </div>
      </div>
      <div class="field dm-org">
        <label>You run a <span class="req">*</span></label>
        <div class="dm-chips" role="radiogroup" aria-label="Organization type">
<?php foreach (['Hospital' => 'HIMS', 'Laboratory' => 'LIMS', 'Clinic' => 'CIMS', 'Radiology Center' => 'RIS / RIMS', 'Other' => 'Multiple / Not sure'] as $org => $prod): ?>
          <label class="dm-chip"><input type="radio" name="orgType" value="<?= h($org) ?>" data-interest="<?= h($prod) ?>" required><span><?= h($org === 'Radiology Center' ? 'Radiology' : $org) ?></span></label>
<?php endforeach; ?>
        </div>
        <span class="err-msg">Please pick one.</span>
      </div>
      <div class="field">
        <label for="dmCompany">Organization name</label>
        <input id="dmCompany" type="text" name="company" placeholder="e.g. City Care Hospital" autocomplete="organization">
      </div>
      <div class="dm-foot">
        <button type="submit" class="btn btn-primary btn-block btn-lg dm-cta">
          Continue — pick a time
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </button>
        <p class="dm-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>No spam. By continuing you agree to our <a href="/privacy-policy" class="link-cyan">Privacy Policy</a>.</p>
      </div>
    </form>

    <!-- Step 2: pick a slot -->
    <form class="dm-pane dm-pane--2" data-pane="2" novalidate hidden>
      <div class="form-status"></div>
      <div class="dm-hp" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
      <input type="hidden" name="slot" value="">
      <button type="button" class="dm-link" data-back>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 19l-7-7 7-7"/></svg>Edit my details
      </button>
      <div class="dm-cal-head">
        <span class="dm-label">Choose a day</span>
        <div class="dm-cal-nav">
          <button type="button" data-scroll="-1" aria-label="Earlier days"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="m15 18-6-6 6-6"/></svg></button>
          <button type="button" data-scroll="1" aria-label="Later days"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="m9 18 6-6-6-6"/></svg></button>
        </div>
      </div>
      <div class="dm-days" data-days role="listbox" aria-label="Available days"></div>
      <div class="dm-times" data-times aria-live="polite"></div>
      <details class="dm-more">
        <summary>Add a note for our specialist <span>(optional)</span></summary>
        <div data-questions></div>
        <div class="field"><textarea name="notes" rows="3" placeholder="e.g. We are a 50-bed hospital and want to see billing &amp; pharmacy."></textarea></div>
      </details>
      <div class="dm-foot dm-foot--sticky">
        <div class="dm-picked" data-picked>Select a time slot above</div>
        <button type="submit" class="btn btn-primary btn-lg dm-cta" disabled>Confirm booking</button>
      </div>
    </form>

    <!-- Step 3: done -->
    <div class="dm-pane dm-pane--3" data-pane="3" hidden>
      <div class="dm-done">
        <div class="dm-tick"><svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24"/><path d="m15 27 7 7 15-16"/></svg></div>
        <h4 data-done-title>You're booked!</h4>
        <p data-done-text></p>
        <div class="dm-ticket" data-ticket></div>
        <div class="dm-actions" data-actions></div>
        <button type="button" class="btn btn-outline dm-close-btn" data-close>Done</button>
      </div>
    </div>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-about">
        <span class="footer-logo"><img src="/assets/images/logo.webp" alt="HealthO Pro" width="167" height="36" loading="lazy" decoding="async"></span>
        <p>HealthO Pro by Healthocare Private Limited — empowering healthcare providers with cloud-enabled HIMS, LIMS, CIMS and RIS/RIMS software since 2011.</p>
        <?= social_links() ?>
      </div>
      <div>
        <h5>Solutions</h5>
        <ul class="footer-links">
<?php foreach (PRODUCTS as $slug => $p): ?>
          <li><a href="/<?= $slug ?>"><?= h($p['short']) ?></a></li>
<?php endforeach; ?>
          <li><a href="/features">All Features</a></li>
          <li><a href="/pricing">Pricing</a></li>
        </ul>
      </div>
      <div>
        <h5>Company</h5>
        <ul class="footer-links">
          <li><a href="/#solutions">About Us</a></li>
          <li><a href="/our-clients">Our Clients</a></li>
          <li><a href="/testimonials">Testimonials</a></li>
          <li><a href="/blog">Blog</a></li>
          <li><a href="/partners">Channel Partners</a></li>
          <li><a href="/verify-partner">Verify Partner</a></li>
          <li><a href="/careers">Careers</a></li>
          <li><a href="/contact">Contact Us</a></li>
          <li><a href="/message-to-ceo">Message to CEO</a></li>
        </ul>
      </div>
      <div>
        <h5>Get in Touch</h5>
        <ul class="footer-contact">
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg><span><strong style="color:#fff;">Sales</strong><br><a href="tel:<?= str_replace(' ', '', PHONE_SALES) ?>"><?= PHONE_SALES ?></a></span></li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg><span><strong style="color:#fff;">Support</strong><br><a href="tel:<?= str_replace(' ', '', PHONE_SUPPORT) ?>"><?= PHONE_SUPPORT ?></a></span></li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg><span><a href="mailto:sales@healtho.pro">sales@healtho.pro</a><br><a href="mailto:support@healtho.pro">support@healtho.pro</a></span></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> Healthocare Private Limited. All rights reserved.</span>
      <div class="legal">
        <a href="/privacy-policy">Privacy Policy</a>
        <a href="/terms-conditions">Terms &amp; Conditions</a>
      </div>
    </div>
  </div>
</footer>

<!-- Floating actions -->
<a class="wa-float" href="<?= WHATSAPP_URL ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35M12 21.5a9.5 9.5 0 0 1-4.84-1.32l-.35-.21-3.59.94.96-3.5-.23-.36A9.5 9.5 0 1 1 12 21.5M12 2a11.5 11.5 0 0 0-9.86 17.4L1 23l3.7-1.12A11.5 11.5 0 1 0 12 2"/></svg>
</a>
<button class="to-top" id="toTop" aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m18 15-6-6-6 6"/></svg>
</button>

<!-- Scripts fetched on demand (the brochure renderer) cache-bust off the same version. -->
<script>window.HP_ASSET_VER = '<?= ASSET_VER ?>';</script>
<script src="/script.js?v=<?= ASSET_VER ?>" defer></script>
<?php foreach ($foot_scripts as $src): ?>
<script src="/<?= h($src) ?>?v=<?= ASSET_VER ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
