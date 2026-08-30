<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$FAQ = [
  'Is a refurbishment cheaper than a new trailer?' =>
    'Usually, and sometimes by a lot. It depends entirely on the chassis. A sound chassis is worth refurbishing around. A rotten one rarely is, and we will say so.',
  'How long does a refit take?' =>
    'Two to four weeks for most refits, against six to ten for a new build. We will give you a date before you commit.',
  'Can you change the layout completely?' =>
    'Yes. Strip out, move the hatch, re-plan the cook line and re-fit in stainless. People do this when their menu has outgrown the trailer they started with.',
  'Will it be recertified?' =>
    'Any gas or electrical work we do is tested and certified. If we re-pipe or rewire, you get the paperwork for it.',
];

$PAGE = [
  'title'       => 'Refurbishments & Upgrades | Catering Trailer Refits | Catering Trailers NW',
  'description' => 'Catering trailer refurbishments and upgrades in the North West. Full stainless refits, layout changes, equipment installation, electrical and gas upgrades.',
  'path'        => '/refurbishments-upgrades',
  'nav'         => 'refurb',
  'schema'      => [
    schema_service('Catering trailer refurbishment and upgrades',
      'Full refits and upgrades for existing catering trailers: stainless fit-outs, layout changes, equipment installation, gas and electrical upgrades.',
      '/refurbishments-upgrades'),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Refurbishments & Upgrades' => '/refurbishments-upgrades']),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Refurbishments and upgrades</p>
      <h1>The trailer is sound. The inside has had its day.</h1>
      <p class="lede">Plenty of trailers are worth keeping. The chassis is solid, the body
         is straight, but the fit-out was built for a menu you stopped serving two seasons
         ago. That is a refit, not a replacement, and it costs a fraction of one.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Get a refit quote</a>
        <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="ref-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">What a refit covers</p>
      <h2 id="ref-h">Strip it back, build it right</h2></div>

    <div class="why rise stagger" style="margin-top:2.4rem">
      <div class="why__item"><span class="why__n">01</span><h3>Stainless steel fit-outs</h3>
        <p>New counters, splashbacks, shelving and prep surfaces in stainless with sealed
           joints. The single biggest jump in how a trailer inspects and how it looks.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Equipment installation</h3>
        <p>Griddles, fryers, ranges, pizza ovens, espresso machines and bain maries.
           Ours or yours, installed and certified around.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Layout changes</h3>
        <p>Move the hatch, re-plan the cook line, change where the service point sits.
           Worth doing when the menu has moved on.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Gas and electrical upgrades</h3>
        <p>Re-pipes, new consumer units, extra sockets, hook-up points and LED lighting,
           certified when finished.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Extraction and ventilation</h3>
        <p>Canopies, filters and fans sized to what you actually cook, so the trailer is
           workable in July and not just in March.</p></div>
      <div class="why__item"><span class="why__n">06</span><h3>Panels and cosmetics</h3>
        <p>Panel replacement, resealing, new trim and a surface fit to take a wrap.
           Worth doing last, once the working parts are right.</p></div>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="worth-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">The honest bit</p>
      <h2 id="worth-h">When a refit is the wrong answer</h2>
      <p class="lede">We will tell you when not to spend the money. A refit is worth it
         when the chassis is sound and the body is straight. If the chassis has gone, you
         are putting a new kitchen into something that will not pass a road check, and you
         would be better off starting again.</p>
      <p class="lede">Send us photographs of the chassis rails and the underside before
         anything else. That one set of photos decides the whole conversation.</p>
      <div class="btn-row" style="margin-top:1.6rem">
        <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href('Hi, I am thinking about refurbishing my catering trailer. Sending photos of the chassis.')) ?>"
           target="_blank" rel="noopener">Send chassis photos</a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="ufaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">Refit questions</p><h2 id="ufaq-h">What people ask</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p class="lede rise" style="margin-top:1.6rem">
      Something broken rather than tired? See
      <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">trailer repairs</a>.
    </p>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
