<?php
/**
 * The pricing block on a single-product page (HIMS / LIMS / CIMS).
 * The plan cards themselves are filled in by script.js from api/plans.php.
 */
require_once __DIR__ . '/user-stepper.php';

function pricing_section(string $slug): void
{
    $label = PRODUCTS[$slug]['short'];
    ?>
<section class="section section--soft" id="pricing">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Pricing</span>
      <h2 class="h-sec"><?= h($label) ?> plans &amp; pricing</h2>
      <p class="lead">Pay 6-monthly or yearly and save. Set your team size and we'll highlight your best-fit plan.</p>
    </div>
    <div class="billing-toggle-wrap">
      <div class="billing-toggle">
        <button data-cycle="half">6 Months</button>
        <button data-cycle="year" class="active">Yearly<span class="save-badge">Save 20%</span></button>
      </div>
    </div>
    <div class="user-scaler reveal">
      <div class="user-scaler-head">
        <div>
          <h3>How many users do you need?</h3>
        </div>
        <?php user_stepper(); ?>
      </div>
      <?php user_slider(); ?>
      <div class="user-scaler-presets">
        <button type="button" data-users="5">Solo · 5</button>
        <button type="button" data-users="10">Small · 10</button>
        <button type="button" data-users="25">Growing · 25</button>
        <button type="button" data-users="50">Large · 50</button>
      </div>
      <div class="user-scaler-rec">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 15.09 8.26 22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <!-- Rewritten by script.js on every change; this matches USERS_DEFAULT. -->
        <span class="user-scaler-rec-text">For <strong><?= USERS_DEFAULT ?> users</strong>, we recommend the <strong>Startup</strong> plan.</span>
      </div>
    </div>
    <div class="price-panel active" id="panel-<?= h($slug) ?>">
      <div class="dynamic-plans" data-group="<?= h($slug) ?>"></div>
    </div>
  </div>
</section>
    <?php
}
