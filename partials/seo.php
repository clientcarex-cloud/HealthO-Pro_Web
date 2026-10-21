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
    'facebook'  => 'https://www.facebook.com/profile.php?id=61575489640939',
    'x'         => '',
    'instagram' => 'https://www.instagram.com/healthocare/',
    'youtube'   => 'https://www.youtube.com/@HealthO-YourCaringPartner',
];

/**
 * Social icon links. Profiles left blank above are skipped, and a WhatsApp
 * chat icon is added at the end. Used in the footer and on the Contact page.
 */
function social_links(string $class = 'socials'): string
{
    $icons = [
        'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77Z"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z"/></svg>',
        'x' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.46l8.6-9.83L0 1.15h7.59l5.24 6.93 6.07-6.93Zm-1.29 19.5h2.04L6.49 3.24H4.3L17.61 20.65Z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 1.95c-3.15 0-3.52.01-4.76.07-.92.04-1.42.2-1.75.33-.44.17-.75.37-1.08.7-.33.33-.53.64-.7 1.08-.13.33-.29.83-.33 1.75-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.04.92.2 1.42.33 1.75.17.44.37.75.7 1.08.33.33.64.53 1.08.7.33.13.83.29 1.75.33 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c.92-.04 1.42-.2 1.75-.33.44-.17.75-.37 1.08-.7.33-.33.53-.64.7-1.08.13-.33.29-.83.33-1.75.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.04-.92-.2-1.42-.33-1.75a2.9 2.9 0 0 0-.7-1.08 2.9 2.9 0 0 0-1.08-.7c-.33-.13-.83-.29-1.75-.33-1.24-.06-1.61-.07-4.76-.07Zm0 3.32a4.57 4.57 0 1 1 0 9.14 4.57 4.57 0 0 1 0-9.14Zm0 7.54a2.97 2.97 0 1 0 0-5.94 2.97 2.97 0 0 0 0 5.94Zm5.82-7.76a1.07 1.07 0 1 1-2.14 0 1.07 1.07 0 0 1 2.14 0Z"/></svg>',
        'youtube' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31.4 31.4 0 0 0 0 12a31.4 31.4 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31.4 31.4 0 0 0 24 12a31.4 31.4 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.3 3.6-6.3 3.6Z"/></svg>',
        'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35M12 21.5a9.5 9.5 0 0 1-4.84-1.32l-.35-.21-3.59.94.96-3.5-.23-.36A9.5 9.5 0 1 1 12 21.5M12 2a11.5 11.5 0 0 0-9.86 17.4L1 23l3.7-1.12A11.5 11.5 0 1 0 12 2"/></svg>',
    ];
    $names = ['linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'x' => 'X', 'instagram' => 'Instagram', 'youtube' => 'YouTube'];
    $out = '';
    foreach ($names as $key => $name) {
        $url = SOCIAL_PROFILES[$key] ?? '';
        if ($url === '') continue;
        $out .= '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener me" aria-label="HealthO Pro on ' . $name . '" title="' . $name . '">' . $icons[$key] . '</a>';
    }
    $out .= '<a href="' . htmlspecialchars(WHATSAPP_URL) . '" target="_blank" rel="noopener" aria-label="Chat with HealthO Pro on WhatsApp" title="WhatsApp">' . $icons['whatsapp'] . '</a>';
    return '<div class="' . $class . '">' . $out . '</div>';
}

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
    'our-clients'      => 'Our Clients',
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
