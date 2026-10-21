<?php
/**
 * Shared building blocks for the Meta-ads product landing pages (HIMS / LIMS / CIMS).
 *
 * Story order each page follows:
 *   hook (hero) → "does this happen to you?" checklist → hidden loss number
 *   → before/after switch → 4-step journey → benefits → proof → pricing → FAQ → form.
 *
 * Page-specific copy lives in the page; markup, styles and behaviour live here.
 */

/** Stroke icons (24×24, currentColor). */
require_once __DIR__ . '/clients-data.php';

function lp_ic(string $name): string
{
    static $p = [
        'check'   => '<path d="M20 6 9 17l-5-5"/>',
        'x'       => '<path d="M18 6 6 18M6 6l12 12"/>',
        'doc'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M9 13h6M9 17h6"/>',
        'chart'   => '<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>',
        'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
        'send'    => '<path d="M22 2 11 13M22 2l-7 20-4-9-9-4Z"/>',
        'receipt' => '<path d="M4 2v20l3-2 3 2 2-2 2 2 3-2 3 2V2l-3 2-3-2-2 2-2-2-3 2Z"/><path d="M8 9h8M8 13h6"/>',
        'keyboard'=> '<rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M7 14h10"/>',
        'eye'     => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'redo'    => '<path d="M21 2v6h-6"/><path d="M21 13a9 9 0 1 1-3-7.7L21 8"/>',
        'pen'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'heart'   => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21.2l8.8-8.8a5.5 5.5 0 0 0 0-7.8Z"/>',
        'users'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v1"/>',
        'building'=> '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M8 10h.01M16 10h.01M12 10h.01M8 14h.01M16 14h.01M12 14h.01"/>',
        'tool'    => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9Z"/>',
        'play'    => '<circle cx="12" cy="12" r="10"/><path d="m10 8 6 4-6 4Z"/>',
        'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
        'wa'      => '<path d="M21 12a9 9 0 0 1-13.4 7.9L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/><path d="M8.5 9.5c.5 2.5 3.5 5.5 6 6l1.5-1.5-2-1-1 1c-1-.5-2.5-2-3-3l1-1-1-2Z"/>',
        'phone'   => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/>',
        'calendar'=> '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'bed'     => '<path d="M2 4v16M2 8h18a2 2 0 0 1 2 2v10M2 17h20M6 8v9"/>',
        'pill'    => '<path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/>',
        'flask'   => '<path d="M9 3h6M10 3v6l-5.5 9.5A2 2 0 0 0 6.2 21h11.6a2 2 0 0 0 1.7-2.5L14 9V3"/><path d="M7 15h10"/>',
        'tube'    => '<path d="M14.5 2 21 8.5M16 3.5 5 14.5a3.5 3.5 0 0 0 5 5L21 8.5"/><path d="M7 12h6"/>',
        'barcode' => '<path d="M3 5v14M7 5v14M10 5v14M14 5v14M17 5v14M21 5v14"/>',
        'cpu'     => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>',
        'search'  => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'folder'  => '<path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.7-.9l-.8-1.2A2 2 0 0 0 7.9 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>',
        'box'     => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"/><path d="m3 8 9 5 9-5M12 13v8"/>',
        'rupee'   => '<path d="M6 3h12M6 8h12M6 13l8.5 8M6 13h3a5 5 0 0 0 0-10"/>',
        'bell'    => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.9 1.9 0 0 0 3.4 0"/>',
        'link'    => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
        'lock'    => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'alert'   => '<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/>',
        'ticket'  => '<path d="M2 9a3 3 0 0 0 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 0 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2M13 17v2M13 11v2"/>',
        'msg'     => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>',
        'arrow'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
    ];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($p[$name] ?? '') . '</svg>';
}

/** Page styles — pass to $head_extra. */
function lp_styles(): string
{
    return <<<'HTML'
<style>
/* ---------- Hero ---------- */
.lp-hero { padding-bottom: 88px; }
.lp-hero h1 { font-size: clamp(2.1rem, 4.3vw, 3.4rem); line-height: 1.1; }
.lp-hero h1 .text-grad { display: inline; }
.lp-hero p.lp-sub { max-width: 540px; }
.lp-hero .hero-actions { margin-bottom: 30px; }
.lp-hero .btn svg { width: 20px; height: 20px; }
.lp-pills { display: flex; flex-wrap: wrap; gap: 10px; }
.lp-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: var(--r-pill); background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14); color: #fff; font-size: var(--text-sm); font-weight: 600; }
.lp-pill b { color: #6EE7A1; font-weight: 800; }

/* Animated 3-step picture */
.lp-pipe { position: relative; display: grid; gap: 14px; max-width: 440px; margin-left: auto; }
.lp-node { display: flex; align-items: center; gap: 16px; padding: 16px 18px; border-radius: var(--r-lg); background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.14); backdrop-filter: blur(6px); }
.lp-node-ic { width: 52px; height: 52px; border-radius: 16px; flex: none; display: grid; place-items: center; color: #fff; }
.lp-node-ic svg { width: 26px; height: 26px; }
.lp-node small { display: block; color: rgba(255,255,255,.6); font-size: var(--text-xs); font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.lp-node strong { color: #fff; font-size: var(--text-lg); }
.lp-node-body { flex: 1; min-width: 0; text-align: left; }
.lp-vals { margin-top: 10px; display: flex; flex-wrap: wrap; gap: 6px; }
.lp-val { font: 700 .78rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; color: #A5F3FC; background: rgba(0,180,216,.16); border: 1px solid rgba(72,202,228,.35); padding: 5px 7px; border-radius: 6px; animation: lpFly 4.8s ease-in-out infinite; }
.lp-val.warn { color: #FECACA; background: rgba(229,72,77,.18); border-color: rgba(248,113,113,.4); }
.lp-val:nth-child(2) { animation-delay: .25s; } .lp-val:nth-child(3) { animation-delay: .5s; } .lp-val:nth-child(4) { animation-delay: .75s; } .lp-val:nth-child(5) { animation-delay: 1s; }
.lp-paper { margin-top: 10px; background: #fff; border-radius: 10px; padding: 10px 12px; display: grid; gap: 6px; }
.lp-line { height: 7px; border-radius: 4px; background: #E4ECF4; position: relative; overflow: hidden; }
.lp-line::after { content: ""; position: absolute; inset: 0; width: 0; background: linear-gradient(90deg, #00B4D8, #2ECC71); animation: lpFill 4.8s ease-in-out infinite; }
.lp-line:nth-child(2) { width: 80%; } .lp-line:nth-child(2)::after { animation-delay: .3s; }
.lp-line:nth-child(3) { width: 60%; } .lp-line:nth-child(3)::after { animation-delay: .6s; }
.lp-bubble { margin-top: 10px; display: inline-flex; align-items: center; gap: 8px; background: #DCF8C6; color: #14532D; font-weight: 700; font-size: .88rem; padding: 8px 12px; border-radius: 12px 12px 12px 3px; animation: lpPop 4.8s ease-in-out infinite; }
.lp-bubble svg { width: 16px; height: 16px; color: #16A34A; flex: none; }
.lp-link { display: flex; justify-content: center; height: 14px; margin: -7px 0; position: relative; z-index: 1; }
.lp-link span { width: 2px; height: 100%; background: repeating-linear-gradient(180deg, #48CAE4 0 4px, transparent 4px 8px); background-size: 2px 8px; animation: lpFlow .6s linear infinite; }
.c-navy { background: #3B5BA9; } .c-cyan { background: var(--cyan); } .c-violet { background: #7C5CFC; } .c-green { background: #22B573; } .c-amber { background: #F59E0B; }

@keyframes lpFly { 0%, 10% { opacity: 0; transform: translateY(6px); } 20%, 85% { opacity: 1; transform: none; } 100% { opacity: 0; } }
@keyframes lpFill { 0%, 25% { width: 0; } 55%, 90% { width: 100%; } 100% { width: 100%; opacity: 0; } }
@keyframes lpPop { 0%, 55% { opacity: 0; transform: scale(.85); } 65%, 92% { opacity: 1; transform: none; } 100% { opacity: 0; } }
@keyframes lpFlow { to { background-position: 0 8px; } }

/* ---------- "Does this happen to you?" checklist ---------- */
.lp-pains { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; max-width: 1040px; margin: 0 auto; }
.lp-pain { position: relative; cursor: pointer; display: flex; gap: 14px; align-items: flex-start; padding: 20px; border-radius: var(--r-lg); background: #fff; border: 2px solid var(--line); box-shadow: var(--e1); transition: border-color .25s, background .25s, transform .25s; }
.lp-pain:hover { transform: translateY(-2px); }
.lp-pain input { position: absolute; opacity: 0; pointer-events: none; }
.lp-pain .box { width: 26px; height: 26px; flex: none; border-radius: 8px; border: 2px solid #C9D6E3; display: grid; place-items: center; color: transparent; transition: all .2s; }
.lp-pain .box svg { width: 16px; height: 16px; }
.lp-pain .txt { font-weight: 700; color: var(--ink); line-height: 1.4; }
.lp-pain .txt small { display: block; font-weight: 500; color: var(--text-soft); margin-top: 4px; font-size: .88rem; }
.lp-pain:has(input:checked) { border-color: #E5484D; background: #FFF6F6; }
.lp-pain input:checked + .box { background: #E5484D; border-color: #E5484D; color: #fff; }
.lp-pain input:focus-visible + .box { outline: 3px solid var(--cyan); outline-offset: 2px; }
.lp-score { max-width: 760px; margin: 30px auto 0; text-align: center; }
.lp-meter { height: 10px; border-radius: 6px; background: #E4ECF4; overflow: hidden; margin: 12px 0 14px; }
.lp-meter span { display: block; height: 100%; width: 0; background: linear-gradient(90deg, #F59E0B, #E5484D); border-radius: 6px; transition: width .5s var(--ease); }
.lp-score-n { font-weight: 800; color: var(--ink); font-size: var(--text-lg); }
.lp-score-msg { color: var(--text-soft); min-height: 1.6em; }
.lp-score-msg b { color: #C62F35; }
.lp-score .btn { margin-top: 16px; }

/* ---------- Hidden loss number ---------- */
.lp-leak { max-width: 980px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 0; border-radius: var(--r-xl); overflow: hidden; box-shadow: var(--e4); }
.lp-leak-in { background: #fff; padding: 38px; }
.lp-leak-in label { display: block; font-weight: 700; color: var(--ink); margin: 0 0 8px; }
.lp-leak-in label + input { margin-bottom: 26px; }
.lp-leak-in output { color: var(--cyan-dark); font-weight: 800; }
.lp-leak-in input[type=range] { width: 100%; accent-color: var(--cyan); height: 28px; cursor: pointer; }
.lp-leak-note { color: var(--text-mute); font-size: .85rem; margin: 0; }
.lp-leak-out { position: relative; background: var(--grad-hero); color: #fff; padding: 38px; display: flex; flex-direction: column; justify-content: center; overflow: hidden; }
.lp-leak-out::after { content: ""; position: absolute; right: -80px; top: -80px; width: 240px; height: 240px; border-radius: 50%; background: rgba(229,72,77,.25); filter: blur(60px); }
.lp-leak-out > * { position: relative; z-index: 1; }
.lp-leak-out .k { color: rgba(255,255,255,.7); font-weight: 700; font-size: var(--text-sm); text-transform: uppercase; letter-spacing: .08em; }
.lp-leak-out .big { font-size: clamp(2.6rem, 6vw, 3.8rem); font-weight: 800; line-height: 1.05; color: #FCA5A5; margin: 8px 0 6px; }
.lp-leak-out .sub { color: rgba(255,255,255,.85); }
.lp-leak-out .sub b { color: #fff; }
.lp-leak-res { transition: filter .6s var(--ease), opacity .6s; }
.lp-leak-out .btn { margin-top: 22px; align-self: flex-start; }
.lp-leak-lock { position: absolute !important; inset: 0; z-index: 2 !important; display: grid; place-items: center; text-align: center; padding: 30px; font-weight: 800; font-size: var(--text-lg); color: #fff; transition: opacity .5s; }
.lp-leak-lock svg { width: 34px; height: 34px; display: block; margin: 0 auto 10px; color: #FCD34D; rotate: 180deg; animation: lpNudge 1.6s ease-in-out infinite; }
.lp-leak:not(.on) .lp-leak-res { filter: blur(10px); opacity: .6; user-select: none; }
.lp-leak.on .lp-leak-lock { opacity: 0; pointer-events: none; }
@keyframes lpNudge { 50% { transform: translateX(8px); } }

/* ---------- Before / After switch ---------- */
.lp-ba { max-width: 980px; margin: 0 auto; }
.lp-ba > input { position: absolute; opacity: 0; pointer-events: none; }
.lp-switch { display: flex; width: max-content; max-width: 100%; margin: 0 auto 34px; padding: 6px; border-radius: var(--r-pill); background: #fff; border: 1px solid var(--line); box-shadow: var(--e2); }
.lp-switch label { cursor: pointer; display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: var(--r-pill); font-weight: 800; color: var(--text-soft); transition: background .3s, color .3s; white-space: nowrap; }
.lp-switch svg { width: 18px; height: 18px; }
#lp-old:checked ~ .lp-switch label[for="lp-old"] { background: #E5484D; color: #fff; }
#lp-new:checked ~ .lp-switch label[for="lp-new"] { background: var(--green-dark); color: #fff; }
#lp-old:focus-visible ~ .lp-switch label[for="lp-old"], #lp-new:focus-visible ~ .lp-switch label[for="lp-new"] { outline: 3px solid var(--cyan); outline-offset: 2px; }
.lp-panel { display: none; border-radius: var(--r-xl); padding: 36px; animation: lpIn .45s var(--ease); }
#lp-old:checked ~ .lp-panels .old, #lp-new:checked ~ .lp-panels .new { display: block; }
.lp-panel.old { background: #FFF6F6; border: 1px solid #F8D7D8; }
.lp-panel.new { background: var(--grad-hero); color: #fff; box-shadow: var(--e4); }
.lp-chain { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; list-style: none; margin: 0; padding: 0; }
.lp-chain li { position: relative; text-align: center; padding: 18px 12px; border-radius: var(--r-md); font-weight: 700; font-size: .93rem; }
.old .lp-chain li { background: #fff; border: 1px dashed #F1B5B7; color: var(--ink); }
.new .lp-chain li { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16); color: #fff; padding: 26px 12px; font-size: var(--text-base); }
.lp-chain .ic { width: 44px; height: 44px; margin: 0 auto 10px; border-radius: 50%; display: grid; place-items: center; }
.lp-chain .ic svg { width: 22px; height: 22px; }
.old .lp-chain .ic { background: #FDECEC; color: #C62F35; }
.new .lp-chain .ic { background: var(--grad-cyan); color: #fff; width: 56px; height: 56px; }
.new .lp-chain .ic svg { width: 26px; height: 26px; }
.lp-chain .n { position: absolute; top: 8px; left: 10px; font-size: var(--text-xs); font-weight: 800; opacity: .5; }
.lp-verdict { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.lp-verdict span { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; border-radius: var(--r-pill); font-weight: 700; font-size: .93rem; }
.lp-verdict svg { width: 17px; height: 17px; }
.old .lp-verdict span { background: #fff; color: #C62F35; border: 1px solid #F8D7D8; }
.new .lp-verdict span { background: rgba(46,204,113,.16); color: #6EE7A1; }
.lp-hint { text-align: center; margin-top: 18px; color: var(--text-soft); font-size: var(--text-sm); }
@keyframes lpIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

/* ---------- Journey ---------- */
.lp-journey { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; position: relative; list-style: none; padding: 0; margin: 0; }
.lp-journey::before { content: ""; position: absolute; top: 44px; left: 12%; right: 12%; height: 3px; background: repeating-linear-gradient(90deg, var(--cyan-light) 0 10px, transparent 10px 18px); }
.lp-step { position: relative; text-align: center; }
.lp-step-ic { position: relative; width: 88px; height: 88px; margin: 0 auto 18px; border-radius: 28px; display: grid; place-items: center; color: #fff; box-shadow: var(--e3); }
.lp-step-ic svg { width: 40px; height: 40px; }
.lp-step-ic .n { position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; border-radius: 50%; background: #fff; color: var(--ink); font-weight: 800; font-size: var(--text-sm); display: grid; place-items: center; box-shadow: var(--e2); }
.lp-step h3 { font-size: var(--text-xl); margin-bottom: 8px; }
.lp-step p { color: var(--text-soft); font-size: .96rem; }
.lp-step .tag { display: inline-block; margin-top: 12px; padding: 6px 12px; border-radius: var(--r-pill); background: var(--cyan-tint); color: var(--cyan-dark); font-weight: 700; font-size: var(--text-sm); }
.lp-idea { margin: 56px auto 0; max-width: 820px; display: flex; gap: 22px; align-items: center; padding: 26px 30px; border-radius: var(--r-xl); background: #FFF9EB; border: 2px dashed #F5C46B; }
.lp-idea-ic { width: 64px; height: 64px; flex: none; border-radius: 50%; background: var(--amber); color: #fff; display: grid; place-items: center; }
.lp-idea-ic svg { width: 30px; height: 30px; }
.lp-idea b { display: block; color: #92400E; font-size: var(--text-lg); margin-bottom: 4px; }
.lp-idea p { color: #7C5A1E; margin: 0; }
.lp-idea p strong { color: #92400E; }

/* ---------- Benefits ---------- */
.lp-ben { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
.lp-ben .card { padding: 28px; }
.lp-ben h3 { font-size: var(--text-xl); margin: 18px 0 8px; }
.lp-ben p { color: var(--text-soft); font-size: .96rem; }

/* ---------- Included strip ---------- */
.lp-incl { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 40px; }
.lp-incl .card { display: flex; gap: 14px; align-items: center; padding: 18px 20px; }
.lp-incl .card:hover { transform: none; }
.lp-incl .feat-ic { flex: none; margin: 0; }
.lp-incl b { display: block; color: var(--ink); }
.lp-incl span { color: var(--text-soft); font-size: .88rem; }

.lp-sticky { display: none; }

@media (prefers-reduced-motion: reduce) {
  .lp-val, .lp-line::after, .lp-bubble, .lp-link span, .lp-leak-lock svg { animation: none; }
  .lp-line::after { width: 100%; }
}
@media (max-width: 1024px) {
  .lp-ben, .lp-pains { grid-template-columns: repeat(2, 1fr); }
  .lp-incl { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 860px) {
  .lp-hero .hero-grid { grid-template-columns: 1fr; gap: 44px; }
  .lp-pipe { margin: 0; max-width: 520px; }
  .lp-journey { grid-template-columns: repeat(2, 1fr); row-gap: 40px; }
  .lp-journey::before { display: none; }
  .lp-chain { grid-template-columns: repeat(2, 1fr); }
  .lp-leak { grid-template-columns: 1fr; }
  .lp-leak-lock svg { rotate: -90deg; }
}
@media (max-width: 680px) {
  .lp-hero { padding-top: calc(var(--nav-h) + 36px); padding-bottom: 56px; }
  .lp-hero .hero-actions .btn { flex: 1 1 100%; }
  .lp-switch label { padding: 10px 14px; font-size: var(--text-sm); }
  .lp-panel { padding: 22px 16px; }
  .lp-ben, .lp-journey, .lp-pains, .lp-incl { grid-template-columns: 1fr; }
  .lp-idea { flex-direction: column; text-align: center; padding: 24px 20px; }
  .lp-leak-in, .lp-leak-out { padding: 28px 22px; }
  .lp-sticky { display: flex; gap: 10px; position: fixed; left: 0; right: 0; bottom: 0; z-index: 950; padding: 10px 12px calc(10px + env(safe-area-inset-bottom)); background: rgba(255,255,255,.97); border-top: 1px solid var(--line); box-shadow: 0 -8px 24px rgba(15,27,51,.08); }
  .lp-sticky .btn { flex: 1; padding: 14px 10px; font-size: var(--text-base); }
  .lp-sticky .btn-wa { flex: 0 0 52px; padding: 0; background: #25D366; color: #fff; }
  .lp-sticky .btn-wa svg { width: 24px; height: 24px; }
  .wa-float { display: none; }
  .to-top { bottom: 86px !important; }
  .footer { padding-bottom: 90px; }
}
</style>
HTML;
}

/** Section heading block. */
function lp_head(string $eyebrow, string $title, string $lead = ''): void
{
    ?>
    <div class="sec-head reveal">
      <span class="eyebrow"><?= h($eyebrow) ?></span>
      <h2 class="h-sec"><?= $title ?></h2>
<?php if ($lead !== ''): ?>
      <p class="lead"><?= $lead ?></p>
<?php endif; ?>
    </div>
    <?php
}

/**
 * Hero with the animated 3-step picture.
 * $h: eyebrow, title (HTML), sub (HTML), wa, pills [[bold, text]], aria, nodes [[icon, colour, small, strong, extraHtml]]
 */
function lp_hero(array $h): void
{
    ?>
<header class="hero lp-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="hero-grid">
      <div class="reveal">
        <span class="eyebrow"><?= h($h['eyebrow']) ?></span>
        <h1><?= $h['title'] ?></h1>
        <p class="lp-sub"><?= $h['sub'] ?></p>
        <div class="hero-actions">
          <a href="#demo" class="btn btn-primary btn-lg"><?= lp_ic('play') ?> Book a Free Demo</a>
          <a href="#check" class="btn btn-ghost-light btn-lg"><?= lp_ic('search') ?> Take the 30-sec check</a>
        </div>
        <div class="lp-pills">
<?php foreach ($h['pills'] as [$b, $t]): ?>
          <span class="lp-pill"><b><?= h($b) ?></b> <?= h($t) ?></span>
<?php endforeach; ?>
        </div>
      </div>
      <div class="lp-pipe reveal d1" aria-label="<?= h($h['aria']) ?>">
<?php foreach ($h['nodes'] as $i => [$ic, $c, $small, $strong, $extra]): ?>
<?php if ($i): ?>
        <div class="lp-link"><span></span></div>
<?php endif; ?>
        <div class="lp-node">
          <span class="lp-node-ic <?= $c ?>"><?= lp_ic($ic) ?></span>
          <div class="lp-node-body"><small><?= ($i + 1) . ' · ' . h($small) ?></small><strong><?= h($strong) ?></strong><?= $extra ?></div>
        </div>
<?php endforeach; ?>
      </div>
    </div>
  </div>
</header>
<?= client_trust_strip($GLOBALS['page'] ?? '') ?>
    <?php
}

/**
 * "Does this happen to you?" — tick the problems, see a verdict.
 * $pains: [[title, small]]; $verdicts: [0 => msg, 2 => msg, 4 => msg] (min ticks => HTML message)
 */
function lp_pains(string $title, string $lead, array $pains, array $verdicts): void
{
    ?>
<section class="section section--soft" id="check">
  <div class="container">
    <?php lp_head('30-Second Check', $title, $lead); ?>
    <div class="lp-pains" data-verdicts='<?= h(json_encode($verdicts)) ?>'>
<?php foreach ($pains as $i => [$t, $s]): ?>
      <label class="lp-pain reveal<?= $i % 3 ? ' d' . ($i % 3) : '' ?>">
        <input type="checkbox"><span class="box"><?= lp_ic('check') ?></span>
        <span class="txt"><?= h($t) ?><small><?= h($s) ?></small></span>
      </label>
<?php endforeach; ?>
    </div>
    <div class="lp-score reveal" aria-live="polite">
      <div class="lp-score-n">You ticked <span class="lp-ticks">0</span> of <?= count($pains) ?></div>
      <div class="lp-meter"><span></span></div>
      <div class="lp-score-msg"><?= $verdicts[0] ?></div>
      <a href="#leak" class="btn btn-primary">See what it's costing you <?= lp_ic('arrow') ?></a>
    </div>
  </div>
</section>
    <?php
}

/**
 * The hidden "what is it costing you" number. Revealed when a slider moves.
 * $sliders: [[id, label, min, max, step, value, prefix, suffix]]
 * The page defines window.lpCalc(values) → {big, sub} (HTML strings).
 */
function lp_leak(string $title, string $lead, array $sliders, string $kicker, string $note): void
{
    ?>
<section class="section" id="leak">
  <div class="container">
    <?php lp_head('The Hidden Number', $title, $lead); ?>
    <div class="lp-leak reveal">
      <div class="lp-leak-in">
<?php foreach ($sliders as [$id, $label, $min, $max, $step, $val, $pre, $suf]): ?>
        <label for="<?= $id ?>"><?= h($label) ?>: <output data-pre="<?= h($pre) ?>" data-suf="<?= h($suf) ?>"><?= h($pre . number_format($val) . $suf) ?></output></label>
        <input type="range" id="<?= $id ?>" min="<?= $min ?>" max="<?= $max ?>" step="<?= $step ?>" value="<?= $val ?>">
<?php endforeach; ?>
        <p class="lp-leak-note"><?= $note ?></p>
      </div>
      <div class="lp-leak-out">
        <div class="lp-leak-lock"><div><?= lp_ic('arrow') ?>Move a slider to reveal your number</div></div>
        <div class="lp-leak-res">
          <div class="k"><?= h($kicker) ?></div>
          <div class="big">—</div>
          <div class="sub"></div>
        </div>
        <a href="#demo" class="btn btn-primary">Stop the leak — book a free demo</a>
      </div>
    </div>
  </div>
</section>
    <?php
}

/** Before / after switch. $old / $new: [[icon, text]]; verdicts: [[icon, text]] */
function lp_before_after(string $title, string $lead, array $old, array $oldV, array $new, array $newV): void
{
    $chain = function (array $rows): void {
        foreach ($rows as $i => [$ic, $t]) {
            echo '<li><span class="n">' . ($i + 1) . '</span><span class="ic">' . lp_ic($ic) . '</span>' . h($t) . '</li>';
        }
    };
    $verdict = function (array $rows): void {
        foreach ($rows as [$ic, $t]) {
            echo '<span>' . lp_ic($ic) . h($t) . '</span>';
        }
    };
    ?>
<section class="section section--soft" id="before-after">
  <div class="container">
    <?php lp_head('Before vs After', $title, $lead); ?>
    <div class="lp-ba reveal">
      <input type="radio" name="lp-mode" id="lp-old" checked>
      <input type="radio" name="lp-mode" id="lp-new">
      <div class="lp-switch" role="presentation">
        <label for="lp-old"><?= lp_ic('x') ?> The old way</label>
        <label for="lp-new"><?= lp_ic('check') ?> With HealthO Pro</label>
      </div>
      <div class="lp-panels">
        <div class="lp-panel old"><ol class="lp-chain"><?php $chain($old); ?></ol><div class="lp-verdict"><?php $verdict($oldV); ?></div></div>
        <div class="lp-panel new"><ol class="lp-chain"><?php $chain($new); ?></ol><div class="lp-verdict"><?php $verdict($newV); ?></div></div>
      </div>
      <p class="lp-hint">Tap the switch above to compare</p>
    </div>
  </div>
</section>
    <?php
}

/** 4-step journey + "think of it like this" analogy. $steps: [[icon, colour, title, text, tag]] */
function lp_journey(string $title, array $steps, string $idea): void
{
    ?>
<section class="section" id="how">
  <div class="container">
    <?php lp_head('How It Works', $title); ?>
    <ol class="lp-journey">
<?php foreach ($steps as $i => [$ic, $c, $t, $d, $tag]): ?>
      <li class="lp-step reveal<?= $i ? ' d' . $i : '' ?>">
        <div class="lp-step-ic <?= $c ?>"><?= lp_ic($ic) ?><span class="n"><?= $i + 1 ?></span></div>
        <h3><?= h($t) ?></h3>
        <p><?= h($d) ?></p>
        <span class="tag"><?= h($tag) ?></span>
      </li>
<?php endforeach; ?>
    </ol>
    <div class="lp-idea reveal">
      <span class="lp-idea-ic"><?= lp_ic('sparkle') ?></span>
      <div><b>Think of it like this</b><p><?= $idea ?></p></div>
    </div>
  </div>
</section>
    <?php
}

/** Benefit tiles + "included" strip. $items: [[icon, bg, title, text]] */
function lp_benefits(string $title, string $lead, array $items): void
{
    $incl = [
        ['tool',   'bg-cyan',   'Free setup',        'We install & configure'],
        ['users',  'bg-violet', 'Free training',     'Your team learns in a day'],
        ['phone',  'bg-green',  '24×7 help',         'Call or WhatsApp us'],
        ['lock',   'bg-navy',   'Secure cloud',      'Role access & audit trail'],
    ];
    ?>
<section class="section section--tint" id="features">
  <div class="container">
    <?php lp_head('What You Get', $title, $lead); ?>
    <div class="lp-ben">
<?php foreach ($items as $i => [$ic, $bg, $t, $d]): ?>
      <div class="card reveal<?= $i % 3 ? ' d' . ($i % 3) : '' ?>">
        <span class="feat-ic <?= $bg ?>"><?= lp_ic($ic) ?></span>
        <h3><?= h($t) ?></h3>
        <p><?= h($d) ?></p>
      </div>
<?php endforeach; ?>
    </div>
    <div class="lp-incl">
<?php foreach ($incl as $i => [$ic, $bg, $t, $d]): ?>
      <div class="card reveal<?= $i % 2 ? ' d1' : '' ?>"><span class="feat-ic <?= $bg ?>"><?= lp_ic($ic) ?></span><div><b><?= h($t) ?></b><span><?= h($d) ?></span></div></div>
<?php endforeach; ?>
    </div>
  </div>
</section>
    <?php
}

/** Navy proof band. $stats: [[count, prefix, suffix, display, label]] */
function lp_stats(string $title, array $stats): void
{
    ?>
<section class="section section--navy">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">The Result</span><h2 class="h-sec"><?= $title ?></h2></div>
    <div class="stats">
<?php foreach ($stats as $i => [$n, $pre, $suf, $show, $l]): ?>
      <div class="stat reveal<?= $i ? ' d' . $i : '' ?>"><div class="n"><span data-count="<?= $n ?>"<?= $pre !== '' ? ' data-prefix="' . h($pre) . '"' : '' ?> data-suffix="<?= h($suf) ?>"><?= h($show) ?></span></div><div class="l"><?= h($l) ?></div></div>
<?php endforeach; ?>
    </div>
  </div>
</section>
    <?php
}

/** Final CTA + form + mobile sticky bar. */
function lp_demo(array $d): void
{
    ?>
<section class="section" id="demo">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">Free Demo</span>
        <h2 class="h-sec"><?= $d['title'] ?></h2>
        <p class="lead" style="margin:16px 0 28px;"><?= $d['lead'] ?></p>
        <?= client_proof($GLOBALS['page'] ?? '') ?>
        <?php contact_strip(); ?>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;"><?= h($d['form_title']) ?></h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">We'll call you within one business day. No obligation.</p>
        <?php lead_form($d['form']); ?>
      </div>
    </div>
  </div>
</section>

<div class="lp-sticky">
  <a href="#demo" class="btn btn-primary">Book Free Demo</a>
  <a href="<?= $d['wa'] ?>" target="_blank" rel="noopener" class="btn btn-wa" aria-label="Chat on WhatsApp"><?= lp_ic('wa') ?></a>
</div>
    <?php
}

/** Behaviour for the checklist, hidden number and before/after switch. */
function lp_script(): void
{
    ?>
<script>
(function () {
  var fmt = function (n) { return Math.round(n).toLocaleString('en-IN'); };

  // Checklist: count ticks, fill the meter, pick the verdict.
  var pains = document.querySelector('.lp-pains');
  if (pains) {
    var boxes = pains.querySelectorAll('input'), verdicts = JSON.parse(pains.dataset.verdicts),
        n = document.querySelector('.lp-ticks'), bar = document.querySelector('.lp-meter span'), msg = document.querySelector('.lp-score-msg');
    pains.addEventListener('change', function () {
      var c = pains.querySelectorAll('input:checked').length, best = 0;
      Object.keys(verdicts).forEach(function (k) { if (c >= +k) best = Math.max(best, +k); });
      n.textContent = c; bar.style.width = (c / boxes.length * 100) + '%'; msg.innerHTML = verdicts[best];
    });
  }

  // Hidden number: blurred until the visitor moves a slider.
  var leak = document.querySelector('.lp-leak');
  if (leak && window.lpCalc) {
    var ranges = leak.querySelectorAll('input[type=range]'), big = leak.querySelector('.big'), sub = leak.querySelector('.sub');
    var run = function () {
      var v = {};
      ranges.forEach(function (r) {
        v[r.id] = +r.value;
        var o = leak.querySelector('label[for="' + r.id + '"] output');
        o.textContent = o.dataset.pre + fmt(+r.value) + o.dataset.suf;
      });
      var res = window.lpCalc(v, fmt); big.innerHTML = res.big; sub.innerHTML = res.sub;
    };
    ranges.forEach(function (r) { r.addEventListener('input', function () { leak.classList.add('on'); run(); }); });
    run();
  }

  // Before/after: flip to "With HealthO Pro" once, a moment after it is seen, unless the visitor already chose.
  var ba = document.querySelector('.lp-ba'), touched = false;
  if (ba && 'IntersectionObserver' in window) {
    ba.addEventListener('change', function () { touched = true; });
    var io = new IntersectionObserver(function (es) {
      if (!es[0].isIntersecting) return;
      io.disconnect();
      setTimeout(function () { if (!touched) document.getElementById('lp-new').checked = true; }, 3500);
    }, { rootMargin: '0px 0px -40% 0px' });
    io.observe(ba);
  }
})();
</script>
    <?php
}
