<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/bootstrap.php';

$POSTS = require __DIR__ . '/../inc/posts.php';

$PAGE = [
  'title'       => 'Blog | Advice for Mobile Catering Traders | Catering Trailers NW',
  'description' => 'Practical advice on buying, running and maintaining a catering trailer in the UK. Costs, certificates, towing weights and what actually goes wrong.',
  'path'        => '/blog',
  'nav'         => 'blog',
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
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Blog</p>
      <h1>What we tell traders before they spend</h1>
      <p class="lede">Practical answers to the questions that cost people money when they
         get them wrong. No filler.</p>
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
