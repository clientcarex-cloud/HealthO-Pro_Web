<?php
$page        = 'hims';
$title       = 'Where Is Your Hospital Losing Money? — HIMS | HealthO Pro';
$description = 'Missed ward charges, slow discharges, stuck TPA claims. HealthO Pro HIMS connects reception, wards, pharmacy, lab and billing so nothing slips through. Book a free demo.';
require_once __DIR__ . '/partials/seo.php';
$faqs = [
    'What is HealthO Pro HIMS, in simple words?' =>
        'It is one software for your whole hospital. Reception, doctors, wards, pharmacy, lab, radiology and billing all work on the same patient record — so information and charges move by themselves instead of on paper.',
    'How does it stop charges from being missed?' =>
        'Every medicine issued, test ordered, procedure done and bed-day used is added to the patient\'s running bill the moment it happens. At discharge the bill is already complete — nobody has to collect slips from each department.',
    'Does it handle insurance and TPA claims?' =>
        'Yes. Cash, credit, insurance, package and TPA billing are built in, with GST-ready invoices. Claim documents come from the patient record, so fewer claims get stuck for missing papers.',
    'Is it suitable for a small hospital?' =>
        'Yes. It works for small nursing homes under 50 beds, 500+ bed hospitals and multi-site chains. You choose a plan that fits your size.',
    'Will my staff find it hard to learn?' =>
        'No. We set it up for you, train your team for free, and stay a call or WhatsApp away 24×7. Most staff are comfortable within a few days.',
    'Is patient data safe?' =>
        'Yes. Every user only sees what their role allows, every change is recorded, and data is encrypted on a secure cloud you can reach from anywhere.',
];
$schema = [seo_software('hims', 'HIMS — Hospital Information Management System', $description, 'Hospital Information Management System', null)];

require __DIR__ . '/partials/landing.php';
$head_extra = lp_styles();

require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/pricing-section.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';

$wa_link = WHATSAPP_URL . '?text=' . rawurlencode('Hi, I want a demo of HealthO Pro HIMS for my hospital.');

lp_hero([
    'eyebrow' => 'For Hospitals & Nursing Homes',
    'title'   => 'Somewhere in your hospital,<br><span class="text-grad">money is quietly leaking.</span>',
    'sub'     => 'A medicine given in the ward that never reaches the bill. A free bed nobody knows about. A TPA claim stuck for one missing paper. <b>HealthO Pro HIMS</b> connects every desk — so every charge is caught and every discharge is fast.',
    'pills'   => [['Every', 'charge captured'], ['Fast', 'discharge billing'], ['Free', 'setup & training'], ['24×7', 'help']],
    'aria'    => 'A patient is registered, every ward charge is added automatically, and the bill is ready at discharge',
    'nodes'   => [
        ['user',    'c-cyan',   'Reception',  'Patient registered once', '<div class="lp-vals"><span class="lp-val">UHID 10482</span><span class="lp-val">ABHA ✓</span><span class="lp-val">Bed 12</span></div>'],
        ['bed',     'c-violet', 'Ward',       'Every charge auto-added', '<div class="lp-vals"><span class="lp-val">+ Inj. Ceftriaxone</span><span class="lp-val">+ CBC</span><span class="lp-val">+ X-Ray</span><span class="lp-val">+ Bed-day</span></div>'],
        ['receipt', 'c-green',  'Discharge',  'Bill ready in minutes',   '<div><span class="lp-bubble">' . lp_ic('check') . ' Final bill ready · TPA claim sent</span></div>'],
    ],
]);

lp_pains(
    'Does any of this happen in your hospital?',
    'Tick what feels familiar. Be honest — nobody else will see it.',
    [
        ['Discharge takes hours',                      'The patient is ready to go, but the bill isn\'t.'],
        ['Ward charges don\'t reach the final bill',   'A medicine, a test, an extra bed-day — just forgotten.'],
        ['Nobody knows which beds are free right now', 'Reception calls each ward to ask.'],
        ['TPA / insurance claims get stuck',           'Rejected or delayed for a missing paper.'],
        ['Patient files go missing',                   'Between OPD, ward, lab and records room.'],
        ['Pharmacy stock expires on the shelf',        'Or runs out exactly when it\'s needed.'],
    ],
    [
        0 => 'Tick the problems you see — we\'ll tell you what they mean.',
        1 => 'Even <b>one</b> of these quietly costs money every single day.',
        3 => '<b>Your hospital is leaking money and time.</b> The good news: every one of these is fixable.',
        5 => '<b>This is costing you more than you think.</b> Scroll down to see the number.',
    ]
);

lp_leak(
    'How much revenue slips through the cracks?',
    'Most hospitals never find out — because a missed charge leaves no trace. Set your numbers to see yours.',
    [
        ['lp-rev',  'Monthly hospital billing',       5,  500, 5, 40, '₹', ' lakh'],
        ['lp-leakp','Charges missed (be honest)',     1,  10,  1, 3,  '',  '%'],
    ],
    'You could be losing every year',
    'Missed charges = medicines, consumables, tests or bed-days that were given but never billed. Your own estimate — move it to match your hospital.'
);
?>
<script>
window.lpCalc = function (v, fmt) {
  var month = v['lp-rev'] * 100000 * v['lp-leakp'] / 100, year = month * 12;
  var show = year >= 10000000 ? '₹' + (year / 10000000).toFixed(2) + ' Cr' : '₹' + fmt(year / 100000) + ' lakh';
  return { big: show, sub: 'That\'s about <b>₹' + fmt(month) + '</b> every month — care you gave, but never got paid for.' };
};
</script>
<?php
lp_before_after(
    'Same patient. Same stay. Nothing missed.',
    'Flip the switch to see one IPD stay the old way — and with HealthO Pro HIMS.',
    [['pen', 'Register on paper'], ['folder', 'Make a paper file'], ['pen', 'Write ward notes by hand'], ['receipt', 'Note charges in a register'], ['search', 'Collect slips from pharmacy & lab'], ['keyboard', 'Add it all up by hand'], ['redo', 'Chase TPA papers'], ['clock', 'Patient waits hours to leave']],
    [['x', '8 steps'], ['x', 'Charges get missed'], ['x', 'Claims get stuck'], ['clock', 'Slow discharge']],
    [['user', 'Register once (UHID)'], ['doc', 'Treat — notes in EMR'], ['receipt', 'Charges add themselves'], ['send', 'Bill & claim ready']],
    [['check', 'Just 4 steps'], ['check', 'Every charge caught'], ['check', 'Claims go complete'], ['check', 'Discharge in minutes']]
);

lp_journey(
    'One patient\'s stay, in 4 steps',
    [
        ['user',    'c-navy',   'Register once',        'Reception creates a UHID (ABHA supported). Every department sees the same patient.', 'No re-typing anywhere'],
        ['doc',     'c-cyan',   'Treat',                'Doctors write notes and orders in the EMR. Lab and radiology get the order instantly.', 'No lost files'],
        ['receipt', 'c-violet', 'Charges add themselves','Each medicine, test, procedure and bed-day goes onto the running bill as it happens.', 'Nothing forgotten'],
        ['send',    'c-green',  'Discharge fast',       'The bill is ready. TPA claim papers come from the record. The patient leaves happy.', 'Minutes, not hours'],
    ],
    'At a supermarket, every item is scanned at the counter — nothing gets "forgotten" on the bill. HealthO Pro HIMS <strong>scans every service your hospital gives</strong>, as it happens, so your final bill is always complete.'
);

lp_benefits(
    'Your whole hospital, on one screen',
    'All the modules you need — working together from day one.',
    [
        ['bed',     'bg-navy',   'OPD, IPD & live bed board', 'See every free, booked and occupied bed in real time. No calling around.'],
        ['doc',     'bg-cyan',   'Patient records (EMR)',     'History, prescriptions, vitals and reports in one record, on any screen.'],
        ['receipt', 'bg-green',  'Billing, insurance & TPA',  'Cash, credit, package and TPA billing with GST-ready invoices.'],
        ['pill',    'bg-amber',  'Pharmacy & inventory',      'Batch and expiry tracking, indents and reorder alerts — before stock runs out.'],
        ['flask',   'bg-violet', 'Lab & radiology built in',  'Orders, results and reports flow into the patient file automatically.'],
        ['chart',   'bg-navy',   'Owner\'s dashboard',        'Occupancy, revenue and department performance — live on your phone.'],
    ]
);

lp_stats('What hospitals see after switching', [
    [38, '', '%', '38%', 'Faster patient throughput'],
    [60, '', '%', '60%', 'Fewer billing errors'],
    [100, '', '%', '100%', 'Paperless records'],
    [24, '', '×7', '24×7', 'Support & uptime'],
]);

pricing_section('hims');

faq_section($faqs, 'Questions? Simple answers.', 'section section--soft');

lp_demo([
    'title'      => 'Find your hospital\'s leaks — in a free 30-minute demo',
    'lead'       => 'Tell us about your hospital. We\'ll walk you through one patient\'s full stay in HealthO Pro HIMS, set up for your size and specialties.',
    'form_title' => 'Book your free HIMS demo',
    'wa'         => $wa_link,
    'form'       => [
        'hidden'   => ['interest' => 'HIMS', 'source' => 'HIMS landing — footer'],
        'aside'    => form_select('orgType', 'Hospital Beds', ['Under 50 beds', '50–150 beds', '150–500 beds', '500+ beds']),
        'interest' => '',
        'message'  => ['label' => 'Biggest headache right now (optional)', 'placeholder' => 'e.g. slow discharge, TPA claims, pharmacy stock…'],
        'submit'   => 'Get My Free Demo',
    ],
]);

lp_script();
require __DIR__ . '/partials/foot.php';
