<?php
/**
 * Shared renderer for an area page.
 *
 * An area file sets $AREA to its slug and requires this. Everything else comes
 * from areas-data.php, so a new location is one data entry plus a two-line file.
 */

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$AREAS = require __DIR__ . '/areas-data.php';

if (!isset($AREA) || !isset($AREAS[$AREA])) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$A    = $AREAS[$AREA];
$town = $A['name'];
$path = '/areas/catering-trailers-' . $AREA;

$FAQ = [
  "Do you deliver catering trailers to {$town}?" =>
    "Yes. We deliver new builds and collect trailers for repair throughout {$town} and the surrounding area. {$A['logistics']}",
  "Can you repair a trailer you did not build, in {$town}?" =>
    "Yes, any make and any age. Send photographs and we will tell you the same day what it needs and roughly what it costs.",
  "How long is the wait for a new trailer?" =>
    "Currently {$CFG['lead_time']} for most builds from the day the deposit clears. April to July books up first.",
  "Who do I register my catering trailer with in {$town}?" =>
    "{$A['council']}. {$A['note']}",
];

$PAGE = [
  'title'       => "Catering Trailers {$town} | New Builds & Repairs | Catering Trailers NW",
  'description' => "Bespoke catering trailers built and repaired for traders in {$town}. "
                 . "New builds, chassis and accident repairs, gas and electrical work, "
                 . "delivered across {$A['county']}.",
  'path'        => $path,
  'nav'         => '',
  'schema'      => [
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Areas' => '/areas', "Catering Trailers {$town}" => $path]),
    [
      '@type'       => 'Service',
      'name'        => "Catering trailer manufacture and repair in {$town}",
      'description' => "Bespoke catering trailer builds, repairs and refurbishments for mobile "
                     . "caterers in {$town}, {$A['county']}.",
      'url'         => url($path),
      'provider'    => ['@id' => url('/#business')],
      'areaServed'  => [
        '@type'            => 'City',
        'name'             => $town,
        'containedInPlace' => ['@type' => 'AdministrativeArea', 'name' => $A['county']],
      ],
    ],
  ],
];

require __DIR__ . '/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <nav aria-label="Breadcrumb" style="margin-bottom:1.4rem">
      <ol style="display:flex;flex-wrap:wrap;gap:.5rem;list-style:none;margin:0;padding:0;
                 font:500 .72rem/1 var(--mono);letter-spacing:.1em;text-transform:uppercase;color:var(--steel)">
        <li><a href="/" style="color:inherit">Home</a></li>
        <li aria-hidden="true">/</li>
        <li>Catering Trailers <?= e($town) ?></li>
      </ol>
    </nav>

    <div class="rise" style="max-width:62ch">
      <p class="kicker"><?= e($town) ?> · <?= e($A['county']) ?></p>
      <h1>Catering Trailers <?= e($town) ?></h1>
      <p class="lede"><?= e($A['lead']) ?></p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
        <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="local-h">
  <div class="wrap">
    <div class="process rise">
      <div>
        <p class="kicker">Trading in <?= e($town) ?></p>
        <h2 id="local-h">What the trade looks like here</h2>
        <p class="lede"><?= e($A['trading']) ?></p>
        <p class="lede" style="margin-top:1.1rem"><?= e($A['logistics']) ?></p>

        <div class="callout" style="margin-top:1.6rem">
          <p><strong>Registering in <?= e($town) ?>.</strong> <?= e($A['note']) ?>
             Register at least 28 days before you intend to start trading.</p>
        </div>
      </div>

      <div>
        <?= picture('catering-trailer-serving-hatch-open',
            "Catering trailer built for a trader in {$town}",
            ['widths'=>[480,800,1200],'sizes'=>'(max-width:960px) 100vw, 46vw','ratio'=>'4/3']) ?>
      </div>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="serv-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">What we do for <?= e($town) ?> traders</p>
      <h2 id="serv-h">New builds and repairs, both ways</h2>
    </div>

    <div class="why rise stagger" style="margin-top:2.4rem">
      <div class="why__item"><span class="why__n">01</span><h3>New bespoke trailers</h3>
        <p>Built around your menu, 2.4m to 4.2m, single or twin axle, delivered to
           <?= e($town) ?>. <a href="/new-catering-trailers" style="color:var(--accent-hover)">How we build them</a>.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Accident and damage repairs</h3>
        <p>Insurance work handled directly with the assessor. Collection from
           <?= e($town) ?> arranged if the trailer is not roadworthy.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Chassis repair and replacement</h3>
        <p>Rot, cracks and failed welds. We will tell you honestly whether it is worth
           doing before you spend.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Gas and electrical work</h3>
        <p>Full re-pipes and rewires, tested and certified, so you pass your next
           inspection rather than argue about it.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Refits and upgrades</h3>
        <p>Stainless fit-outs and layout changes when the menu has outgrown the trailer.
           <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">See refits</a>.</p></div>
      <div class="why__item"><span class="why__n">06</span><h3>Serving hatch changes</h3>
        <p>New apertures, hatch conversions and canopies, including moving the hatch to
           the other side of the body.</p></div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="afaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker"><?= e($town) ?> questions</p>
      <h2 id="afaq-h">What traders here ask</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $ans): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($ans) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="near-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">Nearby</p>
      <h2 id="near-h">We also cover</h2></div>
    <div class="areas rise stagger" style="margin-top:2rem">
      <?php foreach ($AREAS as $slug => $other):
              if ($slug === $AREA) continue; ?>
        <a class="area" href="/areas/catering-trailers-<?= e($slug) ?>">
          <b><?= e($other['name']) ?></b><span><?= e($other['county']) ?></span></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/footer.php'; ?>
