<?php
/**
 * Paid 1:1 consultation with Fahad Ahmed — sales landing page.
 * Every "Book" button goes to the CRM booking page (BOOK_URL), which handles
 * scheduling and payment. Prices live in the two constants below.
 */
require_once __DIR__ . '/partials/site.php';

const BOOK_URL   = 'https://healtho.pro/book/get-your-90-day-lab-automation-roadmap';
const PRICE_FULL  = 3999;
const PRICE_OFFER = 999;

/** Instagram post IDs shown under "Recent work" (instagram.com/p/{id}). */
const IG_POSTS = ['DTA8DIfkgYB', 'DTA75AOkuUV', 'DS-X1Olktji', 'DS-XIwLkilU', 'DS-WHxeEvIf', 'DS-Uo8mEjuo'];

$page        = 'fahad-ahmed-consultation';
$title       = '1:1 Consultation with Fahad Ahmed — 90-Day Lab Automation Roadmap | HealthO Pro';
$description = 'Book a one-hour 1:1 consultation with Fahad Ahmed, healthcare lab automation expert who has helped 120+ labs, and get a complete 90-day lab automation roadmap & guide. Regular price ₹3,999 — limited-time offer ₹999.';
$og_type     = 'website';
$og_image    = SITE_URL . '/assets/images/fahad-ahmed.jpg';
$og_image_alt = 'Fahad Ahmed — Healthcare Lab Automation Expert';
$crumbs      = ['Consultation with Fahad Ahmed' => SITE_URL . '/' . $page];

$off = (int) round((1 - PRICE_OFFER / PRICE_FULL) * 100);
$inr = static fn(int $n): string => '₹' . number_format($n);

$faqs = [
    'What happens after I book?' =>
        'You pick a time slot and complete the payment on the booking page. You get an instant confirmation with the meeting link.',
    'Is this a software sales call?' =>
        'No. This is a paid, independent consultation. The roadmap covers what your lab needs — process, people and technology — whichever software you use or choose. You get honest, practical advice from someone who has automated 120+ labs.',
    'What exactly do I receive?' =>
        'A live one-hour 1:1 video call with Fahad Ahmed, followed by your written 90-day lab automation roadmap and an implementation guide: priorities week by week, quick wins, tool and analyser-interfacing checklist, and the KPIs to track.',
    'Who is this consultation for?' =>
        'Owners, directors and managers of pathology labs, diagnostic centres, collection-centre networks, hospital labs and imaging centres — whether you are starting a new lab, still on paper and Excel, or outgrowing your current software.',
    'Why is it ₹' . number_format(PRICE_OFFER) . ' instead of ₹' . number_format(PRICE_FULL) . '?' =>
        'The regular fee for this consultation is ₹' . number_format(PRICE_FULL) . ' per hour. The current launch offer brings it down to ₹' . number_format(PRICE_OFFER) . ' with the complete roadmap and guide included. The offer can end at any time — the price shown on the booking page when you book is the price you pay.',
];

$head_extra = <<<'HTML'
<style>
.fc-hero { position: relative; overflow: hidden; background: var(--grad-hero); color: #fff; padding: calc(var(--nav-h) + 64px) 0 88px; }
.fc-hero::before { content: ""; position: absolute; inset: 0; background: radial-gradient(60% 70% at 85% 10%, rgba(0,180,216,.30), transparent 60%), radial-gradient(50% 60% at 0% 100%, rgba(46,204,113,.18), transparent 60%); pointer-events: none; }
.fc-hero .container { position: relative; display: grid; grid-template-columns: 1.15fr .85fr; gap: 56px; align-items: center; }
.fc-pill { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: var(--r-pill); background: rgba(245,158,11,.16); border: 1px solid rgba(245,158,11,.45); color: #FCD34D; font-size: var(--text-sm); font-weight: 700; letter-spacing: .02em; }
.fc-pill i { width: 8px; height: 8px; border-radius: 50%; background: #F59E0B; box-shadow: 0 0 0 0 rgba(245,158,11,.7); animation: fcPulse 1.8s infinite; }
@keyframes fcPulse { 70% { box-shadow: 0 0 0 10px rgba(245,158,11,0); } 100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); } }
.fc-hero h1 { color: #fff; font-size: clamp(2rem, 4.6vw, 3.4rem); line-height: 1.1; margin: 20px 0 18px; letter-spacing: -.02em; }
.fc-hero h1 .hl { background: linear-gradient(135deg, #48CAE4, #2ECC71); -webkit-background-clip: text; background-clip: text; color: transparent; }
.fc-hero .sub { font-size: clamp(1.02rem, 1.6vw, 1.18rem); color: rgba(255,255,255,.82); max-width: 600px; line-height: 1.65; }
.fc-price { display: flex; align-items: center; flex-wrap: wrap; gap: 10px 16px; margin: 28px 0 24px; }
.fc-price .now { font-size: clamp(2.4rem, 5vw, 3.2rem); font-weight: 800; color: #fff; line-height: 1; }
.fc-price .now small { font-size: 1rem; font-weight: 600; color: rgba(255,255,255,.7); margin-left: 4px; }
.fc-price .was { font-size: 1.3rem; color: rgba(255,255,255,.55); text-decoration: line-through; text-decoration-color: #F87171; text-decoration-thickness: 2px; }
.fc-price .save { background: #2ECC71; color: #06371C; font-weight: 800; font-size: var(--text-sm); padding: 6px 12px; border-radius: var(--r-pill); }
.fc-cta { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 18px 30px; border-radius: var(--r-pill); font-weight: 800; font-size: 1.08rem; color: #fff; background: linear-gradient(135deg, #F59E0B, #F97316); box-shadow: 0 14px 34px rgba(249,115,22,.38); transition: transform .25s var(--ease), box-shadow .25s; text-align: center; }
.fc-cta:hover { transform: translateY(-3px); box-shadow: 0 18px 40px rgba(249,115,22,.48); color: #fff; }
.fc-cta svg { width: 20px; height: 20px; flex: none; }
.fc-assure { display: flex; flex-wrap: wrap; gap: 8px 20px; margin-top: 18px; font-size: var(--text-sm); color: rgba(255,255,255,.75); }
.fc-assure span { display: inline-flex; align-items: center; gap: 6px; }
.fc-assure svg { width: 16px; height: 16px; color: #2ECC71; }

.fc-profile { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.16); border-radius: var(--r-xl); padding: 34px 30px; backdrop-filter: blur(10px); box-shadow: 0 30px 60px rgba(0,0,0,.25); }
.fc-avatar { width: 112px; height: 112px; border-radius: 50%; object-fit: cover; display: block; border: 3px solid #48CAE4; box-shadow: 0 0 0 6px rgba(255,255,255,.1), 0 12px 30px rgba(0,0,0,.3); margin-bottom: 18px; }
.fc-badge { display: inline-flex; align-items: center; gap: 6px; font-size: .78rem; font-weight: 700; color: #06371C; background: #2ECC71; padding: 4px 10px; border-radius: var(--r-pill); margin-bottom: 10px; }
.fc-badge svg { width: 13px; height: 13px; flex: none; }
.fc-by { display: none; align-items: center; gap: 12px; margin-bottom: 18px; }
.fc-by img { width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid #48CAE4; }
.fc-by b { display: block; color: #fff; font-size: 1rem; }
.fc-by span { font-size: .82rem; color: var(--cyan-light); font-weight: 600; }
.fc-photo { width: 100%; max-width: 340px; aspect-ratio: 1; object-fit: cover; border-radius: var(--r-xl); box-shadow: var(--e-navy); display: block; margin: 0 auto 28px; }
.fc-profile h2 { color: #fff; font-size: 1.6rem; margin-bottom: 4px; }
.fc-profile .role { color: var(--cyan-light); font-weight: 600; margin-bottom: 18px; }
.fc-profile ul { display: grid; gap: 12px; margin-bottom: 22px; }
.fc-profile li { display: flex; gap: 10px; color: rgba(255,255,255,.86); font-size: .95rem; line-height: 1.5; }
.fc-profile li svg { width: 18px; height: 18px; flex: none; margin-top: 2px; color: #2ECC71; }
.fc-mini { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; border-top: 1px solid rgba(255,255,255,.14); padding-top: 18px; }
.fc-mini b { display: block; color: #fff; font-size: 1.35rem; font-weight: 800; }
.fc-mini span { font-size: .78rem; color: rgba(255,255,255,.65); }


.fc-sec { padding: var(--section-y) 0; }
.fc-sec--soft { background: var(--bg-soft); }
.fc-head { text-align: center; max-width: 720px; margin: 0 auto 52px; }
.fc-head .lead { margin-top: 14px; color: var(--text-soft); font-size: 1.08rem; }
.fc-grid { display: grid; gap: 22px; grid-template-columns: repeat(3, 1fr); }
.fc-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); padding: 28px 26px; box-shadow: var(--e1); transition: transform .3s var(--ease), box-shadow .3s; }
.fc-card:hover { transform: translateY(-4px); box-shadow: var(--e3); }
.fc-ic { width: 50px; height: 50px; border-radius: 14px; display: grid; place-items: center; color: #fff; margin-bottom: 16px; background: var(--grad-cyan); }
.fc-ic svg { width: 24px; height: 24px; }
.fc-card h3 { font-size: 1.12rem; margin-bottom: 8px; color: var(--ink); }
.fc-card p { color: var(--text-soft); font-size: .96rem; line-height: 1.6; }
.fc-pain .fc-ic { background: linear-gradient(135deg, #F87171, #EF4444); }

.fc-split { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; }
.fc-quote { background: var(--grad-brand); color: #fff; border-radius: var(--r-xl); padding: 40px 36px; box-shadow: var(--e-navy); }
.fc-quote p { font-size: 1.18rem; line-height: 1.65; font-style: italic; color: rgba(255,255,255,.92); }
.fc-quote .by { margin-top: 20px; font-weight: 700; color: var(--cyan-light); font-style: normal; }

.fc-ig { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; align-items: start; }
.fc-ig .instagram-media { min-width: 0 !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; border-radius: var(--r-lg) !important; box-shadow: var(--e1) !important; background: var(--surface) !important; min-height: 420px; }
.fc-ig blockquote.instagram-media a { display: grid; place-items: center; min-height: 420px; color: var(--navy); font-weight: 700; }
.fc-steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; counter-reset: st; }
.fc-step { position: relative; background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); padding: 30px 26px; box-shadow: var(--e1); }
.fc-step::before { counter-increment: st; content: counter(st); width: 44px; height: 44px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; color: #fff; background: var(--grad-brand); margin-bottom: 16px; }
.fc-step h3 { font-size: 1.12rem; margin-bottom: 8px; }
.fc-step p { color: var(--text-soft); font-size: .96rem; }


.fc-offer { max-width: 760px; margin: 0 auto; background: var(--surface); border: 2px solid var(--cyan); border-radius: var(--r-xl); box-shadow: 0 30px 70px rgba(18,43,92,.14); overflow: hidden; }
.fc-offer-top { background: var(--grad-brand); color: #fff; padding: 26px 32px; text-align: center; }
.fc-offer-top h2 { color: #fff; font-size: clamp(1.4rem, 3vw, 1.9rem); }
.fc-offer-top p { color: rgba(255,255,255,.78); margin-top: 6px; }
.fc-offer-body { padding: 30px 32px 34px; }
.fc-val { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
.fc-val td { padding: 13px 0; border-bottom: 1px dashed var(--line); color: var(--text); font-size: .98rem; vertical-align: top; }
.fc-val td:last-child { text-align: right; font-weight: 700; color: var(--navy); white-space: nowrap; padding-left: 14px; }
.fc-val tr.total td { border-bottom: 0; font-weight: 800; color: var(--ink); font-size: 1.05rem; padding-top: 18px; }
.fc-val tr.total td:last-child { text-decoration: line-through; color: var(--text-soft); }
.fc-offer .fc-price { justify-content: center; margin: 4px 0 22px; }
.fc-offer .fc-price .now { color: var(--navy); }
.fc-offer .fc-price .now small { color: var(--text-soft); }
.fc-offer .fc-price .was { color: var(--text-mute); }
.fc-offer .fc-cta { width: 100%; }
.fc-offer .fc-assure { justify-content: center; color: var(--text-soft); }

.fc-final { background: var(--grad-hero); color: #fff; text-align: center; padding: 84px 0; position: relative; overflow: hidden; }
.fc-final h2 { color: #fff; font-size: clamp(1.8rem, 4vw, 2.7rem); max-width: 760px; margin: 0 auto 14px; }
.fc-final p { color: rgba(255,255,255,.8); max-width: 620px; margin: 0 auto 30px; font-size: 1.08rem; }

/* Sticky booking bar (mobile) */
.fc-bar { position: fixed; left: 0; right: 0; bottom: 0; z-index: 900; display: none; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px calc(12px + env(safe-area-inset-bottom)); background: rgba(255,255,255,.97); border-top: 1px solid var(--line); box-shadow: 0 -10px 30px rgba(15,27,51,.12); transform: translateY(110%); transition: transform .35s var(--ease); }
.fc-bar.show { transform: none; }
.fc-bar .p b { font-size: 1.35rem; font-weight: 800; color: var(--navy); }
.fc-bar .p s { color: var(--text-mute); margin-left: 6px; font-size: .9rem; }
.fc-bar .p div { font-size: .75rem; color: var(--text-soft); }
.fc-bar .fc-cta { padding: 13px 20px; font-size: .98rem; }

@media (max-width: 980px) {
  .fc-hero .container, .fc-split { grid-template-columns: 1fr; gap: 40px; }
  .fc-grid, .fc-steps, .fc-ig { grid-template-columns: 1fr 1fr; }
  .fc-by { display: flex; }
  }
@media (max-width: 680px) {
  :root { --section-y: 64px; }
  .container { padding: 0 16px; }
  .fc-hero { padding: calc(var(--nav-h) + 36px) 0 60px; }
  .fc-grid, .fc-steps { grid-template-columns: 1fr; }
  /* Recent work: swipe sideways instead of six stacked posts */
  .fc-ig { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; gap: 14px; margin: 0 -16px; padding: 4px 16px 16px; scrollbar-width: none; }
  .fc-ig::-webkit-scrollbar { display: none; }
  .fc-ig > * { flex: 0 0 86% !important; width: 86% !important; scroll-snap-align: center; }
  .fc-swipe { display: block !important; }
  .fc-hero .fc-cta { width: 100%; }
  .fc-profile { padding: 28px 22px; }
  .fc-offer-body, .fc-offer-top { padding-left: 20px; padding-right: 20px; }
  .fc-quote { padding: 30px 24px; }
  .fc-bar { display: flex; }
  body { padding-bottom: 84px; }
  .wa-float, .to-top { bottom: 100px !important; }
}
</style>
HTML;

require __DIR__ . '/partials/head.php';

$check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>';
$cross = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>';
$arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>';

/** Primary booking button. */
$book = static function (string $label = 'Book My 1:1 Session') use ($arrow, $inr): string {
    return '<a href="' . h(BOOK_URL) . '" class="fc-cta" data-book>' . h($label) . ' — ' . $inr(PRICE_OFFER) . ' ' . $arrow . '</a>';
};
$assure = '<div class="fc-assure">'
    . '<span>' . $check . 'Secure online booking</span>'
    . '<span>' . $check . 'Instant confirmation</span>'
    . '<span>' . $check . 'Roadmap &amp; guide included</span></div>';
?>

<!-- ===== HERO ===== -->
<header class="fc-hero">
  <div class="container">
    <div class="reveal">
      <div class="fc-by"><img src="/assets/images/fahad-ahmed.jpg" alt="" width="52" height="52"><div><b>Fahad Ahmed</b><span>Healthcare Lab Automation Expert · 120+ labs</span></div></div>
      <span class="fc-pill"><i></i>Limited-time offer · <?= $off ?>% off</span>
      <h1>Automate your lab in <span class="hl">90 days</span> — with a 1:1 roadmap from Fahad Ahmed</h1>
      <p class="sub">One focused hour on your lab: your workflows, your bottlenecks, your growth plan. You leave with a complete, step-by-step 90-day lab automation roadmap &amp; guide — from a healthcare lab automation expert who has already helped 120+ labs do it.</p>
      <div class="fc-price">
        <span class="now"><?= $inr(PRICE_OFFER) ?><small>/ 1 hour</small></span>
        <span class="was"><?= $inr(PRICE_FULL) ?></span>
        <span class="save">Save <?= $inr(PRICE_FULL - PRICE_OFFER) ?></span>
      </div>
      <?= $book() ?>
      <?= $assure ?>
    </div>

    <aside class="fc-profile reveal d1" aria-label="About Fahad Ahmed">
      <img class="fc-avatar" src="/assets/images/fahad-ahmed.jpg" alt="Fahad Ahmed" width="112" height="112" fetchpriority="high">
      <div><span class="fc-badge"><?= $check ?>Verified lab automation expert</span></div>
      <h2>Fahad Ahmed</h2>
      <div class="role">Healthcare Lab Automation Expert</div>
      <ul>
        <li><?= $check ?><span>Helped 120+ pathology labs &amp; diagnostic centres automate</span></li>
        <li><?= $check ?><span>5+ years hands-on in healthcare lab automation</span></li>
        <li><?= $check ?><span>Specialist in LIMS, analyser interfacing, report automation &amp; lab revenue</span></li>
      </ul>
      <div class="fc-mini">
        <div><b>120+</b><span>Labs helped</span></div>
        <div><b>5+</b><span>Years</span></div>
        <div><b>90</b><span>Day roadmap</span></div>
      </div>
    </aside>
  </div>
</header>

<!-- ===== PAIN ===== -->
<section class="fc-sec">
  <div class="container">
    <div class="fc-head reveal">
      <span class="eyebrow">Sound familiar?</span>
      <h2 class="h-sec">Manual work is quietly costing your lab every day</h2>
      <p class="lead">Most labs don't need more staff — they need the right system, set up in the right order.</p>
    </div>
    <div class="fc-grid fc-pain">
      <div class="fc-card reveal"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><h3>Slow turnaround</h3><p>Results typed by hand, reports printed and re-checked — patients and doctors keep calling for status.</p></div>
      <div class="fc-card reveal d1"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg></div><h3>Errors &amp; re-work</h3><p>Transcription mistakes, mixed-up samples and missed critical values put your reputation at risk.</p></div>
      <div class="fc-card reveal d2"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3>Revenue leaks</h3><p>Unbilled tests, untracked B2B dues, discounts with no control — money slips out without anyone noticing.</p></div>
    </div>
  </div>
</section>

<!-- ===== WHAT YOU GET ===== -->
<section class="fc-sec fc-sec--soft">
  <div class="container">
    <div class="fc-head reveal">
      <span class="eyebrow">What you get</span>
      <h2 class="h-sec">One hour. A complete 90-day automation plan.</h2>
      <p class="lead">Not a generic presentation — a roadmap built live around your lab, your tests, your team and your budget.</p>
    </div>
    <div class="fc-grid">
      <div class="fc-card reveal"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div><h3>Lab workflow audit</h3><p>We walk through your journey from registration and sample collection to reporting and billing, and pinpoint every bottleneck.</p></div>
      <div class="fc-card reveal d1"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg></div><h3>Your 90-day roadmap</h3><p>A week-by-week plan in three phases — quick wins in month one, core automation in month two, scale and optimise in month three.</p></div>
      <div class="fc-card reveal d2"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M6 7V5M18 7V5M7 12h.01M12 12h5"/></svg></div><h3>Analyser interfacing plan</h3><p>Which machines to connect first, what the interface needs and how to remove manual result entry for good.</p></div>
      <div class="fc-card reveal"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg></div><h3>Software selection guide</h3><p>The exact checklist to evaluate any LIMS — the features that matter, the red flags, and the questions vendors don't want asked.</p></div>
      <div class="fc-card reveal d1"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3>Revenue &amp; billing fixes</h3><p>Plug leaks in billing, discounts, B2B / referral dues and collection centres — and set up the reports that show it.</p></div>
      <div class="fc-card reveal d2"><div class="fc-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9M13 17V5M8 17v-3"/></svg></div><h3>KPIs to track</h3><p>Turnaround time, cost per test, repeat rate, patient growth — the numbers that tell you the automation is working.</p></div>
    </div>
  </div>
</section>

<!-- ===== ABOUT / EXPERTISE ===== -->
<section class="fc-sec">
  <div class="container fc-split">
    <div class="reveal">
      <span class="eyebrow">Meet your consultant</span>
      <h2 class="h-sec">Fahad Ahmed</h2>
      <div class="role" style="color:var(--cyan-dark);font-weight:700;margin-top:6px;">Healthcare Lab Automation Expert</div>
      <p class="lead" style="margin:16px 0;color:var(--text-soft);">Fahad Ahmed is a healthcare expert who has spent the last 5+ years doing one thing: helping labs run on automation instead of manual work. He has worked hands-on with 120+ pathology labs, diagnostic centres, collection-centre networks and hospital labs — from single-room labs going digital for the first time to multi-branch chains.</p>
      <p style="color:var(--text-soft);line-height:1.7;margin-bottom:12px;">He has set up LIMS, connected analysers, automated reports and WhatsApp delivery, fixed billing and B2B revenue leaks, and trained teams to actually use the system. He knows what works, what fails, and the order to do it in — so your lab doesn't waste months and money learning the hard way.</p>
    </div>
    <div class="reveal d1">
    <img class="fc-photo" src="/assets/images/fahad-ahmed.jpg" alt="Fahad Ahmed, Healthcare Lab Automation Expert" width="320" height="320" loading="lazy">
    <div class="fc-quote">
      <p>"Most labs don't fail at automation because of the software — they fail because they automate in the wrong order. Give me one hour, and you'll know exactly what to do first, what to do next, and what to ignore."</p>
      <div class="by">— Fahad Ahmed, Healthcare Lab Automation Expert</div>
    </div>
    </div>
  </div>
</section>

<!-- ===== RECENT WORK (Instagram) ===== -->
<section class="fc-sec fc-sec--soft" id="work">
  <div class="container">
    <div class="fc-head reveal">
      <span class="eyebrow">Recent work</span>
      <h2 class="h-sec">Labs Fahad has helped recently</h2>
      <p class="lead">Real labs, real automation — straight from Fahad's Instagram.</p>
      <p class="fc-swipe" style="display:none;margin-top:10px;font-size:.85rem;font-weight:600;color:var(--cyan-dark);">Swipe to see more →</p>
    </div>
    <div class="fc-ig">
<?php foreach (IG_POSTS as $id): ?>
      <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/p/<?= h($id) ?>/" data-instgrm-version="14"><a href="https://www.instagram.com/p/<?= h($id) ?>/" target="_blank" rel="noopener">View this post on Instagram</a></blockquote>
<?php endforeach; ?>
    </div>
  </div>
</section>
<script>
/* Load Instagram's embed script only when the section is close to view. */
(function () {
  var sec = document.getElementById('work'), done = false;
  function load() { if (done) return; done = true; var s = document.createElement('script'); s.async = true; s.src = 'https://www.instagram.com/embed.js'; document.body.appendChild(s); }
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (e) { if (e[0].isIntersecting) { io.disconnect(); load(); } }, { rootMargin: '600px' });
    io.observe(sec);
  } else { load(); }
})();
</script>

<!-- ===== HOW IT WORKS ===== -->
<section class="fc-sec">
  <div class="container">
    <div class="fc-head reveal">
      <span class="eyebrow">How it works</span>
      <h2 class="h-sec">Three simple steps</h2>
    </div>
    <div class="fc-steps">
      <div class="fc-step reveal"><h3>Book your slot</h3><p>Pick a time that suits you and confirm your session at the offer price of <?= $inr(PRICE_OFFER) ?>.</p></div>
      <div class="fc-step reveal d1"><h3>Meet 1:1 for an hour</h3><p>A live video call with Fahad focused entirely on your lab — your questions, your numbers, your goals.</p></div>
      <div class="fc-step reveal d2"><h3>Get your roadmap</h3><p>Receive your complete 90-day lab automation roadmap &amp; guide, ready to put into action from day one.</p></div>
    </div>
    <div style="text-align:center;margin-top:40px;" class="reveal"><?= $book('Reserve My Session') ?></div>
  </div>
</section>

<!-- ===== OFFER ===== -->
<section class="fc-sec fc-sec--soft" id="offer">
  <div class="container">
    <div class="fc-offer reveal">
      <div class="fc-offer-top">
        <h2>Everything you get today</h2>
        <p>1:1 consultation + complete roadmap &amp; guide</p>
      </div>
      <div class="fc-offer-body">
        <table class="fc-val">
          <tr><td>60-minute 1:1 consultation with Fahad Ahmed</td><td><?= $inr(PRICE_FULL) ?></td></tr>
          <tr><td>Lab workflow audit &amp; bottleneck review</td><td>Included</td></tr>
          <tr><td>Personalised 90-day lab automation roadmap</td><td>Included</td></tr>
          <tr><td>Implementation guide &amp; software selection checklist</td><td>Included</td></tr>
          <tr><td>Analyser interfacing &amp; revenue-leak action plan</td><td>Included</td></tr>
          <tr class="total"><td>Regular price</td><td><?= $inr(PRICE_FULL) ?></td></tr>
        </table>
        <div class="fc-price">
          <span class="now"><?= $inr(PRICE_OFFER) ?><small>/ 1 hour</small></span>
          <span class="was"><?= $inr(PRICE_FULL) ?></span>
          <span class="save"><?= $off ?>% OFF</span>
        </div>
        <?= $book('Yes, Book My Session') ?>
        <?= $assure ?>
        <p style="text-align:center;font-size:.85rem;color:var(--text-mute);margin-top:14px;">Offer price is for a limited time and may end without notice.</p>
      </div>
    </div>
  </div>
</section>

<?php faq_section($faqs, 'Questions, answered'); ?>

<!-- ===== FINAL CTA ===== -->
<section class="fc-final">
  <div class="container">
    <span class="fc-pill"><i></i>Offer price — <?= $inr(PRICE_OFFER) ?> instead of <?= $inr(PRICE_FULL) ?></span>
    <h2 style="margin-top:20px;">Your lab can run faster, cleaner and more profitably in 90 days</h2>
    <p>One hour with Fahad Ahmed — the expert behind 120+ lab automations — is all it takes to get the plan. Book now while the offer lasts.</p>
    <?= $book() ?>
  </div>
</section>

<!-- Sticky booking bar (mobile) -->
<div class="fc-bar" id="fcBar">
  <div class="p"><b><?= $inr(PRICE_OFFER) ?></b><s><?= $inr(PRICE_FULL) ?></s><div>1 hour · roadmap included</div></div>
  <a href="<?= h(BOOK_URL) ?>" class="fc-cta" data-book>Book Now <?= $arrow ?></a>
</div>
<script>
(function () {
  var bar = document.getElementById('fcBar');
  var hero = document.querySelector('.fc-hero');
  if (bar && hero && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { bar.classList.toggle('show', !e[0].isIntersecting); }).observe(hero);
  } else if (bar) { bar.classList.add('show'); }
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('[data-book]');
    if (a && window.gtag) gtag('event', 'begin_checkout', { item_name: 'Fahad Ahmed 1:1 Consultation', value: <?= PRICE_OFFER ?>, currency: 'INR' });
  });
})();
</script>
<?php require __DIR__ . '/partials/foot.php';
