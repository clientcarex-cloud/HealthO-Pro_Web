<?php
/**
 * Feature guides — the content behind /blog, /blog/{slug} and /features.
 *
 * Each post lives in partials/features/*.php, one file per category, as
 * slug => [
 *   'name'     feature name as shown on /features
 *   'cat'      a FEATURE_CATEGORIES key
 *   'for'      who it is built for: any of hims, lims, cims, ris
 *   'short'    one line for cards (~90 chars)
 *   'title'    the post's H1
 *   'desc'     meta description (≤ 155 chars)
 *   'intro'    opening paragraph
 *   'points'   [[heading, text], …] — what the feature does
 *   'benefits' [text, …] — why it matters
 *   'faqs'     [question => answer]
 * ]
 * Adding a post is adding an entry: the blog, the features page, the sitemap
 * and llms.txt links all read from here.
 */
require_once __DIR__ . '/site.php';

/** Publication date shown on posts (ISO 8601). */
const BLOG_PUBLISHED = '2026-09-24';

/**
 * Pretty post URLs (/blog/{slug}) need the rewrite in .htaccess — and, because
 * this domain also serves the CRM, the server must send /blog/ paths to the
 * website at all (as it already does for /careers/). Until it does, the CRM
 * answers them with its 404, so posts use /blog-post?s={slug}, which needs no
 * server change. Flip to true once /blog/ is routed to the website.
 */
const BLOG_PRETTY_URLS = false;

/** Blog index or post URL. Used for links, canonicals, structured data and the sitemap. */
function blog_url(string $slug = '', bool $absolute = false): string
{
    if ($slug === '') {
        $path = '/blog';
    } else {
        $path = BLOG_PRETTY_URLS
            ? '/blog/' . rawurlencode($slug)
            : '/blog-post?s=' . rawurlencode($slug);
    }
    return $absolute ? SITE_URL . $path : $path;
}

/** key => [name, blurb, svg path(s) for a 24×24 stroke icon] — in display order. */
const FEATURE_CATEGORIES = [
    'patient'   => ['Patient Experience & Front Desk', 'Bookings, queues, self check-in and loyalty — everything a patient touches.', '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>'],
    'billing'   => ['Billing & Finance', 'Invoices, collections, refunds and expenses with a full audit trail.', '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>'],
    'analytics' => ['Reports & Analytics', 'Live dashboards and reports that show how every shift and department performs.', '<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-6"/>'],
    'clinical'  => ['Clinical & Doctors', 'Records, prescriptions, reporting and compliance for doctors and clinicians.', '<path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6 6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>'],
    'hospital'  => ['Hospital & Inpatient', 'Wards, theatres, rooms, blood bank and the facilities around them.', '<path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/><path d="M9 9v.01M9 12v.01M9 15v.01"/>'],
    'lab'       => ['Laboratory & Diagnostics', 'From sample collection to validated report — tests, packages, QC and referrals.', '<path d="M9 2v6L4 18a2 2 0 0 0 1.8 3h12.4a2 2 0 0 0 1.8-3L15 8V2"/><path d="M8 2h8M7 15h10"/>'],
    'growth'    => ['Marketing & Growth', 'Leads, campaigns and patient communication that bring people back.', '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>'],
    'ai'        => ['AI & Automation', 'An AI assistant, AI call agents and reminders that work in the background.', '<path d="M12 8V4H8"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M2 14h2M20 14h2M15 13v2M9 13v2"/>'],
    'team'      => ['Team & HR', 'Staff, shifts, tasks, hiring, attendance and payroll in the same system.', '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
    'admin'     => ['Administration & Operations', 'Masters, SOPs, assets, purchases, vendors and support for the back office.', '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'],
];

/** Audience labels for the 'for' field, linking to each product page. */
const FEATURE_AUDIENCE = [
    'hims' => 'Hospitals',
    'lims' => 'Laboratories',
    'cims' => 'Clinics',
    'ris'  => 'Radiology centers',
];

/** Every post, slug => post (with 'slug' filled in), in category order. */
function blog_posts(): array
{
    static $posts = null;
    if ($posts !== null) {
        return $posts;
    }

    $all = [];
    foreach (glob(__DIR__ . '/features/*.php') as $file) {
        $all += require $file;
    }

    $order = array_flip(array_keys(FEATURE_CATEGORIES));
    // Stable (PHP 8), so posts keep their file order within a category.
    uksort($all, static fn($a, $b) => $order[$all[$a]['cat']] <=> $order[$all[$b]['cat']]);

    $posts = [];
    foreach ($all as $slug => $post) {
        $post['slug'] = $slug;
        $posts[$slug] = $post;
    }

    return $posts;
}

/** Posts grouped by category key, in category order. */
function blog_by_category(): array
{
    $groups = array_fill_keys(array_keys(FEATURE_CATEGORIES), []);
    foreach (blog_posts() as $slug => $post) {
        $groups[$post['cat']][$slug] = $post;
    }

    return array_filter($groups);
}

/** Minutes to read, from the words a post puts on the page. */
function blog_read_minutes(array $post): int
{
    $text = $post['intro'] . ' ' . implode(' ', $post['benefits']);
    foreach ($post['points'] as [$h, $t]) {
        $text .= " $h $t";
    }
    foreach ($post['faqs'] as $q => $a) {
        $text .= " $q $a";
    }

    return max(2, (int) ceil(str_word_count($text) / 200));
}

/**
 * Cover image for a post, from assets/images/blog/{slug}: 'lg' is the 1200×672
 * WebP for the post page, 'sm' the 640-wide WebP for cards and 'share' the JPG
 * for og:image and structured data. Empty string when the post has no cover.
 */
function blog_image(string $slug, string $size = 'lg', bool $absolute = false): string
{
    $file = match ($size) {
        'sm'    => "$slug-sm.webp",
        'share' => "$slug.jpg",
        default => "$slug.webp",
    };
    if (!is_file(__DIR__ . '/../assets/images/blog/' . $file)) {
        return '';
    }
    $path = '/assets/images/blog/' . rawurlencode($file);
    return $absolute ? SITE_URL . $path : $path;
}

/** Inline category icon. */
function blog_icon(string $cat, string $class = ''): string
{
    return '<svg class="' . h($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . FEATURE_CATEGORIES[$cat][2] . '</svg>';
}
