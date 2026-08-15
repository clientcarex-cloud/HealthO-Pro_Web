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

/**
 * Facets, taken from the openings that are actually on the page so a filter can
 * never return nothing. Each is `value => label`; a facet with fewer than two
 * distinct values is dropped, because a filter that cannot narrow anything is
 * only noise on the page.
 *
 * The experience filter is banded rather than exact: candidates search for
 * "I have about 4 years", not for "3 – 5 yrs". A posting lands in a band by its
 * MINIMUM required experience, which is the number that decides whether someone
 * is eligible at all.
 */
$expBands = [
    'entry'  => ['label' => 'Entry level (0 – 1 yr)', 'min' => 0,  'max' => 1],
    'junior' => ['label' => '1 – 3 yrs',              'min' => 1,  'max' => 3],
    'mid'    => ['label' => '3 – 5 yrs',              'min' => 3,  'max' => 5],
    'senior' => ['label' => '5 – 10 yrs',             'min' => 5,  'max' => 10],
    'lead'   => ['label' => '10+ yrs',                'min' => 10, 'max' => 999],
];

/** Which band a posting belongs to, or '' when it states no experience at all. */
$expBandOf = static function (array $job) use ($expBands) {
    if (!isset($job['experience_min']) || $job['experience_min'] === null) {
        return '';
    }

    $min = (float) $job['experience_min'];

    foreach ($expBands as $key => $band) {
        if ($min >= $band['min'] && $min < $band['max']) {
            return $key;
        }
    }

    return 'lead';
};

$facetDepartments = [];
$facetTypes       = [];
$facetModes       = [];
$facetLocations   = [];
$facetExperience  = [];

foreach ($jobs as $job) {
    if (($job['department'] ?? '') !== '') {
        $facetDepartments[$job['department']] = $job['department'];
    }
    if (($job['type'] ?? '') !== '') {
        $facetTypes[$job['type']] = $job['type_label'] ?? $job['type'];
    }
    if (($job['work_mode'] ?? '') !== '') {
        $facetModes[$job['work_mode']] = $job['work_mode_label'] ?? $job['work_mode'];
    }
    if (($job['location'] ?? '') !== '') {
        $facetLocations[$job['location']] = $job['location'];
    }

    $band = $expBandOf($job);
    if ($band !== '') {
        $facetExperience[$band] = $expBands[$band]['label'];
    }
}

asort($facetDepartments, SORT_NATURAL | SORT_FLAG_CASE);
asort($facetLocations, SORT_NATURAL | SORT_FLAG_CASE);
// Experience reads as a ladder, so keep the band order rather than the
// order the postings happened to appear in.
$facetExperience = array_intersect_key($expBands, $facetExperience);
$facetExperience = array_map(static function ($band) {
    return $band['label'];
}, $facetExperience);

/** The facet groups the filter bar renders, in order. */
$facetGroups = array_filter([
    ['key' => 'department', 'label' => 'Department',  'options' => $facetDepartments],
    ['key' => 'type',       'label' => 'Job type',    'options' => $facetTypes],
    ['key' => 'mode',       'label' => 'Work mode',   'options' => $facetModes],
    ['key' => 'location',   'label' => 'Location',    'options' => $facetLocations],
    ['key' => 'experience', 'label' => 'Experience',  'options' => $facetExperience],
], static function ($group) {
    return count($group['options']) > 1;
});

// The filter bar earns its space only once there is enough to sift through.
$showFilters = count($jobs) > 3 && !empty($facetGroups);

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

/* ── Faceted search ── */
.cr-filters { margin-bottom:18px; }
.cr-searchrow { display:flex; flex-wrap:wrap; gap:10px; align-items:center; }
.cr-searchwrap { position:relative; flex:1; min-width:240px; display:flex; align-items:center; }
.cr-searchic { position:absolute; left:16px; width:17px; height:17px; color:var(--text-mute); pointer-events:none; }
.cr-search { width:100%; padding:12px 40px 12px 43px; border:1.5px solid var(--line); border-radius:var(--r-pill); font:inherit; font-size:.92rem; background:var(--surface); }
.cr-search:focus { outline:none; border-color:var(--cyan); box-shadow:0 0 0 4px var(--cyan-tint); }
.cr-search::-webkit-search-cancel-button { display:none; }
.cr-searchclear { position:absolute; right:8px; width:26px; height:26px; border:0; border-radius:50%; background:var(--navy-tint); color:var(--text-soft); font-size:1.1rem; line-height:1; cursor:pointer; }
.cr-searchclear:hover { background:var(--cyan-tint); color:var(--cyan-dark); }

.cr-sortwrap { display:inline-flex; align-items:center; gap:8px; }
.cr-sortlab { font-size:.82rem; font-weight:700; color:var(--text-mute); text-transform:uppercase; letter-spacing:.04em; }
.cr-sort { padding:11px 14px; border:1.5px solid var(--line); border-radius:var(--r-pill); font:inherit; font-size:.88rem; background:var(--surface); color:var(--navy); cursor:pointer; }
.cr-sort:focus { outline:none; border-color:var(--cyan); }

.cr-facetoggle { display:none; align-items:center; gap:8px; padding:11px 18px; border:1.5px solid var(--line); border-radius:var(--r-pill); background:var(--surface); color:var(--navy); font:inherit; font-size:.88rem; font-weight:700; cursor:pointer; }
.cr-facetoggle svg { width:16px; height:16px; }
.cr-facetcount { min-width:20px; padding:1px 6px; border-radius:var(--r-pill); background:var(--cyan); color:#fff; font-size:.74rem; }

.cr-facets { display:flex; flex-wrap:wrap; gap:18px 30px; margin-top:18px; padding:18px 20px; background:var(--surface); border:1px solid var(--line); border-radius:var(--r-md); }
.cr-facet { border:0; margin:0; padding:0; min-width:0; }
.cr-facetlab { padding:0; margin:0 0 9px; font-size:.74rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; color:var(--text-mute); }
.cr-facetopts { display:flex; flex-wrap:wrap; gap:8px; }
.cr-chip { display:inline-flex; align-items:center; gap:7px; border:1.5px solid var(--line); background:var(--surface); color:var(--text-soft); font:inherit; font-size:.85rem; font-weight:600; padding:7px 14px; border-radius:var(--r-pill); cursor:pointer; transition:all .18s ease; }
.cr-chip:hover { border-color:var(--cyan); color:var(--navy); }
.cr-chip[aria-pressed="true"] { background:var(--navy); border-color:var(--navy); color:#fff; }
.cr-chip[disabled] { opacity:.4; cursor:not-allowed; }
.cr-chip[disabled]:hover { border-color:var(--line); color:var(--text-soft); }
.cr-chipn { font-size:.74rem; font-weight:700; color:var(--text-mute); }
.cr-chip[aria-pressed="true"] .cr-chipn { color:rgba(255,255,255,.75); }
.cr-chipn:empty { display:none; }

.cr-active { display:flex; flex-wrap:wrap; align-items:center; gap:8px 12px; margin-top:14px; }
/* A class that sets `display` beats the UA's [hidden] rule, so every element in
   here that JS toggles with .hidden needs this or it never actually hides. */
.cr-filters [hidden], .cr-active[hidden], .cr-more[hidden], .cr-count[hidden] { display:none; }
.cr-activelab { font-size:.78rem; font-weight:800; letter-spacing:.05em; text-transform:uppercase; color:var(--text-mute); }
.cr-activepills { display:flex; flex-wrap:wrap; gap:8px; }
.cr-pill { display:inline-flex; align-items:center; gap:7px; padding:5px 8px 5px 13px; border-radius:var(--r-pill); background:var(--cyan-tint); color:var(--cyan-dark); font-size:.82rem; font-weight:700; }
.cr-pill button { width:18px; height:18px; border:0; border-radius:50%; background:rgba(0,150,183,.18); color:inherit; font-size:.9rem; line-height:1; cursor:pointer; }
.cr-pill button:hover { background:var(--cyan); color:#fff; }
.cr-clear { border:0; background:none; font:inherit; font-size:.84rem; font-weight:700; color:var(--text-soft); text-decoration:underline; cursor:pointer; }
.cr-clear:hover { color:var(--cyan-dark); }

.cr-count { margin-bottom:16px; font-size:.88rem; color:var(--text-soft); }
.cr-count strong { color:var(--navy); }
.cr-more { text-align:center; margin-top:24px; }

@media (max-width:820px) {
  .cr-facetoggle { display:inline-flex; }
  .cr-facets { display:none; }
  .cr-facets.is-open { display:flex; }
  .cr-sortwrap { flex:1; }
  .cr-sort { flex:1; }
}

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
.cr-noresult { text-align:center; padding:40px 20px; color:var(--text-soft); }
.cr-noresult strong { display:block; color:var(--navy); font-size:1.05rem; margin-bottom:6px; }
.cr-noresult p { margin:0 0 12px; font-size:.9rem; }
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
      <?php if ($showFilters): ?>
      <!--
        Faceted search. Every control is additive: picking two departments
        widens, picking a department AND a job type narrows. It filters the
        cards already in the HTML below — nothing is fetched — so the openings
        stay readable with JavaScript off, and the whole bar is hidden until
        the script that drives it has bound to it.
      -->
      <div class="cr-filters" id="crFilters" hidden>
        <div class="cr-searchrow">
          <div class="cr-searchwrap">
            <svg class="cr-searchic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="search" class="cr-search" id="crSearch" autocomplete="off"
                   placeholder="Search by role, skill, department or location…" aria-label="Search openings">
            <button type="button" class="cr-searchclear" id="crSearchClear" aria-label="Clear search" hidden>&times;</button>
          </div>
          <label class="cr-sortwrap">
            <span class="cr-sortlab">Sort</span>
            <select class="cr-sort" id="crSort" aria-label="Sort openings">
              <option value="relevance">Most relevant</option>
              <option value="newest">Newest first</option>
              <option value="title">A – Z</option>
            </select>
          </label>
          <button type="button" class="cr-facetoggle" id="crFacetToggle" aria-expanded="false" aria-controls="crFacets">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 5h18M6 12h12M10 19h4"/></svg>
            Filters<span class="cr-facetcount" id="crFacetCount" hidden></span>
          </button>
        </div>

        <div class="cr-facets" id="crFacets">
          <?php foreach ($facetGroups as $group): ?>
            <fieldset class="cr-facet" data-facet="<?= h($group['key']) ?>">
              <legend class="cr-facetlab"><?= h($group['label']) ?></legend>
              <div class="cr-facetopts">
                <?php foreach ($group['options'] as $value => $label): ?>
                  <button type="button" class="cr-chip" data-facet="<?= h($group['key']) ?>"
                          data-value="<?= h((string) $value) ?>" aria-pressed="false">
                    <?= h((string) $label) ?><span class="cr-chipn" aria-hidden="true"></span>
                  </button>
                <?php endforeach; ?>
              </div>
            </fieldset>
          <?php endforeach; ?>
        </div>

        <div class="cr-active" id="crActive" hidden>
          <span class="cr-activelab">Filtering by</span>
          <div class="cr-activepills" id="crActivePills"></div>
          <button type="button" class="cr-clear" id="crClear">Clear all</button>
        </div>
      </div>
      <div class="cr-count" id="crCount" hidden></div>
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
            // One lowercase haystack per card. Education and the experience text
            // are in here even though neither is a chip: someone typing "MBA" or
            // "fresher" is searching, not filtering.
            $search = strtolower(trim(preg_replace('/\s+/', ' ',
                ($job['title'] ?? '') . ' ' . ($job['department'] ?? '') . ' ' . ($job['location'] ?? '')
                . ' ' . ($job['type_label'] ?? '') . ' ' . ($job['work_mode_label'] ?? '')
                . ' ' . implode(' ', (array) ($job['skills'] ?? [])) . ' ' . ($job['summary'] ?? '')
                . ' ' . ($job['education'] ?? '') . ' ' . ($job['experience'] ?? '')
            )));
          ?>
          <article class="cr-job"
                   data-type="<?= h($job['type'] ?? '') ?>"
                   data-department="<?= h($job['department'] ?? '') ?>"
                   data-mode="<?= h($job['work_mode'] ?? '') ?>"
                   data-location="<?= h($job['location'] ?? '') ?>"
                   data-experience="<?= h($expBandOf($job)) ?>"
                   data-title="<?= h(strtolower((string) ($job['title'] ?? ''))) ?>"
                   data-posted="<?= h((string) (!empty($job['posted_at']) ? strtotime((string) $job['posted_at']) : 0)) ?>"
                   data-featured="<?= !empty($job['featured']) ? '1' : '0' ?>"
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
      <div class="cr-more" id="crMore" hidden>
        <button type="button" class="btn btn-ghost" id="crMoreBtn"></button>
      </div>
      <div class="cr-noresult" id="crNoResult" hidden>
        <strong>No openings match those filters.</strong>
        <p>Try removing one, or search a different keyword.</p>
        <button type="button" class="link-cyan" id="crReset" style="border:0;background:none;font:inherit;cursor:pointer;color:var(--cyan-dark);">Clear all filters</button>
      </div>

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
<?php require __DIR__ . '/partials/foot.php';
