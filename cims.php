<?php
$page        = 'cims';
$title       = 'Why Patients Don\'t Come Back — Clinic Software (CIMS) | HealthO Pro';
$description = 'Long waits, lost prescriptions, forgotten follow-ups. HealthO Pro CIMS gives your clinic online booking, queue tokens, e-prescriptions, billing and WhatsApp reminders. Free demo.';
require_once __DIR__ . '/partials/seo.php';
$faqs = [
    'What does HealthO Pro CIMS do, in simple words?' =>
        'It runs your clinic from one app: patients book online or walk in, get a token, the doctor sees their full history and writes a digital prescription, the bill is made in a click, and the patient gets a WhatsApp reminder for their next visit.',
    'How does it bring patients back?' =>
        'When the doctor sets a follow-up date, the patient automatically gets a WhatsApp/SMS reminder before it. Fewer patients forget — so more of them return to you instead of going elsewhere.',
    'Can patients book appointments online?' =>
        'Yes. Online and walk-in booking with doctor availability slots, a calendar view and token/queue management — so the waiting room stays calm.',
    'Is it hard for the doctor to use?' =>
        'No. Prescriptions use quick-pick medicines and dosage templates, so a full Rx takes a few taps. Old visits, vitals and reports are on the same screen.',
    'Will it work for a polyclinic or clinic chain?' =>
        'Yes. It works for single-doctor clinics, multi-specialty polyclinics and multi-location chains, on the cloud.',
    'How quickly can we start?' =>
        'Most clinics go live in a few days. Setup and training are free, and help is a call or WhatsApp away 24×7.',
];
$schema = [seo_software('cims', 'CIMS — Clinic Information Management System', $description, 'Clinic Management Software', null)];

require __DIR__ . '/partials/landing.php';
$head_extra = lp_styles();

require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/pricing-section.php';
require __DIR__ . '/partials/contact-strip.php';
require __DIR__ . '/partials/head.php';

$wa_link = WHATSAPP_URL . '?text=' . rawurlencode('Hi, I want a demo of HealthO Pro CIMS for my clinic.');

lp_hero([
    'eyebrow' => 'For Clinics & Polyclinics',
    'title'   => 'Why do some patients visit once…<br><span class="text-grad">and never come back?</span>',
    'sub'     => 'It\'s rarely the doctor. It\'s the long wait, the prescription nobody can read, the follow-up nobody reminded them about. <b>HealthO Pro CIMS</b> fixes all three — from booking to a WhatsApp nudge for the next visit.',
    'pills'   => [['Online', 'booking'], ['Auto', 'WhatsApp reminders'], ['Free', 'setup & training'], ['24×7', 'help']],
    'aria'    => 'A patient books and gets a token, the doctor writes a digital prescription, and the patient gets a WhatsApp reminder for the follow-up',
    'nodes'   => [
        ['ticket', 'c-cyan',   'Booking',         'Token on the patient\'s phone', '<div class="lp-vals"><span class="lp-val">Token #14</span><span class="lp-val">Dr. Mehta</span><span class="lp-val">~10 min</span></div>'],
        ['doc',    'c-violet', 'Consultation',    'E-prescription in a few taps',  '<div class="lp-paper"><span class="lp-line"></span><span class="lp-line"></span><span class="lp-line"></span></div>'],
        ['wa',     'c-green',  'A week later',    'Patient reminded to return',    '<div><span class="lp-bubble">' . lp_ic('bell') . ' Your follow-up is on Monday, 10 AM</span></div>'],
    ],
]);

lp_pains(
    'Does any of this happen at your clinic?',
    'Tick what feels familiar. It takes 30 seconds.',
    [
        ['The waiting room is always crowded',        'And patients keep asking "how much longer?"'],
        ['The phone never stops ringing',             'Just to book or change appointments.'],
        ['Old prescriptions are hard to find',        'Paper files, or "I forgot to bring it, doctor".'],
        ['Chemists call about handwritten Rx',        'Patients can\'t read the dose either.'],
        ['Follow-up patients simply don\'t return',   'Nobody reminded them.'],
        ['Day-end cash doesn\'t match the register',  'And no one knows where the gap is.'],
    ],
    [
        0 => 'Tick the problems you see — we\'ll tell you what they mean.',
        1 => 'Even <b>one</b> of these makes patients think twice about coming back.',
        3 => '<b>Patients are quietly slipping away.</b> Every one of these is fixable.',
        5 => '<b>This is costing your clinic real patients.</b> Scroll down to see how many.',
    ]
);

lp_leak(
    'How many patients forget to come back?',
    'Every missed follow-up is a patient who needed you — and a consultation you never got. Set your numbers.',
    [
        ['lp-pts', 'Patients you see per day', 5,   150,  5,   30,  '',  ''],
        ['lp-fee', 'Your consultation fee',    100, 2000, 50,  500, '₹', ''],
        ['lp-miss','Follow-ups that never return', 5, 60, 5,   25,  '',  '%'],
    ],
    'Missed follow-ups every year',
    'Assumes about 1 in 3 patients is told to come back, over 26 working days a month. Move the sliders to match your clinic.'
);
?>
<script>
window.lpCalc = function (v, fmt) {
  var missedMonth = v['lp-pts'] * 26 / 3 * v['lp-miss'] / 100, year = missedMonth * 12, money = year * v['lp-fee'];
  var show = money >= 10000000 ? '₹' + (money / 10000000).toFixed(2) + ' Cr' : (money >= 100000 ? '₹' + (money / 100000).toFixed(1) + ' lakh' : '₹' + fmt(money));
  return { big: fmt(year) + ' patients', sub: 'That\'s about <b>' + show + '</b> a year in consultations — from patients who already trust you. A simple reminder brings many of them back.' };
};
</script>
<?php
lp_before_after(
    'Same patient. Calmer clinic. They come back.',
    'Flip the switch to see one patient visit the old way — and with HealthO Pro CIMS.',
    [['phone', 'Patient calls to book'], ['pen', 'Name in a register'], ['clock', 'Waits with no token'], ['search', 'Doctor looks for old file'], ['pen', 'Handwritten Rx'], ['rupee', 'Cash in a drawer'], ['x', 'No reminder sent'], ['user', 'Patient forgets to return']],
    [['x', '8 steps'], ['clock', 'Crowded waiting room'], ['x', 'Lost history'], ['x', 'Patients don\'t return']],
    [['calendar', 'Book online or walk in'], ['ticket', 'Token on phone'], ['doc', 'History + e-Rx on one screen'], ['bell', 'Bill & auto reminder']],
    [['check', 'Just 4 steps'], ['check', 'Calm waiting room'], ['check', 'Clear prescriptions'], ['check', 'Patients come back']]
);

lp_journey(
    'One patient\'s visit, in 4 steps',
    [
        ['calendar', 'c-navy',   'Book',           'Patients book online or walk in. The doctor\'s slots fill without phone calls.',      'Fewer calls'],
        ['ticket',   'c-cyan',   'Token & queue',  'A token number on their phone shows when it\'s their turn.',                           'No "how long?"'],
        ['doc',      'c-violet', 'Consult',        'Full history on one screen. A clear e-prescription in a few taps.',                   'Readable Rx'],
        ['bell',     'c-green',  'Bill & remind',  'Bill in a click. The patient gets a WhatsApp reminder before the follow-up date.',   'They come back'],
    ],
    'A good restaurant gives you a buzzer — you relax until your table is ready, and you come back because the experience was easy. HealthO Pro CIMS <strong>gives your patients that same easy experience</strong>, from booking to their next visit.'
);

lp_benefits(
    'Everything your clinic needs, nothing it doesn\'t',
    'Simple for the front desk. Fast for the doctor.',
    [
        ['calendar', 'bg-green',  'Online appointments',     'Online and walk-in booking with doctor slots and a calendar view.'],
        ['ticket',   'bg-cyan',   'Token & queue',           'Patients see their turn. The waiting room stays calm.'],
        ['doc',      'bg-navy',   'E-prescriptions',         'Quick-pick medicines and dosage templates. Printed or sent on WhatsApp.'],
        ['folder',   'bg-violet', 'Patient history',         'Every visit, vital and report in one place — no more lost files.'],
        ['rupee',    'bg-amber',  'Billing & payments',      'Quick bills, online payments and a day-end collection report that matches.'],
        ['bell',     'bg-green',  'Reminders on autopilot',  'WhatsApp/SMS reminders, follow-ups and feedback — sent by themselves.'],
    ]
);

lp_stats('What clinics see after switching', [
    [45, '', '%', '45%', 'Less waiting time'],
    [30, '', '%', '30%', 'More repeat visits'],
    [5, '<', 'min', '<5min', 'Avg. checkout time'],
    [24, '', '×7', '24×7', 'Support & uptime'],
]);

pricing_section('cims');

faq_section($faqs, 'Questions? Simple answers.', 'section section--soft');

lp_demo([
    'title'      => 'Bring more patients back — see how in a free demo',
    'lead'       => 'Tell us about your clinic. We\'ll show you one patient visit in HealthO Pro CIMS — from booking to the WhatsApp reminder — set up for your practice.',
    'form_title' => 'Book your free CIMS demo',
    'wa'         => $wa_link,
    'form'       => [
        'hidden'   => ['interest' => 'CIMS', 'source' => 'CIMS landing — footer'],
        'aside'    => form_select('orgType', 'Clinic Type', ['Single-doctor clinic', 'Multi-specialty polyclinic', 'Clinic chain', 'Dental / Eye / Specialty']),
        'interest' => '',
        'message'  => ['label' => 'Your speciality (optional)', 'placeholder' => 'e.g. general physician, paediatrics, dental…'],
        'submit'   => 'Get My Free Demo',
    ],
]);

lp_script();
require __DIR__ . '/partials/foot.php';
