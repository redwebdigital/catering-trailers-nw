<?php
/**
 * Shared opening for a blog post.
 *
 * A post file sets $SLUG, then requires this, writes its body, then requires
 * post-bottom.php. Everything else (meta, schema, hero, related links) is
 * handled here from the registry in posts.php.
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$POSTS = require __DIR__ . '/posts.php';

if (!isset($SLUG) || !isset($POSTS[$SLUG])) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$POST = $POSTS[$SLUG];
$path = '/blog/' . $SLUG;

$PAGE = [
  'title'       => $POST['title'] . ' | Catering Trailers NW',
  'description' => $POST['excerpt'],
  'path'        => $path,
  'nav'         => 'blog',
  'og_image'    => '/assets/img/gallery/' . $POST['image'] . '-1200.jpg',
  'schema'      => [
    [
      '@type'            => 'BlogPosting',
      'headline'         => $POST['title'],
      'description'      => $POST['excerpt'],
      'url'              => url($path),
      'mainEntityOfPage' => url($path),
      'datePublished'    => $POST['date'],
      'dateModified'     => $POST['updated'],
      'image'            => url('/assets/img/gallery/' . $POST['image'] . '-1200.jpg'),
      'author'           => ['@id' => url('/#business')],
      'publisher'        => ['@id' => url('/#business')],
      'articleSection'   => $POST['category'],
    ],
    schema_breadcrumbs(['Home' => '/', 'Blog' => '/blog', $POST['title'] => $path]),
  ],
];

require __DIR__ . '/header.php';
?>

<article>
<section class="band band--tight">
  <div class="wrap wrap--narrow">
    <nav aria-label="Breadcrumb" style="margin-bottom:1.4rem">
      <ol style="display:flex;flex-wrap:wrap;gap:.5rem;list-style:none;margin:0;padding:0;
                 font:500 .72rem/1 var(--mono);letter-spacing:.1em;text-transform:uppercase;color:var(--steel)">
        <li><a href="/" style="color:inherit">Home</a></li>
        <li aria-hidden="true">/</li>
        <li><a href="/blog" style="color:inherit">Blog</a></li>
      </ol>
    </nav>

    <div class="rise">
      <p class="kicker"><?= e($POST['category']) ?> · <?= (int)$POST['read'] ?> min read</p>
      <h1><?= e($POST['title']) ?></h1>
      <p class="lede"><?= e($POST['excerpt']) ?></p>
      <p style="font:500 .78rem/1 var(--mono);color:var(--steel);margin-top:1.2rem">
        Published <time datetime="<?= e($POST['date']) ?>"><?= e(date('j F Y', strtotime($POST['date']))) ?></time>
      </p>
    </div>
  </div>
</section>

<section class="band--tight">
  <div class="wrap wrap--narrow rise">
    <?= picture($POST['image'], $POST['alt'],
        ['widths'=>[480,800,1200],'sizes'=>'(max-width:800px) 100vw, 760px','eager'=>true,'ratio'=>'16/9']) ?>
  </div>
</section>

<section class="band" style="padding-top:2rem">
  <div class="wrap wrap--narrow prose rise">
