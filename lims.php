<?php
$page        = 'lims';
$title       = 'Your Analyzer Already Knows the Result — LIMS | HealthO Pro';
$description = 'Stop typing results. HealthO Pro LIMS pulls values from your analyzers, checks them and sends branded reports on WhatsApp. Barcodes, QC, B2B and TAT dashboards. Free demo.';
require_once __DIR__ . '/partials/seo.php';
$faqs = [
    'Do we still have to type results?' =>
        'No. HealthO Pro LIMS connects to your biochemistry, hematology and immunoassay analyzers. Results come into the report by themselves — your team only checks and approves.',
    'Will it work with my analyzers?' =>
        'Most modern analyzers can send results to a computer, and we support two-way (bi-directional) connection. Tell us your machine models and we\'ll confirm in the demo.',
    'How do patients get their reports?' =>
        'Branded reports go out on WhatsApp, email and a patient portal the moment they are signed. Patients stop calling to ask "is my report ready?".',
    'Does it help with quality control?' =>
        'Yes. Normal ranges, auto-validation rules and Levey-Jennings QC charts are built in, so unusual values are flagged before a report goes out.',
    'Can it manage collection centres and referral doctors?' =>
        'Yes. The B2B module tracks collection centres, referral doctors, outsourced tests and their accounts — no separate notebook or Excel.',
    'How long does it take to start?' =>
        'Usually a few days. We set up your tests, rates and report format, connect your analyzers and train your team for free.',
];
$schema = [seo_software('lims', 'LIMS — Laboratory Information Management System', $description, 'Laboratory Information Management System', null)];

require __DIR__ . '/partials/landing.php';
$head_extra = lp_styles();

require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/pricing-section.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';

$wa_link = WHATSAPP_URL . '?text=' . rawurlencode('Hi, I want a demo of HealthO Pro LIMS for my lab.');

lp_hero([
    'eyebrow' => 'For Diagnostic Labs & Collection Centres',
    'title'   => 'Your analyzer already knows the result.<br><span class="text-grad">So why is someone still typing it?</span>',
    'sub'     => 'Every typed value is a chance for a wrong digit — and a patient who waits longer. <b>HealthO Pro LIMS</b> reads results straight from your analyzers, checks them, and sends a branded report to the patient\'s WhatsApp.',
    'pills'   => [['Zero', 'result typing'], ['Barcode', 'every sample'], ['Free', 'setup & training'], ['24×7', 'help']],
    'aria'    => 'A barcoded sample runs on the analyzer, results flow into the report, and the patient gets it on WhatsApp',
    'nodes'   => [
        ['barcode', 'c-cyan',   'Sample',          'Barcoded at collection', '<div class="lp-vals"><span class="lp-val">SID 240817</span><span class="lp-val">CBC</span><span class="lp-val">LFT</span><span class="lp-val">TSH</span></div>'],
        ['cpu',     'c-violet', 'Analyzer → LIMS', 'Results flow in & get checked', '<div class="lp-vals"><span class="lp-val">Hb 13.2</span><span class="lp-val">SGPT 34</span><span class="lp-val warn">TSH 6.8 ↑</span><span class="lp-val">QC ✓</span></div>'],
        ['wa',      'c-green',  'Patient\'s phone','Report delivered', '<div><span class="lp-bubble">' . lp_ic('check') . ' Your lab report is ready</span></div>'],
    ],
]);

lp_pains(
    'Does any of this happen in your lab?',
    'Tick what feels familiar. It takes 30 seconds.',
    [
        ['Patients call again and again',            '"Is my report ready yet?"'],
        ['Results are typed from the analyzer',      'Copied from a screen or printout by hand.'],
        ['A wrong value once went out',              'And the report had to be corrected and re-printed.'],
        ['A sample got mixed up or misplaced',       'Hand-written labels, busy morning rush.'],
        ['Doctor & centre accounts live in a notebook','Or in an Excel sheet only one person understands.'],
        ['You don\'t know today\'s numbers until night','Tests done, pending reports, collection.'],
    ],
    [
        0 => 'Tick the problems you see — we\'ll tell you what they mean.',
        1 => 'Even <b>one</b> of these slows your lab every single day.',
        3 => '<b>Your team is doing work a machine should do.</b> Every one of these is fixable.',
        5 => '<b>Your lab is losing hours — and trust.</b> Scroll down to see how many.',
    ]
);

lp_leak(
    'How many hours does typing results really cost?',
    'Nobody notices it — it\'s spread across every shift. Set your numbers to see the total.',
    [
        ['lp-tests', 'Test results per day',            20, 2000, 10, 200, '', ''],
        ['lp-secs',  'Seconds to type & check each one', 10, 60,   5,  30,  '', ' sec'],
    ],
    'Your team spends every month',
    'Based on 26 working days. Move the sliders to match your lab — it\'s your own estimate.'
);
?>
<script>
window.lpCalc = function (v, fmt) {
  var hrs = v['lp-tests'] * v['lp-secs'] * 26 / 3600, days = hrs / 8;
  return { big: fmt(hrs) + ' hours', sub: 'That\'s about <b>' + fmt(days) + ' full working days</b> a month — just typing numbers your analyzer already has. Every one is a chance for a wrong digit.' };
};
</script>
<?php
lp_before_after(
    'Same sample. Half the steps. Zero typing.',
    'Flip the switch to see one lab report made the old way — and with HealthO Pro LIMS.',
    [['pen', 'Write in register'], ['pen', 'Label tubes by hand'], ['cpu', 'Run on the analyzer'], ['eye', 'Read the printout'], ['keyboard', 'Type every value'], ['redo', 'Fix typing mistakes'], ['doc', 'Print & sign'], ['phone', 'Patient calls to ask']],
    [['x', '8 steps'], ['keyboard', 'Lots of typing'], ['x', 'Wrong digits slip in'], ['clock', 'Slow reports']],
    [['barcode', 'Register & barcode'], ['cpu', 'Run on the analyzer'], ['shield', 'Results auto-checked'], ['wa', 'Report on WhatsApp']],
    [['check', 'Just 4 steps'], ['check', 'No values typed'], ['check', 'Abnormals flagged'], ['check', 'Faster reports']]
);

lp_journey(
    'One sample\'s journey, in 4 steps',
    [
        ['barcode', 'c-navy',   'Register & barcode', 'Bill the patient and print a barcode. The tube is tracked from collection to report.', 'No mix-ups'],
        ['cpu',     'c-cyan',   'Run the analyzer',   'The analyzer reads the barcode and knows which tests to run.',                         'Nothing new to learn'],
        ['shield',  'c-violet', 'Results check themselves', 'Values flow into the report. Ranges, rules and QC flag anything unusual.',          'TSH 6.8 → flagged'],
        ['send',    'c-green',  'Sign & send',        'The pathologist approves. The branded report reaches WhatsApp, email and the portal.', 'No "is it ready?" calls'],
    ],
    'Think of UPI. You don\'t count notes or write a receipt — the money just moves, and both sides get proof instantly. HealthO Pro LIMS <strong>moves results from analyzer to patient the same way</strong> — no hands in between.'
);

lp_benefits(
    'Everything a busy lab needs',
    'From a single lab to a multi-centre reference lab.',
    [
        ['barcode', 'bg-cyan',   'Barcode sample tracking',   'Every tube tracked from collection to report. Nothing misplaced.'],
        ['cpu',     'bg-navy',   'Analyzer connection',       'Two-way connection with biochemistry, hematology and immunoassay machines.'],
        ['shield',  'bg-green',  'Auto-check & QC',           'Normal ranges, validation rules and Levey-Jennings charts built in.'],
        ['send',    'bg-amber',  'Reports on WhatsApp',       'Branded reports on WhatsApp, email and a patient portal — the moment they\'re signed.'],
        ['users',   'bg-violet', 'B2B & referral doctors',    'Collection centres, referral doctors and outsourced tests — all accounted for.'],
        ['chart',   'bg-navy',   'Live TAT & revenue',        'Pending reports, turnaround time and collection — live, from your phone.'],
    ]
);

lp_stats('What labs see after switching', [
    [50, '', '%', '50%', 'Faster report TAT'],
    [99, '', '%', '99%', 'Result accuracy'],
    [1000, '', '+', '1,000+', 'Tests/day capacity'],
    [24, '', '×7', '24×7', 'Support & uptime'],
]);

lp_videos(
    'Hear it from lab owners',
    'Real labs talking about life after switching to HealthO Pro LIMS.',
    ['DevmbT4qPgL', 'DetED2yKICo', 'DeqfDm7KiM7']
);

pricing_section('lims');

require_once __DIR__ . '/partials/city-data.php';
city_links_section('lims');

faq_section($faqs, 'Questions? Simple answers.', 'section section--soft');

lp_demo([
    'title'      => 'See your own analyzer send a report — free',
    'lead'       => 'Tell us about your lab. We\'ll show HealthO Pro LIMS working with your tests, your machines and your report format — free, no obligation.',
    'form_title' => 'Book your free LIMS demo',
    'wa'         => $wa_link,
    'form'       => [
        'hidden'   => ['interest' => 'LIMS', 'source' => 'LIMS landing — footer'],
        'aside'    => form_select('orgType', 'Daily Test Volume', ['Under 100 tests/day', '100–500 tests/day', '500–2000 tests/day', '2000+ tests/day']),
        'interest' => '',
        'message'  => ['label' => 'Analyzers you use (optional)', 'placeholder' => 'e.g. Sysmex XN-350, Erba EM 200, Mindray BS-240…'],
        'submit'   => 'Get My Free Demo',
    ],
]);

lp_script();
require __DIR__ . '/partials/foot.php';
