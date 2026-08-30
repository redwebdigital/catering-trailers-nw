<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

/**
 * The gallery.
 *
 * To add a build: drop the photos into assets/img/gallery, run
 * review/build-images.py to make the WebP and JPEG sizes, then add a row here.
 * Nothing else needs changing.
 */
$BUILDS = [
  ['catering-trailer-interior-swirl-stainless', 'Brand new interior with swirl finish stainless steel walls, extraction canopy and counter run', 'Swirl stainless', 'Interior fit-out'],
  ['catering-trailer-new-build-workshop', 'A finished catering trailer in the workshop with its serving hatch raised', 'New build', 'In the workshop'],
  ['catering-trailer-serving-hatch-open', 'Serving hatch raised on its gas struts, counter and interior visible', '3.0m body', 'Single axle'],
  ['catering-trailer-serving-side',       'Serving side of a white catering trailer with the hatch closed',        '3.0m body', 'Serving side'],
  ['catering-trailer-front-three-quarter','Front three quarter view showing the A frame, jockey wheel and gas locker', '3.0m body', 'A frame and gas locker'],
  ['catering-trailer-side-elevation',     'Full side elevation of a white single axle catering trailer',           '3.0m body', 'Nearside elevation'],
  ['catering-trailer-rear-three-quarter', 'Rear three quarter view showing the closed serving window',             '3.0m body', 'Rear three quarter'],
  ['catering-trailer-rear-door',          'Rear of the trailer showing the personnel door and road lights',        '3.0m body', 'Rear door'],
  ['catering-trailer-hitched-rear',       'Catering trailer hitched to a tow vehicle, ready to move',              '3.0m body', 'Hitched and ready'],
];

$PAGE = [
  'title'       => 'Our Builds | Catering Trailer Gallery | Catering Trailers NW',
  'description' => 'Photographs of catering trailers built and refitted by Catering Trailers NW in the North West. Serving hatches, chassis, fit-outs and finished units.',
  'path'        => '/gallery',
  'nav'         => 'gallery',
  'og_image'    => '/assets/img/gallery/catering-trailer-serving-hatch-open-1200.jpg',
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'Our Builds' => '/gallery']),
    [
      '@type' => 'ImageGallery',
      'name'  => 'Catering Trailers NW builds',
      'url'   => url('/gallery'),
    ],
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Our builds</p>
      <h1>Trailers that left our workshop</h1>
      <p class="lede">Real photographs of our own units, not stock images. Every trailer
         goes out in white as standard and is finished to whatever colour, livery or wrap
         you want.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap">
    <div class="gal rise stagger">
      <?php foreach ($BUILDS as $i => [$slug, $alt, $spec, $label]): ?>
        <figure>
          <?= picture($slug, $alt, [
                'sizes'  => '(max-width:700px) 100vw, (max-width:1100px) 50vw, 33vw',
                'widths' => [480, 800, 1200],
                'eager'  => $i < 2,
              ]) ?>
          <figcaption><?= e($label) ?> <span class="tag"><?= e($spec) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>

    <div class="placeholder rise" style="margin-top:2.4rem">
      <b>More builds going up here</b>
      <p>We photograph every trailer before it leaves. As the newer builds are shot they
         get added to this page, along with the fit-outs behind the hatch.</p>
    </div>
  </div>
</section>

<section class="band band--well">
  <div class="wrap wrap--narrow rise" style="text-align:center">
    <h2>Want one like these, built for your menu?</h2>
    <p class="lede" style="margin-inline:auto">Tell us what you serve and we will draw
       something around it.</p>
    <div class="btn-row" style="justify-content:center;margin-top:1.6rem">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">How we build them</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
