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
  ['catering-trailer-interior-swirl-stainless', 'Catering trailer interior with swirl finish stainless steel walls, extraction canopy and counter run', 'Swirl stainless', 'Interior fit-out', 'Interior Fit-Outs'],
  ['catering-trailer-new-build-workshop', 'A finished catering trailer in the workshop with its serving hatch raised', 'New build', 'In the workshop', 'New Builds'],
  ['catering-trailer-serving-hatch-open', 'Serving hatch raised on its gas struts, counter and interior visible', '3.0m body', 'Single axle', 'Serving Hatches'],
  ['catering-trailer-serving-side',       'Serving side of a white catering trailer with the hatch closed',        '3.0m body', 'Serving side', 'Serving Hatches'],
  ['catering-trailer-front-three-quarter','Front three quarter view showing the A frame, jockey wheel and gas locker', '3.0m body', 'A frame and gas locker', 'New Builds'],
  ['catering-trailer-side-elevation',     'Full side elevation of a white single axle catering trailer',           '3.0m body', 'Nearside elevation', 'New Builds'],
  ['catering-trailer-rear-three-quarter', 'Rear three quarter view showing the closed serving window',             '3.0m body', 'Rear three quarter', 'New Builds'],
  ['catering-trailer-rear-door',          'Rear of the trailer showing the personnel door and road lights',        '3.0m body', 'Rear door', 'New Builds'],
  ['catering-trailer-hitched-rear',       'Catering trailer hitched to a tow vehicle, ready to move',              '3.0m body', 'Hitched and ready', 'New Builds'],
];

/* Only categories we actually have photographs for. The rest of the taxonomy
   waits until there is genuine work to show under it. */
$FILTERS = array_values(array_intersect(
    ['New Builds', 'Repairs', 'Refurbishments', 'Interior Fit-Outs', 'Serving Hatches', 'Custom Work'],
    array_unique(array_column($BUILDS, 4))
));

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
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Our builds</p>
      <h1><?= e(page_h1('/gallery', 'Catering Trailer Builds & Projects')) ?></h1>
      <p class="lede">Take a look at catering trailers, repairs, refurbishments and custom
         trailer work.</p>
      <p class="lede">These are photographs of our own units, not stock images. This gallery
         will continue to grow as new projects are completed.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap">
    <?php if (count($FILTERS) > 1): ?>
      <div class="chips rise" id="galFilters" style="margin-bottom:1.8rem">
        <button class="chip" type="button" data-cat="all" aria-pressed="true">All</button>
        <?php foreach ($FILTERS as $f): ?>
          <button class="chip" type="button" data-cat="<?= e($f) ?>" aria-pressed="false"><?= e($f) ?></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="gal rise stagger" id="galGrid">
      <?php foreach ($BUILDS as $i => [$slug, $alt, $spec, $label, $cat]): ?>
        <figure data-cat="<?= e($cat) ?>">
          <?= picture($slug, $alt, [
                'sizes'  => '(max-width:700px) 100vw, (max-width:1100px) 50vw, 33vw',
                'widths' => [480, 800, 1200],
                'eager'  => $i < 2,
              ]) ?>
          <figcaption><?= e($label) ?> <span class="tag"><?= e($spec) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>

    <p class="hint rise" id="galEmpty" hidden style="margin-top:1.4rem">
      Nothing photographed under that heading yet.</p>

    <div class="placeholder rise" style="margin-top:2.4rem">
      <b>More builds going up here</b>
      <p>We photograph every trailer before it leaves. As the newer builds are shot they
         get added to this page, along with the fit-outs behind the hatch.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="sim-h">
  <div class="wrap wrap--narrow rise">
    <p class="kicker">Seen something you like?</p>
    <h2 id="sim-h">Planning Something Similar?</h2>
    <p class="lede">If you see a trailer layout, serving hatch or feature you like, mention it
       when requesting your quote.</p>
    <p class="lede">We can discuss how similar ideas may work with your own menu and trailer
       requirements. See how we approach a
       <a href="/new-catering-trailers" style="color:var(--accent-hover)">new build</a>.</p>
    <div class="btn-row" style="margin-top:1.6rem">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">View New Catering Trailers</a>
    </div>
  </div>
</section>

<script>
/* Gallery filtering. Additive: with scripting off every photograph is visible. */
(function () {
  var bar = document.getElementById('galFilters');
  if (!bar) return;
  var figs  = Array.prototype.slice.call(document.querySelectorAll('#galGrid figure'));
  var empty = document.getElementById('galEmpty');

  bar.addEventListener('click', function (e) {
    var btn = e.target.closest('.chip');
    if (!btn) return;
    var want = btn.getAttribute('data-cat');

    bar.querySelectorAll('.chip').forEach(function (c) {
      c.setAttribute('aria-pressed', String(c === btn));
    });

    var shown = 0;
    figs.forEach(function (f) {
      var on = want === 'all' || f.getAttribute('data-cat') === want;
      f.hidden = !on;
      if (on) shown++;
    });
    empty.hidden = shown > 0;
  });
})();
</script>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
