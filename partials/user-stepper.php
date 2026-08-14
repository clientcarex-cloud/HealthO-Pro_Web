<?php
/**
 * Team-size controls. Shared by the product pages' pricing block and the
 * pricing page's sticky bar; script.js binds #globalUsers / #userSlider.
 * The two pieces are separate because the pages nest them differently.
 */
require_once __DIR__ . '/site.php';

const USERS_MIN     = 5;
const USERS_MAX     = 100;
const USERS_DEFAULT = 5;

function user_stepper(): void
{
    ?>
    <div class="user-scaler-count">
      <button type="button" class="us-step" data-step="-1" aria-label="Decrease users">−</button>
      <input type="number" id="globalUsers" min="<?= USERS_MIN ?>" max="<?= USERS_MAX ?>" value="<?= USERS_DEFAULT ?>" aria-label="Number of users">
      <span class="us-unit">users</span>
      <button type="button" class="us-step" data-step="1" aria-label="Increase users">+</button>
    </div>
    <?php
}

function user_slider(): void
{
    ?>
    <input type="range" id="userSlider" class="user-range" min="<?= USERS_MIN ?>" max="<?= USERS_MAX ?>" value="<?= USERS_DEFAULT ?>" step="1" aria-label="Number of users slider">
    <?php
}

/**
 * "Download brochure" trigger. script.js builds the sheet from the plans currently
 * on screen and saves it straight to a PDF file; the contact details it carries are
 * passed down from here so this file stays the single source for them. The sticky
 * pricing bar passes a short label — the full one tips that single row of controls
 * onto a second line.
 */
function brochure_button(string $label = 'Download Brochure'): void
{
    ?>
    <button type="button" class="pb-brochure js-brochure"
            aria-label="Download the pricing brochure as a PDF"
            data-phone="<?= h(PHONE_SALES) ?>"
            data-email="sales@healtho.pro"
            data-wa="<?= h(WHATSAPP_URL) ?>"
            data-site="<?= h(SITE_URL) ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      <span><?= h($label) ?></span><span class="pb-brochure-tag">PDF</span>
    </button>
    <?php
}
