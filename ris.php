<?php
$page        = 'ris';
$title       = 'Scan Done, Report Ready — Ultrasound RIS / RIMS | HealthO Pro';
$description = 'HealthO Pro RIS reads every number from your ultrasound machine, writes the report with charts and images, and sends it to the patient on WhatsApp. No typing. ₹5 per patient.';
require_once __DIR__ . '/partials/seo.php';
$faqs = [
    'Do we still have to type the measurements?' =>
        'No. The HealthO Pro RIS app reads the numbers straight from your ultrasound machine — baby measurements, weight, fluid, Doppler values and more — and puts them into your report format by itself.',
    'Will it work with my ultrasound machine?' =>
        'If your machine can send images to a computer (this is called DICOM, and most modern machines can), it works. Machines with a "worklist" option also show your billed patients automatically, so nobody types names twice.',
    'What do I need to buy or install?' =>
        'Nothing extra. You only need one Windows PC at your centre. Our team installs the app, connects your machine and sets up your report format for free.',
    'Can the doctor still change the report?' =>
        'Always. The report opens like a Word page on your letterhead. The doctor can edit anything, use ready sentences or voice typing, and then signs it.',
    'Does it help with PNDT Form F?' =>
        'Yes. For pregnancy scans, Form F is pre-filled from the scan and the patient details. Your staff checks and saves it. Fetal sex is never captured from the scan.',
    'How much does it cost?' =>
        '₹5 per patient. No monthly licence fee, no setup charge, and 24×7 support is free. If you scan fewer patients, you simply pay less.',
];
$schema = [seo_software('ris', 'RIS / RIMS — Radiology Information System', $description, 'Radiology Information System', ['price' => '5', 'unit' => '₹5 per patient processed, pay-as-you-go, no fixed licence fee'])];

/** Stroke icons used across the page (24×24, currentColor). */
function ris_ic(string $name): string
{
    static $p = [
        'check'   => '<path d="M20 6 9 17l-5-5"/>',
        'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
        'monitor' => '<rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'scan'    => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2M7 12h10"/>',
        'doc'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>',
        'chart'   => '<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>',
        'mic'     => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 17v5"/>',
        'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'send'    => '<path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z"/>',
        'receipt' => '<path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2Z"/><path d="M8 9h8M8 13h6"/>',
        'keyboard'=> '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M7 14h10"/>',
        'eye'     => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'image'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>',
        'redo'    => '<path d="M21 2v6h-6"/><path d="M21 13a9 9 0 1 1-3-7.7L21 8"/>',
        'pen'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'heart'   => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21.2l8.8-8.8a5.5 5.5 0 0 0 0-7.8Z"/>',
        'users'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'building'=> '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>',
        'cup'     => '<path d="M17 8h1a4 4 0 1 1 0 8h-1"/><path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"/><path d="M6 2v2M10 2v2M14 2v2"/>',
        'tool'    => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9Z"/>',
        'play'    => '<circle cx="12" cy="12" r="10"/><path d="m10 8 6 4-6 4Z"/>',
        'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
        'wa'      => '<path d="M21 12a9 9 0 0 1-13.4 7.9L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/><path d="M8.5 9.5c.5 2.5 3.5 5.5 6 6l1.5-1.5-2-1-1 1c-1-.5-2.5-2-3-3l1-1-1-2Z"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$name] ?? '') . '</svg>';
}

// Before / after: the same scan, done the old way and with RIS.
$old_way = [
    ['pen',      'Write in register'],
    ['keyboard', 'Type name again on machine'],
    ['eye',      'Read numbers off the screen'],
    ['keyboard', 'Type every number in Word'],
    ['image',    'Paste charts & photos'],
    ['redo',     'Fix typing mistakes, re-print'],
    ['pen',      'Fill Form F by hand'],
    ['clock',    'Patient keeps waiting'],
];
$new_way = [
    ['receipt', 'Bill the patient'],
    ['scan',    'Do the scan'],
    ['doc',     'Report writes itself'],
    ['wa',      'Sent on WhatsApp'],
];

// The 4-step journey of one scan.
$journey = [
    ['receipt', 'c-navy',   'Bill once',            'Reception bills the scan. The patient\'s name appears on the ultrasound machine by itself.', 'No typing names twice'],
    ['scan',    'c-cyan',   'Scan as usual',        'The doctor scans like always. Images travel to the computer on their own.',                 'Nothing new to learn'],
    ['doc',     'c-violet', 'Report writes itself', 'RIS reads every number from the scan and fills your report — with charts and photos.',      'BPD 45 mm → in the report'],
    ['send',    'c-green',  'Check, sign, send',    'The doctor checks and signs. The report reaches the patient\'s WhatsApp instantly.',          'Patient goes home happy'],
];

// What it does, in one line each.
$benefits = [
    ['scan',   'bg-cyan',   'Reads the numbers for you',  'Baby\'s size, weight, fluid level, Doppler values — read straight from the machine. Nobody types them.'],
    ['chart',  'bg-violet', 'Charts & photos added',      'Growth charts, tables and scan photos are placed in the report on your letterhead, automatically.'],
    ['mic',    'bg-navy',   'A helper for the doctor',    'Ready-made sentences, alerts for unusual values, a suggested impression and voice typing.'],
    ['shield', 'bg-amber',  'Form F made easy',           'PNDT Form F is pre-filled from the scan for staff to check. Fetal sex is never captured.'],
    ['send',   'bg-green',  'Report reaches the phone',   'Signed reports go out on WhatsApp, SMS or email — no printing, no waiting.'],
    ['heart',  'bg-cyan',   'Pregnancy follow-ups',       'Expecting mothers get reminders for their next scan, so no visit is missed.'],
];

$head_extra = <<<'HTML'
<style>
/* ---------- Hero ---------- */
.ris-hero { padding-bottom: 88px; }
.ris-hero h1 { font-size: clamp(2.3rem, 4.6vw, 3.6rem); line-height: 1.1; }
.ris-hero h1 .text-grad { display: inline; }
.ris-hero p.ris-sub { max-width: 520px; }
.ris-hero .hero-actions { margin-bottom: 30px; }
.ris-hero .btn svg { width: 20px; height: 20px; }
.ris-pills { display: flex; flex-wrap: wrap; gap: 10px; }
.ris-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: var(--r-pill); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); color: #fff; font-size: var(--text-sm); font-weight: 600; }
.ris-pill b { color: #6EE7A1; font-weight: 800; }

/* Animated "scan → report → phone" picture */
.ris-pipe { position: relative; display: grid; gap: 14px; max-width: 440px; margin-left: auto; }
.ris-node { display: flex; align-items: center; gap: 16px; padding: 16px 18px; border-radius: var(--r-lg); background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14); backdrop-filter: blur(6px); }
.ris-node-ic { width: 52px; height: 52px; border-radius: 16px; flex: none; display: grid; place-items: center; color: #fff; }
.ris-node-ic svg { width: 26px; height: 26px; }
.ris-node small { display: block; color: rgba(255,255,255,.6); font-size: var(--text-xs); font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.ris-node strong { color: #fff; font-size: var(--text-lg); }
.ris-node-body { flex: 1; min-width: 0; text-align: left; }
.ris-screen { margin-top: 10px; display: grid; grid-template-columns: 74px 1fr; gap: 12px; align-items: center; }
.ris-fan { width: 74px; height: 56px; border-radius: 8px; background: #050B18; position: relative; overflow: hidden; }
.ris-fan::before { content: ""; position: absolute; left: 50%; top: 4px; width: 90px; height: 90px; transform: translateX(-50%); border-radius: 50%; background: conic-gradient(from 150deg, transparent 0 0deg, rgba(160,200,230,.55) 0deg 60deg, transparent 60deg); filter: blur(1px); }
.ris-fan::after { content: ""; position: absolute; left: 34px; top: 26px; width: 14px; height: 10px; border-radius: 50%; background: rgba(255,255,255,.75); animation: risBeat 1.6s ease-in-out infinite; }
.ris-vals { display: flex; flex-wrap: wrap; gap: 6px; }
.ris-val { font: 700 .78rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; color: #A5F3FC; background: rgba(0,180,216,.16); border: 1px solid rgba(72,202,228,.35); padding: 5px 7px; border-radius: 6px; animation: risFly 4.8s ease-in-out infinite; }
.ris-val:nth-child(2) { animation-delay: .25s; } .ris-val:nth-child(3) { animation-delay: .5s; } .ris-val:nth-child(4) { animation-delay: .75s; }
.ris-paper { margin-top: 10px; background: #fff; border-radius: 10px; padding: 10px 12px; display: grid; gap: 6px; }
.ris-line { height: 7px; border-radius: 4px; background: #E4ECF4; position: relative; overflow: hidden; }
.ris-line::after { content: ""; position: absolute; inset: 0; width: 0; background: linear-gradient(90deg, #00B4D8, #2ECC71); animation: risFill 4.8s ease-in-out infinite; }
.ris-line:nth-child(2) { width: 80%; } .ris-line:nth-child(2)::after { animation-delay: .3s; }
.ris-line:nth-child(3) { width: 60%; } .ris-line:nth-child(3)::after { animation-delay: .6s; }
.ris-bubble { margin-top: 10px; display: inline-flex; align-items: center; gap: 8px; background: #DCF8C6; color: #14532D; font-weight: 700; font-size: .88rem; padding: 8px 12px; border-radius: 12px 12px 12px 3px; animation: risPop 4.8s ease-in-out infinite; }
.ris-bubble svg { width: 16px; height: 16px; color: #16A34A; }
.ris-link { display: flex; justify-content: center; height: 14px; margin: -7px 0; position: relative; z-index: 1; }
.ris-link span { width: 2px; height: 100%; background: repeating-linear-gradient(180deg, #48CAE4 0 4px, transparent 4px 8px); background-size: 2px 8px; animation: risFlow .6s linear infinite; }
.c-navy { background: #3B5BA9; } .c-cyan { background: var(--cyan); } .c-violet { background: #7C5CFC; } .c-green { background: #22B573; }

@keyframes risBeat { 50% { transform: scale(1.25); opacity: .5; } }
@keyframes risFly { 0%, 10% { opacity: 0; transform: translateY(6px); } 20%, 85% { opacity: 1; transform: none; } 100% { opacity: 0; } }
@keyframes risFill { 0%, 25% { width: 0; } 55%, 90% { width: 100%; } 100% { width: 100%; opacity: 0; } }
@keyframes risPop { 0%, 55% { opacity: 0; transform: scale(.85); } 65%, 92% { opacity: 1; transform: none; } 100% { opacity: 0; } }
@keyframes risFlow { to { background-position: 0 8px; } }

/* ---------- Before / After switch ---------- */
.ris-ba { max-width: 980px; margin: 0 auto; }
.ris-ba > input { position: absolute; opacity: 0; pointer-events: none; }
.ris-switch { display: flex; width: max-content; max-width: 100%; margin: 0 auto 34px; padding: 6px; border-radius: var(--r-pill); background: #fff; border: 1px solid var(--line); box-shadow: var(--e2); }
.ris-switch label { cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: var(--r-pill); font-weight: 800; color: var(--text-soft); transition: background .3s, color .3s; white-space: nowrap; }
.ris-switch svg { width: 18px; height: 18px; }
#ris-old:checked ~ .ris-switch label[for="ris-old"] { background: #E5484D; color: #fff; }
#ris-new:checked ~ .ris-switch label[for="ris-new"] { background: var(--green-dark); color: #fff; }
#ris-old:focus-visible ~ .ris-switch label[for="ris-old"], #ris-new:focus-visible ~ .ris-switch label[for="ris-new"] { outline: 3px solid var(--cyan); outline-offset: 2px; }
.ris-panel { display: none; border-radius: var(--r-xl); padding: 36px; animation: risIn .45s var(--ease); }
#ris-old:checked ~ .ris-panels .old, #ris-new:checked ~ .ris-panels .new { display: block; }
.ris-panel.old { background: #FFF6F6; border: 1px solid #F8D7D8; }
.ris-panel.new { background: var(--grad-hero); color: #fff; box-shadow: var(--e4); }
.ris-chain { display: grid; gap: 14px; list-style: none; margin: 0; padding: 0; }
.old .ris-chain { grid-template-columns: repeat(4, 1fr); }
.new .ris-chain { grid-template-columns: repeat(4, 1fr); }
.ris-chain li { position: relative; text-align: center; padding: 18px 12px; border-radius: var(--r-md); font-weight: 700; font-size: .93rem; }
.old .ris-chain li { background: #fff; border: 1px dashed #F1B5B7; color: var(--ink); }
.new .ris-chain li { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16); color: #fff; padding: 26px 12px; font-size: var(--text-base); }
.ris-chain .ic { width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 50%; display: grid; place-items: center; }
.ris-chain .ic svg { width: 22px; height: 22px; }
.old .ris-chain .ic { background: #FDECEC; color: #C62F35; }
.new .ris-chain .ic { background: var(--grad-cyan); color: #fff; width: 56px; height: 56px; }
.new .ris-chain .ic svg { width: 26px; height: 26px; }
.ris-chain .n { position: absolute; top: 8px; left: 10px; font-size: var(--text-xs); font-weight: 800; opacity: .5; }
.ris-verdict { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.ris-verdict span { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: var(--r-pill); font-weight: 700; font-size: .93rem; }
.ris-verdict svg { width: 17px; height: 17px; }
.old .ris-verdict span { background: #fff; color: #C62F35; border: 1px solid #F8D7D8; }
.new .ris-verdict span { background: rgba(46,204,113,.16); color: #6EE7A1; }
.ris-hint { text-align: center; margin-top: 18px; color: var(--text-soft); font-size: var(--text-sm); }
@keyframes risIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

/* ---------- Journey ---------- */
.ris-journey { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; position: relative; list-style: none; padding: 0; margin: 0; }
.ris-journey::before { content: ""; position: absolute; top: 44px; left: 12%; right: 12%; height: 3px; background: repeating-linear-gradient(90deg, var(--cyan-light) 0 10px, transparent 10px 18px); }
.ris-step { position: relative; text-align: center; }
.ris-step-ic { position: relative; width: 88px; height: 88px; margin: 0 auto 18px; border-radius: 28px; display: grid; place-items: center; color: #fff; box-shadow: var(--e3); }
.ris-step-ic svg { width: 40px; height: 40px; }
.ris-step-ic .n { position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background: #fff; color: var(--ink); font-weight: 800; font-size: var(--text-sm); display: grid; place-items: center; box-shadow: var(--e2); }
.ris-step h3 { font-size: var(--text-xl); margin-bottom: 8px; }
.ris-step p { color: var(--text-soft); font-size: .96rem; }
.ris-step .tag { display: inline-block; margin-top: 12px; padding: 6px 12px; border-radius: var(--r-pill); background: var(--cyan-tint); color: var(--cyan-dark); font-weight: 700; font-size: var(--text-sm); }

.ris-idea { margin: 56px auto 0; max-width: 820px; display: flex; gap: 22px; align-items: center; padding: 26px 30px; border-radius: var(--r-xl); background: #FFF9EB; border: 2px dashed #F5C46B; }
.ris-idea-ic { width: 64px; height: 64px; flex: none; border-radius: 50%; background: var(--amber); color: #fff; display: grid; place-items: center; }
.ris-idea-ic svg { width: 30px; height: 30px; }
.ris-idea b { display: block; color: #92400E; font-size: var(--text-lg); margin-bottom: 4px; }
.ris-idea p { color: #7C5A1E; margin: 0; }

/* ---------- Benefits ---------- */
.ris-ben { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.ris-ben .card { padding: 28px; }
.ris-ben h3 { font-size: var(--text-xl); margin: 18px 0 8px; }
.ris-ben p { color: var(--text-soft); font-size: .96rem; }

/* ---------- Price calculator ---------- */
.ris-price { display: grid; grid-template-columns: 1.05fr .95fr; gap: 28px; align-items: stretch; }
.ris-calc { background: var(--grad-hero); color: #fff; border-radius: var(--r-xl); padding: 40px; box-shadow: var(--e4); position: relative; overflow: hidden; }
.ris-calc::after { content: ""; position: absolute; right: -80px; top: -80px; width: 240px; height: 240px; border-radius: 50%; background: rgba(0,180,216,.25); filter: blur(60px); }
.ris-calc > * { position: relative; z-index: 1; }
.ris-calc .big { font-size: clamp(3.4rem, 7vw, 5rem); font-weight: 800; line-height: 1; }
.ris-calc .big small { font-size: var(--text-xl); font-weight: 600; color: rgba(255,255,255,.75); }
.ris-calc label { display: block; margin: 30px 0 10px; font-weight: 700; color: rgba(255,255,255,.85); }
.ris-calc output { color: #6EE7A1; }
.ris-calc input[type=range] { width: 100%; accent-color: var(--cyan); height: 28px; cursor: pointer; }
.ris-total { margin-top: 18px; padding: 18px 20px; border-radius: var(--r-md); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
.ris-total span { color: rgba(255,255,255,.75); }
.ris-total strong { font-size: var(--text-3xl); color: #fff; }
.ris-chai { margin-top: 16px; display: flex; gap: 10px; align-items: center; color: rgba(255,255,255,.75); font-size: .93rem; }
.ris-chai svg { width: 20px; height: 20px; color: #FCD34D; flex: none; }
.ris-incl { display: grid; gap: 14px; }
.ris-incl .card { display: flex; gap: 16px; align-items: center; padding: 20px 22px; }
.ris-incl .card:hover { transform: none; }
.ris-incl .feat-ic { flex: none; margin: 0; }
.ris-incl b { display: block; color: var(--ink); font-size: var(--text-lg); }
.ris-incl span { color: var(--text-soft); font-size: .93rem; }

.ris-sticky { display: none; }

@media (prefers-reduced-motion: reduce) {
  .ris-val, .ris-line::after, .ris-bubble, .ris-fan::after, .ris-link span { animation: none; }
  .ris-line::after { width: 100%; }
}
@media (max-width: 1024px) {
  .ris-ben { grid-template-columns: repeat(2, 1fr); }
  .ris-price { grid-template-columns: 1fr; }
}
@media (max-width: 860px) {
  .ris-hero .hero-grid { grid-template-columns: 1fr; gap: 44px; }
  .ris-pipe { margin: 0; max-width: 520px; }
  .ris-journey { grid-template-columns: repeat(2, 1fr); row-gap: 40px; }
  .ris-journey::before { display: none; }
  .old .ris-chain { grid-template-columns: repeat(2, 1fr); }
  .new .ris-chain { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 680px) {
  .ris-hero { padding-top: calc(var(--nav-h) + 36px); padding-bottom: 56px; }
  .ris-hero .hero-actions .btn { flex: 1 1 100%; }
  .ris-switch label { padding: 10px 14px; font-size: var(--text-sm); }
  .ris-panel { padding: 22px 16px; }
  .ris-ben, .ris-journey { grid-template-columns: 1fr; }
  .ris-idea { flex-direction: column; text-align: center; padding: 24px 20px; }
  .ris-calc { padding: 28px 22px; }
  .ris-sticky { display: flex; gap: 10px; position: fixed; left: 0; right: 0; bottom: 0; z-index: 950; padding: 10px 12px calc(10px + env(safe-area-inset-bottom)); background: rgba(255,255,255,.97); border-top: 1px solid var(--line); box-shadow: 0 -8px 24px rgba(15,27,51,.08); }
  .ris-sticky .btn { flex: 1; padding: 14px 10px; font-size: var(--text-base); }
  .ris-sticky .btn-wa { flex: 0 0 52px; padding: 0; background: #25D366; color: #fff; }
  .ris-sticky .btn-wa svg { width: 24px; height: 24px; }
  .wa-float { display: none; }
  .to-top { bottom: 86px !important; }
  .footer { padding-bottom: 90px; }
}
</style>
HTML;

require __DIR__ . '/partials/clients-data.php';
require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';

$wa_link = WHATSAPP_URL . '?text=' . rawurlencode('Hi, I want a demo of HealthO Pro RIS for my ultrasound centre.');
$volume  = form_select('orgType', 'Monthly Scan Volume', ['Under 500 scans/month', '500–2000 scans/month', '2000–5000 scans/month', '5000+ scans/month']);
?>
<!-- ===== 1. HERO: the whole idea in one picture ===== -->
<header class="hero ris-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="hero-grid">
      <div class="reveal">
        <span class="eyebrow">For Ultrasound &amp; Imaging Centres</span>
        <h1>Scan done.<br><span class="text-grad">Report ready.</span></h1>
        <p class="ris-sub">HealthO Pro RIS connects your ultrasound machine to your computer. It <b>reads every number</b> from the scan, <b>writes the report</b> with charts and photos, and <b>sends it to the patient's WhatsApp</b>. Your doctor just checks and signs.</p>
        <div class="hero-actions">
          <a href="#demo" class="btn btn-primary btn-lg"><?= ris_ic('play') ?> Book a Free Demo</a>
          <a href="<?= $wa_link ?>" target="_blank" rel="noopener" class="btn btn-ghost-light btn-lg"><?= ris_ic('wa') ?> WhatsApp Us</a>
        </div>
        <div class="ris-pills">
          <span class="ris-pill"><b>₹5</b> per patient</span>
          <span class="ris-pill"><b>₹0</b> monthly fee</span>
          <span class="ris-pill"><b>Free</b> setup</span>
          <span class="ris-pill"><b>24×7</b> help</span>
        </div>
      </div>

      <div class="ris-pipe reveal d1" aria-label="How a scan becomes a report: the machine shows numbers, RIS writes the report, the patient gets it on WhatsApp">
        <div class="ris-node">
          <span class="ris-node-ic c-cyan"><?= ris_ic('monitor') ?></span>
          <div class="ris-node-body">
            <small>1 · Ultrasound machine</small><strong>Scan shows the numbers</strong>
            <div class="ris-screen"><span class="ris-fan"></span><span class="ris-vals"><span class="ris-val">BPD 45mm</span><span class="ris-val">FL 32mm</span><span class="ris-val">EFW 410g</span><span class="ris-val">AFI 12</span></span></div>
          </div>
        </div>
        <div class="ris-link"><span></span></div>
        <div class="ris-node">
          <span class="ris-node-ic c-violet"><?= ris_ic('sparkle') ?></span>
          <div class="ris-node-body">
            <small>2 · HealthO Pro RIS</small><strong>Report writes itself</strong>
            <div class="ris-paper"><span class="ris-line"></span><span class="ris-line"></span><span class="ris-line"></span></div>
          </div>
        </div>
        <div class="ris-link"><span></span></div>
        <div class="ris-node">
          <span class="ris-node-ic c-green"><?= ris_ic('wa') ?></span>
          <div class="ris-node-body">
            <small>3 · Patient's phone</small><strong>Report delivered</strong>
            <div><span class="ris-bubble"><?= ris_ic('check') ?> Your scan report is ready</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<?= client_trust_strip('ris') ?>

<!-- ===== 2. BEFORE vs AFTER (one switch) ===== -->
<section class="section section--soft" id="before-after">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Before vs After</span>
      <h2 class="h-sec">Same scan. Half the steps. Zero typing.</h2>
      <p class="lead">Flip the switch to see how one ultrasound report is made today — and with HealthO Pro RIS.</p>
    </div>
    <div class="ris-ba reveal">
      <input type="radio" name="ris-mode" id="ris-old" checked>
      <input type="radio" name="ris-mode" id="ris-new">
      <div class="ris-switch" role="presentation">
        <label for="ris-old"><?= ris_ic('x') ?> The old way</label>
        <label for="ris-new"><?= ris_ic('check') ?> With HealthO Pro</label>
      </div>
      <div class="ris-panels">
        <div class="ris-panel old">
          <ol class="ris-chain">
<?php foreach ($old_way as $i => [$ic, $t]): ?>
            <li><span class="n"><?= $i + 1 ?></span><span class="ic"><?= ris_ic($ic) ?></span><?= h($t) ?></li>
<?php endforeach; ?>
          </ol>
          <div class="ris-verdict"><span><?= ris_ic('x') ?>8 steps</span><span><?= ris_ic('keyboard') ?>Lots of typing</span><span><?= ris_ic('x') ?>Wrong digits slip in</span><span><?= ris_ic('clock') ?>Long waiting room</span></div>
        </div>
        <div class="ris-panel new">
          <ol class="ris-chain">
<?php foreach ($new_way as $i => [$ic, $t]): ?>
            <li><span class="n"><?= $i + 1 ?></span><span class="ic"><?= ris_ic($ic) ?></span><?= h($t) ?></li>
<?php endforeach; ?>
          </ol>
          <div class="ris-verdict"><span><?= ris_ic('check') ?>Just 4 steps</span><span><?= ris_ic('check') ?>No numbers typed</span><span><?= ris_ic('check') ?>No copy mistakes</span><span><?= ris_ic('check') ?>Report ready when the scan ends</span></div>
        </div>
      </div>
      <p class="ris-hint">Tap the switch above to compare</p>
    </div>
  </div>
</section>

<!-- ===== 3. HOW IT WORKS ===== -->
<section class="section" id="how">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">How It Works</span>
      <h2 class="h-sec">One scan's journey, in 4 steps</h2>
    </div>
    <ol class="ris-journey">
<?php foreach ($journey as $i => [$ic, $c, $t, $d, $tag]): ?>
      <li class="ris-step reveal<?= $i ? ' d' . $i : '' ?>">
        <div class="ris-step-ic <?= $c ?>"><?= ris_ic($ic) ?><span class="n"><?= $i + 1 ?></span></div>
        <h3><?= h($t) ?></h3>
        <p><?= h($d) ?></p>
        <span class="tag"><?= h($tag) ?></span>
      </li>
<?php endforeach; ?>
    </ol>
    <div class="ris-idea reveal">
      <span class="ris-idea-ic"><?= ris_ic('sparkle') ?></span>
      <div>
        <b>Think of it like this</b>
        <p>Copying answers from the blackboard into your notebook takes time, and you sometimes write a wrong number. RIS is like a friend who <b style="display:inline;font-size:inherit;">reads the board and fills your notebook perfectly</b> — every single time.</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== 4. WHAT YOU GET ===== -->
<section class="section section--tint" id="features">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">What You Get</span>
      <h2 class="h-sec">Everything a scan centre needs</h2>
      <p class="lead">All included in one simple price. No extra modules to buy later.</p>
    </div>
    <div class="ris-ben">
<?php foreach ($benefits as $i => [$ic, $bg, $t, $d]): ?>
      <div class="card reveal<?= $i % 3 ? ' d' . ($i % 3) : '' ?>">
        <span class="feat-ic <?= $bg ?>"><?= ris_ic($ic) ?></span>
        <h3><?= h($t) ?></h3>
        <p><?= h($d) ?></p>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== 5. PRICE (with calculator) + SETUP ===== -->
<section class="section" id="payg">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Simple Pricing</span>
      <h2 class="h-sec">Pay only for the patients you scan</h2>
    </div>
    <div class="ris-price">
      <div class="ris-calc reveal">
        <div class="big">₹5 <small>/ patient</small></div>
        <label for="ris-n">Patients you scan in a month: <output id="ris-n-out" for="ris-n">1,000</output></label>
        <input type="range" id="ris-n" min="100" max="5000" step="100" value="1000">
        <div class="ris-total"><span>You pay this month</span><strong id="ris-total">₹5,000</strong></div>
        <div class="ris-chai"><?= ris_ic('cup') ?>Less than a cup of chai for every report. Fewer scans? You pay less.</div>
      </div>
      <div class="ris-incl">
        <div class="card reveal d1"><span class="feat-ic bg-green"><?= ris_ic('check') ?></span><div><b>No monthly licence fee</b><span>No upfront cost. Nothing to pay when there are no scans.</span></div></div>
        <div class="card reveal d1"><span class="feat-ic bg-cyan"><?= ris_ic('tool') ?></span><div><b>We set it up for you — free</b><span>We install the app on one Windows PC, connect your machine and copy your report format.</span></div></div>
        <div class="card reveal d2"><span class="feat-ic bg-violet"><?= ris_ic('users') ?></span><div><b>Free training &amp; 24×7 help</b><span>Your team learns in a day. We're a call or WhatsApp away.</span></div></div>
        <div class="card reveal d2"><span class="feat-ic bg-navy"><?= ris_ic('building') ?></span><div><b>Made for</b><span>Ultrasound centres, pregnancy scan clinics, hospital radiology and diagnostic chains.</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== 6. FAQ ===== -->
<?php faq_section($faqs, 'Questions? Simple answers.', 'section section--soft'); ?>

<!-- ===== 7. FINAL CTA + FORM ===== -->
<section class="section" id="demo">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">Free Demo</span>
        <h2 class="h-sec">See your own scan become a report</h2>
        <p class="lead" style="margin:16px 0 28px;">Tell us about your centre. We'll show HealthO Pro RIS working with your machine and your report format — free, no obligation.</p>
        <?= client_proof('ris') ?>
        <?php contact_strip(); ?>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Book your free RIS demo</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">We'll call you within one business day.</p>
        <?php lead_form([
          'hidden'   => ['interest' => 'RIS / RIMS', 'source' => 'RIS landing — footer'],
          'aside'    => $volume,
          'interest' => '',
          'message'  => ['label' => 'Ultrasound machine (optional)', 'placeholder' => 'e.g. GE Voluson E8, Samsung HS70…'],
          'submit'   => 'Get My Free Demo',
        ]); ?>
      </div>
    </div>
  </div>
</section>

<!-- Mobile sticky CTA -->
<div class="ris-sticky">
  <a href="#demo" class="btn btn-primary">Book Free Demo</a>
  <a href="<?= $wa_link ?>" target="_blank" rel="noopener" class="btn btn-wa" aria-label="Chat on WhatsApp"><?= ris_ic('wa') ?></a>
</div>

<script>
(function () {
  // Price calculator
  var r = document.getElementById('ris-n'), out = document.getElementById('ris-n-out'), tot = document.getElementById('ris-total');
  var fmt = function (n) { return n.toLocaleString('en-IN'); };
  r.addEventListener('input', function () { var n = +r.value; out.textContent = fmt(n); tot.textContent = '₹' + fmt(n * 5); });

  // Before/after: flip to "With HealthO Pro" once, a moment after it is seen, unless the visitor already chose.
  var ba = document.querySelector('.ris-ba'), touched = false;
  ba.addEventListener('change', function () { touched = true; });
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (es) {
      if (!es[0].isIntersecting) return;
      io.disconnect();
      setTimeout(function () { if (!touched) document.getElementById('ris-new').checked = true; }, 3500);
    }, { threshold: .6 });
    io.observe(ba);
  }
})();
</script>
<?php require __DIR__ . '/partials/foot.php';
