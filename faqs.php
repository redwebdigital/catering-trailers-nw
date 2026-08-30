<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

/** Grouped so the page is scannable, flattened into one FAQPage for schema. */
$GROUPS = [
  'Buying a new trailer' => [
    'How long does a catering trailer build take?' =>
      'Six to ten weeks for most builds from the day your deposit clears. April to July is our busiest run, so book earlier if you want a summer start.',
    'What deposit do you need?' =>
      'Thirty percent to book your slot and buy your equipment. The balance clears before you collect.',
    'What sizes do you build?' =>
      'From 2.4m up to 4.2m as standard, single or twin axle. Larger by arrangement, since most of what we do is bespoke anyway.',
    'Can I supply my own appliances?' =>
      'Yes. Plenty of traders bring a griddle or a coffee machine they already trust. We fit it and certify around it.',
    'Do you offer finance?' =>
      'We are not a lender, but most traders fund a build through an asset finance company and we are happy to provide whatever paperwork they ask for.',
    'What is included in the price?' =>
      'The trailer, the fit-out, any appliances we supply, the gas and electrical work, both certificates and the handover. Nothing appears at the end that was not in the quote.',
  ],
  'Rules, safety and inspections' => [
    'Will it pass my council\'s inspection?' =>
      'Every trailer leaves us with a Gas Safe certificate and an electrical certificate. Stainless throughout, sealed joints, hot and cold water, and the separate wash hand basin your environmental health officer will look for.',
    'Do I get a gas safety certificate?' =>
      'Yes, with every trailer we build and with any gas work we carry out. Gas work on a mobile catering unit must be done by a Gas Safe registered engineer, and without the certificate your insurance may not stand.',
    'Do I need to register with the council?' =>
      'Yes. You register the unit with environmental health in the authority where you are based, normally at least 28 days before you start trading. Do this early, because you cannot get a street trading licence without it.',
    'What about fire safety?' =>
      'You need suitable extinguishers and a fire blanket, and your gas installation has to be sound. We will point out anything on your build that would fail.',
  ],
  'Towing and the road' => [
    'Can my car tow it?' =>
      'Send us your vehicle and we will tell you straight. This catches more people out than anything else in the trade, and it is far easier to solve before the build than after.',
    'Do I need a special licence?' =>
      'It depends on when you passed your test and on the combined weight. Drivers who passed after 1 January 1997 have had their towing entitlement widened in recent years, but the weights still matter. Tell us your vehicle and your licence date and we will work it through with you.',
    'Single or twin axle?' =>
      'Single is lighter, cheaper and easier to manoeuvre by hand. Twin carries more weight, tows more steadily at speed and is the usual answer above 3.5m or with a heavy cook line.',
  ],
  'Repairs and refits' => [
    'Do you repair trailers you did not build?' =>
      'Yes, any make and any age. Most of the repair work through our doors was built by somebody else.',
    'How fast can you fix accident damage?' =>
      'Get photos to us the day it happens. Most insurance work starts within the week, because every day off the road is a day you are not taking money.',
    'Do you handle insurance work?' =>
      'Yes. We quote in the format insurers expect and deal with the assessor directly so you are not stuck in the middle.',
    'Can you replace a rotten chassis?' =>
      'Yes, full replacement or a local repair and re-galvanise. We will tell you honestly which is worth doing, and occasionally that the trailer is not worth either.',
    'Is a refurbishment cheaper than a new trailer?' =>
      'Usually, and sometimes by a lot. It depends entirely on the chassis. A sound chassis is worth refurbishing around, a rotten one rarely is.',
  ],
];

$FLAT = [];
foreach ($GROUPS as $qs) { $FLAT += $qs; }

$PAGE = [
  'title'       => 'FAQs | Catering Trailer Questions Answered | Catering Trailers NW',
  'description' => 'Straight answers on catering trailer lead times, deposits, gas and electrical certificates, council registration, towing weights, repairs and refits.',
  'path'        => '/faqs',
  'nav'         => 'faqs',
  'schema'      => [
    schema_faq($FLAT),
    schema_breadcrumbs(['Home' => '/', 'FAQs' => '/faqs']),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Frequently asked questions</p>
      <h1>Straight answers, no sales talk</h1>
      <p class="lede">These are the questions we get asked every week. If yours is not
         here, call <a href="<?= e(tel_href()) ?>" style="color:var(--accent-hover)"><?= e($CFG['phone_display']) ?></a>
         and ask. We would rather answer it now than have you find out later.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap wrap--narrow">
    <?php foreach ($GROUPS as $heading => $qs): ?>
      <div class="rise" style="margin-bottom:3rem">
        <h2 style="font-size:clamp(1.3rem,1.1rem + 1vw,1.75rem)"><?= e($heading) ?></h2>
        <div class="faq" style="margin-top:1.2rem">
          <?php foreach ($qs as $q => $a): ?>
            <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <p class="lede rise">
      Ready to price something up? Use the
      <a href="/request-a-quote" style="color:var(--accent-hover)">quote form</a>, or read about
      <a href="/new-catering-trailers" style="color:var(--accent-hover)">new builds</a> and
      <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">repairs</a>.
    </p>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
