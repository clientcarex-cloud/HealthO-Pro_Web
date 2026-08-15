<?php
/**
 * Shared footer, floating actions and script tags — closes the document.
 *
 * Optional, set by the page before including this file:
 *   $foot_scripts  array of extra script src paths loaded after script.js
 */
require_once __DIR__ . '/site.php';
require_once __DIR__ . '/lead-form.php';

$foot_scripts = $foot_scripts ?? [];

// On a product page the popup pre-selects that product.
$demo_interest = PRODUCTS[$page ?? '']['short'] ?? '';
?>
<!-- Demo popup — script.js opens this on any [data-demo] / .nav-cta click. -->
<div class="demo-modal" role="dialog" aria-modal="true" aria-label="Book a free demo" data-page-interest="<?= h($demo_interest) ?>">
  <div class="demo-modal__dialog">
    <button type="button" class="demo-modal__close" aria-label="Close">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <div class="demo-modal__head"><div>
      <h3 class="h-card">Book a free demo</h3>
      <p>Fill in the form and our team will reach out shortly.</p>
    </div></div>
    <?php lead_form(['hidden' => ['source' => 'Demo popup']]); ?>
  </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="container">
    <div class="footer-top">
      <div class="footer-about">
        <span class="footer-logo"><img src="/assets/images/logo.webp" alt="HealthO Pro" width="167" height="36" loading="lazy" decoding="async"></span>
        <p>HealthO Pro by Healthocare Private Limited — empowering healthcare providers with cloud-enabled HIMS, LIMS, CIMS and RIS/RIMS software since 2011.</p>
        <div class="socials">
          <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77Z"/></svg></a>
          <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z"/></svg></a>
          <a href="#" aria-label="X"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.46l8.6-9.83L0 1.15h7.59l5.24 6.93 6.07-6.93Zm-1.29 19.5h2.04L6.49 3.24H4.3L17.61 20.65Z"/></svg></a>
          <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 1.95c-3.15 0-3.52.01-4.76.07-.92.04-1.42.2-1.75.33-.44.17-.75.37-1.08.7-.33.33-.53.64-.7 1.08-.13.33-.29.83-.33 1.75-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.92.2 1.42.33 1.75.17.44.37.75.7 1.08.33.33.64.53 1.08.7.33.13.83.29 1.75.33 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.92-.04 1.42-.2 1.75-.33.44-.17.75-.37 1.08-.7.33-.33.53-.64.7-1.08.13-.33.29-.83.33-1.75.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.92-.2-1.42-.33-1.75a2.9 2.9 0 0 0-.7-1.08 2.9 2.9 0 0 0-1.08-.7c-.33-.13-.83-.29-1.75-.33-1.24-.06-1.61-.07-4.76-.07Zm0 3.32a4.57 4.57 0 1 1 0 9.14 4.57 4.57 0 0 1 0-9.14Zm0 7.54a2.97 2.97 0 1 0 0-5.94 2.97 2.97 0 0 0 0 5.94Zm5.82-7.76a1.07 1.07 0 1 1-2.14 0 1.07 1.07 0 0 1 2.14 0Z"/></svg></a>
        </div>
      </div>
      <div>
        <h5>Solutions</h5>
        <ul class="footer-links">
<?php foreach (PRODUCTS as $slug => $p): ?>
          <li><a href="/<?= $slug ?>"><?= h($p['short']) ?></a></li>
<?php endforeach; ?>
          <li><a href="/pricing">Pricing</a></li>
        </ul>
      </div>
      <div>
        <h5>Company</h5>
        <ul class="footer-links">
          <li><a href="/#solutions">About Us</a></li>
          <li><a href="/testimonials">Testimonials</a></li>
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
