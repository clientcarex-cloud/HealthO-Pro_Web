<?php
/**
 * Legal framework cross-references.
 *
 * Every legal page links to every other legal page so a reader of any one
 * document is told the others exist, that they form one framework, and how
 * conflicts between them are resolved.
 *
 *   legal_framework_notice($page)  short notice near the top of the page
 *   legal_framework_related($page) full "Related legal documents" section
 *
 * $page is the current page slug (the same $page every legal page sets).
 * Keep this list in sync with the footer "Legal" column in partials/foot.php
 * and with the documents seeded in the CCX Legal module (modules/ccx_legal).
 */

require_once __DIR__ . '/legal-live.php';

function legal_framework_docs(): array
{
    // Order here = order of precedence (see legal_framework_related()).
    return [
        'data-protection-addendum' => [
            'title' => 'Data Protection Addendum',
            'desc'  => 'Our full, binding data-protection commitments as Data Processor of patient and practice data: security, sub-processors, breach notification, cross-border transfers, return and deletion.',
        ],
        'subscription-terms' => [
            'title' => 'Platform Subscription Terms',
            'desc'  => 'The contract for subscribing to and using the HealthO Pro platform: subscription scope, fees and payment, renewal, acceptable use, liability, termination and dispute resolution.',
        ],
        'data-processing-agreement' => [
            'title' => 'Data Processing Agreement',
            'desc'  => 'A summary of how we process personal and health data on your behalf under the DPDP Act, 2023, including roles, instructions and processing details.',
        ],
        'sla-and-support-services' => [
            'title' => 'SLA & Support Services',
            'desc'  => 'Uptime commitment, support hours and channels, incident severity levels, response and resolution times, maintenance windows and service credits.',
        ],
        'terms-conditions' => [
            'title' => 'Terms & Conditions',
            'desc'  => 'The general terms for using our website, products and services, including intellectual property, pricing, availability and limitation of liability.',
        ],
        'privacy-policy' => [
            'title' => 'Privacy Policy',
            'desc'  => 'How we collect, use, share and protect the information of website visitors, enquirers and account users, and the rights available to you.',
        ],
    ];
}

function legal_framework_notice(string $current): void
{
    $links = [];
    foreach (legal_framework_docs() as $slug => $d) {
        if ($slug === $current) continue;
        $links[] = '<a href="/' . $slug . '">' . htmlspecialchars($d['title']) . '</a>';
    }
    $last = array_pop($links);
    ?>
      <div class="legal-note legal-note--blue legal-framework-note"><strong>Part of one legal framework.</strong> This document must be read together with our <?= implode(', ', $links) ?> and <?= $last ?>. Together they form the <a href="/saas-agreement">HealthO Pro SaaS Agreement</a>. By accessing our website, signing an Order Form or using any HealthO Pro product or service, you accept all of these documents as they apply to you. See <a href="#legal-related">Related legal documents</a> below.</div>
    <?php
}

function legal_framework_related(string $current): void
{
    $docs = legal_framework_docs();
    $current_title = $docs[$current]['title'] ?? 'this document';
    ?>
      <h2 id="legal-related" class="legal-related-h"><span class="legal-n">§</span>Related legal documents</h2>
      <p>This <?= htmlspecialchars($current_title) ?> is one of the following documents published by Healthocare Private Limited. Together they set out the full terms on which we provide the HealthO Pro website, platform, products and services. Each one is incorporated into the others by this reference.</p>
      <div class="legal-related">
        <?php foreach ($docs as $slug => $d): $is_current = ($slug === $current); ?>
          <?php if ($is_current): ?>
            <div class="legal-related-item is-current" aria-current="page">
              <h4><?= htmlspecialchars($d['title']) ?> <small>You are reading this</small></h4>
              <p><?= htmlspecialchars($d['desc']) ?></p>
            </div>
          <?php else: ?>
            <a class="legal-related-item" href="/<?= $slug ?>">
              <h4><?= htmlspecialchars($d['title']) ?> <span aria-hidden="true">→</span></h4>
              <p><?= htmlspecialchars($d['desc']) ?></p>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <h3>Acceptance</h3>
      <p>By accessing our website, submitting an enquiry, signing an Order Form, accepting any of these documents online, or using any HealthO Pro product, app or service, you confirm that you have read, understood and agree to be bound by all of the documents listed above, to the extent each applies to you. If you do not agree with any of them, please do not use our website, products or services.</p>

      <h3>Order of precedence</h3>
      <p>If two of these documents conflict on the same subject, the conflict is resolved in this order, with the first prevailing:</p>
      <ol>
        <li>Your signed Order Form or service agreement</li>
        <?php foreach ($docs as $slug => $d): ?>
          <li><?= htmlspecialchars($d['title']) ?><?= $slug === 'data-protection-addendum' ? ' (on data-protection matters)' : '' ?></li>
        <?php endforeach; ?>
      </ol>
      <p>Each of these documents may be updated from time to time as described in it. The current version is always the one published on this website at the link above.</p>

      <h3>The HealthO Pro SaaS Agreement and changes</h3>
      <p>Together, these documents form the <a href="/saas-agreement">HealthO Pro SaaS Agreement</a>. Every customer accepts the agreement as a whole, at sign-up and again whenever we publish a new release. When we make a material change, we publish a new release that lists the version of every document, explain what changed, and ask your account administrator to accept it within the deadline shown. We keep a record of each acceptance (who accepted, when, and the exact versions accepted). Each page shows its own version history below.</p>
      <?php legal_version_history($current); ?>
    <?php
}
