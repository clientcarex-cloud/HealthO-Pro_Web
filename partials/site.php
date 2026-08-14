<?php
/**
 * Site-wide constants and helpers shared by every page and partial.
 */

const SITE_URL   = 'https://healtho.pro';
const GA_ID      = 'G-1BJ76YS8W8';
const CLARITY_ID = 'xc1duel6z4';

/** Bump to bust the browser cache for styles.css / script.js. */
const ASSET_VER = '24';

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
