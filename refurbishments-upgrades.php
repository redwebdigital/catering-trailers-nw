<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/refurbishments-upgrades';

$FAQ = [
  'Can you refurbish an old catering trailer?' =>
    'Yes. If the basic trailer remains suitable, refurbishment can include new interior surfaces, worktops, equipment layout, hatches, doors, electrics, water systems and other upgrades.',
  'Can you add another serving hatch?' =>
    'Potentially, depending on the trailer construction and available structure. The trailer will need to be assessed before structural changes are confirmed.',
  'Can you change the equipment inside my trailer?' =>
    'Yes, depending on available space and the electrical, gas, water and extraction requirements of the new equipment.',
];

$PAGE = [
  'title'       => 'Catering Trailer Refurbishments & Upgrades',
  'description' => 'Upgrade your catering trailer with new interiors, worktops, hatches, electrics, water systems, equipment and professional refurbishment work.',
  'path'        => $SLUG,
  'nav'         => 'refurb',
  'crumbs'      => ['Home' => '/', 'Refurbishments & Upgrades' => $SLUG],
  'schema'      => [
    schema_service('Catering trailer refurbishment and upgrades',
      'Refurbishment and upgrade work on existing catering trailers: interiors, worktops, serving hatches, doors, electrical systems, water systems and equipment changes.',
      $SLUG),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Refurbishments & Upgrades' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Refurbishments and upgrades</p>
      <h1><?= e(page_h1($SLUG, 'Catering Trailer Refurbishments & Upgrades')) ?></h1>
      <p class="lede"><?= e(page_hero($SLUG, 'Improve an existing catering trailer without necessarily starting again.')) ?></p>
      <p class="lede">From interior upgrades and new worktops to hatch modifications, electrics
         and equipment changes, we can help modernise your trailer around your business.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Refurbishment Quote</a>
        <a class="btn btn--ghost btn--lg" href="/gallery">See Our Work</a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="life-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Worth keeping?</p>
      <h2 id="life-h">Give Your Catering Trailer a New Lease of Life</h2>
      <p class="lede">An older trailer may still have a perfectly usable shell and chassis but
         no longer suit the way your business operates.</p>
      <p class="lede">Perhaps your menu has changed. Maybe you need more refrigeration, better
         preparation space or a larger serving hatch.</p>
      <p class="lede">A refurbishment allows you to improve your existing trailer without
         automatically replacing the entire unit. If the shell is past saving we will tell you
         and talk about a
         <a href="/new-catering-trailers" style="color:var(--accent-hover)">new build</a>
         instead.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="int-h">
  <div class="wrap">
    <div class="grid2" style="gap:2.6rem;align-items:start">
      <div class="rise">
        <p class="kicker">Inside</p>
        <h2 id="int-h">Interior Catering Trailer Refurbishments</h2>
        <p class="lede">Interior upgrades can include:</p>
        <ul class="clean-list clean-list--cols" style="margin-top:1rem">
          <?php foreach (['New work surfaces','Stainless steel counters','Cupboards','Shelving',
                          'Storage','Flooring','Wall panels','Lighting','Electrical sockets',
                          'Sinks','Hot water','Fresh water systems','Waste water systems'] as $i): ?>
            <li><?= e($i) ?></li>
          <?php endforeach; ?>
        </ul>
        <p class="lede" style="margin-top:1.4rem">The final specification can be adapted around
           your existing trailer.</p>
      </div>
      <div class="rise">
        <?= picture('catering-trailer-interior-swirl-stainless',
            'Refurbished catering trailer interior with stainless steel wall panels and a clean counter run',
            ['class'=>'elev','ratio'=>'4/3']) ?>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="hatchmod-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Serving hatches</p>
      <h2 id="hatchmod-h">Serving Hatch Modifications</h2>
      <p class="lede">A small or poorly positioned serving hatch can restrict service and
         customer interaction.</p>
      <h3 style="margin-top:1.6rem">Depending on the trailer construction, it may be possible to</h3>
      <ul class="clean-list">
        <?php foreach (['Enlarge an existing hatch','Add a new serving hatch',
                        'Replace a damaged hatch','Change hatch supports','Add a serving counter',
                        'Modify internal preparation space around the opening'] as $h): ?>
          <li><?= e($h) ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="callout" style="margin-top:1.4rem">
        <p style="margin:0">Every trailer needs to be assessed individually before structural
           modifications are confirmed.</p>
      </div>
    </div>
  </div>
</section>

<section class="band band--panel" aria-labelledby="doormod-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Doors</p>
      <h2 id="doormod-h">Door Modifications</h2>
      <p class="lede">Door position can have a big impact on usable internal space.</p>
      <h3 style="margin-top:1.6rem">Possible work may include</h3>
      <ul class="clean-list">
        <?php foreach (['Replacement entrance doors','Repairing damaged doors',
                        'Adding another door where appropriate',
                        'Changing internal arrangements around an entrance',
                        'Updating locks and hardware'] as $d): ?>
          <li><?= e($d) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="elec-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Power</p>
      <h2 id="elec-h">Electrical Upgrades</h2>
      <p class="lede">As catering businesses add equipment, the original electrical setup may
         no longer be suitable.</p>
      <h3 style="margin-top:1.6rem">Electrical upgrades can include</h3>
      <ul class="clean-list">
        <?php foreach (['Additional sockets','Lighting','Equipment supply points',
                        'Site-power connections','Generator connections',
                        'Reconfiguration to suit new appliances'] as $x): ?>
          <li><?= e($x) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">Any electrical installation should be designed
         around the actual equipment and power requirements.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="water-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Water</p>
      <h2 id="water-h">Water Systems</h2>
      <p class="lede">A catering trailer water setup may include:</p>
      <ul class="clean-list clean-list--cols" style="margin-top:1rem">
        <?php foreach (['Fresh water tank','Waste water tank','Pump','Sink',
                        'Hand wash facility','Hot water heater','Pipework'] as $w): ?>
          <li><?= e($w) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">If your existing system is outdated or poorly
         positioned, it may be possible to improve the layout during refurbishment.</p>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="equip-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Equipment</p>
      <h2 id="equip-h">Equipment Changes</h2>
      <p class="lede">If your menu changes, your equipment may need to change with it. We can
         plan refurbishment work around equipment such as:</p>
    </div>
    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['Griddles','Fryers','Bain maries','Refrigerators','Freezers',
                      'Coffee machines','Microwaves','Extraction','Hot holding equipment'] as $q): ?>
        <li><?= e($q) ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="callout rise" style="margin-top:1.6rem;max-width:70ch">
      <p style="margin:0">Equipment availability and installation requirements depend on the
         individual build.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="look-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Appearance</p>
      <h2 id="look-h">Refresh the Look of Your Trailer</h2>
      <p class="lede">A clean, professional trailer helps your business make a stronger first
         impression. Refurbishment can also address:</p>
      <ul class="clean-list clean-list--cols" style="margin-top:1rem">
        <?php foreach (['Damaged panels','Worn trim','Old flooring','Poor interior finishes',
                        'Untidy worktops','Ageing doors','Damaged hatch areas'] as $l): ?>
          <li><?= e($l) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">If the trailer is damaged rather than simply
         tired, start with
         <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">catering trailer repairs</a>.</p>
      <div class="btn-row" style="margin-top:1.6rem">
        <a class="btn btn--accent" href="/request-a-quote">Request a Refurbishment Quote</a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="ufaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">Refurbishment questions</p>
      <h2 id="ufaq-h">Questions About Refurbishments</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="cta band" aria-labelledby="ucta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Send us photos</p>
    <h2 id="ucta-h">Is Your Existing Trailer Worth Upgrading?</h2>
    <p>Send us photographs and tell us what you would like to change.</p>
    <p>We can then discuss whether repair, refurbishment or replacement may be the best route.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/catering-trailer-repairs">View Trailer Repairs</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
