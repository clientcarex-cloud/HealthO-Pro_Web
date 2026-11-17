<?php
$page        = 'maintenance';
$title       = 'Updates & Maintenance | HealthO Pro';
$description = 'HealthO Pro planned maintenance, emergency work and software release notes, with our maintenance commitments under the SLA. All times in IST.';
require __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/legal-related.php';

$feed     = legal_live_notices();
$notices  = $feed['notices'] ?? [];
$now      = [];
$upcoming = [];
$releases = [];
$recent   = [];
foreach ($notices as $n) {
    if ($n['status'] === 'in_progress') {
        $now[] = $n;
    } elseif (in_array($n['status'], ['scheduled', 'upcoming'], true)) {
        $upcoming[] = $n;
    } elseif ($n['type'] === 'software_update') {
        $releases[] = $n;
    } else {
        $recent[] = $n;
    }
}
usort($upcoming, function ($a, $b) { return strcmp($a['starts_at'], $b['starts_at']); });

$h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$card = function (array $n) use ($h) {
    $tone = ['in_progress' => 'red', 'scheduled' => 'amber', 'upcoming' => 'amber', 'completed' => 'green', 'effective' => 'blue'][$n['status']] ?? 'blue';
    $label = ['in_progress' => 'In progress', 'scheduled' => 'Scheduled', 'upcoming' => 'Upcoming', 'completed' => 'Completed', 'effective' => 'In effect'][$n['status']] ?? ucfirst($n['status']);
    ?>
    <article class="mt-card mt-<?= $tone ?>" id="notice-<?= (int) $n['id'] ?>">
      <header>
        <span class="mt-type"><?= $h($n['type_label']) ?><?= $n['release_version'] ? ' · ' . $h($n['release_version']) : '' ?></span>
        <span class="mt-status"><?= $h($label) ?></span>
      </header>
      <h3><?= $h($n['title']) ?></h3>
      <?php if ($n['summary'] !== ''): ?><p class="mt-sum"><?= $h($n['summary']) ?></p><?php endif; ?>
      <dl class="mt-dl">
        <?php if ($n['timed']): ?>
          <dt>When</dt><dd><?= $h($n['window']) ?></dd>
          <?php if ($n['actual_end_at']): ?><dt>Finished</dt><dd><?= $h(legal_live_ist($n['actual_end_at'])) ?> IST</dd><?php endif; ?>
        <?php else: ?>
          <dt>Takes effect</dt><dd><?= $h(legal_live_ist($n['starts_at'], 'j F Y')) ?></dd>
        <?php endif; ?>
        <dt>Impact</dt><dd><?= $h($n['impact_label']) ?></dd>
        <?php if ($n['products']): ?><dt>Affects</dt><dd><?= $h(implode(', ', $n['products'])) ?></dd><?php endif; ?>
        <?php if ($n['published_at']): ?><dt>Announced</dt><dd><?= $h(legal_live_ist($n['published_at'])) ?> IST</dd><?php endif; ?>
      </dl>
      <?php if ($n['details'] !== ''): ?>
        <details class="mt-details"><summary><?= $n['type'] === 'software_update' ? 'Release notes' : 'Details' ?></summary><div><?= nl2br($h($n['details'])) ?></div></details>
      <?php endif; ?>
      <?php if ($n['updates']): ?>
        <ol class="mt-updates">
          <?php foreach ($n['updates'] as $u): ?>
            <li><time><?= $h(legal_live_ist($u['at'], 'j M, g:i A')) ?></time><?= $h($u['message']) ?></li>
          <?php endforeach; ?>
        </ol>
      <?php endif; ?>
    </article>
    <?php
};
?>
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Updates &amp; Maintenance</div>
    <h1>Updates &amp; <span class="text-grad">Maintenance</span></h1>
    <p>Planned maintenance, emergency work and software releases for the HealthO Pro platform. All times are India Standard Time (IST).</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="prose legal-doc mt-wrap">

      <?php if ($feed === null): ?>
        <div class="mt-banner mt-grey"><b>Live notices are temporarily unavailable.</b> Signed-in customers still see every notice inside HealthO Pro. For urgent help call +91 97007 10055.</div>
      <?php elseif ($now): ?>
        <div class="mt-banner mt-red"><b>Maintenance in progress.</b> <?= $h($now[0]['title']) ?><?= $now[0]['window'] ? ' · ' . $h($now[0]['window']) : '' ?></div>
      <?php else: ?>
        <div class="mt-banner mt-green"><b>No maintenance in progress.</b> <?= $upcoming ? 'The next planned work is listed below.' : 'Nothing is scheduled right now.' ?></div>
      <?php endif; ?>

      <?php if ($now): ?>
        <h2 id="now"><span class="legal-n">●</span>Happening now</h2>
        <?php foreach ($now as $n) { $card($n); } ?>
      <?php endif; ?>

      <h2 id="upcoming"><span class="legal-n">01</span>Upcoming</h2>
      <?php if ($upcoming): foreach ($upcoming as $n) { $card($n); } else: ?>
        <p class="mt-empty">No upcoming maintenance or changes have been announced.</p>
      <?php endif; ?>

      <h2 id="releases"><span class="legal-n">02</span>Release notes</h2>
      <?php if ($releases): foreach ($releases as $n) { $card($n); } else: ?>
        <p class="mt-empty">No releases published in the last 90 days.</p>
      <?php endif; ?>

      <?php if ($recent): ?>
        <h2 id="history"><span class="legal-n">03</span>Recent maintenance &amp; notices</h2>
        <?php foreach ($recent as $n) { $card($n); } ?>
      <?php endif; ?>

      <h2 id="commitments"><span class="legal-n">§</span>Our maintenance commitments</h2>
      <p>These are the notice periods we promise in our <a href="/sla-and-support-services">SLA &amp; Support Services</a>, <a href="/subscription-terms">Platform Subscription Terms</a> and <a href="/data-protection-addendum">Data Protection Addendum</a>, part of the <a href="/saas-agreement">HealthO Pro SaaS Agreement</a>. Our notice system checks every announcement against them before it is published.</p>
      <div class="legal-cards legal-cards--3">
        <div class="legal-card"><span class="legal-ic">48h</span><h4>Planned maintenance</h4><p>Announced at least 48 hours ahead in-app and by email, normally between 11:00 PM and 6:00 AM IST.</p></div>
        <div class="legal-card"><span class="legal-ic">!</span><h4>Emergency work</h4><p>Urgent work such as a security fix may happen with shorter notice. We keep disruption as short as possible and post updates.</p></div>
        <div class="legal-card"><span class="legal-ic">↑</span><h4>Software updates</h4><p>Regular updates with fixes, security improvements and new features at no extra cost. Updates that affect availability follow the 48-hour rule.</p></div>
        <div class="legal-card"><span class="legal-ic">60d</span><h4>Feature retirement</h4><p>At least 60 days' notice before a significant feature is retired.</p></div>
        <div class="legal-card"><span class="legal-ic">15d</span><h4>Sub-processors</h4><p>At least 15 days' notice before adding or replacing a sub-processor, with the right to object on reasonable data-protection grounds.</p></div>
        <div class="legal-card"><span class="legal-ic">%</span><h4>Uptime</h4><p>We target 99.5% monthly uptime. Properly announced maintenance is excluded, as set out in the SLA.</p></div>
      </div>
      <p>Questions about a notice? Email <a href="mailto:support@healtho.pro">support@healtho.pro</a> or call <a href="tel:+919700710055">+91 97007 10055</a>.</p>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
