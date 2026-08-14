<?php
/**
 * Careers — live openings.
 *
 * Every opening on this page comes from the Careers module in the CRM, read
 * from its keyless embed endpoint by partials/careers-data.php. It is rendered
 * server-side (not fetched by the browser) so search engines see the real
 * listings, and the filters below are pure client-side work over the markup
 * that is already here.
 */
require __DIR__ . '/partials/careers-data.php';   // pulls in partials/site.php

$data     = ho_careers_jobs();
$jobs     = (!empty($data['ok']) && !empty($data['jobs'])) ? $data['jobs'] : [];
$facets   = $data['facets'] ?? [];
$loadFail = empty($data['ok']);
// Job alerts post to the CRM's keyless endpoint, so the form does not depend on
// the API credentials: it is shown unless the CRM positively reports alerts off.
$alerts   = $loadFail ? true : !empty($data['alerts_enabled']);

// Openings that are internships / apprenticeships get their own counter — it is
// the question early-career visitors come to this page to answer.
$earlyCount = 0;
foreach ($jobs as $job) {
    if (($job['type_family'] ?? '') === 'early') {
        $earlyCount++;
    }
}

$page        = 'careers';
$title       = 'Careers — Join HealthO Pro | Healthocare Private Limited';
$description = $jobs
    ? 'We are hiring: ' . count($jobs) . ' open role' . (count($jobs) === 1 ? '' : 's') . ' across engineering, product, sales and support at HealthO Pro. Explore jobs, internships and apprenticeships, and apply online.'
    : 'Build the future of healthcare technology with HealthO Pro. Explore jobs, internships and apprenticeships at Healthocare Private Limited and apply online.';

// ItemList structured data for the listing; each opening carries its own
// JobPosting markup on its detail page, which is what Google Jobs reads.
$itemList = [];
foreach ($jobs as $index => $job) {
    $itemList[] = [
        '@type'    => 'ListItem',
        'position' => $index + 1,
        'url'      => career_url($job['slug'], true),
        'name'     => $job['title'],
    ];
}

// Filter values, taken from the openings that are actually on the page so a
// filter can never return nothing.
$filterDepartments = [];
$filterTypes       = [];
foreach ($jobs as $job) {
    if (($job['department'] ?? '') !== '') {
        $filterDepartments[$job['department']] = true;
    }
    if (($job['type'] ?? '') !== '') {
        $filterTypes[$job['type']] = $job['type_label'] ?? $job['type'];
    }
}
$filterDepartments = array_keys($filterDepartments);

$head_extra = '<script type="application/ld+json">' . json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Open positions at HealthO Pro',
    'numberOfItems'   => count($itemList),
    'itemListElement' => $itemList,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . <<<'HTML'
<style>
/* ── Careers page ──
   The openings are rendered server-side (Google Jobs and the ItemList above
   read them from the HTML), so they are styled here rather than by the widget. */
.cr-empty { text-align:center; padding:56px 20px; color:var(--text-soft); background:var(--surface); border:1px dashed var(--line); border-radius:var(--r-md); }
.cr-empty h3 { color:var(--navy); margin-bottom:8px; }

.cr-filters { display:flex; flex-wrap:wrap; gap:10px; align-items:center; margin-bottom:26px; }
.cr-chip { border:1.5px solid var(--line); background:var(--surface); color:var(--text-soft); font:inherit; font-size:.85rem; font-weight:600; padding:8px 16px; border-radius:var(--r-pill); cursor:pointer; transition:all .18s ease; }
.cr-chip:hover { border-color:var(--cyan); color:var(--navy); }
.cr-chip[aria-pressed="true"] { background:var(--navy); border-color:var(--navy); color:#fff; }
.cr-search { flex:1; min-width:220px; padding:10px 16px; border:1.5px solid var(--line); border-radius:var(--r-pill); font:inherit; font-size:.9rem; background:var(--surface); }
.cr-search:focus { outline:none; border-color:var(--cyan); }

.cr-jobs { display:grid; gap:18px; }
.cr-job { background:var(--surface); border:1px solid var(--line); border-radius:var(--r-md); padding:24px 26px; box-shadow:var(--e1); transition:box-shadow .2s ease, transform .2s ease, border-color .2s ease; }
.cr-job:hover { box-shadow:var(--e3); transform:translateY(-2px); border-color:var(--cyan-light); }
.cr-job[hidden] { display:none; }
.cr-job-top { display:flex; flex-wrap:wrap; gap:12px 18px; align-items:flex-start; justify-content:space-between; }
.cr-job h3 { font-size:1.16rem; color:var(--navy); margin:0 0 6px; }
.cr-job h3 a { color:inherit; text-decoration:none; }
.cr-job h3 a:hover { color:var(--cyan-dark); }
.cr-tags { display:flex; flex-wrap:wrap; gap:8px; margin:0 0 12px; }
.cr-tag { font-size:.74rem; font-weight:700; letter-spacing:.02em; text-transform:uppercase; padding:4px 10px; border-radius:var(--r-pill); background:var(--navy-tint); color:var(--navy); }
.cr-tag--type { background:var(--cyan-tint); color:var(--cyan-dark); }
.cr-tag--new { background:var(--green-tint); color:var(--green-dark); }
.cr-tag--urgent { background:#FEF3C7; color:#B45309; }
.cr-meta { display:flex; flex-wrap:wrap; gap:6px 18px; color:var(--text-soft); font-size:.86rem; margin:0 0 10px; }
.cr-meta span { display:inline-flex; align-items:center; gap:6px; }
.cr-job p.cr-summary { color:var(--text); font-size:.92rem; margin:0; max-width:70ch; }
.cr-job-cta { display:flex; flex-direction:column; align-items:flex-end; gap:8px; white-space:nowrap; }
.cr-posted { color:var(--text-mute); font-size:.78rem; }
.cr-noresult { text-align:center; padding:34px 20px; color:var(--text-soft); }
@media (max-width:640px) {
  .cr-job-top { flex-direction:column; }
  .cr-job-cta { align-items:flex-start; width:100%; }
}

.cr-alert-box { background:var(--navy-tint); border:1px solid var(--line); border-radius:var(--r-md); padding:26px 28px; }
.cr-alert-form { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
.cr-alert-form input { flex:1; min-width:230px; padding:12px 16px; border:1.5px solid var(--line); border-radius:12px; font:inherit; background:#fff; }
.cr-alert-msg { margin-top:10px; font-size:.88rem; font-weight:600; }
.cr-hp { position:absolute; left:-9999px; opacity:0; height:0; width:0; }
</style>
HTML;

$foot_scripts = ['js/careers.js'];
require __DIR__ . '/partials/lead-form.php';
require __DIR__ . '/partials/head.php';
?>
<!-- ===== PAGE HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Careers</div>
    <h1>Build the future of <span class="text-grad">healthcare technology</span></h1>
    <p>Join a passionate team that's empowering healthcare providers across 8 countries. Grow your career while making a real difference.</p>
    <div class="hero-actions hero-actions--center">
      <a href="#openings" class="btn btn-primary btn-lg"><?= $jobs ? 'View ' . count($jobs) . ' Open Role' . (count($jobs) === 1 ? '' : 's') : 'View Open Roles' ?></a>
      <a href="#general" class="btn btn-ghost-light btn-lg">Send Your Profile</a>
    </div>
  </div>
</header>

<!-- ===== PERKS ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">Why Join Us</span><h2 class="h-sec">Where talent meets purpose</h2><p class="lead">We invest in our people the way we invest in our products — for the long term.</p></div>
    <div class="grid grid-4">
      <div class="perk reveal"><span class="perk-ic bg-cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></span><h4>Meaningful work</h4><p>Build software that improves patient care every day.</p></div>
      <div class="perk reveal d1"><span class="perk-ic bg-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/></svg></span><h4>Growth &amp; learning</h4><p>Mentorship, training and clear career paths.</p></div>
      <div class="perk reveal d2"><span class="perk-ic bg-navy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span><h4>Flexibility</h4><p>Hybrid options and a healthy work-life balance.</p></div>
      <div class="perk reveal d3"><span class="perk-ic bg-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 15 9l7 .5-5.5 4.5L18 21l-6-3.8L6 21l1.5-7L2 9.5 9 9Z"/></svg></span><h4>Global exposure</h4><p>Work with clients across India, the GCC &amp; beyond.</p></div>
    </div>
  </div>
</section>

<!-- ===== OPENINGS ===== -->
<section class="section" id="openings">
  <div class="container">
    <div class="sec-head reveal">
      <span class="eyebrow">Open Positions</span>
      <h2 class="h-sec">Current openings</h2>
      <p class="lead">
        <?php if ($jobs): ?>
          <?= count($jobs) ?> live opening<?= count($jobs) === 1 ? '' : 's' ?><?= $earlyCount ? ' — including ' . $earlyCount . ' internship' . ($earlyCount === 1 ? '' : 's') . ' &amp; apprenticeship' . ($earlyCount === 1 ? '' : 's') : '' ?>.
          Find your role and apply online.
        <?php else: ?>
          Don't see a fit right now? Send us your profile and we will reach out when something opens.
        <?php endif; ?>
      </p>
    </div>

    <?php if ($jobs): ?>
      <?php if (count($jobs) > 3 && (count($filterDepartments) > 1 || count($filterTypes) > 1)): ?>
      <div class="cr-filters" id="crFilters">
        <button type="button" class="cr-chip" data-filter="all" aria-pressed="true">All roles</button>
        <?php foreach ($filterTypes as $typeKey => $typeLabel): ?>
          <button type="button" class="cr-chip" data-filter="type" data-value="<?= h($typeKey) ?>" aria-pressed="false"><?= h($typeLabel) ?></button>
        <?php endforeach; ?>
        <?php foreach ($filterDepartments as $department): ?>
          <button type="button" class="cr-chip" data-filter="department" data-value="<?= h($department) ?>" aria-pressed="false"><?= h($department) ?></button>
        <?php endforeach; ?>
        <input type="search" class="cr-search" id="crSearch" placeholder="Search roles, skills or locations…" aria-label="Search openings">
      </div>
      <?php endif; ?>

      <!--
        The openings are rendered here, server-side, from the Careers module in
        the CRM. They must stay in the HTML: Google Jobs, the ItemList markup in
        the head and every visitor without JavaScript read them from here, not
        from a script.
      -->
      <div class="cr-jobs" id="crJobs">
        <?php foreach ($jobs as $job): ?>
          <?php
            $url    = career_url($job['slug']);
            $isNew  = !empty($job['posted_at']) && strtotime((string) $job['posted_at']) > strtotime('-14 days');
            $search = strtolower(trim(
                ($job['title'] ?? '') . ' ' . ($job['department'] ?? '') . ' ' . ($job['location'] ?? '')
                . ' ' . ($job['type_label'] ?? '') . ' ' . ($job['work_mode_label'] ?? '')
                . ' ' . implode(' ', (array) ($job['skills'] ?? [])) . ' ' . ($job['summary'] ?? '')
            ));
          ?>
          <article class="cr-job"
                   data-type="<?= h($job['type'] ?? '') ?>"
                   data-department="<?= h($job['department'] ?? '') ?>"
                   data-search="<?= h($search) ?>">
            <div class="cr-job-top">
              <div>
                <h3><a href="<?= h($url) ?>"><?= h($job['title']) ?></a></h3>
                <div class="cr-tags">
                  <?php if (($job['department'] ?? '') !== ''): ?><span class="cr-tag"><?= h($job['department']) ?></span><?php endif; ?>
                  <span class="cr-tag cr-tag--type"><?= h($job['type_label'] ?? '') ?></span>
                  <?php if (!empty($job['urgent'])): ?><span class="cr-tag cr-tag--urgent">Urgent</span><?php endif; ?>
                  <?php if ($isNew): ?><span class="cr-tag cr-tag--new">New</span><?php endif; ?>
                </div>
                <div class="cr-meta">
                  <?php if (($job['location'] ?? '') !== ''): ?><span>📍 <?= h($job['location']) ?></span><?php endif; ?>
                  <?php if (($job['work_mode_label'] ?? '') !== ''): ?><span>🏢 <?= h($job['work_mode_label']) ?></span><?php endif; ?>
                  <?php if (($job['experience'] ?? '') !== ''): ?><span>💼 <?= h($job['experience']) ?></span><?php endif; ?>
                  <?php $pay = ($job['salary'] ?? '') !== '' ? $job['salary'] : ($job['stipend'] ?? ''); ?>
                  <?php if ($pay !== ''): ?><span>💰 <?= h($pay) ?></span><?php endif; ?>
                  <?php if (!empty($job['openings']) && (int) $job['openings'] > 1): ?><span>👥 <?= (int) $job['openings'] ?> positions</span><?php endif; ?>
                </div>
                <?php if (($job['summary'] ?? '') !== ''): ?>
                  <p class="cr-summary"><?= h($job['summary']) ?></p>
                <?php endif; ?>
              </div>
              <div class="cr-job-cta">
                <a href="<?= h($url) ?>" class="btn btn-primary">View &amp; Apply</a>
                <?php if (($job['posted_ago'] ?? '') !== ''): ?><span class="cr-posted">Posted <?= h($job['posted_ago']) ?></span><?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="cr-noresult" id="crNoResult" hidden>No openings match that filter. <button type="button" class="link-cyan" id="crReset" style="border:0;background:none;font:inherit;cursor:pointer;color:var(--cyan-dark);">Show all roles</button></div>

    <?php elseif ($loadFail): ?>
      <!--
        The CRM could not be reached server-side. The embeddable widget is the
        second route to the same openings — it needs no API key and no server
        configuration — so it is loaded here rather than showing a visitor an
        empty page.
      -->
      <!--
        The message sits inside the widget's own container: the widget replaces
        everything in there when it loads, so a visitor sees the openings if it
        answers and this if it does not — never a blank section.
      -->
      <div data-careers-embed data-accent="#00B4D8">
        <div class="cr-empty">
          <h3>Our openings are being updated</h3>
          <p>Please check back shortly, or email your CV to <a href="mailto:sales@healtho.pro" class="link-cyan">sales@healtho.pro</a> and we will get in touch.</p>
        </div>
      </div>
      <script src="<?= h(CAREERS_EMBED_JS) ?>" async></script>

    <?php else: ?>
      <div class="cr-empty">
        <h3>No openings are listed right now</h3>
        <p>New roles go up here as soon as they open. Send us your profile below and we will reach out when something matches.</p>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php if ($alerts): ?>
<!-- ===== JOB ALERTS ===== -->
<section class="section section--soft">
  <div class="container">
    <div class="cr-alert-box reveal">
      <span class="eyebrow">Job Alerts</span>
      <h3 class="h-card" style="margin:6px 0 6px;">Be the first to know</h3>
      <p style="color:var(--text-soft);margin:0;">Tell us where to reach you and we will email you when a matching role opens — no newsletters, only openings.</p>
      <form class="cr-alert-form" id="crAlertForm" novalidate
            data-endpoint="<?= h(CAREERS_EMBED_BASE) ?>/subscribe">
        <input type="text" name="company_website" class="cr-hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <input type="text" name="name" placeholder="Your name" autocomplete="name">
        <input type="email" name="email" placeholder="you@email.com" required autocomplete="email">
        <button type="submit" class="btn btn-primary">Notify me</button>
      </form>
      <div class="cr-alert-msg" id="crAlertMsg" hidden></div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== GENERAL APPLICATION ===== -->
<section class="section" id="general">
  <div class="container">
    <div class="cta-split">
      <div class="reveal">
        <span class="eyebrow">General Application</span>
        <h2 class="h-sec">Don't see your role listed?</h2>
        <p class="lead" style="margin:16px 0 28px;">Send us your profile anyway. We review every application personally and keep strong profiles on file for upcoming openings.</p>
        <ul class="checks">
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Transparent, friendly hiring process</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>Competitive compensation &amp; benefits</span></li>
          <li><span class="ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></span><span>A team that values your growth</span></li>
        </ul>
        <div style="margin-top:28px;"><div style="font-size:.8rem;color:var(--text-mute);">Talent team</div><a href="mailto:sales@healtho.pro" style="font-weight:700;color:var(--navy);">sales@healtho.pro</a></div>
      </div>
      <div class="form-card reveal d1">
        <h3 class="h-card" style="margin-bottom:8px;">Tell us about yourself</h3>
        <p style="color:var(--text-mute);font-size:.92rem;margin-bottom:24px;">Fields marked * are required.</p>
        <?php lead_form([
          'hidden'   => ['orgType' => 'Job Application', 'formType' => 'General Career Application'],
          'aside'    => form_select('interest', 'Area of interest', [
              'Engineering', 'Product & Design', 'Sales & Business Development',
              'Customer Success / Implementation', 'Support', 'Marketing',
              'Finance & HR', 'Internship / Apprenticeship', 'Other',
          ], true, 'Select an area…'),
          'interest' => '',
          'extra'    => form_input('url', 'resume', 'Resume / CV link', 'Link to your CV (Google Drive, LinkedIn, etc.)'),
          'message'  => ['label' => 'About you', 'placeholder' => "Tell us what you do and what you'd like to work on…"],
          'submit'   => 'Submit Profile',
          'note'     => 'Applying for a listed role? Use the <strong>View &amp; Apply</strong> button on that opening — it reaches our hiring team directly.',
        ]); ?>
      </div>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
