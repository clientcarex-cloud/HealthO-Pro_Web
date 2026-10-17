<?php
$page        = 'ris';
$title       = 'Ultrasound Reports That Write Themselves — RIS / RIMS | HealthO Pro';
$description = 'HealthO Pro RIS connects your ultrasound machine to your reports: auto worklist, OCR of every measurement, auto-built reports with charts, PNDT Form F draft and WhatsApp delivery. ₹5 per patient.';
require_once __DIR__ . '/partials/seo.php';
$faqs = [
    'Do I have to type measurements into the report?' =>
        'No. The HealthO Pro RIS desktop app receives the study from your ultrasound machine and reads the measurements from the report page and from the measurement boxes on the scan images (biometry, EFW, AFI, Doppler, ratios). The values go straight into your report template.',
    'Which ultrasound machines does it work with?' =>
        'Any ultrasound machine that can send DICOM images to a PC on your network (DICOM Store). Machines that support a DICOM Modality Worklist also receive the patient list automatically, so nobody retypes patient names on the machine.',
    'What do I need to install?' =>
        'One Windows PC at your centre running the HealthO Pro RIS app (installer or Microsoft Store). Our team sets up the DICOM connection with your machine and maps your exam types during free onboarding.',
    'Does it help with PNDT Form F?' =>
        'Yes. For obstetric scans the system auto-drafts Form F from the scan and the patient record. Staff review and save it. Fetal sex is never auto-captured from the scan.',
    'Can the radiologist still edit the report?' =>
        'Always. The report opens in a Word-style A4 editor with your letterhead. The doctor gets ready phrases, flagged findings, a suggested impression, previous scan comparison and voice dictation, then signs the report.',
    'How do patients receive their reports?' =>
        'Signed reports can be sent automatically on official WhatsApp, SMS or email, and shared through a secure link or the patient portal.',
    'How much does HealthO Pro RIS cost?' =>
        'RIS/RIMS is pay-as-you-go at ₹5 per patient processed. There are no fixed monthly licence fees, all core features are included, and onboarding and 24×7 support are free.',
    'Is it suitable for hospital radiology departments?' =>
        'Yes. It works for standalone ultrasound and imaging centres and for hospital radiology departments, and it plugs into HealthO Pro HIMS billing, LIMS and the antenatal follow-up module.',
];
$schema = [seo_software('ris', 'RIS / RIMS — Radiology Information System', $description, 'Radiology Information System', ['price' => '5', 'unit' => '₹5 per patient processed, pay-as-you-go, no fixed licence fee'])];

/** Stroke icons used across the page (24×24, currentColor). */
function ris_ic(string $name): string
{
    static $p = [
        'check'   => '<path d="M20 6 9 17l-5-5"/>',
        'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
        'arrow'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'monitor' => '<rect x="2" y="4" width="20" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'scan'    => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2M7 12h10"/>',
        'doc'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>',
        'chart'   => '<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>',
        'mic'     => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 17v5"/>',
        'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'send'    => '<path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z"/>',
        'wallet'  => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'list'    => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'heart'   => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21.2l8.8-8.8a5.5 5.5 0 0 0 0-7.8Z"/>',
        'edit'    => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'zap'     => '<path d="M13 2 3 14h9l-1 8 10-12h-9Z"/>',
        'users'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'link'    => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
        'wa'      => '<path d="M21 12a9 9 0 0 1-13.4 7.9L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/><path d="M8.5 9.5c.5 2.5 3.5 5.5 6 6l1.5-1.5-2-1-1 1c-1-.5-2.5-2-3-3l1-1-1-2Z"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$name] ?? '') . '</svg>';
}

$problems = [
    ['Typists retype every measurement', 'BPD, HC, AC, FL, EFW, AFI and Doppler values are copied by hand from the machine screen into Word — slow, and one wrong digit changes a report.',
     'Measurements read straight from the machine', 'The RIS app reads the report page and the measurement boxes on every scan image and fills your template. Nobody types a number.'],
    ['Patient names typed twice', 'Reception bills the patient, then the sonographer types the same name, age and ID into the machine. Spelling mismatches lose studies.',
     'Billed scans appear on the machine', 'Bill the scan and the patient shows up on the machine\'s Modality Worklist. The study comes back already tagged to the right test.'],
    ['Long waits for reports', 'Patients sit in the waiting area while the report is typed, printed, corrected and re-printed.',
     'Report ready when the scan ends', 'The report is built on the right template with values, charts and images the moment images arrive. The doctor reviews and signs.'],
    ['Growth charts and images done by hand', 'Charts are drawn or pasted, images printed and stuck on — every report looks different.',
     'Charts, tables and images built in', 'Growth and Doppler charts, percentile tables and a scan-image page are generated automatically on your letterhead.'],
    ['PNDT Form F paperwork', 'Every obstetric scan means filling Form F by hand, and missed entries are a compliance risk.',
     'Form F auto-drafted', 'Form F is auto-drafted from the scan and the patient record for staff to review and save. Fetal sex is never auto-captured.'],
    ['Follow-ups and delivery fall through', 'Reports are handed over or WhatsApped manually; ANC patients are not reminded about their next scan.',
     'Auto delivery and ANC follow-up', 'Signed reports go out on WhatsApp, SMS or email. Pregnant patients are enrolled in antenatal follow-up with reminders.'],
];

$old_flow = [
    ['Register & bill', 'Reception writes the patient in the register and raises the bill.'],
    ['Retype on the machine', 'Sonographer types name, age and ID again on the ultrasound machine.'],
    ['Scan & note values', 'Doctor scans and reads the measurements off the screen.'],
    ['Type the report', 'Typist copies every value into a Word file and picks a format.'],
    ['Paste charts & images', 'Charts drawn or pasted, images printed separately.'],
    ['Correct & re-print', 'Doctor finds typos, typist fixes them, report printed again.'],
    ['Fill Form F', 'Form F written by hand for every obstetric scan.'],
    ['Hand over', 'Patient waits; follow-up reminders depend on memory.'],
];
$new_flow = [
    ['Bill the scan', 'The patient appears on the machine\'s worklist automatically.'],
    ['Scan', 'Images flow from the machine to HealthO Pro as you scan.'],
    ['Measurements read', 'OCR reads the report page and every measurement box.'],
    ['Report auto-built', 'Right template, values, growth and Doppler charts, images.'],
    ['Review with assist', 'Flags, phrases, suggested impression, previous scans, dictation.'],
    ['Sign & deliver', 'E-sign; report goes out on WhatsApp, SMS or email. Form F and ANC follow-up are drafted for you.'],
];

$feature_groups = [
    ['monitor', 'bg-navy', 'Machine connectivity', [
        'Windows RIS app receives DICOM studies from your ultrasound machine',
        'Modality Worklist — billed scans appear on the machine, no retyping',
        'Each study auto-linked to the right visit and test (Visit ID / accession)',
        'Live dashboard of incoming studies and images',
        'Remote Control: manage the centre PC\'s settings from HealthO Pro',
        'Installer or Microsoft Store build, with free setup',
    ]],
    ['scan', 'bg-cyan', 'Auto measurement capture', [
        'OCR of the machine\'s report page (biometry, EFW, AFI, GA, EDD)',
        'Reads the measurement boxes printed on scan frames',
        'Doppler values: Umbilical, MCA and Uterine PI / RI / PSV, CPR',
        'Ratios such as FL/AC and HC/AC',
        'Units and dates cleaned up automatically (mm, GA in weeks + days)',
        'Exam Type Mapping sends each study to the right report template',
    ]],
    ['doc', 'bg-violet', 'Automatic report building', [
        'Report created on your template the moment images arrive',
        '{Tags} fill patient, visit and measurement values anywhere in the format',
        'Growth charts and Doppler charts generated per study',
        'Premium tables: biometry, Doppler percentiles, vitals',
        'Scan-image page with all study images, laid out for print',
        'Re-process a study in one click if values change',
    ]],
    ['mic', 'bg-green', 'Radiologist & typist assist', [
        'Findings to Review — out-of-range values flagged before signing',
        'Phrase library: built-in and your own, filled from the study',
        'Suggested impression, with optional "Refine with HealthO AI"',
        'Previous scans of the same patient side by side',
        'Voice dictation in Chrome and Edge, with spoken punctuation',
        'Obstetric calculators: GA, EDD and 2nd trimester risk',
    ]],
    ['edit', 'bg-amber', 'Report editor & print', [
        'Word-style A4 editor with your real letterhead',
        'Fixed (parameter) and free-text report formats',
        'Report fonts, spacing and print scale controls',
        'Doctor authorization and e-signature',
        'Barcode / MR number on the report',
        'Print or save as PDF exactly as previewed',
    ]],
    ['shield', 'bg-navy', 'Compliance & follow-up', [
        'PNDT Form F auto-drafted from obstetric scans',
        'Fetal sex is never auto-captured from the scan',
        'Antenatal enrolment, ANC visit and USG risk flags (AFI, EFW centile)',
        'Next-visit reminders for pregnant patients',
        'NABL / NABH turnaround-time (TAT) tracking',
        'Full audit trail of every study and report',
    ]],
    ['send', 'bg-cyan', 'Report delivery', [
        'Automatic delivery once the report is signed',
        'Official WhatsApp, SMS and email',
        'Secure report link and patient portal',
        'Work Queue shows every scan by stage: waiting, typed, signed, sent',
    ]],
    ['wallet', 'bg-green', 'Billing & analytics', [
        'Scan-wise billing, packages and referral doctor tracking',
        'Live RIS report: studies received, matched, reported',
        'Volume, TAT and revenue by modality and doctor',
        'Works with HealthO Pro HIMS, LIMS and pharmacy',
    ]],
];

$head_extra = <<<'HTML'
<style>
.ris-hero { padding-bottom: 80px; }
.ris-hero .hero-grid { align-items: start; }
.ris-hero h1 { font-size: clamp(2.1rem, 4vw, 3.3rem); }
.ris-hero .checks { margin: 0 0 30px; gap: 12px; }
.ris-hero .checks span { color: rgba(255,255,255,.9); }
.ris-hero .checks .ck { background: rgba(46,204,113,.18); color: #2ECC71; }
.ris-hero .form-card { padding: 30px; }
.ris-hero .form-card h3 { color: var(--ink); }
.ris-hero .hero-trust { border-top-color: rgba(255,255,255,.12); padding-top: 28px; gap: 30px; }
.ris-hero .hero-trust .num { font-size: var(--text-3xl); }
.ris-hero .form-card p { color: var(--text-mute); max-width: none; font-size: .85rem; line-height: 1.5; margin: 0; }
.ris-hero .form-card p.ris-form-sub { font-size: .92rem; margin: 6px 0 20px; }

.ris-strip { background: var(--navy-dark); color: rgba(255,255,255,.85); padding: 18px 0; font-weight: 600; }
.ris-strip .container { display: flex; flex-wrap: wrap; gap: 14px 34px; justify-content: center; }
.ris-strip span { display: inline-flex; align-items: center; gap: 8px; font-size: var(--text-sm); }
.ris-strip svg { width: 18px; height: 18px; color: var(--cyan-light); }

.ris-ps { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px; }
.ris-ps-card { background: var(--surface); border: 1px solid var(--line); border-radius: var(--r-lg); overflow: hidden; box-shadow: var(--e1); display: grid; grid-template-rows: 1fr auto 1fr; }
.ris-ps-p, .ris-ps-s { padding: 22px 24px; display: flex; gap: 14px; }
.ris-ps-p { background: #FFF6F6; }
.ris-ps-s { background: #F2FBF6; }
.ris-ps-ic { width: 32px; height: 32px; border-radius: 50%; flex: none; display: grid; place-items: center; color: #fff; }
.ris-ps-ic svg { width: 17px; height: 17px; }
.ris-ps-p .ris-ps-ic { background: #E5484D; }
.ris-ps-s .ris-ps-ic { background: var(--green-dark); }
.ris-ps-tag { display: block; font-size: var(--text-xs); font-weight: 800; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 4px; }
.ris-ps-p .ris-ps-tag { color: #C62F35; }
.ris-ps-s .ris-ps-tag { color: var(--green-dark); }
.ris-ps-card h4 { font-size: var(--text-lg); margin-bottom: 4px; }
.ris-ps-card p { color: var(--text-soft); font-size: .95rem; }
.ris-ps-mid { height: 0; border-top: 1px dashed var(--line); position: relative; }
.ris-ps-mid::after { content: "↓"; position: absolute; left: 50%; top: -14px; transform: translateX(-50%); width: 28px; height: 28px; border-radius: 50%; background: #fff; border: 1px solid var(--line); display: grid; place-items: center; font-weight: 800; color: var(--cyan-dark); font-size: 14px; line-height: 26px; text-align: center; }

.ris-flow { display: grid; grid-template-columns: 1fr 1fr; gap: 26px; align-items: start; }
.ris-flow-col { border-radius: var(--r-xl); padding: 32px; border: 1px solid var(--line); }
.ris-flow-col.old { background: #fff; }
.ris-flow-col.new { background: var(--grad-hero); color: #fff; border-color: transparent; box-shadow: var(--e4); }
.ris-flow-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 22px; flex-wrap: wrap; }
.ris-flow-head h3 { font-size: var(--text-2xl); }
.ris-flow-col.new h3 { color: #fff; }
.ris-badge { font-size: var(--text-xs); font-weight: 800; letter-spacing: .06em; text-transform: uppercase; padding: 6px 12px; border-radius: var(--r-pill); }
.old .ris-badge { background: #FDECEC; color: #C62F35; }
.new .ris-badge { background: rgba(46,204,113,.2); color: #6EE7A1; }
.ris-steps { counter-reset: s; display: grid; gap: 4px; list-style: none; padding: 0; margin: 0; }
.ris-steps li { counter-increment: s; position: relative; padding: 10px 0 10px 50px; }
.ris-steps li::before { content: counter(s); position: absolute; left: 0; top: 9px; width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; font-size: var(--text-sm); }
.ris-steps li:not(:last-child)::after { content: ""; position: absolute; left: 16.5px; top: 46px; bottom: -6px; width: 1px; }
.old .ris-steps li::before { background: #F1F5F9; color: var(--text-soft); }
.old .ris-steps li::after { background: var(--line); }
.new .ris-steps li::before { background: var(--grad-cyan); color: #fff; }
.new .ris-steps li::after { background: rgba(255,255,255,.18); }
.ris-steps b { display: block; color: var(--ink); font-size: var(--text-base); }
.ris-steps span { color: var(--text-soft); font-size: .93rem; }
.new .ris-steps b { color: #fff; }
.new .ris-steps span { color: rgba(255,255,255,.75); }
.ris-flow-foot { margin-top: 20px; padding-top: 18px; border-top: 1px dashed var(--line); font-weight: 700; display: flex; gap: 10px; align-items: center; }
.ris-flow-foot svg { width: 20px; height: 20px; flex: none; }
.old .ris-flow-foot { color: #C62F35; }
.new .ris-flow-foot { color: #6EE7A1; border-top-color: rgba(255,255,255,.18); }

.ris-cmp { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 40px; background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg); overflow: hidden; box-shadow: var(--e1); }
.ris-cmp th, .ris-cmp td { padding: 14px 18px; text-align: left; font-size: .95rem; border-bottom: 1px solid var(--line-soft); vertical-align: top; }
.ris-cmp thead th { background: var(--bg-soft); color: var(--ink); font-weight: 800; }
.ris-cmp tr:last-child td { border-bottom: 0; }
.ris-cmp td:first-child { font-weight: 700; color: var(--ink); width: 26%; }
.ris-cmp td:nth-child(2) { color: var(--text-soft); }
.ris-cmp td:nth-child(3) { color: var(--navy); font-weight: 600; }
.ris-cmp-wrap { overflow-x: auto; }

.ris-feat { display: grid; grid-template-columns: repeat(2, 1fr); gap: 22px; }
.ris-feat .card { padding: 28px; }
.ris-feat .card:hover { transform: none; }
.ris-feat-h { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
.ris-feat-h h3 { font-size: var(--text-xl); }
.ris-feat ul { display: grid; gap: 9px; }
.ris-feat li { display: flex; gap: 10px; align-items: flex-start; font-size: .95rem; color: var(--text); }
.ris-feat li svg { width: 17px; height: 17px; color: var(--green-dark); flex: none; margin-top: 3px; }

.ris-how { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.ris-how .card { text-align: center; }
.ris-how .n { width: 46px; height: 46px; border-radius: 50%; background: var(--grad-cyan); color: #fff; font-weight: 800; display: grid; place-items: center; margin: 0 auto 16px; font-size: var(--text-lg); box-shadow: var(--e-cyan); }
.ris-how h4 { margin-bottom: 8px; }
.ris-how p { color: var(--text-soft); }

.ris-for { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.ris-for .card { padding: 24px; }
.ris-for h4 { font-size: var(--text-lg); margin: 14px 0 6px; }
.ris-for p { color: var(--text-soft); font-size: .93rem; }

.ris-sticky { display: none; }

@media (max-width: 1024px) {
  .ris-feat, .ris-flow { grid-template-columns: 1fr; }
  .ris-for { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 860px) {
  .ris-ps, .ris-how { grid-template-columns: 1fr; }
  .ris-hero .hero-grid { grid-template-columns: 1fr; gap: 40px; text-align: left; }
  .ris-hero .hero-actions, .ris-hero .hero-trust { justify-content: flex-start; }
  .ris-hero .form-card { max-width: 560px; }
}
@media (max-width: 680px) {
  .ris-for { grid-template-columns: 1fr; }
  .ris-flow-col { padding: 24px 20px; }
  .ris-hero { padding-top: calc(var(--nav-h) + 36px); padding-bottom: 56px; }
  .ris-hero .form-card { padding: 24px 18px; }
  .ris-hero .eyebrow { font-size: var(--text-xs); }
  .ris-hero .hero-actions { margin-bottom: 28px; }
  .ris-hero .hero-actions .btn { width: auto; flex: 1 1 100%; }
  .ris-hero .hero-trust { gap: 20px; }
  .ris-cmp th, .ris-cmp td { padding: 12px; font-size: .88rem; }
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

require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';

$volume = form_select('orgType', 'Monthly Scan Volume', ['Under 500 scans/month', '500–2000 scans/month', '2000–5000 scans/month', '5000+ scans/month']);
?>
<!-- ===== HERO + FORM ===== -->
<header class="hero ris-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="hero-grid">
      <div class="reveal">
        <span class="eyebrow">For Ultrasound &amp; Imaging Centres</span>
        <h1>Ultrasound reports that <span class="text-grad">write themselves</span></h1>
        <p>HealthO Pro RIS connects your ultrasound machine to your report. Every measurement is read automatically, the report is built with charts and images, and it reaches the patient on WhatsApp — no retyping, no copy-paste.</p>
        <ul class="checks">
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Zero manual typing of BPD, HC, AC, FL, EFW, AFI &amp; Doppler</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Billed patients appear on the machine's worklist</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>PNDT Form F auto-drafted · reports auto-sent on WhatsApp</span></li>
        </ul>
        <div class="hero-actions">
          <a href="#demo" class="btn btn-primary btn-lg">Book a Free Demo</a>
          <a href="<?= WHATSAPP_URL ?>?text=<?= rawurlencode('Hi, I want a demo of HealthO Pro RIS for my ultrasound centre.') ?>" target="_blank" rel="noopener" class="btn btn-ghost-light btn-lg"><?= ris_ic('wa') ?> WhatsApp Us</a>
        </div>
        <div class="hero-trust">
          <div><div class="num">₹5</div><div class="lbl">per patient</div></div>
          <div><div class="num">₹0</div><div class="lbl">licence fee</div></div>
          <div><div class="num">24×7</div><div class="lbl">support</div></div>
          <div><div class="num">Free</div><div class="lbl">setup &amp; training</div></div>
        </div>
      </div>
      <div class="form-card reveal d1" id="get-demo">
        <h3 class="h-card">See it on your own scans</h3>
        <p class="ris-form-sub">Free demo on your own report format. We'll call you within one business day.</p>
        <?php lead_form([
          'hidden'   => ['interest' => 'RIS / RIMS', 'source' => 'RIS landing — hero'],
          'aside'    => $volume,
          'interest' => '',
          'message'  => ['label' => 'Ultrasound machine (optional)', 'placeholder' => 'e.g. GE Voluson E8, Samsung HS70…'],
          'submit'   => 'Get My Free Demo',
        ]); ?>
      </div>
    </div>
  </div>
</header>

<div class="ris-strip">
  <div class="container">
    <span><?= ris_ic('monitor') ?>Works with DICOM ultrasound machines</span>
    <span><?= ris_ic('shield') ?>PNDT-friendly · Form F auto-draft</span>
    <span><?= ris_ic('clock') ?>NABL / NABH TAT tracking</span>
    <span><?= ris_ic('heart') ?>Built-in antenatal follow-up</span>
  </div>
</div>

<!-- ===== PROBLEMS & SOLUTIONS ===== -->
<section class="section section--soft" id="problems">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Problems &amp; Solutions</span>
      <h2 class="h-sec">Sound familiar at your centre?</h2>
      <p class="lead">The same six problems slow down almost every ultrasound centre. Here's how HealthO Pro RIS removes each one.</p>
    </div>
    <div class="ris-ps">
<?php foreach ($problems as $i => [$pt, $pd, $st, $sd]): ?>
      <div class="ris-ps-card reveal<?= $i % 2 ? ' d1' : '' ?>">
        <div class="ris-ps-p"><span class="ris-ps-ic"><?= ris_ic('x') ?></span><div><span class="ris-ps-tag">Problem</span><h4><?= h($pt) ?></h4><p><?= h($pd) ?></p></div></div>
        <div class="ris-ps-mid"></div>
        <div class="ris-ps-s"><span class="ris-ps-ic"><?= ris_ic('check') ?></span><div><span class="ris-ps-tag">Solution</span><h4><?= h($st) ?></h4><p><?= h($sd) ?></p></div></div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== OLD vs NEW WORKFLOW ===== -->
<section class="section" id="workflow">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Old Workflow vs New Workflow</span>
      <h2 class="h-sec">From 8 manual steps to 6 automatic ones</h2>
      <p class="lead">Same scan, same doctor. The difference is everything that happens around it.</p>
    </div>
    <div class="ris-flow">
      <div class="ris-flow-col old reveal">
        <div class="ris-flow-head"><h3>The old way</h3><span class="ris-badge">Manual · paper · Word</span></div>
        <ol class="ris-steps">
<?php foreach ($old_flow as [$t, $d]): ?>
          <li><b><?= h($t) ?></b><span><?= h($d) ?></span></li>
<?php endforeach; ?>
        </ol>
        <div class="ris-flow-foot"><?= ris_ic('x') ?>Typing errors, long waits, missed follow-ups</div>
      </div>
      <div class="ris-flow-col new reveal d1">
        <div class="ris-flow-head"><h3>With HealthO Pro RIS</h3><span class="ris-badge">Automatic</span></div>
        <ol class="ris-steps">
<?php foreach ($new_flow as [$t, $d]): ?>
          <li><b><?= h($t) ?></b><span><?= h($d) ?></span></li>
<?php endforeach; ?>
        </ol>
        <div class="ris-flow-foot"><?= ris_ic('check') ?>No retyping — the doctor only reviews and signs</div>
      </div>
    </div>

    <div class="ris-cmp-wrap reveal">
      <table class="ris-cmp">
        <thead><tr><th>Task</th><th>Old workflow</th><th>HealthO Pro RIS</th></tr></thead>
        <tbody>
          <tr><td>Patient details on the machine</td><td>Typed again by the sonographer</td><td>Pulled from billing via Modality Worklist</td></tr>
          <tr><td>Measurements in the report</td><td>Copied by hand from the screen</td><td>Read automatically from the machine</td></tr>
          <tr><td>Charts &amp; images</td><td>Drawn, pasted or printed separately</td><td>Generated on the report automatically</td></tr>
          <tr><td>Impression &amp; wording</td><td>Typed from memory every time</td><td>Phrases, flags, suggested impression, dictation</td></tr>
          <tr><td>PNDT Form F</td><td>Filled by hand</td><td>Auto-drafted for review</td></tr>
          <tr><td>Report delivery</td><td>Patient waits or comes back</td><td>WhatsApp, SMS, email &amp; link — automatically</td></tr>
          <tr><td>ANC follow-up</td><td>Depends on staff remembering</td><td>Auto-enrolment with next-visit reminders</td></tr>
          <tr><td>Daily reporting</td><td>Counted from registers</td><td>Live dashboards for volume, TAT &amp; revenue</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- ===== MID CTA ===== -->
<section class="section section--soft" style="padding-top:0;">
  <div class="container">
    <div class="cta-band reveal">
      <h2 class="h-sec">Stop retyping ultrasound reports</h2>
      <p>Book a free demo and watch a real scan turn into a finished report — measurements, charts and images included.</p>
      <div class="cta-actions">
        <a href="#demo" class="btn btn-primary btn-lg">Book a Free Demo</a>
        <a href="tel:<?= str_replace(' ', '', PHONE_SALES) ?>" class="btn btn-ghost-light btn-lg">Call <?= PHONE_SALES ?></a>
      </div>
    </div>
  </div>
</section>

<!-- ===== ALL FEATURES ===== -->
<section class="section section--tint" id="features">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Everything Included</span>
      <h2 class="h-sec">Every feature, one ₹5 price</h2>
      <p class="lead">From the machine to the patient's phone — all of it is in the plan. No add-ons, no modules to buy later.</p>
    </div>
    <div class="ris-feat">
<?php foreach ($feature_groups as $i => [$icon, $bg, $name, $items]): ?>
      <div class="card reveal<?= $i % 2 ? ' d1' : '' ?>">
        <div class="ris-feat-h"><span class="feat-ic <?= $bg ?>"><?= ris_ic($icon) ?></span><h3><?= h($name) ?></h3></div>
        <ul>
<?php foreach ($items as $it): ?>
          <li><?= ris_ic('check') ?><span><?= h($it) ?></span></li>
<?php endforeach; ?>
        </ul>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== PRODUCT SHOT ===== -->
<section class="section">
  <div class="container">
    <div class="split rev">
      <div class="reveal">
        <span class="eyebrow">One Platform</span>
        <h2 class="h-sec">Front desk, machine and doctor — connected</h2>
        <p class="lead" style="margin:16px 0 24px;">Billing, the ultrasound machine, the typist and the radiologist all work on the same study, so every scan is traceable from bill to signed report.</p>
        <ul class="checks">
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Work Queue shows every scan's stage in real time</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Several scans on one visit? Each lands on the right test</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Previous scans of the patient a click away</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Works alongside HealthO Pro HIMS, LIMS and pharmacy</span></li>
        </ul>
      </div>
      <div class="split-media reveal d1"><div class="media-frame"><img src="assets/images/ris.webp" alt="HealthO Pro RIS dashboard showing received ultrasound studies" width="1024" height="1024" loading="lazy" decoding="async"></div></div>
    </div>
  </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Go Live Fast</span>
      <h2 class="h-sec">Up and running in 3 steps</h2>
      <p class="lead">Our team does the setup with you. No server to buy, no IT team needed.</p>
    </div>
    <div class="ris-how">
      <div class="card reveal"><div class="n">1</div><h4>Install the RIS app</h4><p>We install the HealthO Pro RIS app on one Windows PC and connect it to your ultrasound machine.</p></div>
      <div class="card reveal d1"><div class="n">2</div><h4>Map your reports</h4><p>Your existing report formats and letterhead are set up, and each exam type is mapped to its template.</p></div>
      <div class="card reveal d2"><div class="n">3</div><h4>Start scanning</h4><p>Bill, scan, review, sign. Reports build and deliver themselves from day one.</p></div>
    </div>
  </div>
</section>

<!-- ===== WHO IT'S FOR ===== -->
<section class="section">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Built For</span>
      <h2 class="h-sec">Made for busy imaging teams</h2>
    </div>
    <div class="ris-for">
      <div class="card reveal"><span class="feat-ic bg-cyan"><?= ris_ic('scan') ?></span><h4>Ultrasound centres</h4><p>Standalone sonography and Doppler centres with high daily volume.</p></div>
      <div class="card reveal d1"><span class="feat-ic bg-violet"><?= ris_ic('heart') ?></span><h4>Obstetric &amp; fetal medicine</h4><p>Growth scans, Doppler, Form F and ANC follow-up in one flow.</p></div>
      <div class="card reveal d2"><span class="feat-ic bg-navy"><?= ris_ic('users') ?></span><h4>Hospital radiology</h4><p>Radiology departments billing through HealthO Pro HIMS.</p></div>
      <div class="card reveal d3"><span class="feat-ic bg-green"><?= ris_ic('chart') ?></span><h4>Diagnostic chains</h4><p>Multi-centre diagnostics needing one view of volume and TAT.</p></div>
    </div>
  </div>
</section>

<!-- ===== PAY AS YOU GO ===== -->
<section class="section section--tint" id="payg">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Simple Pricing</span>
      <h2 class="h-sec">Pay only for the patients you scan</h2>
      <p class="lead">No upfront cost and no monthly licence. You're billed per patient processed — that's it.</p>
    </div>
    <div class="payg reveal">
      <div>
        <h3 class="h-card" style="margin-bottom:14px;">Pay-As-You-Go</h3>
        <p style="color:var(--text-soft);margin-bottom:22px;">The full RIS platform — machine connectivity, auto reports, assist tools, Form F, delivery and analytics.</p>
        <ul class="checks">
          <li><span class="ck"><?= ris_ic('check') ?></span><span>No fixed monthly licence fees</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Every feature on this page included</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Scales automatically with your volume</span></li>
          <li><span class="ck"><?= ris_ic('check') ?></span><span>Free onboarding, training &amp; 24×7 support</span></li>
        </ul>
        <a href="#demo" class="btn btn-navy" style="margin-top:26px;">Book a Free Demo</a>
      </div>
      <div class="payg-price">
        <div style="font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;color:rgba(255,255,255,.7);">Starting at</div>
        <div class="big">₹5</div>
        <div class="sub">per patient</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<?php faq_section($faqs, 'RIS questions, answered'); ?>

<!-- ===== FINAL CTA + FORM ===== -->
<section class="section section--soft" id="demo">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">Get Started</span>
        <h2 class="h-sec">Your next report could write itself</h2>
        <p class="lead" style="margin:16px 0 28px;">Book a free demo. We'll show HealthO Pro RIS working with your machine and your report format — and set you up on ₹5 per patient.</p>
        <?php contact_strip(); ?>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Request a free RIS demo</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">We'll respond within one business day.</p>
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
  <a href="<?= WHATSAPP_URL ?>?text=<?= rawurlencode('Hi, I want a demo of HealthO Pro RIS for my ultrasound centre.') ?>" target="_blank" rel="noopener" class="btn btn-wa" aria-label="Chat on WhatsApp"><?= ris_ic('wa') ?></a>
</div>
<?php require __DIR__ . '/partials/foot.php';
