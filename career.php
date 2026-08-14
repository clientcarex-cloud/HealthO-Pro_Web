<?php
/**
 * Single opening — /careers/{slug} (rewritten to career.php?j={slug}).
 *
 * Rendered entirely server-side: Google Jobs reads the JobPosting structured
 * data and the description from the HTML, so neither may depend on JavaScript.
 * The apply form is built from the posting's own field configuration and
 * screening questions, and submits with the CV attached straight to the CRM's
 * keyless embed endpoint — this website holds no careers credentials at all.
 */
require __DIR__ . '/partials/careers-data.php';   // pulls in partials/site.php

$slug = trim((string) ($_GET['j'] ?? ''));

if ($slug === '' || !preg_match('/^[a-z0-9\-]{2,191}$/i', $slug)) {
    header('Location: /careers', true, 302);
    exit;
}

// track: this is the page a candidate actually reads, so it is what the CRM's
// view → apply conversion is measured on.
$data = ho_careers_job($slug, true);
$job  = (!empty($data['ok']) && !empty($data['found'])) ? $data['job'] : null;

if (!$job) {
    http_response_code(404);
    $page        = 'careers';
    $title       = 'Position not found | HealthO Pro Careers';
    $description = 'This opening is no longer listed. See all current openings at HealthO Pro.';
    $canonical   = SITE_URL . '/careers';
    require __DIR__ . '/partials/head.php';
    ?>
    <header class="page-hero">
      <div class="hero-glow glow-1"></div>
      <div class="container">
        <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/careers">Careers</a><span>›</span>Not found</div>
        <h1>This position is <span class="text-grad">no longer listed</span></h1>
        <p>It may have been filled or closed. Our current openings are all on the careers page.</p>
        <div class="hero-actions hero-actions--center">
          <a href="/careers" class="btn btn-primary btn-lg">See all openings</a>
        </div>
      </div>
    </header>
    <?php
    require __DIR__ . '/partials/foot.php';
    exit;
}

$questions   = $data['questions'] ?? [];
$fields      = $job['form_fields'] ?? [];
$applyOpen   = !empty($data['apply_enabled']) && ($job['apply_mode'] ?? 'internal') === 'internal';
$external    = ($job['apply_mode'] ?? '') === 'external' && $job['external_url'] !== '';
$expired     = !empty($data['expired']);
$related     = $data['related'] ?? [];
$company     = $data['company'] ?? 'HealthO Pro';
$maxMb       = (int) ($data['max_resume_mb'] ?? 5);
$allowedExt  = $data['allowed_ext'] ?? ['pdf', 'doc', 'docx'];
$resumeReq   = !empty($data['resume_required']) && !empty($fields['resume']);

$page        = 'careers';
$title       = ($job['seo_title'] !== '' ? $job['seo_title'] : $job['title'] . ' — Careers') . ' | ' . $company;
$description = $job['seo_description'] !== '' ? $job['seo_description'] : $job['summary'];
$canonical   = career_url($job['slug'], true);

/* ── JobPosting structured data (Google Jobs) ───────────────────────────── */

$posting = [
    '@context'    => 'https://schema.org',
    '@type'       => 'JobPosting',
    'title'       => $job['title'],
    'description' => trim(
        ($job['description'] ?: '<p>' . h($job['summary']) . '</p>')
        . ($job['responsibilities'] ? '<h3>Responsibilities</h3>' . $job['responsibilities'] : '')
        . ($job['requirements'] ? '<h3>Requirements</h3>' . $job['requirements'] : '')
        . ($job['benefits'] ? '<h3>Benefits</h3>' . $job['benefits'] : '')
    ),
    'identifier' => [
        '@type' => 'PropertyValue',
        'name'  => $company,
        'value' => $job['reference'],
    ],
    'datePosted'          => $job['posted_iso'],
    'employmentType'      => $job['type_schema'],
    'hiringOrganization'  => [
        '@type'  => 'Organization',
        'name'   => $company,
        'sameAs' => SITE_URL,
        'logo'   => SITE_URL . '/assets/images/logo.webp',
    ],
    'totalJobOpenings' => (int) $job['openings'],
    'url'              => $canonical,
];

if ($job['deadline_iso']) {
    $posting['validThrough'] = $job['deadline_iso'];
}

// Remote roles must declare a telecommute type, otherwise Google treats the
// office address as the only place the job can be done.
if ($job['work_mode'] === 'remote') {
    $posting['jobLocationType'] = 'TELECOMMUTE';
    $posting['applicantLocationRequirements'] = [
        '@type' => 'Country',
        'name'  => $job['country'] ?: 'India',
    ];
}

if ($job['city'] !== '' || $job['country'] !== '') {
    $posting['jobLocation'] = [
        '@type'   => 'Place',
        'address' => array_filter([
            '@type'           => 'PostalAddress',
            'streetAddress'   => $job['address'] ?? '',
            'addressLocality' => $job['city'],
            'addressRegion'   => $job['state'],
            'postalCode'      => $job['postal_code'] ?? '',
            'addressCountry'  => $job['country'],
        ], static fn($v) => $v !== ''),
    ];
}

if ($job['salary_min'] !== null || $job['salary_max'] !== null) {
    $posting['baseSalary'] = [
        '@type'    => 'MonetaryAmount',
        'currency' => $job['salary_currency'],
        'value'    => array_filter([
            '@type'    => 'QuantitativeValue',
            'minValue' => $job['salary_min'],
            'maxValue' => $job['salary_max'],
            'unitText' => $job['salary_unit'],
        ], static fn($v) => $v !== null),
    ];
}

if (!empty($job['skills'])) {
    $posting['skills'] = implode(', ', $job['skills']);
}
if ($job['education'] !== '') {
    $posting['educationRequirements'] = $job['education'];
}
if ($job['experience_min'] !== null && $job['experience_min'] > 0) {
    $posting['experienceRequirements'] = [
        '@type'                => 'OccupationalExperienceRequirements',
        'monthsOfExperience'   => (int) round($job['experience_min'] * 12),
    ];
}

$head_extra = '<script type="application/ld+json">'
    . json_encode($posting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    . '</script>' . <<<'HTML'
<style>
/* ── Job detail ── */
.jd-wrap { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:34px; align-items:start; }
@media (max-width: 980px) { .jd-wrap { grid-template-columns:1fr; } }

.jd-body { background:var(--surface); border:1px solid var(--line); border-radius:var(--r-md); padding:34px 36px; box-shadow:var(--e1); }
.jd-body h2 { font-size:1.25rem; color:var(--navy); margin:30px 0 12px; }
.jd-body h2:first-child { margin-top:0; }
.jd-body h3 { font-size:1.05rem; color:var(--navy); margin:22px 0 10px; }
.jd-body p, .jd-body li { color:var(--text); line-height:1.75; font-size:.97rem; }
.jd-body ul, .jd-body ol { padding-left:22px; margin:10px 0; display:grid; gap:7px; }
.jd-body a { color:var(--cyan-dark); }
.jd-body table { width:100%; border-collapse:collapse; margin:14px 0; }
.jd-body td, .jd-body th { border:1px solid var(--line); padding:8px 12px; font-size:.92rem; }

.jd-side { position:sticky; top:96px; display:grid; gap:18px; }
.jd-card { background:var(--surface); border:1px solid var(--line); border-radius:var(--r-md); padding:24px 26px; box-shadow:var(--e1); }
.jd-facts { display:grid; gap:14px; }
.jd-fact { display:flex; gap:12px; align-items:flex-start; }
.jd-fact svg { width:18px; height:18px; flex:none; color:var(--cyan-dark); margin-top:2px; }
.jd-fact-l { font-size:.72rem; text-transform:uppercase; letter-spacing:.5px; color:var(--text-mute); font-weight:700; }
.jd-fact-v { font-size:.94rem; color:var(--ink); font-weight:600; }
.jd-skills { display:flex; flex-wrap:wrap; gap:7px; margin-top:6px; }
.jd-deadline { background:#FEF3C7; color:#92400E; border-radius:10px; padding:10px 14px; font-size:.85rem; font-weight:600; }
.jd-closed { background:#FEE2E2; color:#991B1B; border-radius:10px; padding:14px 16px; font-weight:600; }

.jd-form { display:grid; gap:16px; }
.jd-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media (max-width: 620px) { .jd-row { grid-template-columns:1fr; } }
.jd-field label { display:block; font-size:.82rem; font-weight:700; color:var(--navy); margin-bottom:6px; }
.jd-field .req { color:#DC2626; }
.jd-field input, .jd-field select, .jd-field textarea {
  width:100%; padding:12px 15px; border:1.5px solid var(--line); border-radius:12px;
  font:inherit; font-size:.94rem; background:#fff; color:var(--ink); outline:none; transition:border-color .2s;
}
.jd-field input:focus, .jd-field select:focus, .jd-field textarea:focus { border-color:var(--cyan); }
.jd-field textarea { min-height:110px; resize:vertical; }
.jd-field .hint { font-size:.78rem; color:var(--text-mute); margin-top:5px; }
.jd-choices { display:grid; gap:8px; }
.jd-choices label { display:flex; gap:9px; align-items:center; font-weight:500; font-size:.92rem; color:var(--text); }
.jd-choices input { width:auto; }
.jd-file { border:1.5px dashed var(--line); border-radius:12px; padding:18px; text-align:center; cursor:pointer; display:block; transition:border-color .2s, background .2s; }
.jd-file:hover { border-color:var(--cyan); background:var(--cyan-tint); }
.jd-file input { display:none; }
.jd-file-name { font-size:.86rem; color:var(--cyan-dark); font-weight:700; margin-top:6px; }
.jd-status { border-radius:12px; padding:14px 16px; font-size:.9rem; font-weight:600; display:none; }
.jd-status.err { display:block; background:#FEE2E2; color:#991B1B; }
.jd-status.ok  { display:block; background:var(--green-tint); color:var(--green-dark); }
.jd-hp { position:absolute; left:-9999px; opacity:0; height:0; width:0; }
.jd-done { text-align:center; padding:34px 20px; }
.jd-done .tick { width:64px; height:64px; border-radius:50%; background:var(--green-tint); color:var(--green-dark); display:grid; place-items:center; margin:0 auto 16px; }
.jd-done .tick svg { width:30px; height:30px; }
.jd-ref { font-family:ui-monospace,SFMono-Regular,Menlo,monospace; background:var(--bg-soft); border-radius:8px; padding:6px 12px; display:inline-block; font-weight:700; color:var(--navy); }
.jd-related a { display:block; padding:12px 0; border-bottom:1px solid var(--line-soft); color:var(--navy); font-weight:700; font-size:.93rem; text-decoration:none; }
.jd-related a:last-child { border-bottom:0; }
.jd-related a:hover { color:var(--cyan-dark); }
.jd-related span { display:block; font-weight:500; font-size:.8rem; color:var(--text-mute); margin-top:2px; }
</style>
HTML;

$foot_scripts = ['js/career.js'];
require __DIR__ . '/partials/head.php';

/** One fact row in the sidebar. */
function jd_fact(string $icon, string $label, string $value): void
{
    if (trim($value) === '') {
        return;
    }
    ?>
    <div class="jd-fact">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $icon ?></svg>
      <div><div class="jd-fact-l"><?= h($label) ?></div><div class="jd-fact-v"><?= h($value) ?></div></div>
    </div>
    <?php
}
?>
<!-- ===== HERO ===== -->
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span><a href="/careers">Careers</a><span>›</span><?= h($job['title']) ?></div>
    <h1><?= h($job['title']) ?></h1>
    <p>
      <?= h($job['department'] !== '' ? $job['department'] . ' · ' : '') ?><?= h($job['type_label']) ?>
      <?= $job['location'] !== '' ? ' · ' . h($job['location']) : '' ?> · <?= h($job['work_mode_label']) ?>
    </p>
    <div class="hero-actions hero-actions--center">
      <?php if ($external): ?>
        <a href="<?= h($job['external_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">Apply on our partner site</a>
      <?php elseif ($applyOpen): ?>
        <a href="#apply" class="btn btn-primary btn-lg">Apply for this role</a>
      <?php endif; ?>
      <a href="/careers" class="btn btn-ghost-light btn-lg">All openings</a>
    </div>
  </div>
</header>

<!-- ===== BODY ===== -->
<section class="section">
  <div class="container">
    <div class="jd-wrap">
      <div>
        <article class="jd-body reveal">
          <?php if ($job['summary'] !== ''): ?>
            <p class="lead" style="margin-bottom:24px;"><?= h($job['summary']) ?></p>
          <?php endif; ?>

          <?php if ($job['description'] !== ''): ?>
            <h2>About the role</h2>
            <?= $job['description'] /* admin-authored, tag-filtered by the CRM */ ?>
          <?php endif; ?>

          <?php if ($job['responsibilities'] !== ''): ?>
            <h2>What you will do</h2>
            <?= $job['responsibilities'] ?>
          <?php endif; ?>

          <?php if ($job['requirements'] !== ''): ?>
            <h2>What we are looking for</h2>
            <?= $job['requirements'] ?>
          <?php endif; ?>

          <?php if ($job['benefits'] !== ''): ?>
            <h2>What we offer</h2>
            <?= $job['benefits'] ?>
          <?php endif; ?>

          <?php if (!empty($job['skills'])): ?>
            <h2>Skills</h2>
            <div class="jd-skills">
              <?php foreach ($job['skills'] as $skill): ?>
                <span class="chip dept"><?= h($skill) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </article>

        <!-- ===== APPLY ===== -->
        <div id="apply" style="margin-top:30px;">
          <?php if ($expired): ?>
            <div class="jd-body jd-closed">Applications for this position closed on <?= h(date('d M Y', strtotime($job['deadline']))) ?>. Do have a look at our other openings.</div>
          <?php elseif ($external): ?>
            <div class="jd-body">
              <h2>How to apply</h2>
              <p>Applications for this role are handled on an external site.</p>
              <p><a href="<?= h($job['external_url']) ?>" target="_blank" rel="noopener" class="btn btn-primary">Continue to the application</a></p>
            </div>
          <?php elseif (!$applyOpen): ?>
            <div class="jd-body jd-closed">We are not accepting online applications for this role at the moment. Please email your CV to <a href="mailto:sales@healtho.pro">sales@healtho.pro</a>.</div>
          <?php else: ?>
            <div class="jd-body reveal">
              <h2>Apply for this position</h2>
              <p style="color:var(--text-soft);margin-bottom:22px;">Fields marked <span class="req" style="color:#DC2626">*</span> are required. We review every application personally.</p>

              <!--
                Posts straight to the CRM's keyless embed endpoint — the same
                one the paste-anywhere snippet uses. No proxy, no API key.
              -->
              <form class="jd-form" id="jdApply" novalidate
                    data-endpoint="<?= h(CAREERS_EMBED_BASE) ?>/apply"
                    data-max-mb="<?= $maxMb ?>"
                    data-allowed="<?= h(implode(',', $allowedExt)) ?>">
                <input type="hidden" name="slug" value="<?= h($job['slug']) ?>">
                <input type="hidden" name="source" value="website">
                <input type="hidden" name="utm" id="jdUtm" value="">
                <input type="text" name="company_website" class="jd-hp" tabindex="-1" autocomplete="off" aria-hidden="true">

                <div class="jd-status" id="jdStatus" role="alert"></div>

                <div class="jd-row">
                  <div class="jd-field">
                    <label for="jd-name">Full name <span class="req">*</span></label>
                    <input type="text" id="jd-name" name="name" required autocomplete="name" placeholder="Your full name">
                  </div>
                  <div class="jd-field">
                    <label for="jd-email">Email <span class="req">*</span></label>
                    <input type="email" id="jd-email" name="email" required autocomplete="email" placeholder="you@email.com">
                  </div>
                </div>

                <div class="jd-row">
                  <?php if (!empty($fields['phone'])): ?>
                    <div class="jd-field">
                      <label for="jd-phone">Mobile number <span class="req">*</span></label>
                      <input type="tel" id="jd-phone" name="phone" required autocomplete="tel" placeholder="+91 ...">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($fields['current_location'])): ?>
                    <div class="jd-field">
                      <label for="jd-loc">Current location</label>
                      <input type="text" id="jd-loc" name="current_location" placeholder="City, Country">
                    </div>
                  <?php endif; ?>
                </div>

                <div class="jd-row">
                  <?php if (!empty($fields['total_experience'])): ?>
                    <div class="jd-field">
                      <label for="jd-exp">Total experience (years)</label>
                      <input type="number" step="0.5" min="0" max="50" id="jd-exp" name="total_experience" placeholder="e.g. 4">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($fields['current_company'])): ?>
                    <div class="jd-field">
                      <label for="jd-co">Current company</label>
                      <input type="text" id="jd-co" name="current_company" placeholder="Where you work now">
                    </div>
                  <?php endif; ?>
                </div>

                <div class="jd-row">
                  <?php if (!empty($fields['current_ctc'])): ?>
                    <div class="jd-field">
                      <label for="jd-cctc">Current CTC</label>
                      <input type="text" id="jd-cctc" name="current_ctc" placeholder="e.g. ₹8 LPA">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($fields['expected_ctc'])): ?>
                    <div class="jd-field">
                      <label for="jd-ectc">Expected CTC</label>
                      <input type="text" id="jd-ectc" name="expected_ctc" placeholder="e.g. ₹12 LPA">
                    </div>
                  <?php endif; ?>
                </div>

                <div class="jd-row">
                  <?php if (!empty($fields['notice_period'])): ?>
                    <div class="jd-field">
                      <label for="jd-notice">Notice period</label>
                      <input type="text" id="jd-notice" name="notice_period" placeholder="e.g. 30 days / Immediate">
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($fields['linkedin_url'])): ?>
                    <div class="jd-field">
                      <label for="jd-li">LinkedIn profile</label>
                      <input type="url" id="jd-li" name="linkedin_url" placeholder="https://linkedin.com/in/…">
                    </div>
                  <?php endif; ?>
                </div>

                <?php if (!empty($fields['portfolio_url'])): ?>
                  <div class="jd-field">
                    <label for="jd-pf">Portfolio / GitHub</label>
                    <input type="url" id="jd-pf" name="portfolio_url" placeholder="https://…">
                  </div>
                <?php endif; ?>

                <?php foreach ($questions as $question):
                  $qname = 'q_' . (int) $question['id'];
                  $req   = !empty($question['required']); ?>
                  <div class="jd-field">
                    <label for="<?= h($qname) ?>"><?= h($question['question']) ?><?= $req ? ' <span class="req">*</span>' : '' ?></label>
                    <?php if ($question['type'] === 'textarea'): ?>
                      <textarea id="<?= h($qname) ?>" name="<?= h($qname) ?>"<?= $req ? ' required' : '' ?>></textarea>
                    <?php elseif ($question['type'] === 'select'): ?>
                      <select id="<?= h($qname) ?>" name="<?= h($qname) ?>"<?= $req ? ' required' : '' ?>>
                        <option value="">Select…</option>
                        <?php foreach ($question['options'] as $option): ?>
                          <option><?= h($option) ?></option>
                        <?php endforeach; ?>
                      </select>
                    <?php elseif ($question['type'] === 'yesno'): ?>
                      <div class="jd-choices">
                        <label><input type="radio" name="<?= h($qname) ?>" value="Yes"<?= $req ? ' required' : '' ?>> Yes</label>
                        <label><input type="radio" name="<?= h($qname) ?>" value="No"> No</label>
                      </div>
                    <?php elseif ($question['type'] === 'radio'): ?>
                      <div class="jd-choices">
                        <?php foreach ($question['options'] as $option): ?>
                          <label><input type="radio" name="<?= h($qname) ?>" value="<?= h($option) ?>"<?= $req ? ' required' : '' ?>> <?= h($option) ?></label>
                        <?php endforeach; ?>
                      </div>
                    <?php elseif ($question['type'] === 'checkbox'): ?>
                      <div class="jd-choices">
                        <?php foreach ($question['options'] as $option): ?>
                          <label><input type="checkbox" name="<?= h($qname) ?>[]" value="<?= h($option) ?>"> <?= h($option) ?></label>
                        <?php endforeach; ?>
                      </div>
                    <?php else:
                      $inputType = in_array($question['type'], ['number', 'date', 'url'], true) ? $question['type'] : 'text'; ?>
                      <input type="<?= h($inputType) ?>" id="<?= h($qname) ?>" name="<?= h($qname) ?>"<?= $req ? ' required' : '' ?>>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>

                <?php if (!empty($fields['cover_letter'])): ?>
                  <div class="jd-field">
                    <label for="jd-cover">Why are you a good fit?</label>
                    <textarea id="jd-cover" name="cover_letter" placeholder="A short note about you and why this role interests you…"></textarea>
                  </div>
                <?php endif; ?>

                <?php if (!empty($fields['resume'])): ?>
                  <div class="jd-field">
                    <label>Resume / CV <?= $resumeReq ? '<span class="req">*</span>' : '' ?></label>
                    <label class="jd-file" id="jdFileBox">
                      <input type="file" name="resume" id="jdFile" accept=".<?= h(implode(',.', $allowedExt)) ?>"<?= $resumeReq ? ' required' : '' ?>>
                      <strong style="color:var(--navy)">Click to attach your CV</strong>
                      <div class="hint"><?= h(strtoupper(implode(', ', $allowedExt))) ?> · up to <?= $maxMb ?> MB</div>
                      <div class="jd-file-name" id="jdFileName"></div>
                    </label>
                  </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary btn-lg" id="jdSubmit">Submit application</button>
                <p class="hint" style="font-size:.8rem;color:var(--text-mute);margin:0;">
                  By applying you agree to our <a href="/privacy-policy" class="link-cyan">Privacy Policy</a>.
                  Your details are shared only with our hiring team.
                </p>
              </form>

              <div id="jdDone" class="jd-done" hidden>
                <div class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg></div>
                <h3 class="h-card">Application received</h3>
                <p style="color:var(--text-soft);margin:8px 0 16px;">Thank you for applying for <strong><?= h($job['title']) ?></strong>. A confirmation is on its way to your inbox.</p>
                <p>Your reference: <span class="jd-ref" id="jdRef"></span></p>
                <a href="/careers" class="btn btn-outline" style="margin-top:18px;">Browse other openings</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- ===== SIDEBAR ===== -->
      <aside class="jd-side">
        <div class="jd-card reveal">
          <div class="jd-facts">
            <?php
            jd_fact('<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>', 'Employment type', $job['type_label']);
            jd_fact('<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>', 'Location', $job['location']);
            jd_fact('<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M8 2v4M16 2v4"/>', 'Work mode', $job['work_mode_label']);
            jd_fact('<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>', 'Experience', $job['experience']);
            jd_fact('<path d="M12 3 2 8l10 5 10-5-10-5Z"/><path d="M6 10.5V17c0 1.7 2.7 3 6 3s6-1.3 6-3v-6.5"/>', 'Education', $job['education']);
            jd_fact('<circle cx="12" cy="12" r="10"/><path d="M12 6v12M9.5 9.5a2.5 2.5 0 0 1 5 0c0 3-5 2-5 5a2.5 2.5 0 0 0 5 0"/>', 'Compensation', $job['salary'] ?: $job['stipend']);
            jd_fact('<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>', 'Openings', (int) $job['openings'] > 1 ? $job['openings'] . ' positions' : '1 position');
            jd_fact('<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 2"/>', 'Duration', $job['duration_months'] ? $job['duration_months'] . ' months' : '');
            jd_fact('<path d="M3 3v18h18"/><path d="m7 14 4-4 3 3 5-6"/>', 'Reference', $job['reference']);
            jd_fact('<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>', 'Posted', $job['posted_ago']);
            ?>
          </div>

          <?php if ($job['deadline'] && !$expired): ?>
            <div class="jd-deadline" style="margin-top:18px;">⏳ Apply by <?= h(date('d M Y', strtotime($job['deadline']))) ?></div>
          <?php endif; ?>

          <?php if ($applyOpen && !$expired): ?>
            <a href="#apply" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:18px;">Apply now</a>
          <?php endif; ?>
        </div>

        <?php if ($related): ?>
          <div class="jd-card reveal jd-related">
            <h4 style="color:var(--navy);margin-bottom:6px;">Similar openings</h4>
            <?php foreach ($related as $other): ?>
              <a href="<?= h(career_url($other['slug'])) ?>">
                <?= h($other['title']) ?>
                <span><?= h(trim($other['type_label'] . ' · ' . $other['location'], ' ·')) ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="jd-card reveal">
          <h4 style="color:var(--navy);margin-bottom:8px;">Questions about this role?</h4>
          <p style="color:var(--text-soft);font-size:.9rem;margin:0 0 12px;">Our talent team is happy to help.</p>
          <a href="mailto:sales@healtho.pro" class="link-cyan" style="font-weight:700;">sales@healtho.pro</a>
        </div>
      </aside>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
