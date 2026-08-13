<?php
$page        = 'message-to-ceo';
$title       = 'Message to CEO — Share Your Feedback | HealthO Pro';
$description = 'Have feedback, a suggestion or a concern? Send a message directly to the HealthO Pro CEO. Every message is read and we respond within two business days.';
require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Message to CEO</div>
    <h1>A direct line to our <span class="text-grad">CEO</span></h1>
    <p>Your feedback shapes how we build HealthO Pro. Share a suggestion, an experience or a concern — it goes straight to the CEO's desk, and every message is read.</p>
  </div>
</header>

<!-- ===== MESSAGE TO CEO ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">We're listening</span>
        <h2 class="h-sec">Your voice matters</h2>
        <p class="lead" style="margin:16px 0 24px;">The best ideas come from the people who use HealthO Pro every day. Tell us what's working, what isn't, and what you'd love to see next — candid feedback is always welcome.</p>
        <ul class="checks" style="margin-bottom:30px;">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Product feedback &amp; feature ideas</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Service experience &amp; support quality</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Suggestions &amp; improvements</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Concerns, complaints or appreciation</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Partnership &amp; business proposals</span></li>
        </ul>
        <div style="background:var(--surface);border:1px solid var(--line);border-left:4px solid var(--cyan);border-radius:var(--r-md);padding:24px 26px;box-shadow:var(--e1);">
          <svg viewBox="0 0 24 24" fill="var(--cyan)" style="width:30px;height:30px;opacity:.5;margin-bottom:8px;"><path d="M7.17 6A5.17 5.17 0 0 0 2 11.17V18h6.83v-6.83H5.5A3.67 3.67 0 0 1 9.17 7.5V6h-2Zm10 0A5.17 5.17 0 0 0 12 11.17V18h6.83v-6.83H15.5a3.67 3.67 0 0 1 3.67-3.67V6h-2Z"/></svg>
          <p style="font-size:1.05rem;color:var(--ink);font-style:italic;line-height:1.65;margin-bottom:16px;">Every message reaches our leadership personally. Your honest feedback is how we keep HealthO Pro worthy of the trust healthcare providers place in us.</p>
          <div style="display:flex;align-items:center;gap:12px;">
            <span style="width:46px;height:46px;border-radius:50%;flex:none;display:grid;place-items:center;background:var(--grad-brand);color:#fff;font-weight:800;font-size:.85rem;letter-spacing:.03em;">CEO</span>
            <div>
              <div style="font-weight:700;color:var(--navy);">Office of the CEO</div>
              <div style="font-size:.85rem;color:var(--text-mute);">Healthocare Private Limited</div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Send your message</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">Share candid feedback or a message — we respond within two business days.</p>
        <?php lead_form([
          'hidden'   => ['formType' => 'Message to CEO'],
          'aside'    => form_select('category', 'Type of Message', [
              'General Feedback', 'Product Feedback', 'Service Experience', 'Suggestion / Idea',
              'Complaint', 'Appreciation', 'Partnership / Business', 'Other',
          ]),
          'interest' => '',
          'message'  => ['label' => 'Your Message to the CEO', 'placeholder' => 'Share your feedback, suggestion or message…', 'required' => true],
          'submit'   => 'Send to CEO',
          'note'     => 'Your message is confidential. By submitting, you agree to our <a href="privacy-policy" class="link-cyan">Privacy Policy</a>.',
        ]); ?>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
