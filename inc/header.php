<?php
/**
 * Shared document head, skip link and navigation.
 *
 * Each page sets $PAGE before including this file:
 *   $PAGE = [
 *     'title'       => 'Browser title',
 *     'description' => 'Meta description, 150-160 chars',
 *     'path'        => '/new-catering-trailers',   // canonical, no domain
 *     'nav'         => 'new',                      // which nav item is current
 *     'og_image'    => '/assets/img/og-xxx.jpg',   // optional
 *     'schema'      => [ ... ],                    // optional extra @graph nodes
 *     'hero_scrub'  => true,                       // homepage only
 *   ];
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

/* Holding page, if the owner has switched it on. Sits here rather than in
   bootstrap so it can only ever affect public pages: the admin area has its
   own head and never reaches this line. */
require_once __DIR__ . '/coming-soon.php';
coming_soon_gate();

$PAGE = ($PAGE ?? []) + [
    'title'       => $CFG['name'],
    'description' => '',
    'path'        => '/',
    'nav'         => '',
    'og_image'    => '/assets/img/og-default.jpg',
    'schema'      => [],
    'hero_scrub'  => false,
];

/**
 * Admin overrides. Anything set under Pages & SEO wins over what the page
 * declares; anything left blank there keeps the page's own wording, so the
 * admin area can be used lightly or not at all.
 */
$OV = page_seo($PAGE['path']);
foreach ([
    'seo_title' => 'title',
    'meta_desc' => 'description',
    'og_title'  => 'og_title',
    'og_desc'   => 'og_description',
    'og_image'  => 'og_image',
] as $col => $key) {
    if (!empty($OV[$col])) { $PAGE[$key] = $OV[$col]; }
}
$PAGE['og_title']       = $PAGE['og_title']       ?? $PAGE['title'];
$PAGE['og_description'] = $PAGE['og_description'] ?? $PAGE['description'];

$suffix = (string)setting('seo.title_suffix', '');
if ($suffix !== '' && !empty($OV['seo_title']) && !str_ends_with($PAGE['title'], $suffix)) {
    $PAGE['title'] .= $suffix;
}
if ($PAGE['description'] === '') {
    $PAGE['description'] = (string)setting('seo.default_desc', '');
}

$robots = trim(($OV['robots_index'] ?? 'index') . ', ' . ($OV['robots_follow'] ?? 'follow'));

$canonical = !empty($OV['canonical']) ? $OV['canonical'] : url($PAGE['path']);

// Build the JSON-LD graph: the business is on every page, plus page extras.
$graph = array_merge([schema_local_business()], $PAGE['schema']);
$jsonld = json_encode(
    ['@context' => 'https://schema.org', '@graph' => $graph],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);

// A page may ask for no telephone call to action anywhere in the chrome, so a
// page whose whole job is to collect a written enquiry is not undercut by a
// "Call us" button sitting above it.
$NO_PHONE = !empty($PAGE['no_phone']);

$NAV = [
    'new'      => ['/new-catering-trailers',     'New Trailers'],
    'repairs'  => ['/catering-trailer-repairs',  'Repairs'],
    'refurb'   => ['/refurbishments-upgrades',   'Refurbishments'],
    'hire'     => ['/catering-trailer-hire',     'Hire'],
    'gallery'  => ['/gallery',                   'Our Builds'],

    'about'    => ['/about',                     'About'],
    'faqs'     => ['/faqs',                      'FAQs'],
    'blog'     => ['/blog',                      'Blog'],
    'contact'  => ['/contact',                   'Contact'],
];

/* Hire is optional. Switched off in the admin area, it leaves the menu too,
   rather than advertising a page that answers "not found". */
if (db_ready() && (string)setting('hire.enabled', '1') === '0') {
    unset($NAV['hire']);
}
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<title><?= e($PAGE['title']) ?></title>
<meta name="description" content="<?= e($PAGE['description']) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<meta name="theme-color" content="#0B1A2B">
<meta name="format-detection" content="telephone=yes">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($CFG['name']) ?>">
<meta property="og:title" content="<?= e($PAGE['og_title']) ?>">
<meta property="og:description" content="<?= e($PAGE['og_description']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(url($PAGE['og_image'])) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_GB">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($PAGE['og_title']) ?>">
<meta name="twitter:description" content="<?= e($PAGE['og_description']) ?>">
<meta name="twitter:image" content="<?= e(url($PAGE['og_image'])) ?>">
<?php if ($robots !== 'index, follow'): ?>
<meta name="robots" content="<?= e($robots) ?>">
<?php endif; ?>
<?php if ($gsc = setting('track.gsc')): ?>
<meta name="google-site-verification" content="<?= e((string)$gsc) ?>">
<?php endif; ?>

<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;800&family=Source+Sans+3:wght@400;600&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="/assets/css/site.css?v=2">

<script type="application/ld+json"><?= $jsonld ?></script>

<?php /* Tracking, only ever emitted when the matching field has been filled in. */ ?>
<?php if ($gtm = setting('track.gtm')): ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?= e((string)$gtm) ?>');</script>
<?php endif; ?>

<?php if (($ga4 = setting('track.ga4')) && !$gtm): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e((string)$ga4) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}
gtag('js',new Date());gtag('config','<?= e((string)$ga4) ?>');</script>
<?php endif; ?>

<?php if ($px = setting('track.meta_pixel')): ?>
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;
n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','<?= e((string)$px) ?>');fbq('track','PageView');</script>
<?php endif; ?>

<?= (string)setting('track.custom_head', '') ?>
</head>

<body<?= $PAGE['hero_scrub'] ? ' class="has-scrub"' : '' ?>>

<div class="env" aria-hidden="true"></div>

<a class="skip" href="#main">Skip to content</a>

<header class="masthead" id="masthead">
  <div class="wrap masthead__in">

    <a class="brand" href="/" aria-label="<?= e($CFG['name']) ?> home">
      <picture><source srcset="/assets/img/logo.webp" type="image/webp"><img src="/assets/img/logo.png" alt="<?= e($CFG['name']) ?>" width="230" height="44" decoding="async"></picture>
    </a>

    <nav class="nav" id="nav" aria-label="Main">
      <ul class="nav__list">
        <?php foreach ($NAV as $key => [$href, $label]): ?>
          <li><a href="<?= e($href) ?>"<?= $PAGE['nav'] === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="masthead__cta">
      <?php if (!$NO_PHONE): ?>
      <a class="tel" href="<?= e(tel_href()) ?>" data-track="call-header">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/></svg>
        <span><?= e($CFG['phone_display']) ?></span>
      </a>
      <?php endif; ?>
      <a class="btn btn--accent" href="<?= e($PAGE['cta_href'] ?? '/request-a-quote') ?>">Request a Quote</a>
    </div>

    <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="nav">
      <span class="burger__box" aria-hidden="true"><i></i><i></i><i></i></span>
      <span class="sr">Menu</span>
    </button>

  </div>
</header>

<main id="main" tabindex="-1">
<?php if (!empty($PAGE['crumbs'])): ?>
<nav class="crumbs" aria-label="Breadcrumb">
  <div class="wrap">
    <ol>
      <?php $lastCrumb = array_key_last($PAGE['crumbs']); ?>
      <?php foreach ($PAGE['crumbs'] as $label => $href): ?>
        <li>
          <?php if ($label === $lastCrumb): ?>
            <span aria-current="page"><?= e($label) ?></span>
          <?php else: ?>
            <a href="<?= e($href) ?>"><?= e($label) ?></a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</nav>
<?php endif; ?>
