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
