<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/new-catering-trailers';

$FAQ = [
  'What size catering trailer do I need?' =>
    'That depends on your menu, number of staff, equipment and available towing capacity. Smaller businesses may only require a compact trailer while more complex menus may need substantially more working space.',
  'Can I choose single or twin axle?' =>
    'The axle arrangement depends on trailer size, finished weight and specification. We can discuss the most suitable option while planning the trailer.',
  'Can I supply my own appliances?' =>
    'Potentially. If you already own equipment, send us the make, model and dimensions so it can be considered when developing the layout.',
  'Can you build a catering trailer around my menu?' =>
    'Yes. Your menu is one of the most important things to consider because it determines the appliances, refrigeration, extraction, preparation space and storage you are likely to need.',
];

$PAGE = [
  'title'       => 'New Catering Trailers | Bespoke Builds North West',
  'description' => 'New bespoke catering trailers designed around your menu, equipment and business. Single and twin axle catering trailer builds across the North West.',
  'path'        => $SLUG,
  'nav'         => 'new',
  'crumbs'      => ['Home' => '/', 'New Catering Trailers' => $SLUG],
  'schema'      => [
    schema_service('New bespoke catering trailer manufacture',
      'New catering trailers designed around your menu, appliances, working layout and intended use, built for food and hospitality businesses across the North West.',
      $SLUG),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'New Catering Trailers' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">New catering trailers</p>
      <h1><?= e(page_h1($SLUG, 'New Bespoke Catering Trailers')) ?></h1>
      <p class="lede"><?= e(page_hero($SLUG, 'Build your catering trailer around your business from day one.')) ?></p>
      <p class="lede">We design new catering trailers around your menu, appliances, working
         layout and intended use, helping you create a professional mobile catering setup
         that works for your business.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a New Trailer Quote</a>
        <a class="btn btn--ghost btn--lg" href="/gallery">See Our Builds</a>
      </div>
    </div>
  </div>
</section>

<section class="band--tight">
  <div class="wrap rise">
    <?= picture('catering-trailer-interior-swirl-stainless',
        'Catering trailer interior with stainless steel walls, extraction canopy and a stainless counter run',
        ['widths'=>[480,800,1200],'sizes'=>'100vw','eager'=>true,'ratio'=>'16/9']) ?>
  </div>
</section>

<!-- ── designed around your business ─────────────────────────────────── -->
<section class="band" aria-labelledby="around-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Where a build starts</p>
      <h2 id="around-h">A Catering Trailer Designed Around Your Business</h2>
      <p class="lede">Buying a new catering trailer gives you the opportunity to create your
         workspace from the ground up.</p>
      <p class="lede">Rather than adapting your business around somebody else's old layout,
         the trailer can be planned around how you actually intend to prepare food, operate
         equipment and serve customers.</p>
      <p class="lede">Before developing the specification we can discuss your menu, preferred
         equipment, staff numbers, available power, water requirements, storage and serving
         arrangement. This allows the trailer layout to be developed with your operation in
         mind.</p>
    </div>
  </div>
</section>

<!-- ── size ──────────────────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="size-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Getting the size right</p>
      <h2 id="size-h">Choose the Right Trailer Size</h2>
      <p class="lede">Catering trailers can be built in different sizes depending on your
         business and expected operating weight.</p>
      <p class="lede">A compact trailer may be suitable for coffee, desserts or a simple food
         offering, while a larger trailer can provide the working space required for multiple
         appliances and several members of staff.</p>
    </div>

    <h3 style="margin-top:2rem">Things to consider</h3>
    <ul class="taglist rise stagger">
      <?php foreach (['Overall body length','Internal width','Single or twin axle',
                      'Expected equipment weight','Storage requirements','Staff numbers',
                      'Number of serving hatches','Entrance door location',
                      'Required worktop space'] as $c): ?>
        <li><?= e($c) ?></li>
      <?php endforeach; ?>
    </ul>

    <p class="lede rise" style="margin-top:1.6rem;max-width:70ch">We can discuss the most
       appropriate specification based on your intended use.</p>
  </div>
</section>

<!-- ── axles ─────────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="axle-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Chassis</p>
      <h2 id="axle-h">Single Axle &amp; Twin Axle Catering Trailers</h2>
      <p class="lede">The appropriate axle arrangement will depend on the size and
         specification of the trailer.</p>
      <p class="lede">Single axle trailers can work well for smaller, lighter builds. Larger
         catering trailers, or builds carrying more equipment, may require a twin axle
         arrangement.</p>
      <p class="lede">The final choice should take account of the completed trailer weight,
         equipment, payload and intended use rather than simply appearance.</p>
    </div>
  </div>
</section>

<!-- ── hatches and doors ─────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="hatch-h">
  <div class="wrap">
    <div class="grid2" style="gap:2.6rem;align-items:start">
      <div class="rise">
        <p class="kicker">Serving</p>
        <h2 id="hatch-h">Serving Hatches &amp; Entrance Doors</h2>
        <p class="lede">The position of your serving hatch can have a major effect on the
           internal working layout.</p>
        <h3 style="margin-top:1.6rem">We can plan</h3>
        <ul class="clean-list">
          <?php foreach (['Large serving hatches','Multiple serving hatches where appropriate',
                          'Side entrance doors','Rear entrance doors','Customer serving counters',
                          'Internal counter positions','Work surfaces beneath hatches'] as $h): ?>
            <li><?= e($h) ?></li>
          <?php endforeach; ?>
        </ul>
        <p class="lede" style="margin-top:1.4rem">The best position depends on your menu,
           appliances and how you expect customers to queue and order.</p>
      </div>
      <div class="rise">
        <?= picture('catering-trailer-serving-hatch-open',
            'Catering trailer with the serving hatch raised, showing the counter and internal work surface',
            ['class'=>'elev','ratio'=>'4/3']) ?>
      </div>
    </div>
  </div>
</section>

<!-- ── fit-out ───────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="fit-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Inside the trailer</p>
      <h2 id="fit-h">Catering Trailer Interior Fit-Out</h2>
      <p class="lede">Your internal layout can be designed around the equipment and services
         you need. Possible features include:</p>
    </div>

    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['Stainless steel worktops','Preparation counters','Storage cupboards',
                      'Shelving','Sinks','Hand wash facilities','Fresh water tanks',
                      'Waste water tanks','Hot water systems','Refrigeration',
                      'Under-counter fridges','Freezers','Griddles','Fryers','Bain maries',
                      'Microwaves','Coffee equipment','Extraction systems','Electrical sockets',
                      'Internal lighting','Gas installations','Generator connections',
                      'Site-power connections'] as $f): ?>
        <li><?= e($f) ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="callout rise" style="margin-top:1.8rem;max-width:70ch">
      <p style="margin:0">Equipment and specification depend on the individual project and
         quotation.</p>
    </div>
  </div>
</section>

<!-- ── trailer types ─────────────────────────────────────────────────── -->
<section class="band band--panel" aria-labelledby="types-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">By food business</p>
      <h2 id="types-h">Catering Trailers for Different Food Businesses</h2>
    </div>

    <div class="why rise stagger" style="margin-top:2.2rem">
      <div class="why__item"><span class="why__n">01</span><h3>Burger Trailers</h3>
        <p>Layouts designed around griddles, fryers, refrigerated storage, preparation space
           and fast customer service.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Coffee Trailers</h3>
        <p>Compact or larger coffee units with space for coffee machines, refrigeration,
           sinks, storage and serving areas.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Pizza Trailers</h3>
        <p>Trailer layouts designed around pizza preparation, refrigeration, ovens and
           customer service.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Dessert Trailers</h3>
        <p>Ideal for waffles, crepes, ice cream, milkshakes and other dessert concepts.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Breakfast Trailers</h3>
        <p>Practical layouts for griddles, hot holding, refrigeration and preparation
           areas.</p></div>
      <div class="why__item"><span class="why__n">06</span><h3>Street Food Trailers</h3>
        <p>Bespoke layouts for specialist menus and modern street-food businesses.</p></div>
      <div class="why__item"><span class="why__n">07</span><h3>Mobile Bar Trailers</h3>
        <p>Trailers can also be configured around drink preparation, refrigeration, serving
           counters and bar storage. Also available to
           <a href="/catering-trailer-hire" style="color:var(--accent-hover)">hire</a>.</p></div>
    </div>
  </div>
</section>

<!-- ── menu ──────────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="menu-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">The starting point</p>
      <h2 id="menu-h">Built Around Your Menu</h2>
      <p class="lede">Your menu should be one of the first things considered when planning
         your trailer.</p>
      <p class="lede">A business selling burgers and chips requires a very different layout
         from a coffee trailer or dessert business.</p>
    </div>

    <h3 style="margin-top:2rem">Your menu affects</h3>
    <ul class="clean-list clean-list--cols rise">
      <?php foreach (['Appliance selection','Extraction','Refrigeration','Preparation space',
                      'Storage','Electrical demand','Gas requirements','Water requirements',
                      'Staff movement'] as $m): ?>
        <li><?= e($m) ?></li>
      <?php endforeach; ?>
    </ul>
    <p class="lede rise" style="margin-top:1.4rem;max-width:70ch">Planning these elements
       together helps create a better finished layout.</p>
  </div>
</section>

<!-- ── build process ─────────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="proc-h">
  <div class="wrap">
    <div class="process rise">
      <div>
        <p class="kicker">Step by step</p>
        <h2 id="proc-h">The New Trailer Build Process</h2>
        <p class="lede">Seven stages, from the first conversation about your menu to walking
           round the finished trailer with you.</p>
      </div>
      <ol class="steps">
        <li><h3>Tell us what you need</h3>
          <p>Send us your menu, required trailer size, equipment list and any ideas you
             already have.</p></li>
        <li><h3>Layout and specification</h3>
          <p>We develop the proposed layout and specification.</p></li>
        <li><h3>Quotation</h3>
          <p>You receive a written quotation based on the agreed specification.</p></li>
        <li><h3>Trailer construction</h3>
          <p>The chassis, body, doors and serving openings are prepared.</p></li>
        <li><h3>Internal fit-out</h3>
          <p>Work surfaces, services, equipment and storage are installed as required.</p></li>
        <li><h3>Completion</h3>
          <p>The completed trailer is checked against the agreed specification.</p></li>
        <li><h3>Handover</h3>
          <p>We run through the finished trailer with you before collection or agreed
             delivery arrangements.</p></li>
      </ol>
    </div>
  </div>
</section>

<!-- ── first trailer ─────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="first-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">First time buyers</p>
      <h2 id="first-h">Starting a New Mobile Food Business?</h2>
      <p class="lede">If you are buying your first catering trailer, you may not yet know
         exactly what layout you need. That is normal.</p>
      <h3 style="margin-top:1.6rem">Start by telling us</h3>
      <ul class="clean-list">
        <?php foreach (['What you plan to sell','Your approximate budget',
                        'How many staff you expect','Where you plan to trade',
                        'What appliances you think you need','Your tow vehicle if known'] as $s): ?>
          <li><?= e($s) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">From there we can discuss the type of trailer
         that may suit your business. It is also worth reading our
         <a href="/faqs" style="color:var(--accent-hover)">catering trailer FAQs</a> and
         looking through
         <a href="/gallery" style="color:var(--accent-hover)">recent builds</a>.</p>
    </div>
  </div>
</section>

<!-- ── FAQs ──────────────────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="nfaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">New build questions</p>
      <h2 id="nfaq-h">Questions About New Trailers</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p class="lede rise" style="margin-top:1.6rem">
      More answers on the <a href="/faqs" style="color:var(--accent-hover)">FAQs page</a>, or
      see the <a href="/areas" style="color:var(--accent-hover)">areas we cover</a>.
    </p>
  </div>
</section>

<!-- ── final CTA ─────────────────────────────────────────────────────── -->
<section class="cta band" aria-labelledby="ncta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Next step</p>
    <h2 id="ncta-h">Ready to Plan Your Catering Trailer?</h2>
    <p>Tell us about your menu, equipment and preferred trailer size and we can start working
       through the specification with you.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a New Trailer Quote</a>
      <a class="btn btn--ghost btn--lg" href="/gallery">View Our Builds</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
