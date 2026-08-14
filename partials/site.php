<?php
/**
 * Site-wide constants and helpers shared by every page and partial.
 */

const SITE_URL   = 'https://healtho.pro';
const GA_ID      = 'G-1BJ76YS8W8';
const CLARITY_ID = 'xc1duel6z4';

/** Bump to bust the browser cache for styles.css / script.js. */
const ASSET_VER = '25';

/** Only Plus Jakarta Sans is used by the stylesheet; only these weights appear in it. */
const FONT_CSS = 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap';

const PHONE_SALES   = '+91 97007 30044';
const PHONE_SUPPORT = '+91 97007 10055';
const WHATSAPP_URL  = 'https://wa.me/919700730044';

/** slug => nav dropdown labels. Drives the Solutions menu in the navbar. */
const PRODUCTS = [
    'hims' => ['short' => 'HIMS',       'long' => 'Hospital Information Management'],
    'lims' => ['short' => 'LIMS',       'long' => 'Laboratory Information Management'],
    'cims' => ['short' => 'CIMS',       'long' => 'Clinic Information Management'],
    'ris'  => ['short' => 'RIS / RIMS', 'long' => 'Radiology Information System'],
];

/** Escape for HTML output. */
function h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/**
 * Pretty job URLs (/careers/{slug}) need the rewrite in .htaccess. This domain
 * also serves the CRM, which answers anything the website's rules do not catch,
 * so a missing rewrite shows the CRM's 404 rather than the opening. Set this to
 * false to fall back to /career.php?j={slug}, which needs no server config.
 */
const CAREER_PRETTY_URLS = true;

/**
 * The CRM's embeddable careers widget. Loading this needs no key and no proxy,
 * so it is what keeps /careers showing live openings even when the server-side
 * API credentials are missing — and it is the same snippet that can be pasted
 * on any other site.
 */
const CAREERS_EMBED_BASE = 'https://healtho.pro/careers/careers_embed';
const CAREERS_EMBED_JS   = CAREERS_EMBED_BASE . '/js';

/** Canonical path of one opening. Used for links, canonicals and structured data. */
function career_url(string $slug, bool $absolute = false): string
{
    $path = CAREER_PRETTY_URLS
        ? '/careers/' . rawurlencode($slug)
        : '/career.php?j=' . rawurlencode($slug);

    return $absolute ? SITE_URL . $path : $path;
}
