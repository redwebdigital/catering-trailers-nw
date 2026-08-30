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

$PAGE = ($PAGE ?? []) + [
    'title'       => $CFG['name'],
    'description' => '',
    'path'        => '/',
    'nav'         => '',
    'og_image'    => '/assets/img/og-default.jpg',
    'schema'      => [],
    'hero_scrub'  => false,
];

$canonical = url($PAGE['path']);

// Build the JSON-LD graph: the business is on every page, plus page extras.
$graph = array_merge([schema_local_business()], $PAGE['schema']);
$jsonld = json_encode(
    ['@context' => 'https://schema.org', '@graph' => $graph],
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);

$NAV = [
    'new'      => ['/new-catering-trailers',     'New Trailers'],
    'repairs'  => ['/catering-trailer-repairs',  'Repairs'],
    'refurb'   => ['/refurbishments-upgrades',   'Refurbishments'],
    'gallery'  => ['/gallery',                   'Our Builds'],
    'about'    => ['/about',                     'About'],
    'faqs'     => ['/faqs',                      'FAQs'],
    'blog'     => ['/blog',                      'Blog'],
    'contact'  => ['/contact',                   'Contact'],
];
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
<meta property="og:title" content="<?= e($PAGE['title']) ?>">
<meta property="og:description" content="<?= e($PAGE['description']) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e(url($PAGE['og_image'])) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="en_GB">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($PAGE['title']) ?>">
<meta name="twitter:description" content="<?= e($PAGE['description']) ?>">
<meta name="twitter:image" content="<?= e(url($PAGE['og_image'])) ?>">

<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@700;800&family=Source+Sans+3:wght@400;600&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="/assets/css/site.css?v=1">

<script type="application/ld+json"><?= $jsonld ?></script>
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
      <a class="tel" href="<?= e(tel_href()) ?>" data-track="call-header">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/></svg>
        <span><?= e($CFG['phone_display']) ?></span>
      </a>
      <a class="btn btn--accent" href="/request-a-quote">Request a Quote</a>
    </div>

    <button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="nav">
      <span class="burger__box" aria-hidden="true"><i></i><i></i><i></i></span>
      <span class="sr">Menu</span>
    </button>

  </div>
</header>

<main id="main" tabindex="-1">
