<?php
/**
 * "Sales / Email" pair shown beside the lead form in every CTA section.
 */
require_once __DIR__ . '/site.php';

function contact_strip(bool $spaced = false): void
{
    ?>
    <div class="contact-strip<?= $spaced ? ' contact-strip--spaced' : '' ?>">
      <div><div class="cs-label">Sales</div><a href="tel:<?= str_replace(' ', '', PHONE_SALES) ?>" class="cs-value"><?= PHONE_SALES ?></a></div>
      <div><div class="cs-label">Email</div><a href="mailto:sales@healtho.pro" class="cs-value">sales@healtho.pro</a></div>
    </div>
    <?php
}
