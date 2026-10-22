<?php
/**
 * World premiere: HealthO Pro for Ultrasound Reporting — 24 Oct 2026, 11:00 AM IST.
 *
 * Before the reveal: teaser + live countdown + sign-up (→ Omni Contacts, see
 * api/launch-subscribe.php).
 * After the reveal: the full product is rendered ONLY for subscribers (signed
 * cookie). Everyone else sees the gate. The reveal HTML is never sent to a
 * visitor who is not allowed to see it, so it cannot be read from the source.
 */
require_once __DIR__ . '/partials/launch.php';
require_once __DIR__ . '/partials/seo.php';
date_default_timezone_set('Asia/Kolkata');

$page        = LAUNCH_SLUG;
$canonical   = SITE_URL . '/' . LAUNCH_SLUG;
$title       = 'World Premiere 24 Oct 2026 — HealthO Pro for Ultrasound Reporting | AI Reports in 60 Seconds';
$description = 'On 24 October 2026 at 11:00 AM IST, HealthO Pro unveils the world\'s first AI-driven engine that auto-creates Sonography, Ultrasound, X-Ray, CT and MRI reports within 1 minute. Reserve your front-row seat.';
$crumbs      = ['World Premiere — 24 Oct 2026' => $canonical];
$og_type     = 'website';

$launch_ts  = launch_ts();
$is_live    = launch_is_live();
$subscriber = launch_subscriber();
$unlocked   = $is_live && $subscriber !== null;

// Team preview: ?success=<LAUNCH_PREVIEW_KEY> shows the revealed page before
// launch. A plain ?success (or a wrong code) changes nothing.
$preview_key = launch_env()['LAUNCH_PREVIEW_KEY'] ?? '';
$is_preview  = $preview_key !== '' && isset($_GET['success']) && is_string($_GET['success'])
    && hash_equals($preview_key, $_GET['success']);
if ($is_preview) {
    $unlocked = true;
    $is_live  = true;
    $robots   = 'noindex, nofollow';
    header('X-Robots-Tag: noindex, nofollow');
    header('Cache-Control: no-store');
}
$count      = launch_subscriber_count();
$show_count = $count !== null && $count >= LAUNCH_COUNT_MIN;

$faqs = [
    'When exactly is the reveal?' =>
        'Saturday, 24 October 2026 at 11:00 AM India time (IST). The countdown on this page runs on our server clock, so it is the same for everyone, wherever you are.',
    'Who can see it first?' =>
        'Only people who reserve a seat on this page. At 11:00 AM the full product unlocks on this page for subscribers, and we send you the launch invite on WhatsApp / SMS / e-mail.',
    'Does it cost anything to reserve a seat?' =>
        'No. Reserving is free and takes 20 seconds. There is no obligation to buy anything.',
    'Will AI replace the radiologist?' =>
        'No. It removes the typing, not the doctor. The report is drafted for you in under a minute; the radiologist or sonologist reviews, edits if needed and signs. The medical decision always stays with the doctor.',
    'Which machines will it work with?' =>
        'Machines that can send images and measurements to a computer over DICOM — which most modern ultrasound, X-Ray, CT and MRI systems can. Full compatibility details are part of the reveal.',
    'Will you spam me?' =>
        'No. We use your number and e-mail only for the launch invite and HealthO Pro updates. You can opt out any time by replying STOP.',
];
$schema = [[
    '@type'       => 'Event',
    '@id'         => $canonical . '#event',
    'name'        => 'World Premiere — HealthO Pro for Ultrasound Reporting',
    'description' => $description,
    'startDate'   => date('c', $launch_ts),
    'endDate'     => date('c', $launch_ts + 3600),
    'eventStatus' => 'https://schema.org/EventScheduled',
    'eventAttendanceMode' => 'https://schema.org/OnlineEventAttendanceMode',
    'location'    => ['@type' => 'VirtualLocation', 'url' => $canonical],
    'image'       => [OG_IMAGE],
    'organizer'   => ['@type' => 'Organization', 'name' => 'HealthO Pro', 'url' => SITE_URL],
    'offers'      => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'INR', 'availability' => 'https://schema.org/InStock', 'url' => $canonical . '#reserve', 'validFrom' => date('c', time() - 86400)],
]];

/** Stroke icons (24×24, currentColor). */
function lx_ic(string $name): string
{
    static $p = [
        'lock'    => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
        'unlock'  => '<rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 7.8-1.2"/>',
        'bolt'    => '<path d="M13 2 3 14h9l-1 8 10-12h-9Z"/>',
        'cal'     => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'check'   => '<path d="M20 6 9 17l-5-5"/>',
        'scan'    => '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2M7 12h10"/>',
        'brain'   => '<path d="M9.5 2A2.5 2.5 0 0 0 7 4.5v.1A3 3 0 0 0 4.5 9a3 3 0 0 0 .4 5.4A3 3 0 0 0 8 19a2.5 2.5 0 0 0 4 .5V4a2 2 0 0 0-2.5-2Z"/><path d="M14.5 2A2.5 2.5 0 0 1 17 4.5v.1A3 3 0 0 1 19.5 9a3 3 0 0 1-.4 5.4A3 3 0 0 1 16 19a2.5 2.5 0 0 1-4 .5"/>',
        'doc'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>',
        'send'    => '<path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z"/>',
        'eye'     => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'target'  => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'globe'   => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"/>',
        'users'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'star'    => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1Z"/>',
        'play'    => '<circle cx="12" cy="12" r="10"/><path d="m10 8 6 4-6 4Z"/>',
        'ticket'  => '<path d="M3 9a3 3 0 0 0 0 6v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3a3 3 0 0 0 0-6V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2Z"/><path d="M13 4v2M13 10v4M13 18v2"/>',
        'wa'      => '<path d="M21 12a9 9 0 0 1-13.4 7.9L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/><path d="M8.5 9.5c.5 2.5 3.5 5.5 6 6l1.5-1.5-2-1-1 1c-1-.5-2.5-2-3-3l1-1-1-2Z"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'keyboard'=> '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M7 14h10"/>',
        'hourglass'=> '<path d="M5 22h14M5 2h14M17 22v-4.2a2 2 0 0 0-.6-1.4L12 12l-4.4 4.4a2 2 0 0 0-.6 1.4V22M7 2v4.2a2 2 0 0 0 .6 1.4L12 12l4.4-4.4a2 2 0 0 0 .6-1.4V2"/>',
        'copy'    => '<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>',
        'building'=> '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>',
        'mic'     => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 10a7 7 0 0 0 14 0M12 17v5"/>',
        'chart'   => '<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$name] ?? '') . '</svg>';
}

/** Redaction: shown before the reveal instead of the real words. */
function lx_redact(int $chars): string
{
    return '<span class="lx-redact" aria-label="Classified until launch">' . str_repeat('█', $chars) . '</span>';
}

$modalities = [
    ['Sonography',   'SONO',  'Obstetric, abdomen, pelvis, small parts'],
    ['Ultrasound',   'USG',   'Every probe, every protocol'],
    ['Doppler',      'DOP',   'Flow, indices, waveforms'],
    ['X-Ray',        'XR',    'Chest, spine, skeletal'],
    ['CT',           'CT',    'Plain &amp; contrast studies'],
    ['MRI',          'MRI',   'Brain, spine, joints, body'],
];

// The 60-second journey. [second, icon, title, detail]
$timeline = [
    [0,  'scan',  'Scan ends',              'The doctor finishes the scan exactly as today.'],
    [10, 'eye',   'Every value captured',   'Images, measurements and patient details reach the system on their own.'],
    [30, 'brain', 'AI drafts the report',   'Findings, tables, charts and an impression are written in your format.'],
    [50, 'check', 'Doctor reviews & signs', 'Edit anything, approve with one click.'],
    [60, 'send',  'Patient has it',         'Delivered to the patient\'s WhatsApp before they leave the room.'],
];

// Locked dossier. [icon, hint (always public), title (revealed), body (revealed)]
$dossier = [
    ['eye',    'It reads what the machine sees.',         'Machine-to-report capture',  'Every measurement on the scanner — biometry, weights, indices, Doppler values — is captured straight from the machine. Nobody types a single number.'],
    ['brain',  'It writes like your best radiologist.',   'AI report & impression',     'The AI drafts findings and a suggested impression in your own template and wording, then flags values outside the normal range.'],
    ['globe',  'It speaks every modality.',               'One engine, all modalities', 'Sonography, Ultrasound, Doppler, X-Ray, CT and MRI — one reporting engine for the whole imaging department.'],
    ['target', 'It never copies a wrong digit.',          'Zero transcription errors',  'Values go from the scanner to the report untouched, so the classic “typed 54 instead of 45” error disappears.'],
    ['shield', 'It does the paperwork you dread.',        'Compliance, pre-filled',     'PNDT Form F and registers are pre-filled from the study for staff to verify. Fetal sex is never captured.'],
    ['send',   'It finds the patient\'s phone.',          'Instant delivery',           'Signed reports reach the patient on WhatsApp, SMS or e-mail — with charts and images — the moment they are signed.'],
];

$audience = [
    ['users',    'Radiologists & Sonologists', 'Spend your time on diagnosis, not on typing.'],
    ['building', 'Diagnostic & Scan Centres',  'Double the reports per hour without new staff.'],
    ['star',     'Hospital Radiology',         'One reporting engine for every modality you run.'],
    ['globe',    'Imaging Chains & Teleradiology', 'The same report quality in every branch.'],
];

$perks = [
    ['bolt',   'First look at 11:00 sharp',       'The full reveal unlocks on this page — for subscribers only.'],
    ['play',   'Live launch demo invite',         'Watch a real scan become a signed report in under a minute.'],
    ['ticket', 'Founding-member benefits',        'Launch-day benefits are announced only to people on the list.'],
    ['star',   'Priority onboarding',             'Early sign-ups are set up first, with your machine and your format.'],
];

$wa_share = 'https://wa.me/?text=' . rawurlencode("🔥 World first in healthcare: AI that auto-creates Sonography, Ultrasound, X-Ray, CT & MRI reports in 60 seconds. Reveal on 24 Oct 2026, 11 AM. Reserve your seat: " . $canonical);
$wa_talk  = WHATSAPP_URL . '?text=' . rawurlencode('Hi, I want to know more about HealthO Pro for Ultrasound Reporting.');

$head_extra = <<<'HTML'
<style>
/* ======== Launch page — scoped to .lx ======== */
.lx { --lx-bg: #03060E; --lx-bg2: #070D1D; --lx-card: rgba(255,255,255,.045); --lx-line: rgba(255,255,255,.1); --lx-text: rgba(255,255,255,.78); --lx-mute: rgba(255,255,255,.52); --lx-cyan: #22D3EE; --lx-green: #34E89E; --lx-violet: #A78BFA; --lx-pink: #F472B6; --lx-amber: #FBBF24;
  background: var(--lx-bg); color: #fff; overflow: hidden; }
.lx h1, .lx h2, .lx h3, .lx h4 { color: #fff; }
.lx p { color: var(--lx-text); }
.lx .container { position: relative; z-index: 2; }
.lx-grad { background: linear-gradient(100deg, #22D3EE 0%, #34E89E 45%, #A78BFA 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; background-size: 200% 100%; animation: lxShine 6s linear infinite; }
@keyframes lxShine { to { background-position: -200% 0; } }
.lx-sec { padding: 110px 0; position: relative; }
.lx-sec--alt { background: var(--lx-bg2); }
.lx-head { text-align: center; max-width: 760px; margin: 0 auto 56px; }
.lx-head h2 { font-size: clamp(2rem, 4vw, 3.1rem); line-height: 1.12; letter-spacing: -.02em; margin: 14px 0 16px; font-weight: 800; }
.lx-head p { font-size: 1.12rem; }
.lx-kicker { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; font-size: .78rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: var(--lx-cyan); background: rgba(34,211,238,.1); border: 1px solid rgba(34,211,238,.28); }
.lx-kicker svg { width: 15px; height: 15px; flex: none; }
.lx-kicker i { width: 8px; height: 8px; border-radius: 50%; background: #F43F5E; box-shadow: 0 0 0 0 rgba(244,63,94,.7); animation: lxPing 1.6s infinite; }
@keyframes lxPing { 70% { box-shadow: 0 0 0 10px rgba(244,63,94,0); } 100% { box-shadow: 0 0 0 0 rgba(244,63,94,0); } }
.lx-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 18px 34px; border-radius: 999px; font-weight: 800; font-size: 1.05rem; border: 0; cursor: pointer; text-decoration: none; transition: transform .25s var(--ease), box-shadow .25s; white-space: nowrap; }
.lx-btn svg { width: 20px; height: 20px; }
.lx-btn-main { color: #031018; background: linear-gradient(100deg, #22D3EE, #34E89E); box-shadow: 0 14px 40px rgba(34,211,238,.35), inset 0 1px 0 rgba(255,255,255,.5); position: relative; overflow: hidden; }
.lx-btn-main::after { content: ""; position: absolute; top: 0; left: -60%; width: 40%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.6), transparent); transform: skewX(-20deg); animation: lxSweep 3.2s ease-in-out infinite; }
@keyframes lxSweep { 0%, 60% { left: -60%; } 100% { left: 130%; } }
.lx-btn-main:hover { transform: translateY(-3px) scale(1.02); color: #031018; box-shadow: 0 20px 50px rgba(34,211,238,.5); }
.lx-btn-ghost { color: #fff; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.2); }
.lx-btn-ghost:hover { color: #fff; background: rgba(255,255,255,.12); transform: translateY(-3px); }

/* ---- Backgrounds ---- */
.lx-stars { position: absolute; inset: 0; pointer-events: none; background-image: radial-gradient(1px 1px at 20% 30%, rgba(255,255,255,.5), transparent), radial-gradient(1px 1px at 70% 20%, rgba(255,255,255,.35), transparent), radial-gradient(1.5px 1.5px at 40% 80%, rgba(255,255,255,.4), transparent), radial-gradient(1px 1px at 85% 65%, rgba(255,255,255,.4), transparent), radial-gradient(1px 1px at 10% 70%, rgba(255,255,255,.3), transparent); background-size: 520px 520px; opacity: .6; }
.lx-grid { position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(34,211,238,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(34,211,238,.06) 1px, transparent 1px); background-size: 60px 60px; mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 70%); -webkit-mask-image: radial-gradient(ellipse at 50% 30%, #000 0%, transparent 70%); }
.lx-orb { position: absolute; border-radius: 50%; filter: blur(110px); pointer-events: none; opacity: .55; }
.lx-orb.o1 { width: 560px; height: 560px; background: #0891B2; top: -200px; left: -160px; animation: lxFloat 12s ease-in-out infinite alternate; }
.lx-orb.o2 { width: 520px; height: 520px; background: #7C3AED; top: 10%; right: -200px; opacity: .4; animation: lxFloat 14s ease-in-out infinite alternate-reverse; }
.lx-orb.o3 { width: 420px; height: 420px; background: #059669; bottom: -200px; left: 35%; opacity: .35; animation: lxFloat 10s ease-in-out infinite alternate; }
@keyframes lxFloat { to { transform: translate(40px, 30px) scale(1.08); } }

/* ---- Hero ---- */
.lx-hero { position: relative; min-height: 100vh; display: flex; align-items: center; padding: calc(var(--nav-h) + 60px) 0 90px; }
.lx-hero-grid { display: grid; grid-template-columns: 1.15fr .85fr; gap: 60px; align-items: center; }
.lx-hero h1 { font-size: clamp(2.6rem, 5.6vw, 4.6rem); line-height: 1.02; letter-spacing: -.035em; font-weight: 800; margin: 22px 0 22px; }
.lx-hero h1 .lx-60 { display: inline-block; position: relative; }
.lx-hero .lx-lead { font-size: 1.16rem; line-height: 1.65; max-width: 600px; margin-bottom: 30px; }
.lx-hero .lx-lead b { color: #fff; }
.lx-first { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 4px; font-weight: 800; font-size: .85rem; letter-spacing: .08em; text-transform: uppercase; color: var(--lx-amber); }
.lx-first svg { width: 18px; height: 18px; }
.lx-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-top: 32px; }
.lx-social-proof { margin-top: 22px; display: flex; align-items: center; gap: 12px; color: var(--lx-mute); font-size: .92rem; }
.lx-social-proof b { color: #fff; }
.lx-avatars { display: flex; }
.lx-avatars span { width: 30px; height: 30px; border-radius: 50%; border: 2px solid var(--lx-bg); margin-left: -8px; display: grid; place-items: center; font-size: .7rem; font-weight: 800; color: #031018; }
.lx-avatars span:first-child { margin-left: 0; }

/* ---- Countdown ---- */
.lx-count { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; max-width: 560px; }
.lx-unit { position: relative; text-align: center; padding: 18px 6px 14px; border-radius: 18px; background: linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.02)); border: 1px solid var(--lx-line); box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 20px 40px rgba(0,0,0,.35); overflow: hidden; }
.lx-unit::before { content: ""; position: absolute; left: 0; right: 0; top: 50%; height: 1px; background: rgba(0,0,0,.45); }
.lx-num { display: block; font-size: clamp(2.1rem, 4.4vw, 3.4rem); font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: -.02em; background: linear-gradient(180deg, #fff 30%, #9EE7F5); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
.lx-num.tick { animation: lxTick .45s var(--ease); }
@keyframes lxTick { 0% { transform: translateY(-30%); opacity: 0; filter: blur(4px); } 100% { transform: none; opacity: 1; filter: none; } }
.lx-lbl { display: block; margin-top: 8px; font-size: .7rem; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--lx-mute); }
.lx-when { margin-top: 14px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; color: var(--lx-mute); font-size: .92rem; }
.lx-when b { color: #fff; }
.lx-when a { color: var(--lx-cyan); font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
.lx-when a svg { width: 16px; height: 16px; }

/* ---- Classified device ---- */
.lx-device { position: relative; max-width: 440px; margin-left: auto; aspect-ratio: 4 / 5; border-radius: 28px; padding: 22px; background: linear-gradient(160deg, rgba(255,255,255,.09), rgba(255,255,255,.02)); border: 1px solid rgba(255,255,255,.14); box-shadow: 0 40px 100px rgba(0,0,0,.6), 0 0 0 1px rgba(34,211,238,.08), 0 0 80px rgba(34,211,238,.12); transform: perspective(1200px) rotateY(-8deg) rotateX(4deg); transition: transform .6s var(--ease); }
.lx-device:hover { transform: perspective(1200px) rotateY(0) rotateX(0); }
.lx-screen { position: relative; height: 62%; border-radius: 18px; overflow: hidden; background: radial-gradient(ellipse at 50% 0%, #0d1b2e, #02050b 70%); border: 1px solid rgba(255,255,255,.08); }
.lx-fan { position: absolute; left: 50%; top: 4%; width: 190%; aspect-ratio: 1; transform: translate(-50%, -50%); border-radius: 50%; background: conic-gradient(from 135deg, transparent 0deg, rgba(150,205,235,.08) 0deg, rgba(190,225,245,.35) 45deg, rgba(150,205,235,.08) 90deg, transparent 90deg); filter: blur(1.5px); }
.lx-fan::after { content: ""; position: absolute; inset: 0; border-radius: 50%; background: repeating-radial-gradient(circle at 50% 50%, rgba(255,255,255,.05) 0 2px, transparent 2px 7px); }
.lx-blob { position: absolute; left: 43%; top: 46%; width: 54px; height: 38px; border-radius: 50%; background: radial-gradient(rgba(255,255,255,.75), rgba(255,255,255,.1) 70%); filter: blur(2px); animation: lxBreath 2.2s ease-in-out infinite; }
@keyframes lxBreath { 50% { transform: scale(1.18); opacity: .6; } }
.lx-beam { position: absolute; left: 0; right: 0; height: 70px; top: -70px; background: linear-gradient(180deg, transparent, rgba(34,211,238,.35), transparent); border-bottom: 1px solid rgba(34,211,238,.9); box-shadow: 0 0 30px rgba(34,211,238,.6); animation: lxBeam 3.4s cubic-bezier(.6,0,.4,1) infinite; }
@keyframes lxBeam { 0% { top: -70px; } 100% { top: 100%; } }
.lx-tags { position: absolute; inset: 0; }
.lx-tag { position: absolute; font: 700 .72rem/1 ui-monospace, Menlo, monospace; color: #A5F3FC; background: rgba(8,145,178,.25); border: 1px solid rgba(34,211,238,.5); padding: 5px 8px; border-radius: 6px; opacity: 0; animation: lxTagIn 6.8s ease-in-out infinite; }
.lx-tag:nth-child(1) { left: 8%;  top: 14%; } .lx-tag:nth-child(2) { right: 8%; top: 26%; animation-delay: .6s; }
.lx-tag:nth-child(3) { left: 10%; bottom: 22%; animation-delay: 1.2s; } .lx-tag:nth-child(4) { right: 10%; bottom: 12%; animation-delay: 1.8s; }
@keyframes lxTagIn { 0%, 8% { opacity: 0; transform: translateY(8px); } 15%, 80% { opacity: 1; transform: none; } 92%, 100% { opacity: 0; } }
.lx-report { margin-top: 16px; display: grid; gap: 9px; }
.lx-bar { height: 10px; border-radius: 5px; background: rgba(255,255,255,.08); position: relative; overflow: hidden; }
.lx-bar::after { content: ""; position: absolute; inset: 0; width: 0; background: linear-gradient(90deg, #22D3EE, #34E89E); animation: lxWrite 6.8s ease-in-out infinite; }
.lx-bar:nth-child(2) { width: 84%; } .lx-bar:nth-child(2)::after { animation-delay: .4s; }
.lx-bar:nth-child(3) { width: 92%; } .lx-bar:nth-child(3)::after { animation-delay: .8s; }
.lx-bar:nth-child(4) { width: 60%; } .lx-bar:nth-child(4)::after { animation-delay: 1.2s; }
@keyframes lxWrite { 0%, 30% { width: 0; } 60%, 90% { width: 100%; } 100% { width: 100%; opacity: 0; } }
.lx-stamp { position: absolute; right: -18px; bottom: 26%; transform: rotate(-12deg); padding: 10px 16px; border: 3px solid #F43F5E; color: #F43F5E; border-radius: 10px; font-weight: 900; letter-spacing: .12em; font-size: .95rem; text-transform: uppercase; background: rgba(3,6,14,.75); box-shadow: 0 0 30px rgba(244,63,94,.35); text-align: center; line-height: 1.2; }
.lx-stamp small { display: block; font-size: .62rem; letter-spacing: .1em; font-weight: 800; }
.lx-timer60 { position: absolute; left: -26px; top: 18%; width: 92px; height: 92px; border-radius: 50%; display: grid; place-items: center; background: rgba(3,6,14,.85); border: 1px solid rgba(52,232,158,.35); box-shadow: 0 0 40px rgba(52,232,158,.3); }
.lx-timer60 svg { position: absolute; inset: 0; width: 100%; height: 100%; transform: rotate(-90deg); }
.lx-timer60 circle { fill: none; stroke-width: 5; }
.lx-timer60 .trk { stroke: rgba(255,255,255,.08); }
.lx-timer60 .prg { stroke: #34E89E; stroke-linecap: round; stroke-dasharray: 251; stroke-dashoffset: 251; animation: lxRing 6.8s linear infinite; }
@keyframes lxRing { to { stroke-dashoffset: 0; } }
.lx-timer60 b { font-size: 1.6rem; font-weight: 900; line-height: 1; }
.lx-timer60 span { position: absolute; bottom: 18px; font-size: .55rem; font-weight: 800; letter-spacing: .1em; color: var(--lx-mute); }

/* ---- Marquee ---- */
.lx-marquee { position: relative; z-index: 2; border-block: 1px solid var(--lx-line); background: rgba(255,255,255,.02); overflow: hidden; padding: 18px 0; }
.lx-track { display: flex; width: max-content; gap: 46px; animation: lxMarq 28s linear infinite; }
.lx-track span { display: inline-flex; align-items: center; gap: 46px; font-size: 1.15rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: rgba(255,255,255,.75); white-space: nowrap; }
.lx-track span::after { content: "✦"; color: var(--lx-cyan); }
@keyframes lxMarq { to { transform: translateX(-50%); } }

/* ---- Problem ---- */
.lx-problem { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.lx-pain { padding: 30px; border-radius: 22px; background: var(--lx-card); border: 1px solid var(--lx-line); }
.lx-pain .ic { width: 52px; height: 52px; border-radius: 16px; display: grid; place-items: center; background: rgba(244,63,94,.12); color: #FB7185; margin-bottom: 18px; }
.lx-pain .ic svg { width: 26px; height: 26px; }
.lx-pain h3 { font-size: 1.25rem; margin-bottom: 8px; }
.lx-whatif { margin-top: 56px; text-align: center; font-size: clamp(1.6rem, 3.4vw, 2.6rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.2; color: #fff; }

/* ---- World first ---- */
.lx-world { display: grid; grid-template-columns: .9fr 1.1fr; gap: 60px; align-items: center; }
.lx-globe { position: relative; aspect-ratio: 1; max-width: 440px; margin: 0 auto; border-radius: 50%; background: radial-gradient(circle at 35% 30%, rgba(34,211,238,.35), rgba(8,47,73,.4) 50%, rgba(3,6,14,.2) 70%); border: 1px solid rgba(34,211,238,.25); box-shadow: inset 0 0 80px rgba(34,211,238,.25), 0 0 120px rgba(34,211,238,.15); display: grid; place-items: center; overflow: hidden; }
.lx-globe::before { content: ""; position: absolute; inset: 0; border-radius: 50%; background: repeating-linear-gradient(90deg, rgba(255,255,255,.07) 0 1px, transparent 1px 36px), repeating-linear-gradient(0deg, rgba(255,255,255,.07) 0 1px, transparent 1px 36px); animation: lxSpin 30s linear infinite; }
@keyframes lxSpin { to { background-position: 360px 0, 0 0; } }
.lx-globe-in { position: relative; text-align: center; }
.lx-globe-in b { display: block; font-size: clamp(4rem, 9vw, 6.5rem); font-weight: 900; line-height: .9; letter-spacing: -.04em; }
.lx-globe-in span { font-size: .85rem; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: var(--lx-cyan); }
.lx-ring { position: absolute; inset: -6%; border-radius: 50%; border: 1px dashed rgba(52,232,158,.35); animation: lxRot 40s linear infinite; }
@keyframes lxRot { to { transform: rotate(360deg); } }
.lx-mods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-top: 30px; }
.lx-mod { padding: 18px 16px; border-radius: 18px; background: var(--lx-card); border: 1px solid var(--lx-line); transition: transform .3s var(--ease), border-color .3s; }
.lx-mod:hover { transform: translateY(-4px); border-color: rgba(34,211,238,.45); }
.lx-mod b { display: block; font-size: 1.5rem; font-weight: 900; letter-spacing: -.02em; }
.lx-mod span { display: block; font-weight: 700; color: #fff; margin-top: 2px; }
.lx-mod small { display: block; color: var(--lx-mute); font-size: .8rem; margin-top: 4px; }
.lx-mod em { display: inline-block; margin-top: 10px; font-style: normal; font-size: .72rem; font-weight: 800; color: var(--lx-green); background: rgba(52,232,158,.1); border-radius: 999px; padding: 3px 9px; }

/* ---- Timeline ---- */
.lx-tl { position: relative; display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
.lx-tl-line { position: absolute; left: 6%; right: 6%; top: 37px; height: 3px; background: rgba(255,255,255,.08); border-radius: 3px; overflow: hidden; }
.lx-tl-line i { position: absolute; inset: 0; width: 0; background: linear-gradient(90deg, #22D3EE, #34E89E, #A78BFA); box-shadow: 0 0 20px rgba(34,211,238,.6); transition: width 6s linear; }
.lx-tl.go .lx-tl-line i { width: 100%; }
.lx-tl-step { position: relative; text-align: center; }
.lx-tl-dot { width: 76px; height: 76px; margin: 0 auto 18px; border-radius: 50%; display: grid; place-items: center; background: #0A1328; border: 2px solid rgba(255,255,255,.12); color: var(--lx-mute); transition: all .5s var(--ease); position: relative; z-index: 1; }
.lx-tl-dot svg { width: 30px; height: 30px; }
.lx-tl-step.on .lx-tl-dot { color: #031018; background: linear-gradient(135deg, #22D3EE, #34E89E); border-color: transparent; box-shadow: 0 0 0 8px rgba(34,211,238,.12), 0 0 40px rgba(34,211,238,.55); transform: scale(1.06); }
.lx-tl-sec { display: block; font: 800 1.6rem/1 ui-monospace, Menlo, monospace; color: #fff; margin-bottom: 8px; }
.lx-tl-sec small { font-size: .9rem; color: var(--lx-mute); }
.lx-tl-step h3 { font-size: 1.05rem; margin-bottom: 6px; }
.lx-tl-step p { font-size: .9rem; color: var(--lx-mute); }
.lx-clock { text-align: center; margin-top: 46px; }
.lx-clock b { font: 900 clamp(3rem, 8vw, 5.5rem)/1 ui-monospace, Menlo, monospace; letter-spacing: -.04em; }
.lx-clock p { margin-top: 8px; color: var(--lx-mute); }

/* ---- Dossier ---- */
.lx-dossier { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.lx-file { position: relative; padding: 28px; border-radius: 22px; background: linear-gradient(160deg, rgba(255,255,255,.06), rgba(255,255,255,.015)); border: 1px solid var(--lx-line); overflow: hidden; min-height: 230px; }
.lx-file::before { content: "CLASSIFIED"; position: absolute; right: -36px; top: 22px; transform: rotate(35deg); background: rgba(244,63,94,.85); color: #fff; font-size: .62rem; font-weight: 900; letter-spacing: .2em; padding: 4px 44px; }
.lx-file.open::before { content: "UNLOCKED"; background: #10B981; }
.lx-file .ic { width: 50px; height: 50px; border-radius: 15px; display: grid; place-items: center; background: rgba(34,211,238,.1); color: var(--lx-cyan); margin-bottom: 16px; }
.lx-file .ic svg { width: 24px; height: 24px; }
.lx-file .hint { font-size: 1.12rem; font-weight: 800; color: #fff; line-height: 1.35; margin-bottom: 12px; }
.lx-file h3 { font-size: 1rem; color: var(--lx-cyan); margin-bottom: 6px; }
.lx-redact { display: inline-block; color: transparent; background: repeating-linear-gradient(90deg, #1f2a44 0 9px, #273454 9px 10px); border-radius: 4px; letter-spacing: -2px; user-select: none; line-height: 1.1; }
.lx-file-lock { display: flex; align-items: center; gap: 8px; margin-top: 16px; font-size: .78rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--lx-amber); }
.lx-file-lock svg { width: 15px; height: 15px; }
.lx-file p.body { font-size: .95rem; }
.lx-redacts { display: grid; gap: 8px; }
.lx-redacts .lx-redact { display: block; height: 12px; font-size: 0; overflow: hidden; width: 100%; }
.lx-redacts .lx-redact:nth-child(2) { width: 82%; } .lx-redacts .lx-redact:nth-child(3) { width: 55%; }

/* ---- Audience + perks ---- */
.lx-cards4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.lx-card { padding: 26px 22px; border-radius: 20px; background: var(--lx-card); border: 1px solid var(--lx-line); transition: transform .3s var(--ease), border-color .3s; }
.lx-card:hover { transform: translateY(-5px); border-color: rgba(167,139,250,.45); }
.lx-card .ic { width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center; margin-bottom: 16px; color: #fff; background: linear-gradient(135deg, rgba(124,58,237,.6), rgba(34,211,238,.5)); }
.lx-card .ic svg { width: 24px; height: 24px; }
.lx-card h3 { font-size: 1.08rem; margin-bottom: 6px; }
.lx-card p { font-size: .92rem; color: var(--lx-mute); }

/* ---- Reserve form ---- */
.lx-reserve { display: grid; grid-template-columns: .95fr 1.05fr; gap: 56px; align-items: center; }
.lx-reserve h2 { font-size: clamp(2.1rem, 4.2vw, 3.3rem); line-height: 1.08; letter-spacing: -.025em; font-weight: 800; margin: 16px 0; }
.lx-perks { display: grid; gap: 14px; margin-top: 28px; }
.lx-perk { display: flex; gap: 14px; align-items: flex-start; }
.lx-perk .ic { flex: none; width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; background: rgba(52,232,158,.12); color: var(--lx-green); }
.lx-perk .ic svg { width: 20px; height: 20px; }
.lx-perk b { display: block; color: #fff; }
.lx-perk span { color: var(--lx-mute); font-size: .92rem; }
.lx-form-card { position: relative; padding: 34px; border-radius: 28px; background: linear-gradient(170deg, rgba(16,26,52,.95), rgba(7,13,29,.95)); border: 1px solid rgba(255,255,255,.12); box-shadow: 0 40px 100px rgba(0,0,0,.5); }
.lx-form-card::before { content: ""; position: absolute; inset: -1px; border-radius: 29px; padding: 1px; background: linear-gradient(130deg, #22D3EE, transparent 35%, transparent 65%, #A78BFA); -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none; }
.lx-form-card h3 { font-size: 1.45rem; font-weight: 800; margin-bottom: 4px; }
.lx-form-card > p { color: var(--lx-mute); font-size: .92rem; margin-bottom: 22px; }
.lx-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.lx-f { margin-bottom: 14px; }
.lx-f label { display: block; font-size: .78rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: rgba(255,255,255,.6); margin-bottom: 7px; }
.lx-f label i { color: #FB7185; font-style: normal; }
.lx-f input, .lx-f select { width: 100%; height: 50px; padding: 0 16px; border-radius: 14px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.14); color: #fff; font: inherit; font-size: 1rem; outline: none; transition: border-color .2s, box-shadow .2s, background .2s; }
.lx-f select { appearance: none; -webkit-appearance: none; background-image: linear-gradient(45deg, transparent 50%, rgba(255,255,255,.6) 50%), linear-gradient(135deg, rgba(255,255,255,.6) 50%, transparent 50%); background-position: calc(100% - 20px) 22px, calc(100% - 15px) 22px; background-size: 5px 5px; background-repeat: no-repeat; padding-right: 36px; }
.lx-f select option { color: #0F1B33; }
.lx-f input::placeholder { color: rgba(255,255,255,.35); }
.lx-f input:focus, .lx-f select:focus { border-color: var(--lx-cyan); background: rgba(34,211,238,.06); box-shadow: 0 0 0 4px rgba(34,211,238,.15); }
.lx-f.err input, .lx-f.err select { border-color: #FB7185; box-shadow: 0 0 0 4px rgba(251,113,133,.15); }
.lx-phone { display: grid; grid-template-columns: 110px 1fr; gap: 8px; }
.lx-chips { display: flex; flex-wrap: wrap; gap: 8px; }
.lx-chip { position: relative; }
.lx-chip input { position: absolute; opacity: 0; pointer-events: none; }
.lx-chip span { display: inline-flex; padding: 9px 14px; border-radius: 999px; border: 1px solid rgba(255,255,255,.16); font-size: .85rem; font-weight: 700; color: rgba(255,255,255,.75); cursor: pointer; transition: all .2s; user-select: none; }
.lx-chip input:checked + span { background: rgba(34,211,238,.15); border-color: var(--lx-cyan); color: #fff; box-shadow: 0 0 18px rgba(34,211,238,.25); }
.lx-chip input:focus-visible + span { outline: 2px solid var(--lx-cyan); outline-offset: 2px; }
.lx-consent { display: flex; gap: 10px; align-items: flex-start; font-size: .85rem; color: var(--lx-mute); margin: 6px 0 18px; cursor: pointer; }
.lx-consent input { margin-top: 3px; width: 18px; height: 18px; accent-color: #22D3EE; flex: none; }
.lx-consent.err { color: #FB7185; }
.lx-submit { width: 100%; }
.lx-submit[disabled] { opacity: .7; cursor: wait; }
.lx-msg { margin-top: 12px; font-size: .9rem; font-weight: 600; min-height: 1.2em; }
.lx-msg.bad { color: #FB7185; }
.lx-safe { margin-top: 14px; display: flex; align-items: center; gap: 8px; color: var(--lx-mute); font-size: .8rem; }
.lx-safe svg { width: 15px; height: 15px; color: var(--lx-green); }
.lx-hp { position: absolute; left: -9999px; width: 1px; height: 1px; opacity: 0; }

/* ---- Success / ticket ---- */
.lx-ticket { position: relative; padding: 34px; border-radius: 28px; text-align: center; background: linear-gradient(160deg, rgba(16,185,129,.18), rgba(34,211,238,.1) 60%, rgba(124,58,237,.15)); border: 1px solid rgba(52,232,158,.4); box-shadow: 0 0 80px rgba(52,232,158,.15); animation: lxPop .7s var(--ease); }
@keyframes lxPop { from { transform: scale(.92); opacity: 0; } to { transform: none; opacity: 1; } }
.lx-ticket .big { width: 76px; height: 76px; margin: 0 auto 16px; border-radius: 50%; display: grid; place-items: center; background: linear-gradient(135deg, #34E89E, #22D3EE); color: #031018; box-shadow: 0 0 0 10px rgba(52,232,158,.12); }
.lx-ticket .big svg { width: 38px; height: 38px; }
.lx-ticket h3 { font-size: 1.7rem; font-weight: 800; margin-bottom: 8px; }
.lx-ticket p { margin-bottom: 20px; }
.lx-ticket-no { display: inline-block; font: 800 .85rem/1 ui-monospace, Menlo, monospace; letter-spacing: .14em; color: var(--lx-green); border: 1px dashed rgba(52,232,158,.5); padding: 8px 14px; border-radius: 10px; margin-bottom: 20px; }
.lx-ticket .lx-actions { justify-content: center; margin-top: 4px; }
.lx-ticket .lx-btn { padding: 14px 22px; font-size: .95rem; }
#lx-confetti { position: fixed; inset: 0; pointer-events: none; z-index: 2000; }

/* ---- FAQ ---- */
.lx-faq { max-width: 820px; margin: 0 auto; display: grid; gap: 12px; }
.lx-faq details { border-radius: 18px; background: var(--lx-card); border: 1px solid var(--lx-line); padding: 0 24px; transition: border-color .3s; }
.lx-faq details[open] { border-color: rgba(34,211,238,.4); }
.lx-faq summary { cursor: pointer; list-style: none; padding: 20px 0; font-weight: 800; font-size: 1.05rem; color: #fff; display: flex; justify-content: space-between; gap: 16px; align-items: center; }
.lx-faq summary::-webkit-details-marker { display: none; }
.lx-faq summary::after { content: "+"; font-size: 1.5rem; color: var(--lx-cyan); transition: transform .3s; }
.lx-faq details[open] summary::after { transform: rotate(45deg); }
.lx-faq details p { padding: 0 0 20px; }

/* ---- Final ---- */
.lx-final { text-align: center; }
.lx-final h2 { font-size: clamp(2.4rem, 5.5vw, 4.4rem); line-height: 1.02; letter-spacing: -.035em; font-weight: 900; margin: 18px 0 30px; }
.lx-final .lx-count { margin: 0 auto 34px; }
.lx-final .lx-actions { justify-content: center; }

/* ---- Revealed product ---- */
.lx-reveal-hero h1 { font-size: clamp(2.8rem, 6.5vw, 5.2rem); }
.lx-badge-live { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; background: rgba(16,185,129,.15); border: 1px solid rgba(52,232,158,.45); color: var(--lx-green); font-weight: 800; font-size: .8rem; letter-spacing: .14em; text-transform: uppercase; }
.lx-badge-live i { width: 8px; height: 8px; border-radius: 50%; background: var(--lx-green); animation: lxPing 1.6s infinite; box-shadow: 0 0 0 0 rgba(52,232,158,.7); }
.lx-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 40px; }
.lx-stat { padding: 22px; border-radius: 20px; background: var(--lx-card); border: 1px solid var(--lx-line); text-align: center; }
.lx-stat b { display: block; font-size: 2.2rem; font-weight: 900; letter-spacing: -.03em; }
.lx-stat span { color: var(--lx-mute); font-size: .88rem; }

/* ---- Locked gate (after launch, not subscribed) ---- */
.lx-gate { max-width: 720px; margin: 0 auto; text-align: center; }
.lx-gate .lx-lockbig { width: 110px; height: 110px; margin: 0 auto 24px; border-radius: 32px; display: grid; place-items: center; color: var(--lx-amber); background: rgba(251,191,36,.08); border: 1px solid rgba(251,191,36,.35); box-shadow: 0 0 60px rgba(251,191,36,.2); animation: lxBreath 2.6s ease-in-out infinite; }
.lx-gate .lx-lockbig svg { width: 52px; height: 52px; }

/* ---- Sticky mobile CTA ---- */
.lx-sticky { display: none; }

/* ---- Reveal-on-scroll (shared .reveal classes come from styles.css) ---- */

@media (max-width: 1024px) {
  .lx-hero-grid, .lx-world, .lx-reserve { grid-template-columns: 1fr; }
  .lx-device { margin: 20px auto 0; transform: none; max-width: 380px; }
  .lx-cards4 { grid-template-columns: repeat(2, 1fr); }
  .lx-dossier { grid-template-columns: repeat(2, 1fr); }
  .lx-tl { grid-template-columns: 1fr; gap: 0; max-width: 520px; margin: 0 auto; }
  .lx-tl-line { left: 37px; right: auto; top: 20px; bottom: 20px; width: 3px; height: auto; }
  .lx-tl-line i { width: 100% !important; height: 0; transition: height 6s linear; }
  .lx-tl.go .lx-tl-line i { height: 100%; }
  .lx-tl-step { display: grid; grid-template-columns: 76px 1fr; gap: 0 18px; text-align: left; padding-bottom: 22px; }
  .lx-tl-dot { grid-row: span 3; margin: 0; }
  .lx-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 680px) {
  .lx-sec { padding: 76px 0; }
  .lx-hero { min-height: auto; padding-top: calc(var(--nav-h) + 34px); padding-bottom: 60px; }
  .lx-count { gap: 8px; }
  .lx-unit { padding: 14px 2px 10px; border-radius: 14px; }
  .lx-lbl { font-size: .6rem; letter-spacing: .1em; }
  .lx-actions .lx-btn { flex: 1 1 100%; }
  .lx-problem, .lx-dossier, .lx-cards4 { grid-template-columns: 1fr; }
  .lx-mods { grid-template-columns: repeat(2, 1fr); }
  .lx-row { grid-template-columns: 1fr; gap: 0; }
  .lx-form-card { padding: 24px 18px; }
  .lx-timer60 { left: -8px; width: 76px; height: 76px; }
  .lx-stamp { right: -6px; }
  .lx-sticky { display: flex; position: fixed; left: 0; right: 0; bottom: 0; z-index: 950; padding: 10px 12px calc(10px + env(safe-area-inset-bottom)); background: rgba(3,6,14,.92); backdrop-filter: blur(10px); border-top: 1px solid var(--lx-line); }
  .lx-sticky .lx-btn { flex: 1; padding: 15px; }
  .wa-float { display: none; }
  .to-top { bottom: 86px !important; }
  .footer { padding-bottom: 90px; }
}
@media (prefers-reduced-motion: reduce) {
  .lx *, .lx *::before, .lx *::after { animation: none !important; transition: none !important; }
}
</style>
HTML;

require __DIR__ . '/partials/head.php';

/** Countdown block. $id must be unique on the page. */
function lx_countdown(string $id): string
{
    return '<div class="lx-count" id="' . $id . '" data-countdown role="timer" aria-live="off">'
        . '<div class="lx-unit"><span class="lx-num" data-u="d">00</span><span class="lx-lbl">Days</span></div>'
        . '<div class="lx-unit"><span class="lx-num" data-u="h">00</span><span class="lx-lbl">Hours</span></div>'
        . '<div class="lx-unit"><span class="lx-num" data-u="m">00</span><span class="lx-lbl">Minutes</span></div>'
        . '<div class="lx-unit"><span class="lx-num" data-u="s">00</span><span class="lx-lbl">Seconds</span></div>'
        . '</div>';
}

$sub_name = $subscriber ? $subscriber['name'] : '';
?>
<main class="lx">
<?php if ($is_preview): ?>
<div style="position:fixed;left:50%;bottom:18px;transform:translateX(-50%);z-index:1500;padding:10px 18px;border-radius:999px;background:#FBBF24;color:#1f1300;font-weight:800;font-size:.85rem;box-shadow:0 10px 30px rgba(0,0,0,.4);white-space:nowrap;">PREVIEW — how subscribers see this page after 24 Oct, 11:00 AM</div>
<?php endif; ?>

<?php if ($unlocked): ?>
<!-- ═════════════ REVEALED (launch is live + subscriber) ═════════════ -->
<header class="lx-hero lx-reveal-hero">
  <div class="lx-stars"></div><div class="lx-grid"></div>
  <div class="lx-orb o1"></div><div class="lx-orb o2"></div><div class="lx-orb o3"></div>
  <div class="container">
    <div class="lx-hero-grid">
      <div class="reveal">
        <span class="lx-badge-live"><i></i> Now live · World premiere</span>
        <div class="lx-first" style="margin-top:22px;"><?= lx_ic('globe') ?> First time ever in healthcare, worldwide</div>
        <h1>Introducing<br><span class="lx-grad">HealthO Pro for Ultrasound Reporting</span></h1>
        <p class="lx-lead">Welcome to the front row<?= $sub_name !== '' ? ', <b>' . h($sub_name) . '</b>' : '' ?>. This is the AI-driven reporting engine that <b>auto-creates Sonography, Ultrasound, Doppler, X-Ray, CT and MRI reports within 1 minute</b> — straight from the machine, in your own format, signed by your doctor and delivered to the patient's phone.</p>
        <div class="lx-actions">
          <a href="#" data-demo data-demo-interest="RIS / RIMS" class="lx-btn lx-btn-main"><?= lx_ic('play') ?> Book my live demo</a>
          <a href="<?= h($wa_talk) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= lx_ic('wa') ?> Talk to us</a>
        </div>
      </div>
      <div class="reveal d1">
        <div class="lx-device">
          <div class="lx-screen">
            <div class="lx-fan"></div><div class="lx-blob"></div><div class="lx-beam"></div>
            <div class="lx-tags"><span class="lx-tag">BPD 45.2 mm</span><span class="lx-tag">FL 32.1 mm</span><span class="lx-tag">EFW 410 g</span><span class="lx-tag">AFI 12.4</span></div>
          </div>
          <div class="lx-report"><span class="lx-bar"></span><span class="lx-bar"></span><span class="lx-bar"></span><span class="lx-bar"></span></div>
          <div class="lx-timer60"><svg viewBox="0 0 92 92"><circle class="trk" cx="46" cy="46" r="40"/><circle class="prg" cx="46" cy="46" r="40"/></svg><b>60</b><span>SEC</span></div>
          <div class="lx-stamp" style="border-color:#34E89E;color:#34E89E;box-shadow:0 0 30px rgba(52,232,158,.35);">Report ready<small>Auto-created</small></div>
        </div>
      </div>
    </div>
    <div class="lx-stats reveal">
      <div class="lx-stat"><b class="lx-grad">&lt; 60s</b><span>scan to signed report</span></div>
      <div class="lx-stat"><b class="lx-grad">6</b><span>modalities, one engine</span></div>
      <div class="lx-stat"><b class="lx-grad">0</b><span>numbers typed by hand</span></div>
      <div class="lx-stat"><b class="lx-grad">₹5</b><span>per patient, no licence fee</span></div>
    </div>
  </div>
</header>

<div class="lx-marquee" aria-hidden="true"><div class="lx-track">
<?php for ($i = 0; $i < 2; $i++): foreach ($modalities as $m): ?><span><?= $m[0] ?></span><?php endforeach; endfor; ?>
</div></div>

<section class="lx-sec lx-sec--alt" id="inside">
  <div class="container">
    <div class="lx-head reveal">
      <span class="lx-kicker"><?= lx_ic('unlock') ?> The dossier is open</span>
      <h2>Six things no reporting software <span class="lx-grad">has ever done together</span></h2>
    </div>
    <div class="lx-dossier">
<?php foreach ($dossier as $i => [$ic, $hint, $t, $body]): ?>
      <article class="lx-file open reveal<?= $i % 3 ? ' d' . ($i % 3) : '' ?>">
        <span class="ic"><?= lx_ic($ic) ?></span>
        <p class="hint"><?= h($hint) ?></p>
        <h3><?= h($t) ?></h3>
        <p class="body"><?= h($body) ?></p>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php else: ?>
<!-- ═════════════ TEASER HERO ═════════════ -->
<header class="lx-hero">
  <div class="lx-stars"></div><div class="lx-grid"></div>
  <div class="lx-orb o1"></div><div class="lx-orb o2"></div><div class="lx-orb o3"></div>
  <div class="container">
    <div class="lx-hero-grid">
      <div class="reveal">
<?php if ($is_live): ?>
        <span class="lx-badge-live"><i></i> It's live — for subscribers only</span>
<?php else: ?>
        <span class="lx-kicker"><i></i> World premiere · Sat, 24 Oct 2026 · 11:00 AM IST</span>
<?php endif; ?>
        <h1>Every scan.<br>Every report.<br><span class="lx-grad lx-60">In 60 seconds.</span></h1>
        <div class="lx-first"><?= lx_ic('globe') ?> First time ever in healthcare, worldwide</div>
        <p class="lx-lead">HealthO Pro is about to unveil an <b>AI-driven engine that auto-creates Sonography, Ultrasound, X-Ray, CT &amp; MRI reports within 1 minute</b>. No typing. No copying numbers. No waiting room.
<?php if ($is_live): ?>
          The reveal is happening right now — <b>only for people on the list.</b>
<?php else: ?>
          What it is — and how it works — stays classified until the countdown hits zero. <b>Only people on the list will see it first.</b>
<?php endif; ?>
        </p>

<?php if (!$is_live): ?>
        <?= lx_countdown('lx-count-hero') ?>
        <div class="lx-when">
          <span><b>Saturday, 24 October 2026</b> · 11:00 AM IST</span>
          <a href="#" class="lx-cal-btn"><?= lx_ic('cal') ?> Add to calendar</a>
        </div>
<?php endif; ?>

        <div class="lx-actions">
<?php if ($subscriber && !$is_live): ?>
          <span class="lx-btn lx-btn-ghost" style="cursor:default;border-color:rgba(52,232,158,.5);color:#34E89E;"><?= lx_ic('check') ?> You're on the list<?= $sub_name !== '' ? ', ' . h($sub_name) : '' ?></span>
          <a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= lx_ic('wa') ?> Invite a colleague</a>
<?php else: ?>
          <a href="#reserve" class="lx-btn lx-btn-main"><?= lx_ic('ticket') ?> <?= $is_live ? 'Unlock the reveal now' : 'Reserve my front-row seat' ?></a>
          <a href="#inside" class="lx-btn lx-btn-ghost"><?= lx_ic('eye') ?> What we can tell you</a>
<?php endif; ?>
        </div>
<?php if ($show_count): ?>
        <div class="lx-social-proof">
          <span class="lx-avatars"><span style="background:#22D3EE">Dr</span><span style="background:#34E89E">R</span><span style="background:#A78BFA">S</span><span style="background:#FBBF24">+</span></span>
          <span><b><?= number_format($count) ?></b> healthcare professionals already have a seat</span>
        </div>
<?php endif; ?>
      </div>

      <div class="reveal d1">
        <div class="lx-device" aria-label="A blurred preview of the product, classified until launch">
          <div class="lx-screen">
            <div class="lx-fan"></div><div class="lx-blob"></div><div class="lx-beam"></div>
            <div class="lx-tags"><span class="lx-tag">BPD ██.█ mm</span><span class="lx-tag">FL ██.█ mm</span><span class="lx-tag">EFW ███ g</span><span class="lx-tag">AFI ██.█</span></div>
          </div>
          <div class="lx-report"><span class="lx-bar"></span><span class="lx-bar"></span><span class="lx-bar"></span><span class="lx-bar"></span></div>
          <div class="lx-timer60"><svg viewBox="0 0 92 92"><circle class="trk" cx="46" cy="46" r="40"/><circle class="prg" cx="46" cy="46" r="40"/></svg><b>60</b><span>SEC</span></div>
          <div class="lx-stamp">Classified<small>Reveal 24.10.2026 · 11:00</small></div>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="lx-marquee" aria-hidden="true"><div class="lx-track">
<?php for ($i = 0; $i < 2; $i++): foreach ($modalities as $m): ?><span><?= $m[0] ?></span><span>Auto report &lt; 60s</span><?php endforeach; endfor; ?>
</div></div>

<?php if ($is_live): ?>
<!-- ═════════════ GATE (live, not subscribed) ═════════════ -->
<section class="lx-sec" id="gate">
  <div class="container">
    <div class="lx-gate reveal">
      <div class="lx-lockbig"><?= lx_ic('lock') ?></div>
      <span class="lx-kicker">Subscribers only</span>
      <h2 style="font-size:clamp(2rem,4vw,3rem);font-weight:800;margin:16px 0;">The reveal is behind this door.</h2>
      <p style="font-size:1.1rem;">Add your details below and it unlocks <b style="color:#fff;">instantly</b> — the full product, how it works for all six modalities, and the launch-day benefits.</p>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═════════════ PROBLEM ═════════════ -->
<section class="lx-sec">
  <div class="container">
    <div class="lx-head reveal">
      <span class="lx-kicker">The problem nobody solved</span>
      <h2>The scan takes minutes.<br><span class="lx-grad">The report takes forever.</span></h2>
      <p>Every imaging centre in the world runs the same tired routine — and the patient pays for it with their time.</p>
    </div>
    <div class="lx-problem">
      <div class="lx-pain reveal"><span class="ic"><?= lx_ic('keyboard') ?></span><h3>Typed by hand</h3><p>Doctors read numbers off the screen and type them, one by one, into a Word file — report after report, all day.</p></div>
      <div class="lx-pain reveal d1"><span class="ic"><?= lx_ic('copy') ?></span><h3>One wrong digit</h3><p>45 becomes 54. A copy-paste carries the last patient's line. Small slips, big consequences.</p></div>
      <div class="lx-pain reveal d2"><span class="ic"><?= lx_ic('hourglass') ?></span><h3>A full waiting room</h3><p>Patients wait for paper. Doctors stay late. Centres turn patients away at peak hours.</p></div>
    </div>
    <p class="lx-whatif reveal">What if the report was ready<br><span class="lx-grad">before the patient got off the table?</span></p>
  </div>
</section>

<!-- ═════════════ WORLD FIRST ═════════════ -->
<section class="lx-sec lx-sec--alt" id="inside">
  <div class="container">
    <div class="lx-world">
      <div class="reveal">
        <div class="lx-globe"><span class="lx-ring"></span><div class="lx-globe-in"><span>World's</span><b class="lx-grad">1st</b><span>All-modality AI</span></div></div>
      </div>
      <div class="reveal d1">
        <span class="lx-kicker"><?= lx_ic('globe') ?> First time ever. Anywhere.</span>
        <h2 style="font-size:clamp(2rem,4vw,3rem);line-height:1.1;letter-spacing:-.02em;font-weight:800;margin:16px 0;">One engine. Six modalities.<br><span class="lx-grad">Every report under a minute.</span></h2>
        <p style="font-size:1.08rem;">Reporting tools have helped with one scan type at a time. Never before has a single AI-driven engine auto-created reports across the entire imaging department — in under 60 seconds, in your own format.</p>
        <div class="lx-mods">
<?php foreach ($modalities as $m): ?>
          <div class="lx-mod"><b class="lx-grad"><?= $m[1] ?></b><span><?= $m[0] ?></span><small><?= $m[2] ?></small><em>Auto report &lt; 60s</em></div>
<?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═════════════ 60-SECOND TIMELINE (both states) ═════════════ -->
<section class="lx-sec">
  <div class="container">
    <div class="lx-head reveal">
      <span class="lx-kicker"><?= lx_ic('clock') ?> The 60-second promise</span>
      <h2>From probe off <span class="lx-grad">to report on the phone</span></h2>
      <p>Watch one minute — that is the whole job now.</p>
    </div>
    <div class="lx-tl" id="lx-tl">
      <div class="lx-tl-line"><i></i></div>
<?php foreach ($timeline as $i => [$s, $ic, $t, $d]): ?>
      <div class="lx-tl-step" data-at="<?= $i ?>">
        <div class="lx-tl-dot"><?= lx_ic($ic) ?></div>
        <span class="lx-tl-sec"><?= $s ?><small>s</small></span>
        <h3><?= h($t) ?></h3>
        <p><?= h($d) ?></p>
      </div>
<?php endforeach; ?>
    </div>
    <div class="lx-clock reveal"><b class="lx-grad" id="lx-clock">00:00</b><p>Average reporting time today: long enough to make a cup of tea. Ours: this.</p></div>
  </div>
</section>

<?php if (!$unlocked): ?>
<!-- ═════════════ LOCKED DOSSIER ═════════════ -->
<section class="lx-sec lx-sec--alt">
  <div class="container">
    <div class="lx-head reveal">
      <span class="lx-kicker"><?= lx_ic('lock') ?> The locked dossier</span>
      <h2>Six secrets. <span class="lx-grad">One unlock date.</span></h2>
      <p>We can give you the clues. The answers are reserved for the people on the list — at 11:00 AM, 24 October.</p>
    </div>
    <div class="lx-dossier">
<?php foreach ($dossier as $i => [$ic, $hint]): ?>
      <article class="lx-file reveal<?= $i % 3 ? ' d' . ($i % 3) : '' ?>">
        <span class="ic"><?= lx_ic($ic) ?></span>
        <p class="hint"><?= h($hint) ?></p>
        <div class="lx-redacts"><?= lx_redact(22) ?><?= lx_redact(30) ?><?= lx_redact(16) ?></div>
        <div class="lx-file-lock"><?= lx_ic('lock') ?> Unlocks 24 Oct · 11:00 AM</div>
      </article>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═════════════ WHO IT'S FOR ═════════════ -->
<section class="lx-sec">
  <div class="container">
    <div class="lx-head reveal">
      <span class="lx-kicker"><?= lx_ic('users') ?> Built for imaging</span>
      <h2>If you read scans, <span class="lx-grad">you need to be there.</span></h2>
    </div>
    <div class="lx-cards4">
<?php foreach ($audience as $i => [$ic, $t, $d]): ?>
      <div class="lx-card reveal<?= $i ? ' d' . min($i, 3) : '' ?>"><span class="ic"><?= lx_ic($ic) ?></span><h3><?= h($t) ?></h3><p><?= h($d) ?></p></div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($unlocked): ?>
<!-- ═════════════ REVEALED: next steps ═════════════ -->
<section class="lx-sec lx-sec--alt" id="reserve">
  <div class="container">
    <div class="lx-final reveal">
      <span class="lx-kicker"><?= lx_ic('star') ?> Founding members</span>
      <h2>See it with <span class="lx-grad">your own machine.</span></h2>
      <p style="max-width:640px;margin:0 auto 30px;font-size:1.1rem;">Book a live demo and we'll connect your scanner and your report format — free. Launch-day benefits for subscribers are shared on your WhatsApp and e-mail.</p>
      <div class="lx-actions">
        <a href="#" data-demo data-demo-interest="RIS / RIMS" class="lx-btn lx-btn-main"><?= lx_ic('play') ?> Book my live demo</a>
        <a href="/ris" class="lx-btn lx-btn-ghost"><?= lx_ic('chart') ?> See pricing &amp; details</a>
        <a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= lx_ic('wa') ?> Share with a colleague</a>
      </div>
    </div>
  </div>
</section>
<?php else: ?>
<!-- ═════════════ RESERVE ═════════════ -->
<section class="lx-sec lx-sec--alt" id="reserve">
  <div class="lx-orb o2" style="top:auto;bottom:-260px;"></div>
  <div class="container">
    <div class="lx-reserve">
      <div class="reveal">
        <span class="lx-kicker"><?= lx_ic('ticket') ?> Free · takes 20 seconds</span>
        <h2><?= $is_live ? 'Unlock the reveal' : 'Get your front-row seat' ?> <span class="lx-grad">to the future of reporting.</span></h2>
        <p style="font-size:1.08rem;">Seats are free. What you get for being early is not something we'll put on a public page.</p>
        <div class="lx-perks">
<?php foreach ($perks as [$ic, $t, $d]): ?>
          <div class="lx-perk"><span class="ic"><?= lx_ic($ic) ?></span><div><b><?= h($t) ?></b><span><?= h($d) ?></span></div></div>
<?php endforeach; ?>
        </div>
      </div>

      <div class="reveal d1" id="lx-form-wrap">
<?php if ($subscriber && !$is_live): ?>
        <div class="lx-ticket">
          <div class="big"><?= lx_ic('check') ?></div>
          <h3>Seat confirmed<?= $sub_name !== '' ? ', ' . h($sub_name) : '' ?>!</h3>
          <div class="lx-ticket-no">FRONT ROW · 24.10.2026 · 11:00 IST</div>
          <p>Come back to this page at 11:00 AM on 24 October — it unlocks for you automatically. We'll also send you the invite.</p>
          <div class="lx-actions">
            <a href="#" class="lx-btn lx-btn-main lx-cal-btn"><?= lx_ic('cal') ?> Add to calendar</a>
            <a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= lx_ic('wa') ?> Invite a colleague</a>
          </div>
        </div>
<?php else: ?>
        <form class="lx-form-card" id="lx-form" novalidate>
          <h3><?= $is_live ? 'Unlock instantly' : 'Reserve my seat' ?></h3>
          <p>Already reserved on another device? Just fill this again — you won't be added twice.</p>
          <div class="lx-f"><label for="lx-name">Full name <i>*</i></label><input id="lx-name" name="name" autocomplete="name" placeholder="Dr. Ravi Kumar" required maxlength="120"></div>
          <div class="lx-f">
            <label for="lx-phone">WhatsApp / Mobile <i>*</i></label>
            <div class="lx-phone">
              <select name="country_code" aria-label="Country code">
                <option value="91" selected>🇮🇳 +91</option><option value="971">🇦🇪 +971</option><option value="966">🇸🇦 +966</option><option value="974">🇶🇦 +974</option><option value="968">🇴🇲 +968</option><option value="965">🇰🇼 +965</option><option value="973">🇧🇭 +973</option><option value="977">🇳🇵 +977</option><option value="880">🇧🇩 +880</option><option value="94">🇱🇰 +94</option><option value="1">🇺🇸 +1</option><option value="44">🇬🇧 +44</option><option value="61">🇦🇺 +61</option><option value="65">🇸🇬 +65</option>
              </select>
              <input id="lx-phone" name="phone" type="tel" inputmode="numeric" autocomplete="tel-national" placeholder="98765 43210" required maxlength="16">
            </div>
          </div>
          <div class="lx-row">
            <div class="lx-f"><label for="lx-email">E-mail</label><input id="lx-email" name="email" type="email" autocomplete="email" placeholder="you@centre.com" maxlength="150"></div>
            <div class="lx-f"><label for="lx-city">City</label><input id="lx-city" name="city" autocomplete="address-level2" placeholder="Hyderabad" maxlength="80"></div>
          </div>
          <div class="lx-row">
            <div class="lx-f"><label for="lx-org">Centre / Hospital</label><input id="lx-org" name="organisation" autocomplete="organization" placeholder="Sunrise Diagnostics" maxlength="150"></div>
            <div class="lx-f"><label for="lx-role">I am a</label>
              <select id="lx-role" name="role">
                <option value="">Select…</option><option>Radiologist</option><option>Sonologist</option><option>Centre Owner / Director</option><option>Hospital Administrator</option><option>Gynecologist / Obstetrician</option><option>Technician / Staff</option><option>Other</option>
              </select>
            </div>
          </div>
          <div class="lx-f">
            <label>Modalities you report</label>
            <div class="lx-chips">
<?php foreach (['Ultrasound', 'Doppler', 'X-Ray', 'CT', 'MRI'] as $m): ?>
              <label class="lx-chip"><input type="checkbox" name="modalities[]" value="<?= $m ?>"<?= $m === 'Ultrasound' ? ' checked' : '' ?>><span><?= $m ?></span></label>
<?php endforeach; ?>
            </div>
          </div>
          <input class="lx-hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
          <label class="lx-consent" id="lx-consent-wrap"><input type="checkbox" name="consent" value="1" checked> Send me the launch invite and HealthO Pro updates on WhatsApp, SMS and e-mail. Reply STOP any time.</label>
          <button type="submit" class="lx-btn lx-btn-main lx-submit"><?= lx_ic('bolt') ?> <span><?= $is_live ? 'Unlock the reveal' : 'Reserve my front-row seat' ?></span></button>
          <div class="lx-msg" id="lx-msg" role="status" aria-live="polite"></div>
          <div class="lx-safe"><?= lx_ic('shield') ?> Your details stay with HealthO Pro. No spam, ever.</div>
        </form>
<?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═════════════ FAQ ═════════════ -->
<section class="lx-sec">
  <div class="container">
    <div class="lx-head reveal"><span class="lx-kicker">Questions</span><h2>Before you <span class="lx-grad">take your seat</span></h2></div>
    <div class="lx-faq">
<?php foreach ($faqs as $q => $a): ?>
      <details class="reveal"><summary><?= h($q) ?></summary><p><?= h($a) ?></p></details>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php if (!$unlocked): ?>
<!-- ═════════════ FINAL COUNTDOWN ═════════════ -->
<section class="lx-sec lx-sec--alt">
  <div class="lx-grid"></div>
  <div class="container lx-final reveal">
    <span class="lx-kicker"><i></i> <?= $is_live ? 'Happening now' : 'The clock is running' ?></span>
    <h2>Radiology reporting<br><span class="lx-grad">changes forever.</span></h2>
<?php if (!$is_live): ?>
    <?= lx_countdown('lx-count-final') ?>
<?php endif; ?>
    <div class="lx-actions">
<?php if ($subscriber && !$is_live): ?>
      <a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-main"><?= lx_ic('wa') ?> Invite a colleague</a>
<?php else: ?>
      <a href="#reserve" class="lx-btn lx-btn-main"><?= lx_ic('ticket') ?> <?= $is_live ? 'Unlock now' : 'Reserve my seat' ?></a>
      <a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= lx_ic('wa') ?> Share on WhatsApp</a>
<?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

</main>

<?php if (!$unlocked && !($subscriber && !$is_live)): ?>
<div class="lx-sticky"><a href="#reserve" class="lx-btn lx-btn-main"><?= lx_ic('ticket') ?> <?= $is_live ? 'Unlock the reveal' : 'Reserve my free seat' ?></a></div>
<?php endif; ?>

<script>
(function () {
  var LAUNCH = <?= (int) $launch_ts ?> * 1000;
  var SKEW = <?= time() ?> * 1000 - Date.now(); // trust the server clock, not the device
  var IS_LIVE = <?= $is_live ? 'true' : 'false' ?>;
  var now = function () { return Date.now() + SKEW; };
  var pad = function (n) { return (n < 10 ? '0' : '') + n; };

  /* ---- Countdown ---- */
  var boxes = document.querySelectorAll('[data-countdown]');
  var last = {};
  function tick() {
    var diff = Math.max(0, LAUNCH - now());
    var v = { d: Math.floor(diff / 864e5), h: Math.floor(diff / 36e5) % 24, m: Math.floor(diff / 6e4) % 60, s: Math.floor(diff / 1e3) % 60 };
    boxes.forEach(function (box, bi) {
      ['d', 'h', 'm', 's'].forEach(function (u) {
        var el = box.querySelector('[data-u="' + u + '"]');
        var txt = pad(v[u]);
        if (el.textContent !== txt) {
          el.textContent = txt;
          if (last[bi + u] !== undefined) { el.classList.remove('tick'); void el.offsetWidth; el.classList.add('tick'); }
          last[bi + u] = txt;
        }
      });
    });
    if (diff <= 0 && !IS_LIVE) {
      // Zero hour: the server decides who sees the reveal.
      IS_LIVE = true;
      setTimeout(function () { location.reload(); }, 1200);
    }
  }
  if (boxes.length || !IS_LIVE) { tick(); setInterval(tick, 1000); }

  /* ---- Add to calendar (.ics) ---- */
  function ics() {
    var f = function (t) { return new Date(t).toISOString().replace(/[-:]/g, '').replace(/\.\d{3}/, ''); };
    var url = '<?= h($canonical) ?>';
    var body = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'PRODID:-//HealthO Pro//Launch//EN', 'BEGIN:VEVENT',
      'UID:launch-24oct2026@healtho.pro', 'DTSTAMP:' + f(Date.now()), 'DTSTART:' + f(LAUNCH), 'DTEND:' + f(LAUNCH + 36e5),
      'SUMMARY:World Premiere — HealthO Pro for Ultrasound Reporting',
      'DESCRIPTION:AI auto-creates Sonography / Ultrasound / X-Ray / CT / MRI reports within 1 minute. Open ' + url,
      'URL:' + url, 'BEGIN:VALARM', 'TRIGGER:-PT15M', 'ACTION:DISPLAY', 'DESCRIPTION:HealthO Pro reveal in 15 minutes', 'END:VALARM',
      'END:VEVENT', 'END:VCALENDAR'].join('\r\n');
    var a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([body], { type: 'text/calendar' }));
    a.download = 'HealthO-Pro-Launch-24-Oct-2026.ics';
    document.body.appendChild(a); a.click(); a.remove();
  }
  document.addEventListener('click', function (e) {
    var b = e.target.closest('.lx-cal-btn');
    if (b) { e.preventDefault(); ics(); }
  });

  /* ---- 60-second timeline: plays once when seen (6 s = "60 s") ---- */
  var tl = document.getElementById('lx-tl'), clock = document.getElementById('lx-clock');
  if (tl && 'IntersectionObserver' in window) {
    var steps = tl.querySelectorAll('.lx-tl-step');
    var played = false;
    new IntersectionObserver(function (es, io) {
      if (!es[0].isIntersecting || played) return;
      played = true; io.disconnect();
      tl.classList.add('go');
      var t0 = performance.now();
      (function frame(t) {
        var p = Math.min(1, (t - t0) / 6000), sec = Math.round(p * 60);
        clock.textContent = pad(Math.floor(sec / 60)) + ':' + pad(sec % 60);
        steps.forEach(function (s, i) { if (p >= i / (steps.length - 1) - 0.001) s.classList.add('on'); });
        if (p < 1) requestAnimationFrame(frame);
      })(t0);
    }, { threshold: .45 }).observe(tl);
  } else if (tl) {
    tl.classList.add('go'); tl.querySelectorAll('.lx-tl-step').forEach(function (s) { s.classList.add('on'); });
    if (clock) clock.textContent = '01:00';
  }

  /* ---- Sign-up ---- */
  var form = document.getElementById('lx-form');
  if (!form) return;
  var msg = document.getElementById('lx-msg');
  var params = new URLSearchParams(location.search);

  function confetti() {
    var c = document.createElement('canvas'); c.id = 'lx-confetti'; document.body.appendChild(c);
    var x = c.getContext('2d'), W = c.width = innerWidth, H = c.height = innerHeight;
    var cols = ['#22D3EE', '#34E89E', '#A78BFA', '#FBBF24', '#F472B6', '#ffffff'];
    var ps = Array.from({ length: 160 }, function () {
      return { x: W / 2 + (Math.random() - .5) * 200, y: H * .55, vx: (Math.random() - .5) * 16, vy: -Math.random() * 18 - 6, s: Math.random() * 7 + 4, r: Math.random() * 6, c: cols[Math.floor(Math.random() * cols.length)] };
    });
    var t = 0;
    (function draw() {
      x.clearRect(0, 0, W, H);
      ps.forEach(function (p) { p.vy += .45; p.x += p.vx; p.y += p.vy; p.r += .1; x.save(); x.translate(p.x, p.y); x.rotate(p.r); x.fillStyle = p.c; x.fillRect(-p.s / 2, -p.s / 4, p.s, p.s / 2); x.restore(); });
      if (++t < 170) requestAnimationFrame(draw); else c.remove();
    })();
  }

  function fieldErr(name) {
    var el = form.querySelector('[name="' + name + '"]');
    if (!el) return;
    if (name === 'consent') { document.getElementById('lx-consent-wrap').classList.add('err'); return; }
    var f = el.closest('.lx-f'); if (f) f.classList.add('err');
    el.focus();
  }
  form.addEventListener('input', function (e) { var f = e.target.closest('.lx-f'); if (f) f.classList.remove('err'); });
  form.addEventListener('change', function (e) { if (e.target.name === 'consent') document.getElementById('lx-consent-wrap').classList.remove('err'); });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    msg.textContent = ''; msg.className = 'lx-msg';
    var name = form.name.value.trim(), phone = form.phone.value.replace(/\D/g, '');
    if (name.length < 2) { msg.textContent = 'Please enter your name.'; msg.classList.add('bad'); return fieldErr('name'); }
    if (phone.length < 7) { msg.textContent = 'Please enter a valid mobile number.'; msg.classList.add('bad'); return fieldErr('phone'); }
    if (!form.consent.checked) { msg.textContent = 'Please allow us to send you the launch invite.'; msg.classList.add('bad'); return fieldErr('consent'); }

    var fd = new FormData(form);
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'].forEach(function (k) { if (params.get(k)) fd.append(k, params.get(k)); });
    if (document.referrer && document.referrer.indexOf(location.host) === -1) fd.append('referrer', document.referrer.slice(0, 250));
    fd.append('landing_page', location.pathname + location.search);

    var btn = form.querySelector('.lx-submit'), lbl = btn.querySelector('span'), old = lbl.textContent;
    btn.disabled = true; lbl.textContent = 'Saving your seat…';
    fetch('/api/launch-subscribe.php', { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json().catch(function () { return { success: false, message: 'Something went wrong. Please try again.' }; }); })
      .then(function (r) {
        if (!r.success) {
          msg.textContent = r.message || 'Something went wrong. Please try again.'; msg.classList.add('bad');
          if (r.field) fieldErr(r.field);
          btn.disabled = false; lbl.textContent = old;
          return;
        }
        if (window.gtag) gtag('event', 'generate_lead', { event_category: 'launch', event_label: 'launch_24oct2026' });
        if (window.fbq) fbq('track', 'Lead');
        confetti();
        if (r.live) { setTimeout(function () { location.reload(); }, 1400); return; }
        var wrap = document.getElementById('lx-form-wrap');
        wrap.innerHTML = '<div class="lx-ticket"><div class="big"><?= str_replace(["\n", "'"], ['', "\\'"], lx_ic('check')) ?></div>'
          + '<h3>' + (r.returning ? 'Welcome back' : 'Seat confirmed') + ', ' + (r.name || '').replace(/[<>&"]/g, '') + '!</h3>'
          + '<div class="lx-ticket-no">FRONT ROW · 24.10.2026 · 11:00 IST</div>'
          + '<p>Come back to this page at 11:00 AM on 24 October — it unlocks for you automatically. We\'ll also send you the invite.</p>'
          + '<div class="lx-actions"><a href="#" class="lx-btn lx-btn-main lx-cal-btn"><?= str_replace(["\n", "'"], ['', "\\'"], lx_ic('cal')) ?> Add to calendar</a>'
          + '<a href="<?= h($wa_share) ?>" target="_blank" rel="noopener" class="lx-btn lx-btn-ghost"><?= str_replace(["\n", "'"], ['', "\\'"], lx_ic('wa')) ?> Invite a colleague</a></div></div>';
        var sticky = document.querySelector('.lx-sticky'); if (sticky) sticky.remove();
      })
      .catch(function () { msg.textContent = 'Network problem — please try again.'; msg.classList.add('bad'); btn.disabled = false; lbl.textContent = old; });
  });
})();
</script>
<?php require __DIR__ . '/partials/foot.php';
