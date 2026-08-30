<?php
/**
 * The sitemap, generated rather than hand-maintained.
 *
 * Reads the area list from config.php and the posts from posts.php, so adding
 * a location or an article puts it in the sitemap automatically. Served at
 * /sitemap.xml via the rewrite in .htaccess.
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/bootstrap.php';

$POSTS = require __DIR__ . '/inc/posts.php';

header('Content-Type: application/xml; charset=utf-8');

/** Last modified date of a page file, so lastmod is honest. */
function touched(string $file): string
{
    $p = __DIR__ . '/' . ltrim($file, '/');
    return date('Y-m-d', is_file($p) ? (int)filemtime($p) : time());
}

$urls = [
    ['/',                          '1.0', 'weekly',  touched('index.php')],
    ['/new-catering-trailers',     '0.9', 'monthly', touched('new-catering-trailers.php')],
    ['/catering-trailer-repairs',  '0.9', 'monthly', touched('catering-trailer-repairs.php')],
    ['/refurbishments-upgrades',   '0.8', 'monthly', touched('refurbishments-upgrades.php')],
    ['/request-a-quote',           '0.9', 'monthly', touched('request-a-quote.php')],
    ['/gallery',                   '0.7', 'monthly', touched('gallery.php')],
    ['/about',                     '0.6', 'yearly',  touched('about.php')],
    ['/faqs',                      '0.7', 'monthly', touched('faqs.php')],
    ['/contact',                   '0.8', 'yearly',  touched('contact.php')],
    ['/blog',                      '0.6', 'weekly',  touched('blog/index.php')],
    ['/areas',                     '0.7', 'monthly', touched('areas/index.php')],
    ['/privacy',                   '0.2', 'yearly',  touched('privacy.php')],
];

foreach (array_keys($CFG['areas']) as $slug) {
    $urls[] = ['/areas/catering-trailers-' . $slug, '0.8', 'monthly',
               touched("areas/catering-trailers-{$slug}.php")];
}

foreach ($POSTS as $slug => $post) {
    $urls[] = ['/blog/' . $slug, '0.6', 'yearly', $post['updated']];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as [$loc, $priority, $freq, $lastmod]): ?>
  <url>
    <loc><?= e(url($loc)) ?></loc>
    <lastmod><?= e($lastmod) ?></lastmod>
    <changefreq><?= e($freq) ?></changefreq>
    <priority><?= e($priority) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
