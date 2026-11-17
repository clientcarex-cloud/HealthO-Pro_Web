<?php
$page        = 'saas-agreement';
$title       = 'HealthO Pro SaaS Agreement | HealthO Pro';
$description = 'The HealthO Pro SaaS Agreement: one agreement that brings together our Subscription Terms, Data Protection Addendum, Data Processing Agreement, SLA, Terms & Conditions and Privacy Policy, with the current version of each.';
require __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/legal-related.php';

$agreement = legal_live_agreement();
$frame     = legal_framework_docs();
$live_docs = [];
foreach (($agreement['documents'] ?? []) as $d) {
    $live_docs[$d['slug']] = $d;
}
?>
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>SaaS Agreement</div>
    <h1>HealthO Pro <span class="text-grad">SaaS Agreement</span></h1>
    <p>One agreement. Six documents. Accepted once by every HealthO Pro customer, and again whenever we publish a new release.</p>
    <p class="legal-meta">Issued by Healthocare Private Limited · CIN U62090TS2023PTC180399<?php if ($agreement): ?> · <span class="legal-live">Release <?= htmlspecialchars($agreement['version']) ?> · Published <?= htmlspecialchars(legal_live_date(substr((string) $agreement['published_at'], 0, 10))) ?></span><?php endif; ?></p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="prose legal-doc">
      <h2 class="legal-glance-title">What this agreement is</h2>
      <p>The HealthO Pro SaaS Agreement is the contract between <strong>Healthocare Private Limited</strong> ("HealthO", "we", "us") and the healthcare provider that subscribes to HealthO Pro (the "Customer", "you"). It is made up of the six documents listed below. Each one is incorporated into the others, and accepting the agreement means accepting all of them, at the versions listed, as they apply to you.</p>

      <div class="legal-cards legal-cards--3">
        <div class="legal-card"><span class="legal-ic">1</span><h4>One acceptance</h4><p>You accept the agreement as a whole: at sign-up, and by your account superadmin for each new release.</p></div>
        <div class="legal-card"><span class="legal-ic">2</span><h4>Versioned</h4><p>Every document carries a version, a "Last updated" date and an effective date, with a public version history.</p></div>
        <div class="legal-card"><span class="legal-ic">3</span><h4>On record</h4><p>We keep a record of each acceptance: who accepted, when, and the exact document versions accepted.</p></div>
      </div>

      <h2 id="documents"><span class="legal-n">01</span>Documents in this agreement</h2>
      <?php if ($agreement): ?>
        <p>Release <strong><?= htmlspecialchars($agreement['version']) ?></strong> includes the following versions, listed in order of precedence:</p>
      <?php else: ?>
        <p>The agreement includes the following documents, listed in order of precedence. The current version of each is the one published at its link.</p>
      <?php endif; ?>
      <div class="legal-history-wrap">
        <table class="legal-history-table legal-agreement-table">
          <thead><tr><th>#</th><th>Document</th><?php if ($agreement): ?><th>Version</th><th>Last updated</th><th>Effective</th><?php endif; ?></tr></thead>
          <tbody>
          <?php $n = 0; foreach ($frame as $slug => $f): $n++; $d = $live_docs[$slug] ?? null; ?>
            <tr>
              <td><?= $n ?></td>
              <td><a href="/<?= $slug ?>"><b><?= htmlspecialchars($f['title']) ?></b></a><br><small><?= htmlspecialchars($f['desc']) ?></small></td>
              <?php if ($agreement): ?>
                <td><?= $d ? htmlspecialchars((string) $d['version']) : '—' ?></td>
                <td><?= $d ? htmlspecialchars(legal_live_date($d['last_updated'])) : '—' ?></td>
                <td><?= $d ? htmlspecialchars(legal_live_date($d['effective_date'])) : '—' ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php if ($agreement && trim((string) $agreement['summary']) !== ''): ?>
        <h2 id="changes"><span class="legal-n">02</span>What changed in release <?= htmlspecialchars($agreement['version']) ?></h2>
        <div class="legal-note legal-note--blue"><?= nl2br(htmlspecialchars(trim($agreement['summary']))) ?></div>
      <?php endif; ?>

      <h2 id="acceptance"><span class="legal-n">03</span>How acceptance works</h2>
      <ol>
        <li><strong>At sign-up.</strong> When you create a HealthO Pro account, you accept the release that is current at that moment.</li>
        <li><strong>New releases.</strong> When we make a material change to any document, we publish a new release of this agreement, list the new versions and explain what changed.</li>
        <li><strong>Your superadmin accepts.</strong> The superadmin of your HealthO Pro account sees a notice with the deadline and accepts on behalf of your organisation, after reading each document. We may also email your primary contact.</li>
        <li><strong>Deadline.</strong> Each release states an acceptance deadline. After it, your superadmin may be asked to accept before continuing to use the platform.</li>
        <li><strong>Records.</strong> We record the name, email and designation of the person who accepted, the date and time, the IP address and a fingerprint of each document version. You can ask us for a copy at any time.</li>
      </ol>
      <p>A customer may also accept by signing a printed copy. Signed Order Forms and service agreements take precedence over these documents where they conflict.</p>

      <h2 id="precedence"><span class="legal-n">04</span>Order of precedence</h2>
      <p>If two documents conflict on the same subject, the first one in this list prevails:</p>
      <ol>
        <li>Your signed Order Form or service agreement</li>
        <?php foreach ($frame as $slug => $f): ?>
          <li><?= htmlspecialchars($f['title']) ?><?= $slug === 'data-protection-addendum' ? ' (on data-protection matters)' : '' ?></li>
        <?php endforeach; ?>
      </ol>

      <?php if ($agreement && !empty($agreement['bundle_hash'])): ?>
        <p class="legal-live legal-history-note">Release fingerprint (SHA-256): <code style="word-break:break-all"><?= htmlspecialchars($agreement['bundle_hash']) ?></code></p>
      <?php endif; ?>

      <h2 id="contact"><span class="legal-n">05</span>Questions</h2>
      <p>For questions about this agreement, or for a copy of your organisation's acceptance record, write to <a href="mailto:legal@healtho.pro">legal@healtho.pro</a>.</p>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
