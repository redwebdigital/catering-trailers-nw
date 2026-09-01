<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$FAQ = [
  'How long does a catering trailer build take?' =>
    'Six to ten weeks for most builds from the day your deposit clears. April to July is our busiest run, so book earlier if you want a summer start.',
  'Will it pass my council\'s inspection?' =>
    'Every trailer leaves us with a Gas Safe certificate and an electrical certificate. Stainless throughout, sealed joints, hot and cold water, and the wash hand basin your environmental health officer will look for.',
  'What deposit do you need?' =>
    'Thirty percent to book your slot and buy your equipment. The balance clears before you collect.',
  'Can my car tow it?' =>
    'Send us your vehicle and we will tell you straight. This catches more people out than anything else, and it is easier to solve before the build than after.',
  'Do you repair trailers you did not build?' =>
    'Yes. Chassis, panels, hatches, gas, electrics and accident damage, whoever built it.',
  'How fast can you fix accident damage?' =>
    'Get photos to us the day it happens. Most insurance work starts within the week, because every day off the road is a day you are not taking money.',
];

$PAGE = [
  'title'       => 'Catering Trailers NW | Bespoke Catering Trailers Built in the North West',
  'description' => 'Bespoke catering trailers built in the North West, plus repairs, chassis work and refits on any trailer. Gas Safe and electrical certificates supplied. Get a quote today.',
  'path'        => '/',
  'nav'         => '',
  'hero_scrub'  => false,   // static hero, no scroll film
  'og_image'    => '/assets/img/og-default.jpg',
  'schema'      => [
    schema_faq($FAQ),
    schema_service(
      'Bespoke catering trailer manufacture',
      'Custom built catering trailers for street food traders, burger vans and coffee trailers, built to order in the North West.',
      '/new-catering-trailers'
    ),
  ],
];

require __DIR__ . '/inc/header.php';

$OVH = page_seo('/');
$HERO_H1 = $OVH['h1'] ?? '';
if ($HERO_H1 === '') { $HERO_H1 = $OVH['hero_head'] ?? ''; }
if ($HERO_H1 === '') { $HERO_H1 = copytext('hero_heading', 'Bespoke Catering Trailers Built for Your Business'); }
?>

<!-- ═══ HERO ═══════════════════════════════════════════════════════════ -->
<!--
  A single static hero on every device. The scroll-scrubbed film was
  replaced on request. Its engine still lives in assets/js/hero.js and the
  footage is kept in review/, so it can be brought back by setting
  'hero_scrub' => true and restoring the markup from git history.
-->
<section class="hero--still" aria-label="Introduction">
  <div class="hero--still__img" aria-hidden="true"></div>
  <div class="hero--still__scrim" aria-hidden="true"></div>

  <div class="hero--still__body">
    <div class="wrap">
      <p class="kicker"><?= e(copytext('hero_kicker', 'Built in the North West')) ?></p>
      <h1><?= e($HERO_H1) ?></h1>
      <p class="hero--still__lede"><?= e(copytext('hero_sub',
        'Professional catering trailers designed around your menu, equipment and working layout. Whether you are starting a new mobile food business, replacing an older unit or expanding an existing operation, we can help you create a trailer designed around the way you actually work.')) ?></p>

      <div class="btn-row">
        <a class="btn btn--accent btn--lg" href="/request-a-quote"><?= e(copytext('hero_cta', 'Request a Quote')) ?></a>
        <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">View New Catering Trailers</a>
        <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">
          WhatsApp
        </a>
      </div>

      <ul class="hero--still__proof">
        <?php foreach ([
            copytext('proof_1', 'Gas Safe and electrical certificates handed over with the keys'),
            copytext('proof_2', $CFG['chassis_warranty'] . ' anti corrosion chassis warranty'),
            copytext('proof_3', 'Ready to trade the day you collect it'),
        ] as $proof): if (trim($proof) === '') continue; ?>
          <li><?= e($proof) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ═══ TRUST STRIP ════════════════════════════════════════════════════ -->
<section class="trust" aria-label="Why traders use us">
  <div class="wrap">
    <div class="trust__grid">
      <div class="trust__item"><b>Gas Safe registered</b><span>Certificate supplied with every trailer</span></div>
      <div class="trust__item"><b>Electrical certificate</b><span>Tested, signed and handed over</span></div>
      <div class="trust__item"><b><?= e($CFG['chassis_warranty']) ?> chassis warranty</b><span>Against corrosion, in writing</span></div>
      <div class="trust__item"><b>Built in the North West</b><span>Our own workshop, not an import</span></div>
    </div>
  </div>
</section>


<!-- ═══ THE TWO DOORS ══════════════════════════════════════════════════ -->
<section class="band" aria-labelledby="doors-h">
  <div class="wrap">
    <div class="rise" style="max-width:72ch">
      <p class="kicker">What we do</p>
      <h2 id="doors-h">Professional Catering Trailer Builders in the North West</h2>
      <p class="lede">Catering Trailers NW designs and builds bespoke catering trailers for
         street-food traders, mobile caterers, coffee businesses, event operators and
         established hospitality companies.</p>
      <p class="lede">Every business is different, which is why we do not believe in forcing
         customers into one standard layout. We can plan your trailer around your menu,
         appliances, number of staff, serving position and preferred workflow.</p>
      <p class="lede">Depending on your requirements, your trailer can incorporate serving
         hatches, entrance doors, stainless steel work surfaces, sinks, refrigeration, cooking
         equipment, extraction, electrical systems, water storage and purpose-built storage
         areas.</p>
      <p class="lede">Our aim is simple: to create a trailer that looks professional and works
         properly when your business is busy.</p>
    </div>

    <div class="doors rise" style="margin-top:2.6rem">

      <a class="door" href="/new-catering-trailers">
        <?= picture('catering-trailer-serving-hatch-open',
             'New bespoke catering trailer with the serving hatch raised',
             ['sizes' => '(max-width:900px) 100vw, 58vw', 'widths' => [480,800,1200]]) ?>
        <h3>New Catering Trailers</h3>
        <p>Starting from a blank canvas gives you the opportunity to create a catering trailer
           around your exact business. We can discuss trailer size, single or twin axle
           configurations, serving hatch locations, doors, equipment layout and internal
           working space.</p>
        <span class="door__go">Explore New Catering Trailers
          <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.8 2.4 14.4 8l-5.6 5.6-1.1-1.1 3.7-3.7H1.6V7.2h9.8L7.7 3.5z"/></svg>
        </span>
      </a>

      <a class="door" href="/catering-trailer-repairs">
        <?= picture('catering-trailer-side-elevation',
             'Catering trailer side elevation before repair work',
             ['sizes' => '(max-width:900px) 100vw, 40vw', 'widths' => [480,800,1200]]) ?>
        <h3>Catering Trailer Repairs</h3>
        <p>Damage, wear and ageing components can quickly affect the appearance and operation
           of a catering trailer. We can assist with body damage, serving hatches, doors,
           interior fittings, flooring, chassis-related work and electrical issues.</p>
        <span class="door__go">View Catering Trailer Repairs
          <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.8 2.4 14.4 8l-5.6 5.6-1.1-1.1 3.7-3.7H1.6V7.2h9.8L7.7 3.5z"/></svg>
        </span>
      </a>

    </div>

    <p class="hint rise" style="margin-top:1.4rem">
      If possible, upload photographs when
      <a href="/request-a-quote" style="color:var(--accent-hover)">requesting a quote</a>
      so we can get a better understanding of the work required.
    </p>

    <div class="why rise stagger" style="margin-top:2.6rem">
      <div class="why__item"><span class="why__n">01</span>
        <h3>Trailer Refurbishments &amp; Upgrades</h3>
        <p>Not every trailer needs replacing. If the basic structure of your existing trailer
           is suitable, refurbishment can be an effective way to improve its appearance,
           working layout and equipment. Upgrades can include new worktops, storage, flooring,
           lighting, electrics, water systems, hatches, doors and equipment installation.</p>
        <p><a href="/refurbishments-upgrades" style="color:var(--accent-hover)">View Refurbishments &amp; Upgrades</a></p>
      </div>
      <div class="why__item"><span class="why__n">02</span>
        <h3>Catering Trailer Hire &amp; Mobile Bars</h3>
        <p>For events, temporary requirements and short-term hospitality projects, catering
           trailer and mobile bar hire may also be available. Hire can be useful for festivals,
           events, seasonal trading, temporary kitchens and businesses waiting for a permanent
           solution.</p>
        <p>If you expect to trade regularly, we can also discuss building a bespoke trailer
           specifically for your business.</p>
        <p><a href="/catering-trailer-hire" style="color:var(--accent-hover)">View Trailer Hire</a></p>
      </div>
    </div>
  </div>
</section>


<!-- ═══ WHY US ═════════════════════════════════════════════════════════ -->
<section class="band band--well" aria-labelledby="why-h">
  <div class="wrap">
    <div class="rise" style="max-width:72ch">
      <p class="kicker">How we plan a build</p>
      <h2 id="why-h">Built Around the Way You Work</h2>
      <p class="lede">A catering trailer needs to do more than look good.</p>
      <p class="lede">When several people are working inside, every appliance, worktop,
         storage area and serving point needs to be positioned properly.</p>
      <p class="lede">Before planning a new build we want to understand:</p>
    </div>

    <ul class="clean-list clean-list--cols rise" style="margin-top:1.6rem;max-width:72ch">
      <?php foreach ([
          'What food or drinks you will sell',
          'What equipment you need',
          'How many staff will work inside',
          'How customers will be served',
          'Whether you need gas, electricity or both',
          'Your refrigeration requirements',
          'Fresh and waste water requirements',
          'Storage requirements',
          'Your preferred trailer size',
          'Your tow vehicle where relevant',
      ] as $q): ?>
        <li><?= e($q) ?></li>
      <?php endforeach; ?>
    </ul>

    <p class="lede rise" style="margin-top:1.6rem;max-width:72ch">Getting these details right
       at the beginning can make a major difference to the finished trailer.</p>
  </div>
</section>


<!-- ═══ BUILD PROCESS + THE SPEC BUILDER ═══════════════════════════════ -->
<section class="band" id="process" aria-labelledby="proc-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">How a build runs</p>
      <h2 id="proc-h"><?= e(copytext('builder_heading', 'From Your Menu to Your Pitch')) ?></h2>
      <p class="lede"><?= e(copytext('builder_intro', 'Five stages. You know where your trailer is at every one of them.')) ?></p>
    </div>

    <div class="process rise" style="margin-top:2.8rem">

      <?php
      // Stages come from the admin area. The shipped five are the fallback so
      // the page is never blank if the database is unavailable.
      $STAGES = builder_stages();
      if (!$STAGES) {
          $STAGES = [
            ['title' => 'Your Requirements',      'body' => 'Tell us about your menu, equipment, pitch, trailer size and how you intend to use the trailer.'],
            ['title' => 'Layout & Specification', 'body' => 'We develop the proposed trailer specification and layout around your requirements.'],
            ['title' => 'Chassis & Shell',        'body' => 'The chassis, body, hatch and door openings are prepared to suit the agreed build.'],
            ['title' => 'Fit-Out',                'body' => 'Work surfaces, equipment, gas, electrics, water systems and other internal features are installed as required.'],
            ['title' => 'Handover',               'body' => 'Once the trailer is complete, the finished build is checked and prepared for handover.'],
          ];
      }
      ?>
      <ol class="steps">
        <?php foreach ($STAGES as $st): ?>
          <li>
            <h3><?= e($st['title']) ?></h3>
            <p><?= e($st['body']) ?></p>
          </li>
        <?php endforeach; ?>
      </ol>

      <div>
        <!-- ── the signature element: a technical elevation that draws itself,
             and the one interactive moment, which redraws it live ── -->
        <div class="elev" aria-hidden="true">
          <svg viewBox="0 0 470 252" role="img">
            <rect class="fill" id="specFill" x="46" y="118" width="244" height="70" rx="9"/>
            <rect class="body" id="specBody" x="46" y="118" width="244" height="70" rx="9"/>
            <path class="hatch" id="specHatch"
                  d="M105 170 H235 V132 H105 Z M105 132 L75 104 H205 L235 132"/>
            <path class="chassis" id="specChassis"
                  d="M46 188 H290 M46 188 L16 202 H4 M26 202 V213"/>
            <g class="wheel" id="specAxle1">
              <circle cx="197" cy="196" r="16"/>
              <circle cx="197" cy="196" r="6"/>
            </g>
            <g class="wheel" id="specAxle2" style="display:none">
              <circle cx="241" cy="196" r="16"/>
              <circle cx="241" cy="196" r="6"/>
            </g>
            <path class="dim" id="specDimW" d="M46 224 H290 M46 218 V230 M290 218 V230"/>
            <text class="lbl" id="specDimLbl" x="168" y="244" text-anchor="middle">3.0m</text>
            <path class="dim" id="specDimH" d="M306 118 V188 M300 118 H312 M300 188 H312"/>
            <text class="lbl" id="specDimHLbl" x="320" y="157">2.1m</text>
          </svg>
        </div>

        <?php
        /* Every option below is loaded from the admin area. Nothing here is
           hard-coded: add, rename, reprice, reorder or disable them under
           Quote Builder and this section follows. The fallbacks only appear
           if the database is unreachable, so the page never renders empty. */
        $B_LEN  = builder_options('length');
        $B_AXLE = builder_options('axle');
        $B_USE  = builder_options('use');
        if (!$B_LEN) $B_LEN = [
            ['label'=>'2.4m','value'=>'2.4','draw_width'=>196,'price_from'=>''],
            ['label'=>'3.0m','value'=>'3.0','draw_width'=>244,'price_from'=>''],
            ['label'=>'3.5m','value'=>'3.5','draw_width'=>285,'price_from'=>''],
            ['label'=>'4.2m','value'=>'4.2','draw_width'=>342,'price_from'=>''],
        ];
        if (!$B_AXLE) $B_AXLE = [
            ['label'=>'Single','value'=>'single','price_from'=>''],
            ['label'=>'Twin','value'=>'twin','price_from'=>''],
        ];
        if (!$B_USE) $B_USE = [
            ['label'=>'Burgers','value'=>'Burgers','price_from'=>''],
            ['label'=>'Coffee','value'=>'Coffee','price_from'=>''],
            ['label'=>'Pizza','value'=>'Pizza','price_from'=>''],
            ['label'=>'Fried chicken','value'=>'Fried chicken','price_from'=>''],
            ['label'=>'Desserts','value'=>'Desserts','price_from'=>''],
        ];
        $firstLen  = $B_LEN[0]  ?? null;
        $firstAxle = $B_AXLE[0] ?? null;
        ?>
        <div class="spec" id="spec"
             data-default-length="<?= e((string)($firstLen['value'] ?? '3.0')) ?>"
             data-default-width="<?= (int)($firstLen['draw_width'] ?? 244) ?>"
             data-default-axle="<?= e((string)($firstAxle['value'] ?? 'single')) ?>">

          <div class="spec__row">
            <span>Body length</span>
            <div class="chips">
              <?php foreach ($B_LEN as $i => $o): ?>
                <button class="chip" type="button" data-group="length"
                        data-value="<?= e((string)$o['value']) ?>"
                        data-draw="<?= (int)($o['draw_width'] ?? 0) ?>"
                        <?= !empty($o['price_from']) ? 'data-price="' . e((string)$o['price_from']) . '"' : '' ?>
                        aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"><?= e((string)$o['label']) ?></button>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="spec__row">
            <span>Axle</span>
            <div class="chips">
              <?php foreach ($B_AXLE as $i => $o): ?>
                <button class="chip" type="button" data-group="axle"
                        data-value="<?= e((string)$o['value']) ?>"
                        <?= !empty($o['price_from']) ? 'data-price="' . e((string)$o['price_from']) . '"' : '' ?>
                        aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"><?= e((string)$o['label']) ?></button>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="spec__row">
            <span>Fit-out for</span>
            <div class="chips">
              <?php foreach ($B_USE as $o): ?>
                <button class="chip" type="button" data-group="use"
                        data-value="<?= e((string)$o['value']) ?>"
                        <?= !empty($o['price_from']) ? 'data-price="' . e((string)$o['price_from']) . '"' : '' ?>
                        aria-pressed="false"><?= e((string)$o['label']) ?></button>
              <?php endforeach; ?>
            </div>
          </div>

          <p class="spec__out" id="specOut"></p>
          <a class="btn btn--accent" id="specGo" href="/request-a-quote"><?= e(copytext('builder_button', 'Send this spec')) ?></a>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ═══ PREVIOUS WORK ══════════════════════════════════════════════════ -->
<section class="band band--well" aria-labelledby="work-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">Previous work</p>
      <h2 id="work-h">Trailers that left our workshop</h2>
      <p class="lede">Photographs of our own units. Every trailer goes out in white as
         standard and is finished to whatever colour or wrap you want.</p>
    </div>

    <div class="gal rise stagger" style="margin-top:2.6rem">
      <figure>
        <?= picture('catering-trailer-serving-side', 'White catering trailer, serving side with the hatch closed',
             ['sizes' => '(max-width:700px) 100vw, 33vw']) ?>
        <figcaption>Serving side, hatch closed <span class="tag">Single axle</span></figcaption>
      </figure>
      <figure>
        <?= picture('catering-trailer-front-three-quarter', 'Catering trailer front three quarter showing the A frame and gas locker',
             ['sizes' => '(max-width:700px) 100vw, 33vw']) ?>
        <figcaption>A frame and gas locker <span class="tag">3.0m body</span></figcaption>
      </figure>
      <figure>
        <?= picture('catering-trailer-rear-door', 'Rear of a catering trailer showing the personnel door and road lights',
             ['sizes' => '(max-width:700px) 100vw, 33vw']) ?>
        <figcaption>Rear door and road lights <span class="tag">Road legal</span></figcaption>
      </figure>
    </div>

    <div class="btn-row rise" style="margin-top:2rem">
      <a class="btn btn--ghost" href="/gallery">See all our builds</a>
    </div>
  </div>
</section>


<!-- ═══ TESTIMONIALS ═══════════════════════════════════════════════════ -->
<section class="band" aria-labelledby="say-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">What customers say</p>
      <h2 id="say-h">In their words</h2>
    </div>

    <!-- ────────────────────────────────────────────────────────────────
         PLACEHOLDER. No invented reviews are published here.
         To add a real one, copy a <figure class="quote"> block below and
         fill in the words, the name and the town. Delete this notice
         once you have at least two real testimonials.
         ──────────────────────────────────────────────────────────────── -->
    <div class="placeholder rise" style="margin-top:2rem">
      <b>Your customer testimonials go here</b>
      <p>This space is built and styled, ready for real quotes from your customers.
         Send them over and we will drop them in. Nothing invented gets published on
         a trading website.</p>
    </div>

    <!--
    <div class="quotes rise stagger" style="margin-top:2rem">
      <figure class="quote">
        <blockquote>Their exact words go here.</blockquote>
        <figcaption><cite>Customer name</cite>Business name, town</figcaption>
      </figure>
    </div>
    -->
  </div>
</section>


<!-- ═══ GOOGLE REVIEWS ═════════════════════════════════════════════════ -->
<section class="band band--well" aria-labelledby="rev-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">Google reviews</p>
      <h2 id="rev-h">Rated by the traders we build for</h2>
    </div>

    <?php if ($CFG['google_place_id']): ?>
      <div id="googleReviews" class="quotes rise" style="margin-top:2rem"
           data-place="<?= e($CFG['google_place_id']) ?>"></div>
      <div class="btn-row rise" style="margin-top:1.6rem">
        <a class="btn btn--ghost" href="<?= e($CFG['google_reviews_url']) ?>" target="_blank" rel="noopener">
          Read all reviews on Google
        </a>
      </div>
    <?php else: ?>
      <div class="placeholder rise" style="margin-top:2rem">
        <b>Google reviews connect here</b>
        <p>Add your Google Place ID to <code>inc/config.php</code> and your live reviews
           appear in this block automatically, with a link through to your Google profile.
           Until then this section stays honest and empty rather than showing anything made up.</p>
      </div>
    <?php endif; ?>
  </div>
</section>


<!-- ═══ FAQs ═══════════════════════════════════════════════════════════ -->
<section class="band" aria-labelledby="faq-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Straight answers</p>
      <h2 id="faq-h">Questions we get asked every week</h2>
    </div>

    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details>
          <summary><?= e($q) ?></summary>
          <div class="ans"><?= e($a) ?></div>
        </details>
      <?php endforeach; ?>
    </div>

    <div class="btn-row rise" style="margin-top:1.8rem">
      <a class="btn btn--ghost" href="/faqs">All frequently asked questions</a>
    </div>
  </div>
</section>


<!-- ═══ AREAS COVERED ══════════════════════════════════════════════════ -->
<section class="band band--well" aria-labelledby="areas-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">Areas we cover</p>
      <h2 id="areas-h">Catering Trailers Across the North West</h2>
      <p class="lede">Catering Trailers NW serves customers across Warrington and the wider
         North West.</p>
      <p class="lede">We welcome enquiries from areas including Manchester, Liverpool,
         Cheshire, Widnes, Runcorn, St Helens, Wigan, Bolton, Northwich, Knutsford and
         Altrincham. If your location is not listed, you can still send us your
         requirements.</p>
    </div>

    <div class="areas rise stagger" style="margin-top:2.4rem">
      <?php foreach ($CFG['areas'] as $slug => $area): ?>
        <a class="area" href="/areas/catering-trailers-<?= e($slug) ?>">
          <b><?= e($area['name']) ?></b>
          <span><?= e($area['county']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="btn-row rise" style="margin-top:2rem">
      <a class="btn btn--ghost" href="/areas">View Areas We Cover</a>
    </div>
  </div>
</section>

<!-- ═══ FINAL CALL TO ACTION ═══════════════════════════════════════════ -->
<section class="cta band" aria-labelledby="hcta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Next step</p>
    <h2 id="hcta-h">Planning a Catering Trailer?</h2>
    <p>Tell us about your menu, trailer size, equipment and what you want to achieve.</p>
    <p>The more information you provide, the better we can understand your project.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">View New Catering Trailers</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
