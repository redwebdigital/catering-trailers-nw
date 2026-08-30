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
  'hero_scrub'  => true,
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
?>

<!-- ═══ HERO ═══════════════════════════════════════════════════════════ -->

<!-- Desktop: the film plays as you scroll. -->
<section class="hero hero--scrub" aria-label="Introduction">
  <div class="hero__pin" id="heroPin">
    <div class="hero__stage" id="stage"
         data-video="/assets/video/hero-scrub.mp4"
         data-poster="/assets/img/hero/hero-poster.webp"
         data-poster-fallback="/assets/img/hero/hero-poster.jpg"
         data-bytes="2179207">

      <div class="hero__poster" id="heroPoster" aria-hidden="true"></div>
      <video class="hero__video" id="heroVideo" preload="none" muted playsinline
             aria-hidden="true" tabindex="-1"></video>
      <div class="hero__scrim" aria-hidden="true"></div>

      <div class="hero__bands">

        <!-- The page's single <h1> lives in the static hero below, which is the
             version Google indexes on mobile. This is the same words for desktop
             visitors, marked as a level-one heading for assistive tech without
             putting a second <h1> element in the document. -->
        <div class="cap" data-a="0" data-b="0.20">
          <div class="cap__in">
            <p class="cap-h1" role="heading" aria-level="1" data-split="word">Bespoke Catering Trailers Built in the North West</p>
          </div>
        </div>

        <div class="cap" data-a="0.24" data-b="0.46">
          <div class="cap__in">
            <h2 data-split="grid">Built to your menu. Not off a shelf.</h2>
          </div>
        </div>

        <div class="cap cap--wide" data-a="0.50" data-b="0.72" data-ramp="0.036">
          <div class="cap__in">
            <h2 data-split="word">Gas Safe and electrical certificates handed over with the keys.</h2>
          </div>
        </div>

        <div class="cap" data-a="0.76" data-b="1">
          <div class="cap__in">
            <h2 data-split="word">Ready to trade the day you collect it.</h2>
            <p>Trailers, repairs and refits for street food traders across the North West.</p>
            <div class="btn-row">
              <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
              <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>" data-track="call-hero">
                Call <?= e($CFG['phone_display']) ?>
              </a>
            </div>
          </div>
        </div>

      </div>

      <svg class="ring" id="heroRing" viewBox="0 0 48 48" aria-hidden="true">
        <circle cx="24" cy="24" r="20" fill="none" stroke="currentColor" stroke-width="3"
                stroke-dasharray="126" style="stroke-dashoffset:var(--ld,126)"/>
      </svg>

      <div class="hero__cue" id="heroCue" hidden>
        <svg viewBox="0 0 15 22" aria-hidden="true"><rect x="1" y="1" width="13" height="20" rx="6.5"/><path d="M7.5 6v4"/></svg>
        Scroll
      </div>

    </div>
  </div>
</section>

<!-- Phones, portrait tablets and reduced motion: a composed still. -->
<section class="hero--static" aria-label="Introduction">
  <div class="hero__poster hero__poster--ending" aria-hidden="true"></div>
  <div class="hero__scrim" aria-hidden="true"></div>
  <div class="hero__body">
    <h1>Bespoke Catering Trailers Built in the North West</h1>
    <p>New builds, repairs and refits for street food traders, burger vans and coffee
       trailers. Gas Safe and electrical certificates handed over with the keys.</p>
    <div class="btn-row">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call us</a>
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
    <div class="rise">
      <p class="kicker">Two ways we help</p>
      <h2 id="doors-h">A new trailer, or the one you already own</h2>
      <p class="lede">Most people arrive one of two ways. Either they are starting out and
         need a unit building around their menu, or they have a trailer off the road and
         need it back out this week.</p>
    </div>

    <div class="doors rise" style="margin-top:2.6rem">

      <a class="door" href="/new-catering-trailers">
        <?= picture('catering-trailer-serving-hatch-open',
             'New bespoke catering trailer with the serving hatch raised',
             ['sizes' => '(max-width:900px) 100vw, 58vw', 'widths' => [480,800,1200]]) ?>
        <h3>New Catering Trailers</h3>
        <p>Built around your menu, your appliances and the pitch you are trading from.
           Single or twin axle, 2.4m up to 4.2m, stainless throughout.</p>
        <span class="door__go">See how we build them
          <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.8 2.4 14.4 8l-5.6 5.6-1.1-1.1 3.7-3.7H1.6V7.2h9.8L7.7 3.5z"/></svg>
        </span>
      </a>

      <a class="door" href="/catering-trailer-repairs">
        <?= picture('catering-trailer-side-elevation',
             'Catering trailer side elevation before repair work',
             ['sizes' => '(max-width:900px) 100vw, 40vw', 'widths' => [480,800,1200]]) ?>
        <h3>Trailer Repairs</h3>
        <p>Chassis, panels, hatches, gas pipework, electrics and accident damage.
           Any make, whoever built it.</p>
        <span class="door__go">Get it back on the road
          <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.8 2.4 14.4 8l-5.6 5.6-1.1-1.1 3.7-3.7H1.6V7.2h9.8L7.7 3.5z"/></svg>
        </span>
      </a>

    </div>
  </div>
</section>


<!-- ═══ WHY US ═════════════════════════════════════════════════════════ -->
<section class="band band--well" aria-labelledby="why-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">Why traders choose us</p>
      <h2 id="why-h">The things that actually go wrong, handled</h2>
      <p class="lede">We asked traders what went wrong last time. These are the answers,
         in the order they came up.</p>
    </div>

    <div class="why rise stagger" style="margin-top:2.6rem">
      <div class="why__item">
        <span class="why__n">01</span>
        <h3>It passes, first time</h3>
        <p>Stainless throughout, sealed joints, hot and cold water and a wash hand basin.
           Built to what your environmental health officer will actually look for.</p>
      </div>
      <div class="why__item">
        <span class="why__n">02</span>
        <h3>Certificates in your hand</h3>
        <p>Gas Safe and electrical certificates handed over on collection day, not
           chased for weeks afterwards while your pitch sits empty.</p>
      </div>
      <div class="why__item">
        <span class="why__n">03</span>
        <h3>A date you can plan around</h3>
        <p><?= e($CFG['lead_time']) ?> for most builds. We tell you the real date at the
           start and we tell you early if anything moves.</p>
      </div>
      <div class="why__item">
        <span class="why__n">04</span>
        <h3>A chassis that lasts</h3>
        <p>Hot dip galvanised and warranted <?= e($CFG['chassis_warranty']) ?> against
           corrosion. Rot is what kills resale value on a cheap trailer.</p>
      </div>
      <div class="why__item">
        <span class="why__n">05</span>
        <h3>We check your towing weight</h3>
        <p>Tell us your vehicle before we build. Getting this wrong is the single most
           common and most expensive mistake in the trade.</p>
      </div>
      <div class="why__item">
        <span class="why__n">06</span>
        <h3>Repairs get priority</h3>
        <p>A trailer off the road is lost income. Send photos the day it happens and
           we will tell you the same day what it takes to fix.</p>
      </div>
    </div>
  </div>
</section>


<!-- ═══ BUILD PROCESS + THE SPEC BUILDER ═══════════════════════════════ -->
<section class="band" id="process" aria-labelledby="proc-h">
  <div class="wrap">
    <div class="rise">
      <p class="kicker">How a build runs</p>
      <h2 id="proc-h">From your menu to your pitch</h2>
      <p class="lede">Five stages. You know where your trailer is at every one of them.</p>
    </div>

    <div class="process rise" style="margin-top:2.8rem">

      <ol class="steps">
        <li>
          <h3>We take your spec</h3>
          <p>Your menu, your appliances, your pitch and your tow vehicle. Twenty minutes
             on the phone saves weeks later.</p>
        </li>
        <li>
          <h3>Drawings and a fixed price</h3>
          <p>A layout drawing and a written quote with nothing hidden. Change it as many
             times as you like before you commit.</p>
        </li>
        <li>
          <h3>Chassis and shell</h3>
          <p>Galvanised chassis, insulated body, hatch and door openings cut and framed.
             Photographs sent as it goes.</p>
        </li>
        <li>
          <h3>Fit-out, gas and electrics</h3>
          <p>Stainless surfaces, appliances installed, gas pipework and electrics run,
             then tested and certified.</p>
        </li>
        <li>
          <h3>Handover</h3>
          <p>We walk you round it, hand you both certificates, and show you how everything
             works before you tow away.</p>
        </li>
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

        <div class="spec" id="spec">
          <div class="spec__row">
            <span>Body length</span>
            <div class="chips">
              <button class="chip" type="button" data-group="length" data-value="2.4" aria-pressed="false">2.4m</button>
              <button class="chip" type="button" data-group="length" data-value="3.0" aria-pressed="true">3.0m</button>
              <button class="chip" type="button" data-group="length" data-value="3.5" aria-pressed="false">3.5m</button>
              <button class="chip" type="button" data-group="length" data-value="4.2" aria-pressed="false">4.2m</button>
            </div>
          </div>
          <div class="spec__row">
            <span>Axle</span>
            <div class="chips">
              <button class="chip" type="button" data-group="axle" data-value="single" aria-pressed="true">Single</button>
              <button class="chip" type="button" data-group="axle" data-value="twin" aria-pressed="false">Twin</button>
            </div>
          </div>
          <div class="spec__row">
            <span>Fit-out for</span>
            <div class="chips">
              <button class="chip" type="button" data-group="use" data-value="Burgers" aria-pressed="false">Burgers</button>
              <button class="chip" type="button" data-group="use" data-value="Coffee" aria-pressed="false">Coffee</button>
              <button class="chip" type="button" data-group="use" data-value="Pizza" aria-pressed="false">Pizza</button>
              <button class="chip" type="button" data-group="use" data-value="Fried chicken" aria-pressed="false">Fried chicken</button>
              <button class="chip" type="button" data-group="use" data-value="Desserts" aria-pressed="false">Desserts</button>
            </div>
          </div>

          <p class="spec__out" id="specOut"></p>
          <a class="btn btn--accent" id="specGo" href="/request-a-quote">Send this spec</a>
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
      <h2 id="areas-h">Across the North West</h2>
      <p class="lede">We build and repair for traders throughout the North West, and we
         collect and deliver. If your town is not listed, ask anyway.</p>
    </div>

    <div class="areas rise stagger" style="margin-top:2.4rem">
      <?php foreach ($CFG['areas'] as $slug => $area): ?>
        <a class="area" href="/areas/catering-trailers-<?= e($slug) ?>">
          <b><?= e($area['name']) ?></b>
          <span><?= e($area['county']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
