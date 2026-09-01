<?php
/**
 * Loaded first by every page. Provides $CFG plus the small helper set the
 * templates use. No framework, no dependencies.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * $CFG is config.php overlaid with anything set in the admin area.
 *
 * config.php stays as the shipped defaults and the safety net: if the database
 * is missing or empty the site still renders exactly as it did before. Every
 * template already reads $CFG, so a value changed in admin reaches the header,
 * footer, schema, buttons and forms without touching a single template.
 */
$CFG = require __DIR__ . '/config.php';

(static function (array &$CFG): void {
    if (!db_ready()) return;                 // not installed yet: defaults stand
    $s = settings_all();
    if (!$s) return;

    // flat setting key => where it lives in $CFG
    $scalar = [
        'biz.name'            => 'name',
        'biz.legal_name'      => 'legal_name',
        'biz.domain'          => 'domain',
        'biz.base_url'        => 'base_url',
        'biz.phone_display'   => 'phone_display',
        'biz.phone_e164'      => 'phone_e164',
        'biz.mobile'          => 'mobile',
        'biz.whatsapp'        => 'whatsapp',
        'biz.email'           => 'email',
        'biz.enquiry_inbox'   => 'enquiry_inbox',
        'biz.mail_from'       => 'mail_from',
        'biz.company_number'  => 'company_number',
        'biz.vat_number'      => 'vat_number',
        'biz.lead_time'       => 'lead_time',
        'biz.chassis_warranty'=> 'chassis_warranty',
        'biz.build_warranty'  => 'build_warranty',
        'seo.google_place_id' => 'google_place_id',
        'seo.google_reviews_url' => 'google_reviews_url',
    ];
    foreach ($scalar as $key => $target) {
        if (isset($s[$key]) && $s[$key] !== '') { $CFG[$target] = $s[$key]; }
    }

    foreach (['street','locality','region','postcode','country'] as $part) {
        if (isset($s['biz.address_' . $part]) && $s['biz.address_' . $part] !== '') {
            $CFG['address'][$part] = $s['biz.address_' . $part];
        }
    }
    foreach (['lat','lng'] as $part) {
        if (isset($s['biz.geo_' . $part]) && $s['biz.geo_' . $part] !== '') {
            $CFG['geo'][$part] = (float)$s['biz.geo_' . $part];
        }
    }

    foreach (['facebook','instagram','tiktok','youtube','linkedin'] as $net) {
        if (isset($s['social.' . $net])) { $CFG['social'][$net] = $s['social.' . $net]; }
    }

    if (isset($s['biz.hours_display']) && $s['biz.hours_display'] !== '') {
        $rows = [];
        foreach (preg_split('/\r?\n/', $s['biz.hours_display']) as $line) {
            if (!str_contains($line, '|')) continue;
            [$days, $time] = array_map('trim', explode('|', $line, 2));
            if ($days !== '') { $rows[$days] = $time; }
        }
        if ($rows) { $CFG['hours_display'] = $rows; }
    }

    if (isset($s['content.imagery_notice_on'])) {
        $CFG['show_imagery_notice'] = $s['content.imagery_notice_on'] === '1';
    }

    $CFG['_db'] = true;                       // templates can tell admin is live
})($CFG);

/** Editable site copy, with the shipped wording as the fallback. */
function copytext(string $key, string $default): string
{
    $v = db_ready() ? setting('content.' . $key) : null;
    return ($v === null || $v === '') ? $default : (string)$v;
}

/** Admin overrides for one page, keyed on its canonical path. */
function page_seo(string $path): array
{
    static $cache = [];
    if (isset($cache[$path])) return $cache[$path];
    if (!db_ready()) return $cache[$path] = [];
    try {
        $row = q_one("SELECT * FROM pages WHERE slug = ?", ['/' . trim($path, '/')]);
    } catch (Throwable $e) {
        $row = null;
    }
    return $cache[$path] = $row ?: [];
}

/**
 * A field from the page's admin record, falling back to what the page itself
 * says. Lets every page take its heading and hero wording from Pages & SEO
 * without any page having to know whether the database is there.
 */
function page_field(string $path, string $col, string $default): string
{
    $v = trim((string)(page_seo($path)[$col] ?? ''));
    return $v !== '' ? $v : $default;
}

/** The page's H1, overridable under Pages & SEO. */
function page_h1(string $path, string $default): string
{
    return page_field($path, 'h1', $default);
}

/** The opening line under the H1, overridable under Pages & SEO. */
function page_hero(string $path, string $default): string
{
    return page_field($path, 'hero_intro', $default);
}

/** The configurator's options, grouped. Empty array when not installed. */
function builder_options(string $group): array
{
    static $all = null;
    if ($all === null) {
        $all = [];
        if (db_ready()) {
            try {
                foreach (q_all("SELECT * FROM builder_options WHERE enabled = 1
                                ORDER BY sort_order, id") as $o) {
                    $all[$o['group_key']][] = $o;
                }
            } catch (Throwable $e) { $all = []; }
        }
    }
    return $all[$group] ?? [];
}

function builder_stages(): array
{
    if (!db_ready()) return [];
    try {
        return q_all("SELECT * FROM builder_stages WHERE enabled = 1 ORDER BY sort_order, id");
    } catch (Throwable $e) { return []; }
}

/**
 * A page's FAQs, question => answer.
 *
 * Falls back to $default so the visible list and the FAQ schema are generated
 * from one source and can never drift apart, whatever the database is doing.
 *
 * @param array<int, array{0:string,1:string}> $default
 * @return array<string, string>
 */
function page_faqs(string $slug, array $default = []): array
{
    $rows = [];
    if (db_ready()) {
        try {
            $rows = q_all("SELECT q, a FROM page_faqs WHERE page_slug = ? AND enabled = 1
                           ORDER BY sort_order, id", ['/' . trim($slug, '/')]);
        } catch (Throwable $e) { $rows = []; }
    }
    $out = [];
    foreach ($rows as $r) {
        $q = trim((string)$r['q']);
        if ($q !== '') $out[$q] = (string)$r['a'];
    }
    if ($out) return $out;

    foreach ($default as [$q, $a]) { $out[$q] = $a; }
    return $out;
}

/** A hire type list the owner can extend from the admin area. */
function hire_types(): array
{
    $rows = builder_options('hire_type');
    if ($rows) return array_values(array_filter(array_map(
        fn($r) => trim((string)($r['label'] ?? '')), $rows
    )));
    return ['Catering trailer', 'Food trailer', 'Mobile bar', 'Other'];
}

/** Service areas for the hire page, one per line in the admin area. */
function hire_areas(): array
{
    $raw = db_ready() ? (string)setting('hire.areas', '') : '';
    $list = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $raw) ?: [])));
    if ($list) return $list;
    return ['Warrington', 'Manchester', 'Liverpool', 'Cheshire', 'Widnes', 'Runcorn',
            'St Helens', 'Wigan', 'Bolton', 'Northwich', 'Knutsford', 'Altrincham'];
}

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
