<?php
/**
 * Search-engine and AI-assistant metadata, emitted by partials/head.php.
 *
 * Every page gets Open Graph / Twitter tags and one JSON-LD @graph holding the
 * Organization, the WebSite, this WebPage and its BreadcrumbList. Pages add to
 * it by setting, before head.php:
 *   $faqs      [question => answer] — FAQPage markup; show it with faq_section()
 *   $schema    extra JSON-LD nodes (arrays, no @context)
 *   $crumbs    [name => url] between Home and this page, overriding the default
 *   $robots    robots meta, e.g. 'noindex, follow'
 *   $og_image  absolute URL of the share image
 *   $og_type   defaults to 'website'
 */
require_once __DIR__ . '/site.php';

const ORG_ID      = SITE_URL . '/#organization';
const WEBSITE_ID  = SITE_URL . '/#website';
const OG_IMAGE    = SITE_URL . '/assets/images/og-image.jpg';
const ORG_NAME    = 'Healthocare Private Limited';
const BRAND_NAME  = 'HealthO Pro';

/**
 * Official profiles, used as Organization.sameAs — search engines and AI
 * assistants tie the brand to them. Add each real profile URL here (LinkedIn,
 * Facebook, X, Instagram, YouTube, Crunchbase…); the footer icons read them too.
 */
const SOCIAL_PROFILES = [
    'linkedin'  => '',
    'facebook'  => '',
    'x'         => '',
    'instagram' => '',
];

/** Breadcrumb label for each page slug. */
const PAGE_LABELS = [
    'solutions'        => 'Solutions',
    'hims'             => 'HIMS',
    'lims'             => 'LIMS',
    'cims'             => 'CIMS',
    'ris'              => 'RIS / RIMS',
    'features'         => 'Features',
    'blog'             => 'Blog',
    'pricing'          => 'Pricing',
    'testimonials'     => 'Testimonials',
    'partners'         => 'Channel Partners',
    'verify-partner'   => 'Verify Partner',
    'careers'          => 'Careers',
    'contact'          => 'Contact Us',
    'message-to-ceo'   => 'Message to CEO',
    'privacy-policy'   => 'Privacy Policy',
    'terms-conditions' => 'Terms & Conditions',
];

function seo_organization(): array
{
    return [
        '@type'         => ['Organization', 'Corporation'],
        '@id'           => ORG_ID,
        'name'          => ORG_NAME,
        'alternateName' => [BRAND_NAME, 'HealthOPro', 'Healthocare'],
        'url'           => SITE_URL . '/',
        'logo'          => [
            '@type'  => 'ImageObject',
            'url'    => SITE_URL . '/assets/images/logo.png',
            'width'  => 1024,
            'height' => 218,
        ],
        'image'         => OG_IMAGE,
        'slogan'        => 'Empowering Healthcare Providers',
        'description'   => 'Healthocare Private Limited builds HealthO Pro, cloud-enabled healthcare software — HIMS for hospitals, LIMS for laboratories, CIMS for clinics and RIS/RIMS for radiology centers — serving healthcare providers since 2011.',
        'foundingDate'  => '2011',
        'email'         => 'sales@healtho.pro',
        'telephone'     => '+91-97007-30044',
        'address'       => ['@type' => 'PostalAddress', 'addressCountry' => 'IN'],
        'areaServed'    => ['India', 'United Arab Emirates', 'Qatar', 'Saudi Arabia', 'Bahrain', 'Kuwait', 'Egypt', 'Canada'],
        'knowsAbout'    => [
            'Hospital Information Management System', 'Laboratory Information Management System',
            'Clinic Management Software', 'Radiology Information System', 'Electronic Medical Records',
            'Healthcare billing', 'Healthcare IT',
        ],
        'contactPoint'  => [
            [
                '@type'             => 'ContactPoint',
                'contactType'       => 'sales',
                'telephone'         => '+91-97007-30044',
                'email'             => 'sales@healtho.pro',
                'areaServed'        => ['IN', 'AE', 'QA', 'SA', 'BH', 'KW', 'EG', 'CA'],
                'availableLanguage' => ['English'],
            ],
            [
                '@type'             => 'ContactPoint',
                'contactType'       => 'customer support',
                'telephone'         => '+91-97007-10055',
                'email'             => 'support@healtho.pro',
                'areaServed'        => ['IN', 'AE', 'QA', 'SA', 'BH', 'KW', 'EG', 'CA'],
                'availableLanguage' => ['English'],
            ],
        ],
        'sameAs'        => array_values(array_filter(SOCIAL_PROFILES)),
    ];
}

/**
 * A HealthO Pro product as a SoftwareApplication node.
 * $offer is optional: ['price' => '5', 'unit' => 'per patient'].
 */
function seo_software(string $slug, string $name, string $description, string $category, ?array $offer = null): array
{
    $node = [
        '@type'               => 'SoftwareApplication',
        '@id'                 => SITE_URL . '/' . $slug . '#software',
        'name'                => BRAND_NAME . ' ' . $name,
        'url'                 => SITE_URL . '/' . $slug,
        'description'         => $description,
        'applicationCategory' => 'BusinessApplication',
        'applicationSubCategory' => $category,
        'operatingSystem'     => 'Web browser (cloud-hosted)',
        'image'               => SITE_URL . '/assets/images/' . ($slug === 'cims' ? 'cms' : $slug) . '.webp',
        'publisher'           => ['@id' => ORG_ID],
        'provider'            => ['@id' => ORG_ID],
    ];

    if ($offer) {
        $node['offers'] = [
            '@type'              => 'Offer',
            'price'              => $offer['price'],
            'priceCurrency'      => 'INR',
            'description'        => $offer['unit'],
            'url'                => SITE_URL . '/' . $slug . '#payg',
            'seller'             => ['@id' => ORG_ID],
        ];
    } else {
        $node['offers'] = [
            '@type'         => 'Offer',
            'url'           => SITE_URL . '/pricing',
            'priceCurrency' => 'INR',
            'availability'  => 'https://schema.org/InStock',
            'seller'        => ['@id' => ORG_ID],
        ];
    }

    return $node;
}

/** FAQPage node from [question => answer]. */
function seo_faq(array $faqs, string $url): array
{
    $items = [];
    foreach ($faqs as $q => $a) {
        $items[] = [
            '@type'          => 'Question',
            'name'           => $q,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
        ];
    }

    return ['@type' => 'FAQPage', '@id' => $url . '#faq', 'mainEntity' => $items];
}

/**
 * Visible FAQ accordion — the same markup script.js already animates. Answers
 * stay in the HTML (only collapsed by CSS), so crawlers read all of them.
 */
function faq_section(array $faqs, string $heading = 'Frequently asked questions', string $class = 'section'): void
{
    ?>
<section class="<?= h($class) ?>" id="faq">
  <div class="container">
    <div class="sec-head reveal"><span class="eyebrow">FAQ</span><h2 class="h-sec"><?= h($heading) ?></h2></div>
    <div class="faq">
<?php foreach ($faqs as $q => $a): ?>
      <div class="faq-item reveal"><button class="faq-q" type="button"><?= h($q) ?><span class="ic">+</span></button><div class="faq-a"><div class="faq-a-inner"><?= h($a) ?></div></div></div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php
}

/** The whole <head> SEO block for the current page. */
function seo_head(array $o): string
{
    $url      = $o['canonical'];
    $title    = $o['title'];
    $desc     = $o['description'];
    $page     = $o['page'];
    $ogImage  = $o['og_image'] ?: OG_IMAGE;
    $robots   = $o['robots'] ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';

    // Breadcrumbs: Home › [Solutions ›] Page, unless the page supplied its own trail.
    $crumbs = ['Home' => SITE_URL . '/'];
    if ($o['crumbs'] !== null) {
        $crumbs += $o['crumbs'];
    } elseif ($page !== '') {
        if (isset(PRODUCTS[$page])) {
            $crumbs['Solutions'] = SITE_URL . '/solutions';
        }
        $crumbs[PAGE_LABELS[$page] ?? ucfirst($page)] = $url;
    }

    $webPage = [
        '@type'       => $o['page_type'] ?: 'WebPage',
        '@id'         => $url . '#webpage',
        'url'         => $url,
        'name'        => $title,
        'description' => $desc,
        'isPartOf'    => ['@id' => WEBSITE_ID],
        'about'       => ['@id' => ORG_ID],
        'publisher'   => ['@id' => ORG_ID],
        'inLanguage'  => 'en',
        'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $ogImage],
    ];

    $graph = [
        seo_organization(),
        [
            '@type'      => 'WebSite',
            '@id'        => WEBSITE_ID,
            'url'        => SITE_URL . '/',
            'name'       => BRAND_NAME,
            'alternateName' => ORG_NAME,
            'publisher'  => ['@id' => ORG_ID],
            'inLanguage' => 'en',
        ],
    ];

    if (count($crumbs) > 1) {
        $items = [];
        $i = 0;
        foreach ($crumbs as $name => $href) {
            $items[] = ['@type' => 'ListItem', 'position' => ++$i, 'name' => $name, 'item' => $href];
        }
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => $items];
        $webPage['breadcrumb'] = ['@id' => $url . '#breadcrumb'];
    }

    $graph[] = $webPage;

    if ($o['faqs']) {
        $graph[] = seo_faq($o['faqs'], $url);
    }

    foreach ($o['schema'] as $node) {
        $graph[] = $node;
    }

    $json = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );

    $meta = [
        ['name', 'robots', $robots],
        ['property', 'og:type', $o['og_type'] ?: 'website'],
        ['property', 'og:site_name', BRAND_NAME],
        ['property', 'og:locale', 'en_IN'],
        ['property', 'og:url', $url],
        ['property', 'og:title', $title],
        ['property', 'og:description', $desc],
        ['property', 'og:image', $ogImage],
        ['property', 'og:image:alt', BRAND_NAME . ' — cloud HIMS, LIMS, CIMS & RIS software'],
        ['name', 'twitter:card', 'summary_large_image'],
        ['name', 'twitter:title', $title],
        ['name', 'twitter:description', $desc],
        ['name', 'twitter:image', $ogImage],
    ];
    if ($ogImage === OG_IMAGE) {
        $meta[] = ['property', 'og:image:width', '1200'];
        $meta[] = ['property', 'og:image:height', '630'];
    }

    $out = '';
    foreach ($meta as [$attr, $key, $value]) {
        $out .= '<meta ' . $attr . '="' . $key . '" content="' . h($value) . "\">\n";
    }
    $out .= '<link rel="alternate" type="text/plain" title="LLM summary" href="/llms.txt">' . "\n";
    $out .= '<script type="application/ld+json">' . $json . "</script>\n";

    return $out;
}
