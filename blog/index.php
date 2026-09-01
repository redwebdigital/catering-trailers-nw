<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/bootstrap.php';

$POSTS = require __DIR__ . '/../inc/posts.php';

$SLUG = '/blog';

/* Only categories that actually have something in them are shown. The full
   taxonomy lives here so a new article can be filed without a code change. */
$CATEGORIES = ['Buying a Catering Trailer', 'Trailer Design', 'Equipment & Fit-Out',
               'Repairs & Maintenance', 'Mobile Food Business', 'Trailer Hire', 'Mobile Bars'];
$used = array_unique(array_column($POSTS, 'category'));
$LIVE_CATEGORIES = array_values(array_intersect($CATEGORIES, $used));

$PAGE = [
  'title'       => 'Catering Trailer Advice & Guides | Blog',
  'description' => 'Practical catering trailer advice covering new builds, layouts, equipment, maintenance, repairs, refurbishments and mobile food businesses.',
  'path'        => $SLUG,
  'nav'         => 'blog',
  'crumbs'      => ['Home' => '/', 'Advice & Guides' => $SLUG],
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'Blog' => '/blog']),
    [
      '@type' => 'Blog',
      'url'   => url('/blog'),
      'name'  => 'Catering Trailers NW blog',
      'blogPost' => array_map(fn($slug, $p) => [
        '@type'         => 'BlogPosting',
        'headline'      => $p['title'],
        'description'   => $p['excerpt'],
        'url'           => url('/blog/' . $slug),
        'datePublished' => $p['date'],
        'dateModified'  => $p['updated'],
        'author'        => ['@id' => url('/#business')],
      ], array_keys($POSTS), array_values($POSTS)),
    ],
  ],
];

require __DIR__ . '/../inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Advice and guides</p>
      <h1><?= e(page_h1($SLUG, 'Catering Trailer Advice & Guides')) ?></h1>
      <p class="lede">Useful information for anyone buying, operating, repairing or upgrading
         a catering trailer.</p>
      <p class="lede">Our guides cover trailer design, equipment, layouts, repairs,
         refurbishments and mobile catering businesses.</p>
      <?php if ($LIVE_CATEGORIES): ?>
        <ul class="taglist" style="margin-top:1.6rem">
          <?php foreach ($LIVE_CATEGORIES as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap">
    <div class="gal rise stagger">
      <?php foreach ($POSTS as $slug => $p): ?>
        <figure style="display:flex;flex-direction:column">
          <a href="/blog/<?= e($slug) ?>" style="text-decoration:none;display:block">
            <?= picture($p['image'], $p['alt'], [
                  'sizes'  => '(max-width:700px) 100vw, (max-width:1100px) 50vw, 33vw',
                  'widths' => [480, 800],
                ]) ?>
          </a>
          <figcaption style="display:block;flex:1;padding:1.2rem 1.2rem 1.4rem">
            <span class="tag"><?= e($p['category']) ?> · <?= (int)$p['read'] ?> min read</span>
            <h2 style="font-size:1.16rem;margin:.6rem 0 .5rem">
              <a href="/blog/<?= e($slug) ?>" style="text-decoration:none;color:var(--text-primary)"><?= e($p['title']) ?></a>
            </h2>
            <p style="margin:0 0 .9rem;color:var(--text-secondary);font-size:.95rem"><?= e($p['excerpt']) ?></p>
            <time datetime="<?= e($p['date']) ?>" style="font:500 .72rem/1 var(--mono);color:var(--steel)">
              <?= e(date('j M Y', strtotime($p['date']))) ?>
            </time>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../inc/footer.php'; ?>
