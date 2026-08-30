<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$PAGE = [
  'title'       => 'About Us | Catering Trailer Builders | Catering Trailers NW',
  'description' => 'Catering Trailers NW build and repair bespoke catering trailers from our own workshop in the North West. Gas Safe registered, electrical certificates supplied.',
  'path'        => '/about',
  'nav'         => 'about',
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'About Us' => '/about']),
    ['@type' => 'AboutPage', 'url' => url('/about'), 'mainEntity' => ['@id' => url('/#business')]],
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">About us</p>
      <h1>We build the trailer we would want to trade out of</h1>
      <p class="lede">Catering Trailers NW build bespoke catering trailers from our own
         workshop in the North West, and repair them for traders across the region. New
         builds, accident work, chassis replacement, gas, electrics and full refits.</p>
    </div>
  </div>
</section>

<section class="band--tight">
  <div class="wrap rise">
    <?= picture('catering-trailer-front-three-quarter',
        'A finished catering trailer outside the workshop',
        ['widths'=>[480,800,1200],'sizes'=>'100vw','ratio'=>'16/9']) ?>
  </div>
</section>

<!-- ───────────────────────────────────────────────────────────────────────
     PLACEHOLDER SECTION. This is the one part of the site that cannot be
     written without the owner. Replace the three paragraphs below with the
     real story: when the business started, who runs it, what you did before,
     why you build trailers the way you do. Buyers read this page before they
     spend twenty thousand pounds, and a real story converts far better than
     a general one.
     ─────────────────────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="story-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Our story</p>
      <h2 id="story-h">Built by people who have stood in one</h2>
      <p class="lede">A catering trailer is a workplace before it is a purchase. The
         difference between a good one and a bad one is not the paint. It is whether you
         can reach the fryer without turning round, whether the counter is at the right
         height after nine hours, and whether the extraction copes in August.</p>
      <p class="lede">That is why every build starts with your menu rather than a
         catalogue. We would rather spend an extra hour on the phone at the start than
         hand you something you have to work around for five years.</p>
      <p class="lede">We are Gas Safe registered, we certify our own electrical work, and
         both certificates are in your hand on the day you collect. Not chased for
         afterwards while your pitch sits empty.</p>
    </div>

    <div class="placeholder rise" style="margin-top:2rem;text-align:left">
      <b>Your real story goes here</b>
      <p>The three paragraphs above are honest but general. Send us when the business
         started, who runs it, and what you did before, and this becomes the section
         that wins the jobs. It is the page people read before spending twenty thousand
         pounds. Delete this note once it is written.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="cred-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">Where we stand</p>
      <h2 id="cred-h">What we hold ourselves to</h2></div>
    <div class="why rise stagger" style="margin-top:2.4rem">
      <div class="why__item"><span class="why__n">01</span><h3>Gas Safe registered</h3>
        <p>All gas work carried out and certified by a Gas Safe registered engineer.
           There is no acceptable shortcut here and we do not take one.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Certified electrics</h3>
        <p>Every installation tested and signed off, with the certificate handed over
           on collection.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Straight answers</h3>
        <p>If a refit is not worth the money, or a trailer is too big for your car, we
           will tell you before you spend, not after.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Built here</h3>
        <p>Fabricated and fitted out in our own North West workshop. Not imported,
           badged and sold on.</p></div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="area-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">Where we work</p>
      <h2 id="area-h">Across the North West</h2>
      <p class="lede">Building, repairing, collecting and delivering throughout the region.</p></div>
    <div class="areas rise stagger" style="margin-top:2rem">
      <?php foreach ($CFG['areas'] as $slug => $area): ?>
        <a class="area" href="/areas/catering-trailers-<?= e($slug) ?>">
          <b><?= e($area['name']) ?></b><span><?= e($area['county']) ?></span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
