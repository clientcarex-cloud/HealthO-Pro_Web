<?php
/**
 * XML sitemap — served at /sitemap.xml (rewrite in .htaccess).
 *
 * Built on request so it never goes stale: every page's <lastmod> is its source
 * file's modification time (the latest of the page and the partials it renders),
 * and live job openings come from the same CRM feed /careers renders, so a new
 * opening is discoverable by search engines as soon as it is published.
 *
 * Pages marked noindex (verify-partner) are left out on purpose.
 */
require __DIR__ . '/partials/careers-data.php';   // pulls in partials/site.php
require __DIR__ . '/partials/blog-data.php';

/** slug => [changefreq, priority, images[]] */
$pages = [
    ''                 => ['weekly',  '1.0', ['hero-doctors-machines.webp', 'logo.png']],
    'solutions'        => ['monthly', '0.9', []],
    'hims'             => ['monthly', '0.9', ['hims.webp']],
    'lims'             => ['monthly', '0.9', ['lims.webp']],
    'cims'             => ['monthly', '0.9', ['cms.webp']],
    'ris'              => ['monthly', '0.9', ['ris.webp']],
    'features'         => ['weekly',  '0.9', []],
    'blog'             => ['weekly',  '0.8', []],
    'pricing'          => ['weekly',  '0.9', []],
    'testimonials'     => ['monthly', '0.7', []],
    'partners'         => ['weekly',  '0.6', []],
    'careers'          => ['daily',   '0.7', []],
    'contact'          => ['monthly', '0.8', []],
    'message-to-ceo'   => ['yearly',  '0.4', []],
    'privacy-policy'   => ['yearly',  '0.2', []],
    'terms-conditions' => ['yearly',  '0.2', []],
];

// Shared partials change what every page renders, so they count towards lastmod.
$shared = max(array_map('filemtime', glob(__DIR__ . '/partials/*.php')));

$urls = [];
foreach ($pages as $slug => [$freq, $prio, $images]) {
    $file = __DIR__ . '/' . ($slug === '' ? 'index' : $slug) . '.php';
    if (!is_file($file)) {
        continue;
    }
    $urls[] = [
        'loc'     => SITE_URL . '/' . $slug,
        'lastmod' => date('c', max(filemtime($file), $shared)),
        'freq'    => $freq,
        'prio'    => $prio,
        'images'  => array_map(static fn($img) => SITE_URL . '/assets/images/' . $img, $images),
    ];
}

// Feature guides: lastmod is the latest edit to their content or template.
$blogFiles = array_merge(glob(__DIR__ . '/partials/features/*.php'), [__DIR__ . '/blog-post.php']);
$blogMod   = max(strtotime(BLOG_PUBLISHED), $shared, ...array_map('filemtime', $blogFiles));
foreach (blog_posts() as $slug => $post) {
    $urls[] = [
        'loc'     => blog_url($slug, true),
        'lastmod' => date('c', $blogMod),
        'freq'    => 'monthly',
        'prio'    => '0.7',
        'images'  => [],
    ];
}

$data = ho_careers_jobs();
foreach ((!empty($data['ok']) && !empty($data['jobs'])) ? $data['jobs'] : [] as $job) {
    if (empty($job['slug'])) {
        continue;
    }
    $posted = !empty($job['posted_iso']) ? strtotime($job['posted_iso']) : false;
    $urls[] = [
        'loc'     => career_url($job['slug'], true),
        'lastmod' => $posted ? date('c', $posted) : null,
        'freq'    => 'weekly',
        'prio'    => '0.6',
        'images'  => [],
    ];
}

header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600');
header('X-Robots-Tag: noindex');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">', "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>', h($u['loc']), "</loc>\n";
    if ($u['lastmod']) {
        echo '    <lastmod>', $u['lastmod'], "</lastmod>\n";
    }
    echo '    <changefreq>', $u['freq'], "</changefreq>\n";
    echo '    <priority>', $u['prio'], "</priority>\n";
    foreach ($u['images'] as $img) {
        echo '    <image:image><image:loc>', h($img), "</image:loc></image:image>\n";
    }
    echo "  </url>\n";
}
echo "</urlset>\n";
