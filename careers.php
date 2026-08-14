<?php
/**
 * Careers — live openings.
 *
 * Every opening on this page comes from the Careers module in the CRM through
 * api/careers.php. It is rendered server-side (not fetched by the browser) so
 * search engines see the real listings, and the filters below are pure
 * client-side work over the markup that is already here.
 */
define('HO_CAREERS_LIB_ONLY', true);
require __DIR__ . '/api/careers.php';
// SITE_URL / h() are needed while building the structured data below, i.e.
// before head.php pulls them in.
require_once __DIR__ . '/partials/site.php';

$data     = ho_careers_jobs();
$jobs     = (!empty($data['ok']) && !empty($data['jobs'])) ? $data['jobs'] : [];
$facets   = $data['facets'] ?? [];
$loadFail = empty($data['ok']);
$alerts   = !empty($data['alerts_enabled']);

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

$head_extra = '<script type="application/ld+json">' . json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => 'Open positions at HealthO Pro',
    'numberOfItems'   => count($itemList),
    'itemListElement' => $itemList,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . <<<'HTML'
<style>
/* ── Careers page ── */
.cr-toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:26px; }
.cr-toolbar input, .cr-toolbar select {
  padding:11px 16px; border:1.5px solid var(--line); border-radius:12px; background:var(--surface);
  color:var(--ink); font:inherit; font-size:.95rem; min-width:180px; outline:none; transition:border-color .2s;
}
.cr-toolbar input { min-width:270px; flex:1; }
.cr-toolbar input:focus, .cr-toolbar select:focus { border-color:var(--cyan); }
.cr-pills { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px; }
.cr-pill {
  border:1.5px solid var(--line); background:var(--surface); color:var(--text-soft);
  border-radius:999px; padding:8px 16px; font-size:.85rem; font-weight:700; cursor:pointer; transition:all .2s;
}
.cr-pill:hover { border-color:var(--cyan-light); color:var(--cyan-dark); }
.cr-pill.active { background:var(--navy); border-color:var(--navy); color:#fff; }
.cr-count { color:var(--text-mute); font-size:.88rem; margin-bottom:20px; }

.cr-list { display:grid; gap:16px; }
.cr-job {
  display:grid; grid-template-columns:1fr auto; gap:20px; align-items:center;
  background:var(--surface); border:1px solid var(--line); border-radius:var(--r-md);
  padding:24px 28px; box-shadow:var(--e1); transition:transform .3s, box-shadow .3s, border-color .3s;
}
.cr-job:hover { transform:translateY(-4px); box-shadow:var(--e3); border-color:var(--cyan-light); }
.cr-job--feat { border-color:var(--cyan); box-shadow:var(--e2); }
.cr-job h3 { font-size:var(--text-lg); margin:0 0 8px; color:var(--navy); }
.cr-job h3 a { color:inherit; text-decoration:none; }
.cr-job h3 a:hover { color:var(--cyan-dark); }
.cr-job-meta { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
.cr-job-sum { color:var(--text-soft); font-size:.9rem; margin:10px 0 0; line-height:1.55; }
.cr-tag-new { background:var(--green-tint); color:var(--green-dark); }
.cr-tag-urgent { background:#FEE2E2; color:#B91C1C; }
.cr-tag-feat { background:var(--amber); color:#fff; }
.cr-job-cta { display:flex; flex-direction:column; gap:8px; align-items:stretch; text-align:center; }
.cr-job-sal { font-size:.85rem; font-weight:700; color:var(--green-dark); white-space:nowrap; }
@media (max-width: 640px) {
  .cr-job { grid-template-columns:1fr; }
  .cr-job-cta { align-items:flex-start; text-align:left; }
}

.cr-empty { text-align:center; padding:56px 20px; color:var(--text-soft); background:var(--surface); border:1px dashed var(--line); border-radius:var(--r-md); }
.cr-empty h3 { color:var(--navy); margin-bottom:8px; }

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
      <!-- Quick type filters -->
      <div class="cr-pills" id="crPills">
        <button type="button" class="cr-pill active" data-family="">All roles</button>
        <button type="button" class="cr-pill" data-family="job">Jobs</button>
        <button type="button" class="cr-pill" data-family="early">Internships &amp; apprenticeships</button>
        <button type="button" class="cr-pill" data-mode="remote">Remote friendly</button>
      </div>

      <div class="cr-toolbar">
        <input type="search" id="crSearch" placeholder="Search by title, skill or keyword…" aria-label="Search openings">

        <select id="crDept" aria-label="Filter by department">
          <option value="">All departments</option>
          <?php foreach (($facets['departments'] ?? []) as $dept): ?>
            <option value="<?= h($dept) ?>"><?= h($dept) ?></option>
          <?php endforeach; ?>
        </select>

        <select id="crLoc" aria-label="Filter by location">
          <option value="">All locations</option>
          <?php foreach (($facets['locations'] ?? []) as $loc): ?>
            <option value="<?= h($loc) ?>"><?= h($loc) ?></option>
          <?php endforeach; ?>
        </select>

        <select id="crType" aria-label="Filter by employment type">
          <option value="">All types</option>
          <?php foreach (($facets['types'] ?? []) as $key => $label): ?>
            <option value="<?= h($key) ?>"><?= h($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="cr-count" id="crCount"></div>

      <div class="cr-list" id="crList">
        <?php foreach ($jobs as $job):
          $isNew = !empty($job['posted_at']) && strtotime($job['posted_at']) > strtotime('-14 days');
          $url   = career_url($job['slug']);
        ?>
        <article class="cr-job<?= !empty($job['featured']) ? ' cr-job--feat' : '' ?>"
                 data-family="<?= h($job['type_family']) ?>"
                 data-type="<?= h($job['type']) ?>"
                 data-dept="<?= h($job['department']) ?>"
                 data-loc="<?= h($job['location']) ?>"
                 data-mode="<?= h($job['work_mode']) ?>"
                 data-search="<?= h(strtolower($job['title'] . ' ' . $job['department'] . ' ' . $job['location'] . ' ' . implode(' ', $job['skills']) . ' ' . $job['summary'])) ?>">
          <div>
            <h3><a href="<?= h($url) ?>"><?= h($job['title']) ?></a></h3>
            <div class="cr-job-meta">
              <?php if (!empty($job['featured'])): ?><span class="chip cr-tag-feat">★ Featured</span><?php endif; ?>
              <?php if (!empty($job['urgent'])): ?><span class="chip cr-tag-urgent">Urgent hiring</span><?php endif; ?>
              <?php if ($isNew): ?><span class="chip cr-tag-new">New</span><?php endif; ?>
              <?php if ($job['department'] !== ''): ?><span class="chip dept"><?= h($job['department']) ?></span><?php endif; ?>
              <?php if ($job['location'] !== ''): ?><span class="chip">📍 <?= h($job['location']) ?></span><?php endif; ?>
              <span class="chip"><?= h($job['type_label']) ?></span>
              <span class="chip"><?= h($job['work_mode_label']) ?></span>
              <?php if ($job['experience'] !== ''): ?><span class="chip"><?= h($job['experience']) ?></span><?php endif; ?>
              <?php if ((int) $job['openings'] > 1): ?><span class="chip"><?= (int) $job['openings'] ?> openings</span><?php endif; ?>
            </div>
            <?php if ($job['summary'] !== ''): ?>
              <p class="cr-job-sum"><?= h($job['summary']) ?></p>
            <?php endif; ?>
          </div>
          <div class="cr-job-cta">
            <?php if ($job['salary'] !== ''): ?><span class="cr-job-sal"><?= h($job['salary']) ?></span><?php endif; ?>
            <a href="<?= h($url) ?>" class="btn btn-outline">View &amp; Apply</a>
            <span class="chip" style="justify-content:center"><?= h($job['posted_ago'] ?: 'Just posted') ?></span>
          </div>
        </article>
        <?php endforeach; ?>
      </div>

      <div class="cr-empty" id="crEmpty" hidden>
        <h3>No roles match those filters</h3>
        <p>Try clearing the search, or subscribe below and we will email you the moment something opens.</p>
      </div>

    <?php elseif ($loadFail): ?>
      <!--
        The server-side fetch could not run (no API credentials, or the CRM was
        briefly unreachable), so the embeddable widget takes over and loads the
        same openings straight from the CRM in the visitor's browser. The page
        therefore always shows live roles; configuring CAREERS_API_KEY only adds
        the server-rendered version that search engines read.
      -->
      <div data-careers-embed data-accent="#00B4D8"></div>
      <script src="<?= h(CAREERS_EMBED_JS) ?>" async></script>
    <?php else: ?>
      <div class="cr-empty">
        <h3>No openings right now</h3>
        <p>We are not actively hiring at this moment, but we are always glad to hear from good people. Send us your profile below.</p>
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
      <form class="cr-alert-form" id="crAlertForm" novalidate>
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
