<?php
/**
 * Catering trailer and mobile bar hire.
 *
 * Hire is a short-term answer, so the page satisfies hire intent properly and
 * then points anyone who is going to trade regularly at a new build, which is
 * the business the workshop actually wants. No telephone calls to action: the
 * one thing this page asks for is a written enquiry.
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/catering-trailer-hire';

/* Turned off in the admin area? Behave as though it was never published. */
if (db_ready() && (string)setting('hire.enabled', '1') === '0') {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$FAQ        = page_faqs($SLUG, hire_faq_defaults());
$HIRE_TYPES = hire_types();
$AREAS      = hire_areas();

$PAGE = [
  'title'       => 'Catering Trailer Hire & Mobile Bar Hire | Catering Trailers NW',
  'description' => 'Catering trailer, food trailer and mobile bar hire across the North West. Ideal for events, temporary use and hospitality businesses. Request a tailored quote online.',
  'path'        => $SLUG,
  'nav'         => 'hire',
  'crumbs'      => ['Home' => '/', 'Trailer Hire' => $SLUG],

  // this page collects a written enquiry, so nothing may divert to a phone call
  'no_phone'     => true,
  'hide_cta'     => true,          // it closes with its own tailored ask
  'cta_href'     => '#hire-form',
  'sticky_label' => 'Request a Quote',

  'schema'      => [
    schema_service(
      'Catering trailer and mobile bar hire',
      'Catering trailer, food trailer and mobile bar hire across the North West for events, temporary business requirements and seasonal trading.',
      $SLUG),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Trailer Hire' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<!-- ── hero ──────────────────────────────────────────────────────────── -->
<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Trailer hire</p>
      <h1><?= e(copytext('hire_h1', 'Catering Trailer Hire & Mobile Bar Hire')) ?></h1>
      <p class="lede"><?= e(copytext('hire_intro_1',
        'Need a catering trailer, food trailer or mobile bar for an event, temporary business requirement or busy trading period?')) ?></p>
      <p class="lede"><?= e(copytext('hire_intro_2',
        'Catering Trailers NW offers flexible trailer hire across the North West for events, hospitality businesses, street-food operators and commercial customers.')) ?></p>
      <p class="lede"><?= e(copytext('hire_intro_3',
        'Tell us what you need, where you need it and how long you need it for and we can provide a tailored quotation.')) ?></p>

      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="#hire-form"><?= e(copytext('hire_cta_primary', 'Request a Quote')) ?></a>
      </div>
      <p class="hint" style="margin-top:1rem">
        Looking for a permanent trailer?
        <a href="/new-catering-trailers" style="color:var(--accent-hover)">View New Catering Trailers</a>
      </p>
    </div>
  </div>
</section>

<!-- ── 2. available to hire ──────────────────────────────────────────── -->
<section class="band" aria-labelledby="avail-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">What we hire</p>
      <h2 id="avail-h">Catering Trailers Available to Hire</h2>
      <p class="lede">Our catering trailer hire service is designed for businesses and
         organisations that need a professional mobile catering setup without requiring a
         permanent trailer immediately.</p>
      <p class="lede">Whether you need additional serving capacity for an event, temporary
         catering facilities while your premises are being refurbished, or a trailer for
         seasonal trading, we can help identify a suitable option based on your
         requirements.</p>
    </div>

    <h3 style="margin-top:2.4rem">Hire may be suitable for</h3>
    <ul class="taglist rise stagger">
      <?php foreach (['Street-food businesses','Festivals','Markets','Outdoor events',
                      'Weddings','Private functions','Corporate events',
                      'Pub and restaurant overflow','Seasonal trading',
                      'Temporary commercial kitchens','Product launches',
                      'Promotional events','Emergency kitchen replacement',
                      'Short-term hospitality projects'] as $u): ?>
        <li><?= e($u) ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="btn-row" style="margin-top:2rem">
      <a class="btn btn--accent" href="#hire-form">Request a Hire Quote</a>
    </div>
  </div>
</section>

<!-- ── 3. food trailer hire ──────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="food-h">
  <div class="wrap">
    <div class="grid2" style="gap:2.6rem;align-items:start">
      <div class="rise">
        <p class="kicker">Food trailers</p>
        <h2 id="food-h">Food Trailer Hire</h2>
        <p class="lede">Need a temporary food trailer for an event or commercial catering
           operation?</p>
        <p class="lede">Our catering trailers can be suitable for a wide range of food
           concepts depending on the trailer specification and equipment required.</p>

        <h3 style="margin-top:1.8rem">Potential uses include</h3>
        <ul class="taglist">
          <?php foreach (['Burgers','Breakfast','Hot sandwiches','Coffee','Pizza',
                          'Fried food','Desserts','Event catering','Street food',
                          'Festival catering','General mobile food service'] as $f): ?>
            <li><?= e($f) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="rise">
        <?= picture('catering-trailer-interior-swirl-stainless',
              'Stainless steel interior of a catering trailer with worktops, extraction and serving hatch',
              ['class' => 'elev', 'ratio' => '4/3']) ?>
      </div>
    </div>

    <div class="rise" style="margin-top:2.4rem">
      <h3>Before quoting, we need to understand how you intend to use it</h3>
      <ul class="clean-list clean-list--cols">
        <?php foreach (['Menu or food concept','Required dates','Location',
                        'Number of staff','Expected customer numbers','Gas requirements',
                        'Electrical requirements','Water requirements','Equipment needed',
                        'Delivery requirements'] as $n): ?>
          <li><?= e($n) ?></li>
        <?php endforeach; ?>
      </ul>

      <div class="callout" style="margin-top:1.6rem;max-width:70ch">
        <p style="margin:0">Available equipment and trailer specifications vary, so please
           tell us exactly what you need when requesting a quote.</p>
      </div>
    </div>

    <div class="btn-row" style="margin-top:2.2rem">
      <a class="btn btn--accent" href="#hire-form">Request a Catering Trailer Quote</a>
    </div>
  </div>
</section>

<!-- ── 4. mobile bar hire ────────────────────────────────────────────── -->
<section class="band" aria-labelledby="bar-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Mobile bars</p>
      <h2 id="bar-h">Mobile Bar Hire</h2>
      <p class="lede">A mobile bar trailer can provide a professional serving area for
         events, weddings, festivals, private functions and temporary hospitality
         setups.</p>
      <p class="lede">Depending on the trailer available and your requirements, mobile bar
         configurations may include:</p>
    </div>

    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['Refrigerated storage','Bottle and can storage','Draught beer equipment',
                      'Wine and champagne service areas','Cocktail preparation areas',
                      'Stainless steel counters','Sink facilities','Water systems',
                      'Electrical supply','Lighting','Serving hatches','Storage',
                      'Work surfaces'] as $b): ?>
        <li><?= e($b) ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="callout rise" style="margin-top:1.8rem;max-width:70ch">
      <p style="margin:0">Features depend on the specific trailer and hire requirements, so
         tell us what your event needs and we will tell you honestly what we can cover.</p>
    </div>

    <p class="lede rise" style="margin-top:1.6rem;max-width:70ch">
      If you are planning to operate a mobile bar regularly rather than for a one-off event,
      we can also design and build a bespoke mobile bar trailer specifically for your
      business. See <a href="/new-catering-trailers" style="color:var(--accent-hover)">New
      Catering Trailers</a>.
    </p>

    <div class="btn-row" style="margin-top:1.8rem">
      <a class="btn btn--accent" href="#hire-form">Request a Mobile Bar Quote</a>
    </div>
  </div>
</section>

<!-- ── 5. hire periods ───────────────────────────────────────────────── -->
<section class="band band--panel" aria-labelledby="term-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Hire periods</p>
      <h2 id="term-h">Short-Term &amp; Longer-Term Trailer Hire</h2>
      <p class="lede">Enquiries are welcome for any of the following, and each one is quoted
         on its own merits rather than from a price list.</p>
    </div>

    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['One-day hire','Weekend hire','Weekly hire','Event hire',
                      'Seasonal hire','Temporary business use','Longer-term rental'] as $t): ?>
        <li><?= e($t) ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="callout rise" style="margin-top:1.8rem;max-width:70ch">
      <p style="margin:0"><?= e(copytext('hire_pricing_note',
        'Hire costs depend on the trailer required, hire duration, specification, equipment, delivery location and any additional requirements. Send us your requirements and we will provide a tailored quotation.')) ?></p>
    </div>

    <div class="btn-row" style="margin-top:1.8rem">
      <a class="btn btn--accent" href="#hire-form">Get a Hire Quote</a>
    </div>
  </div>
</section>

<!-- ── 6. cover while yours is repaired ──────────────────────────────── -->
<section class="band" aria-labelledby="repair-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Off the road</p>
      <h2 id="repair-h">Need a Trailer While Yours Is Being Repaired?</h2>
      <p class="lede">If your existing catering trailer is off the road for repairs or
         refurbishment, temporary trailer hire may help keep your business operating while
         the work is completed.</p>
      <p class="lede">Catering Trailers NW also provides
         <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">catering trailer repairs</a>,
         <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">refurbishments, modifications and upgrades</a>.
         Mention your existing trailer in your enquiry and we can look at both together.</p>
      <div class="btn-row" style="margin-top:1.6rem">
        <a class="btn btn--ghost" href="/catering-trailer-repairs">View Trailer Repairs</a>
      </div>
    </div>
  </div>
</section>

<!-- ── 7. the buy case ───────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="own-h">
  <div class="wrap">
    <div class="grid2" style="gap:2.6rem;align-items:start">
      <div class="rise">
        <p class="kicker">Trading regularly?</p>
        <h2 id="own-h">Need a Trailer for the Long Term?</h2>
        <p class="lede">Hiring can be ideal for events, temporary requirements or short-term
           trading. If you plan to use a catering trailer regularly, commissioning your own
           bespoke trailer may make more sense for your business.</p>
        <p class="lede">At Catering Trailers NW we design and build new catering trailers
           around your menu, equipment and preferred working layout.</p>
        <p class="lede">Whether you are starting a new street-food business, replacing an
           existing trailer or expanding your operation, we can build a trailer around how
           you actually intend to work.</p>

        <div class="btn-row" style="margin-top:1.8rem">
          <a class="btn btn--accent btn--lg" href="/new-catering-trailers">Explore New Catering Trailers</a>
          <a class="btn btn--ghost btn--lg" href="/request-a-quote">Request a New Trailer Quote</a>
        </div>
      </div>

      <div class="rise">
        <?= picture('catering-trailer-new-build-workshop',
              'A bespoke catering trailer being built in the Catering Trailers NW workshop',
              ['class' => 'elev', 'ratio' => '4/3']) ?>
      </div>
    </div>

    <div class="rise" style="margin-top:2.4rem">
      <h3>A bespoke build can be designed around</h3>
      <ul class="taglist stagger" style="margin-top:1rem">
        <?php foreach (['Trailer size','Single or twin axle','Serving hatch positions',
                        'Entrance doors','Stainless steel worktops','Cupboards and storage',
                        'Sinks','Fresh and waste water systems','Gas appliances','Fryers',
                        'Griddles','Bain maries','Refrigeration','Freezers','Extraction',
                        'Electrical sockets','Lighting','Generator or site-power connections',
                        'Equipment installation','Branding requirements'] as $s): ?>
          <li><?= e($s) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ── 8. areas ──────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="area-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Where we cover</p>
      <h2 id="area-h">Catering Trailer Hire Across the North West</h2>
      <p class="lede">Based in the North West, Catering Trailers NW handles catering trailer
         and mobile bar hire enquiries from Warrington, Manchester, Liverpool, Cheshire and
         the surrounding areas.</p>

      <ul class="taglist" style="margin-top:1.4rem">
        <?php foreach ($AREAS as $a): ?><li><?= e($a) ?></li><?php endforeach; ?>
      </ul>

      <p class="lede" style="margin-top:1.4rem">Depending on the trailer, hire period and
         delivery requirements, we may also be able to accommodate enquiries from further
         afield. Tell us where you are and we will tell you honestly whether it works.</p>
    </div>
  </div>
</section>

<!-- ── 9. what we need ───────────────────────────────────────────────── -->
<section class="band band--panel" aria-labelledby="info-h">
  <div class="wrap">
    <div class="process rise">
      <div>
        <p class="kicker">How it works</p>
        <h2 id="info-h">What Information Do We Need?</h2>
        <p class="lede">Four steps. The first one takes a couple of minutes and everything
           after it is our side of the job.</p>
      </div>
      <ol class="steps">
        <li><h3>Tell us what you need</h3>
          <p>Trailer type, menu, equipment and intended use.</p></li>
        <li><h3>Tell us where and when</h3>
          <p>Provide your location and required hire dates.</p></li>
        <li><h3>We review the requirements</h3>
          <p>We check suitable trailer options and any equipment or delivery
             requirements.</p></li>
        <li><h3>Receive your quote</h3>
          <p>We send a tailored quotation by email.</p></li>
      </ol>
    </div>
    <div class="btn-row" style="margin-top:2rem">
      <a class="btn btn--accent" href="#hire-form">Request a Quote</a>
    </div>
  </div>
</section>

<!-- ── 10. the form ──────────────────────────────────────────────────── -->
<section class="band" id="hire-form" aria-labelledby="form-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Hire enquiry</p>
      <h2 id="form-h">Hire Enquiry Form</h2>
      <p class="lede">The more you can tell us here, the more accurate the quotation. Nothing
         is booked or reserved by sending this — we will come back to you with what is
         actually available.</p>
    </div>

    <div id="formOk" class="note note--ok rise" hidden>
      <strong>Thank you, that has reached us.</strong> We will review your requirements and
      come back to you by email with a tailored quotation. Sending this does not reserve a
      trailer or confirm availability.
    </div>
    <div id="formBad" class="note note--bad rise" hidden>
      That did not send. Please check the required fields and try again, or email us at
      <?= e($CFG['enquiry_inbox']) ?>.
    </div>

    <form class="form rise" id="hireForm" action="/quote-handler.php" method="post"
          enctype="multipart/form-data" novalidate>

      <!-- honeypot: a real person never fills this in -->
      <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label for="company_website">Leave this empty</label>
        <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
      </div>
      <input type="hidden" name="started_at" value="<?= time() ?>">
      <input type="hidden" name="enquiry_source" value="hire">

      <div class="grid2">
        <div class="field">
          <label for="name">Your name <span class="req" aria-hidden="true">*</span></label>
          <input class="input" type="text" id="name" name="name" required
                 autocomplete="name" maxlength="120">
          <p class="err" data-err="name" hidden>Please tell us your name.</p>
        </div>
        <div class="field">
          <label for="company">Company name</label>
          <input class="input" type="text" id="company" name="company"
                 autocomplete="organization" maxlength="140">
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="email">Email address <span class="req" aria-hidden="true">*</span></label>
          <input class="input" type="email" id="email" name="email" required
                 autocomplete="email" inputmode="email" maxlength="180">
          <p class="err" data-err="email" hidden>That email address does not look right.</p>
          <p class="hint">Your quotation is sent here.</p>
        </div>
        <div class="field">
          <label for="phone">Phone number</label>
          <input class="input" type="tel" id="phone" name="phone"
                 autocomplete="tel" inputmode="tel" maxlength="30">
          <p class="hint">Optional. Useful only if something needs clarifying quickly.</p>
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="hire_type">What do you need? <span class="req" aria-hidden="true">*</span></label>
          <select class="select" id="hire_type" name="job_type" required>
            <?php foreach ($HIRE_TYPES as $t): ?>
              <option value="<?= e($t) ?>"><?= e($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="event_type">Event or business type</label>
          <input class="input" type="text" id="event_type" name="event_type" maxlength="140"
                 placeholder="Wedding, festival, street-food pitch, kitchen refit...">
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="required_date">Required from</label>
          <input class="input" type="date" id="required_date" name="required_date">
        </div>
        <div class="field">
          <label for="until_date">Required until</label>
          <input class="input" type="date" id="until_date" name="until_date">
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="town">Location or postcode</label>
          <input class="input" type="text" id="town" name="town" maxlength="80"
                 autocomplete="postal-code" placeholder="Warrington, WA1">
          <p class="hint">Needed to work out whether delivery is possible.</p>
        </div>
        <div class="field">
          <label for="customers">Expected number of customers</label>
          <input class="input" type="text" id="customers" name="customers" maxlength="60"
                 inputmode="numeric" placeholder="Roughly 300 over the day">
        </div>
      </div>

      <div class="field">
        <label for="message">Menu or intended use <span class="req" aria-hidden="true">*</span></label>
        <textarea class="textarea" id="message" name="message" required maxlength="4000"
                  placeholder="What you plan to serve, how you plan to work, and anything that matters about the site."></textarea>
        <p class="err" data-err="message" hidden>A few lines here makes the quote much more accurate.</p>
      </div>

      <div class="field">
        <label for="equipment">Equipment required</label>
        <textarea class="textarea" id="equipment" name="equipment" maxlength="1200" rows="3"
                  placeholder="Fryer, griddle, hot cupboard, fridge, coffee machine..."></textarea>
        <p class="hint">Specifications vary by trailer, so list what you actually need rather
           than assuming it is fitted.</p>
      </div>

      <?php
      $ynu = ['Yes', 'No', 'Unsure'];
      $services = [
        'gas'      => 'Gas required?',
        'electric' => 'Electrical supply required?',
        'water'    => 'Water required?',
        'delivery' => 'Delivery required?',
      ];
      ?>
      <?php foreach ($services as $key => $label): ?>
        <div class="field">
          <label><?= e($label) ?></label>
          <div class="opts">
            <?php foreach ($ynu as $i => $v): ?>
              <label class="opt">
                <input type="radio" name="<?= e($key) ?>" value="<?= e($v) ?>"<?= $i === 2 ? ' checked' : '' ?>>
                <span><?= e($v) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="field">
        <label>Interested in buying a new bespoke trailer as well?</label>
        <div class="opts">
          <label class="opt"><input type="radio" name="buy_interest" value="Yes"><span>Yes</span></label>
          <label class="opt"><input type="radio" name="buy_interest" value="No" checked><span>No</span></label>
          <label class="opt"><input type="radio" name="buy_interest" value="Possibly, send me information"><span>Possibly, send me information</span></label>
        </div>
      </div>

      <div class="field">
        <label for="extra_notes">Additional information</label>
        <textarea class="textarea" id="extra_notes" name="extra_notes" maxlength="2000" rows="3"
                  placeholder="Access, pitch size, power on site, anything else worth knowing."></textarea>
      </div>

      <div class="field">
        <label for="photos">Photos or documents</label>
        <label class="drop" id="drop" for="photos">
          <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
          <strong>Tap to add images</strong><br>
          <span class="hint">Optional. Site photos or a plan help. Up to
            <?= (int)$CFG['uploads']['max_files'] ?>, <?= (int)($CFG['uploads']['max_bytes']/1048576) ?>MB each.</span>
        </label>
        <div class="thumbs" id="thumbs"></div>
        <p class="err" data-err="photos" hidden></p>
      </div>

      <div class="field">
        <label class="opt" style="display:block">
          <input type="checkbox" name="consent" value="yes" required style="position:static;width:auto;height:auto;opacity:1;margin-right:.5rem">
          <span style="display:inline;border:0;padding:0;background:none;color:var(--text-secondary)">
            I am happy for Catering Trailers NW to contact me about this enquiry.
            <span class="req">*</span>
          </span>
        </label>
        <p class="err" data-err="consent" hidden>We need your permission to reply.</p>
      </div>

      <div class="form__nav">
        <button class="btn btn--accent btn--lg" type="submit" id="sendBtn">Request a Quote</button>
      </div>
    </form>
  </div>
</section>

<!-- ── 11. FAQs ──────────────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="hfaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Hire questions</p>
      <h2 id="hfaq-h">Frequently Asked Questions</h2>
    </div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── final call to action ──────────────────────────────────────────── -->
<section class="cta band" aria-labelledby="hcta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Tell us what you need</p>
    <h2 id="hcta-h">Need a Catering Trailer or Mobile Bar?</h2>
    <p>Tell us what you need, where you need it and how long you need it for.</p>
    <p>Whether you need a trailer for an event, temporary business use or are considering
       your own bespoke catering trailer, send us your requirements and we will come back to
       you with a tailored quotation.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="#hire-form">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">View New Catering Trailers</a>
    </div>
    <p class="hint" style="margin-top:1.2rem">
      Prefer to talk it through? <a href="/contact" style="color:var(--accent-hover)">Contact us</a>.
    </p>
  </div>
</section>

<script src="/assets/js/quote-form.js?v=1" defer></script>

<?php require __DIR__ . '/inc/footer.php'; ?>
