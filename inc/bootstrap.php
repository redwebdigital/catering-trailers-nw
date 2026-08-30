<?php
/**
 * Loaded first by every page. Provides $CFG plus the small helper set the
 * templates use. No framework, no dependencies.
 */

declare(strict_types=1);

$CFG = require __DIR__ . '/config.php';

/** Escape for HTML text and attribute contexts. */
function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Absolute URL for a site-relative path. */
function url(string $path = ''): string
{
    global $CFG;
    return $CFG['base_url'] . '/' . ltrim($path, '/');
}

/** Digits-only phone for tel: links. */
function tel_href(): string
{
    global $CFG;
    return 'tel:' . preg_replace('/[^0-9+]/', '', $CFG['phone_e164']);
}

/** Prefilled WhatsApp deep link. */
function whatsapp_href(string $message = ''): string
{
    global $CFG;
    $msg = $message !== '' ? $message
        : 'Hi Catering Trailers NW, I would like a quote for a catering trailer.';
    return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $CFG['whatsapp'])
        . '?text=' . rawurlencode($msg);
}

/**
 * Responsive <picture> for an image in assets/img/gallery.
 * Serves WebP with a JPEG fallback and lazy-loads by default.
 */
function picture(string $slug, string $alt, array $opt = []): string
{
    $widths  = $opt['widths']  ?? [480, 800, 1200];
    $sizes   = $opt['sizes']   ?? '(max-width: 700px) 100vw, 50vw';
    $class   = $opt['class']   ?? '';
    $eager   = $opt['eager']   ?? false;
    $ratio   = $opt['ratio']   ?? null;   // "4/3", prevents layout shift

    $base = '/assets/img/gallery/' . $slug;
    $webp = implode(', ', array_map(fn($w) => "{$base}-{$w}.webp {$w}w", $widths));
    $jpg  = implode(', ', array_map(fn($w) => "{$base}-{$w}.jpg {$w}w", $widths));
    $fallback = $base . '-' . end($widths) . '.jpg';

    $style = $ratio ? ' style="aspect-ratio:' . e($ratio) . '"' : '';
    $load  = $eager ? 'eager' : 'lazy';
    $prio  = $eager ? 'high' : 'auto';

    return '<picture class="' . e($class) . '">'
         . '<source type="image/webp" srcset="' . e($webp) . '" sizes="' . e($sizes) . '">'
         . '<img src="' . e($fallback) . '" srcset="' . e($jpg) . '" sizes="' . e($sizes) . '"'
         . ' alt="' . e($alt) . '" loading="' . $load . '" decoding="async"'
         . ' fetchpriority="' . $prio . '"' . $style . '>'
         . '</picture>';
}

/** LocalBusiness schema, emitted once per page in the footer. */
function schema_local_business(): array
{
    global $CFG;
    $a = $CFG['address'];

    $spec = [];
    foreach ($CFG['hours'] as $h) {
        $spec[] = [
            '@type'       => 'OpeningHoursSpecification',
            'dayOfWeek'   => $h['days'],
            'opens'       => $h['open'],
            'closes'      => $h['close'],
        ];
    }

    $node = [
        '@type'      => ['LocalBusiness', 'AutomotiveBusiness'],
        '@id'        => url('/#business'),
        'name'       => $CFG['name'],
        'url'        => $CFG['base_url'],
        'telephone'  => $CFG['phone_e164'],
        'email'      => $CFG['email'],
        'image'      => url('/assets/img/og-default.jpg'),
        'logo'       => url('/assets/img/logo.png'),
        'description' => 'Bespoke catering trailer manufacturer and repairer based in the '
                       . 'North West. New builds, chassis and accident repairs, gas and '
                       . 'electrical work, stainless fit-outs and refurbishments.',
        'address' => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $a['street'],
            'addressLocality' => $a['locality'],
            'addressRegion'   => $a['region'],
            'postalCode'      => $a['postcode'],
            'addressCountry'  => $a['country'],
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => $CFG['geo']['lat'],
            'longitude' => $CFG['geo']['lng'],
        ],
        'openingHoursSpecification' => $spec,
        'areaServed' => array_values(array_map(
            fn($x) => ['@type' => 'City', 'name' => $x['name']],
            $CFG['areas']
        )),
        'priceRange' => '££££',
    ];

    $social = array_values(array_filter($CFG['social']));
    if ($social) {
        $node['sameAs'] = $social;
    }

    return $node;
}

/** Service schema for a single service page. */
function schema_service(string $name, string $description, string $path): array
{
    global $CFG;
    return [
        '@type'       => 'Service',
        'name'        => $name,
        'description' => $description,
        'url'         => url($path),
        'serviceType' => $name,
        'provider'    => ['@id' => url('/#business')],
        'areaServed'  => array_values(array_map(
            fn($x) => ['@type' => 'City', 'name' => $x['name']],
            $CFG['areas']
        )),
    ];
}

/** FAQPage schema from a [question => answer] map. */
function schema_faq(array $qa): array
{
    return [
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(fn($q, $a) => [
            '@type'          => 'Question',
            'name'           => $q,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
        ], array_keys($qa), array_values($qa)),
    ];
}

/** BreadcrumbList schema from an ordered [label => path] map. */
function schema_breadcrumbs(array $trail): array
{
    $items = [];
    $i = 1;
    foreach ($trail as $label => $path) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i++,
            'name'     => $label,
            'item'     => url($path),
        ];
    }
    return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
}
