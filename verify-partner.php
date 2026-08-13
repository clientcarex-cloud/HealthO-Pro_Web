<?php
$page        = 'verify-partner';
$title       = 'Verify Partner Certificate | HealthO Pro';
$description = 'Instantly verify the authenticity of a HealthO Pro channel partner certificate. Enter the certificate number or scan the QR code on the certificate.';
$head_extra  = <<<'HTML'
<meta name="robots" content="noindex, follow">
<style>
/* ── Verify page ── */
.vf-shell { max-width:680px; margin:0 auto; }
.vf-form { display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
.vf-form input {
  flex:1; min-width:240px; padding:13px 18px; border:1.5px solid var(--line); border-radius:12px;
  background:var(--surface); color:var(--ink); font:inherit; font-size:1rem; outline:none;
  text-transform:uppercase; letter-spacing:.5px; transition:border-color .2s;
}
.vf-form input:focus { border-color:var(--cyan); }
.vf-hint { text-align:center; color:var(--text-mute); font-size:.85rem; margin-top:12px; }
.vf-result { margin-top:34px; }
.vf-card {
  background:var(--surface); border:1px solid var(--line); border-radius:18px; overflow:hidden;
  box-shadow:0 16px 40px rgba(18,43,92,.08);
}
.vf-head { padding:26px 28px; display:flex; align-items:center; gap:16px; color:#fff; }
.vf-head.ok { background:linear-gradient(135deg,#1d976c,#2ecc71); }
.vf-head.warn { background:linear-gradient(135deg,#c98a12,#f59e0b); }
.vf-head.bad { background:linear-gradient(135deg,#b02a2a,#d64545); }
.vf-head-ic {
  width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,.18);
  display:flex; align-items:center; justify-content:center; flex:0 0 52px;
}
.vf-head-ic svg { width:26px; height:26px; }
.vf-head h3 { margin:0 0 3px; font-size:1.25rem; color:#fff; }
.vf-head p { margin:0; opacity:.92; font-size:.9rem; }
.vf-body { padding:26px 28px; }
.vf-partner { display:flex; align-items:center; gap:16px; margin-bottom:20px; }
.vf-logo { width:60px; height:60px; border-radius:12px; border:1px solid var(--line-soft); object-fit:contain; background:#fff; }
.vf-logo-ph { display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.5rem; color:#fff; background:var(--grad-brand); flex:0 0 60px; }
.vf-pname { font-weight:700; font-size:1.15rem; color:var(--ink); }
.vf-ploc { color:var(--text-soft); font-size:.88rem; }
.vf-rows { display:grid; grid-template-columns:1fr 1fr; gap:12px 20px; }
.vf-row { background:var(--bg-soft); border-radius:10px; padding:10px 14px; }
.vf-row .k { display:block; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mute); margin-bottom:2px; }
.vf-row .v { color:var(--ink); font-weight:600; font-size:.95rem; word-break:break-word; }
.vf-note { margin-top:18px; font-size:.83rem; color:var(--text-soft); display:flex; gap:8px; align-items:flex-start; }
.vf-note svg { width:15px; height:15px; flex:0 0 15px; margin-top:2px; }
.vf-about { margin:0 0 18px; color:var(--text); font-size:.92rem; line-height:1.6; }
@media (max-width:560px){ .vf-rows { grid-template-columns:1fr; } }
</style>
HTML;
$foot_scripts = ['js/verify-partner.js'];
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="partners">Channel Partners</a><span>›</span>Verify</div>
    <h1>Verify a <span class="text-grad">partner certificate</span></h1>
    <p>Scanned a QR code or received a partnership certificate? Confirm its authenticity here in seconds — directly against our live partner registry.</p>
  </div>
</header>

<!-- ===== VERIFIER ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="vf-shell">
      <form class="vf-form" id="vfForm">
        <input type="text" id="vfCode" placeholder="Certificate No. e.g. CCXP-2026-00001" autocomplete="off" aria-label="Certificate number" required>
        <button type="submit" class="btn btn-primary btn-lg" id="vfBtn">Verify</button>
      </form>
      <p class="vf-hint">The certificate number is printed below the QR code on every HealthO Pro partnership certificate.</p>

      <div class="vf-result" id="vfResult" hidden></div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
