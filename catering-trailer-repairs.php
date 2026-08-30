<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$FAQ = [
  'Do you repair trailers you did not build?' =>
    'Yes, any make and any age. Most of the repair work through our doors was built by somebody else.',
  'How quickly can you look at it?' =>
    'Send photos the day it happens and we will tell you the same day what it needs. Most accident work starts within the week.',
  'Do you handle insurance work?' =>
    'Yes. We quote in the format insurers expect and deal with the assessor directly so you are not stuck in the middle.',
  'Can you collect the trailer?' =>
    'Across the North West, yes. If it is not roadworthy tell us and we will arrange recovery rather than have you tow something unsafe.',
  'Can you replace a rotten chassis?' =>
    'Yes. Full chassis replacement, or repair and re-galvanising where the rot is local. We will tell you honestly which one is worth doing.',
];

$PAGE = [
  'title'       => 'Catering Trailer Repairs | Chassis & Accident Damage | Catering Trailers NW',
  'description' => 'Catering trailer repairs across the North West. Chassis replacement, accident and insurance work, gas pipework, electrical upgrades and serving hatch repairs. Any make.',
  'path'        => '/catering-trailer-repairs',
  'nav'         => 'repairs',
  'schema'      => [
    schema_service('Catering trailer repairs',
      'Repairs to catering trailers of any make: chassis replacement, accident and insurance damage, gas pipework, electrical upgrades, serving hatch and panel work.',
      '/catering-trailer-repairs'),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Trailer Repairs' => '/catering-trailer-repairs']),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Trailer repairs</p>
      <h1>Off the road today, back trading this week</h1>
      <p class="lede">Every day your trailer sits in a yard is a day you are not taking
         money. Send us photographs the day it happens and we will tell you what it needs
         and what it costs, same day.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
        <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href('Hi, my catering trailer needs a repair. I am sending photos.')) ?>"
           target="_blank" rel="noopener">Send photos on WhatsApp</a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="rep-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">What we fix</p>
      <h2 id="rep-h">Any make, any age</h2>
      <p class="lede">Most of the repair work that comes through our doors was built by
         someone else. That is fine. We fix trailers, not just our own.</p></div>

    <div class="why rise stagger" style="margin-top:2.4rem">
      <div class="why__item"><span class="why__n">01</span><h3>Accident and damage repairs</h3>
        <p>Panels, corners, frames and hatches after a knock. We quote in the format
           insurers expect and deal with the assessor for you.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Chassis repair and replacement</h3>
        <p>Rot, cracks, failed welds and tired suspension. Full chassis replacement where
           it is beyond saving, honest advice where it is not.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Gas pipework replacement</h3>
        <p>Full re-pipes, bubble testers, regulators and bottle lockers, tested and
           certified by a Gas Safe registered engineer.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Electrical upgrades</h3>
        <p>Consumer units, RCD protection, hook-up points, LED lighting and extra sockets,
           certified when we are done.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Serving hatch modifications</h3>
        <p>New apertures, hatch conversions, gas struts, canopies and shutters. Moving a
           hatch to the other side is a common one.</p></div>
      <div class="why__item"><span class="why__n">06</span><h3>Water, sinks and hygiene</h3>
        <p>Boilers, pumps, tanks and wash hand basins fitted or replaced so you pass your
           next inspection instead of arguing about it.</p></div>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="fast-h">
  <div class="wrap">
    <div class="process rise">
      <div>
        <p class="kicker">How it works</p>
        <h2 id="fast-h">Photos first, always</h2>
        <p class="lede">The fastest repair starts with a photograph, not a phone call
           where we both guess. Four steps, and the first one takes you a minute.</p>
      </div>
      <ol class="steps">
        <li><h3>Send photos</h3>
          <p>WhatsApp or email. Wide shot, then close on the damage. Include the chassis
             plate if you can find it.</p></li>
        <li><h3>Same day assessment</h3>
          <p>We tell you what it needs, roughly what it costs, and whether it is worth
             repairing at all. Sometimes the honest answer is no.</p></li>
        <li><h3>Booked in</h3>
          <p>Repairs jump the queue over new builds, because a broken trailer is lost
             income and a new build is not.</p></li>
        <li><h3>Back on the road</h3>
          <p>Certified where certification applies, and we tell you what caused it so it
             does not happen again.</p></li>
      </ol>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="rfaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">Repair questions</p>
      <h2 id="rfaq-h">What people ask</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p class="lede rise" style="margin-top:1.6rem">
      Bigger job than a repair? See
      <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">refurbishments and upgrades</a>,
      or a <a href="/new-catering-trailers" style="color:var(--accent-hover)">new build</a>.
    </p>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
