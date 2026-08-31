<?php
/**
 * robots.txt, generated so it always matches the live domain and settings.
 * Served at /robots.txt via the rewrite in .htaccess.
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

$lines = [
    'User-agent: *',
    'Allow: /',
    '',
    '# Nothing useful for a crawler, and nothing that should ever be indexed.',
    'Disallow: /admin/',
    'Disallow: /inc/',
    'Disallow: /private/',
    'Disallow: /quote-uploads/',
    'Disallow: /logs/',
    'Disallow: /request-a-quote?',
    'Disallow: /*?len=',
    'Disallow: /thank-you',
];

/* Any page set to noindex in the admin area is also kept out of the crawl. */
if (db_ready()) {
    try {
        $blocked = q_all("SELECT slug FROM pages WHERE robots_index = 'noindex' ORDER BY slug");
        if ($blocked) {
            $lines[] = '';
            $lines[] = '# Set to noindex in the admin area';
            foreach ($blocked as $b) { $lines[] = 'Disallow: ' . $b['slug']; }
        }
    } catch (Throwable $e) { /* fall through to the standard rules */ }
}

$extra = trim((string)setting('seo.robots_extra', ''));
if ($extra !== '') {
    $lines[] = '';
    $lines[] = $extra;
}

$lines[] = '';
$lines[] = 'Sitemap: ' . rtrim((string)$CFG['base_url'], '/') . '/sitemap.xml';

echo implode("\n", $lines) . "\n";
