<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

// Values arriving from the homepage spec builder, so the form opens prefilled.
$preLen  = isset($_GET['len'])  ? preg_replace('/[^0-9.]/', '', (string)$_GET['len']) : '';
$preAxle = isset($_GET['axle']) && in_array($_GET['axle'], ['single','twin'], true) ? $_GET['axle'] : '';
$preUse  = isset($_GET['use'])  ? array_filter(explode('|', (string)$_GET['use'])) : [];

$APPLIANCES = ['Griddle','Fryer','Char grill','Six burner range','Pizza oven','Oven',
               'Bain marie','Hot cupboard','Espresso machine','Water boiler','Fridge',
               'Freezer','Extraction canopy','Wash hand basin','Twin sink','Water heater'];

$SLUG = '/request-a-quote';

/* Enquiry types are shared with the contact form and managed under Quote
   Builder, so a new one appears on both without a code change. */
$ENQ_TYPES = array_values(array_filter(array_map(
    fn($r) => trim((string)($r['label'] ?? '')), builder_options('enquiry_type')
))) ?: ['New Catering Trailer', 'Repair', 'Refurbishment', 'Trailer Hire',
        'Mobile Bar', 'Other'];

$USES = array_values(array_filter(array_map(
    fn($r) => trim((string)($r['label'] ?? '')), builder_options('use')
))) ?: ['Burgers','Coffee','Pizza','Fried Chicken','Desserts','Breakfast',
        'Mobile Bar','General Catering','Other'];

$PAGE = [
  'title'       => 'Request a Catering Trailer Quote | Catering Trailers NW',
  'description' => 'Request a quote for a new catering trailer, repair, refurbishment, mobile bar or trailer hire from Catering Trailers NW.',
  'path'        => $SLUG,
  'nav'         => '',
  'crumbs'      => ['Home' => '/', 'Request a Quote' => $SLUG],
  'schema'      => [schema_breadcrumbs(['Home' => '/', 'Request a Quote' => $SLUG])],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Request a quote</p>
      <h1><?= e(page_h1($SLUG, 'Request a Catering Trailer Quote')) ?></h1>
      <p class="lede">Tell us as much as possible about what you need.</p>
      <p class="lede">For new builds, information about your menu and equipment is extremely
         useful. For repairs or refurbishments, upload photographs of the existing trailer
         where possible.</p>
    </div>
  </div>
</section>

<section class="band band--tight" style="padding-top:0">
  <div class="wrap" style="max-width:860px">

    <div id="formOk" class="note note--ok rise" hidden>
      <strong>Got it.</strong> We will come back to you within one working day, usually
      sooner. If it is urgent, call <?= e($CFG['phone_display']) ?> instead.
    </div>
    <div id="formBad" class="note note--bad rise" hidden>
      That did not send. Call us on <?= e($CFG['phone_display']) ?> and we will take the
      details over the phone.
    </div>

    <form class="form rise" id="quoteForm" action="/quote-handler.php" method="post"
          enctype="multipart/form-data" novalidate>

      <!-- honeypot: a real person never fills this in -->
      <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label for="company_website">Leave this empty</label>
        <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
      </div>
      <input type="hidden" name="started_at" value="<?= time() ?>">

      <ol class="stepper" id="stepper" aria-label="Progress">
        <li class="stepper__i now">1. You</li>
        <li class="stepper__i">2. Trailer</li>
        <li class="stepper__i">3. Fit-out</li>
        <li class="stepper__i">4. Budget</li>
        <li class="stepper__i">5. Details</li>
      </ol>

      <!-- ── step 1 ────────────────────────────────────────────────── -->
      <fieldset class="step now" data-step="1">
        <legend class="sr">About you</legend>
        <div class="grid2">
          <div class="field">
            <label for="name">Your name <span class="req" aria-hidden="true">*</span></label>
            <input class="input" type="text" id="name" name="name" required
                   autocomplete="name" maxlength="120">
            <p class="err" data-err="name" hidden>Please tell us your name.</p>
          </div>
          <div class="field">
            <label for="phone">Phone number <span class="req" aria-hidden="true">*</span></label>
            <input class="input" type="tel" id="phone" name="phone" required
                   autocomplete="tel" inputmode="tel" maxlength="30">
            <p class="err" data-err="phone" hidden>We need a number we can call you back on.</p>
          </div>
        </div>
        <div class="grid2">
          <div class="field">
            <label for="email">Email address <span class="req" aria-hidden="true">*</span></label>
            <input class="input" type="email" id="email" name="email" required
                   autocomplete="email" inputmode="email" maxlength="180">
            <p class="err" data-err="email" hidden>That email address does not look right.</p>
          </div>
          <div class="field">
            <label for="company">Business name</label>
            <input class="input" type="text" id="company" name="company"
                   autocomplete="organization" maxlength="140">
          </div>
        </div>
        <div class="field">
          <label for="town">Postcode</label>
          <input class="input" type="text" id="town" name="town" autocomplete="postal-code" maxlength="80"
                 placeholder="WA1">
          <p class="hint">Helps us work out delivery or collection.</p>
        </div>
        <div class="field">
          <label for="job_type">What is your enquiry about? <span class="req" aria-hidden="true">*</span></label>
          <select class="select" id="job_type" name="job_type" required>
            <?php foreach ($ENQ_TYPES as $t): ?>
              <option value="<?= e($t) ?>"><?= e($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </fieldset>

      <!-- ── step 2 ────────────────────────────────────────────────── -->
      <fieldset class="step" data-step="2">
        <legend class="sr">The trailer</legend>
        <div class="grid2">
          <div class="field">
            <label for="size">Trailer size</label>
            <select class="select" id="size" name="size">
              <option value="">Not sure yet</option>
              <?php foreach (['2.4m','3.0m','3.5m','4.2m','Larger than 4.2m'] as $s): ?>
                <option value="<?= e($s) ?>"<?= ($preLen && $s === $preLen . 'm') ? ' selected' : '' ?>><?= e($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label for="axle">Axle</label>
            <select class="select" id="axle" name="axle">
              <option value="">Not sure yet</option>
              <option value="Single axle"<?= $preAxle === 'single' ? ' selected' : '' ?>>Single axle</option>
              <option value="Twin axle"<?= $preAxle === 'twin' ? ' selected' : '' ?>>Twin axle</option>
            </select>
          </div>
        </div>
        <div class="field">
          <label>What will the trailer be used for?</label>
          <div class="opts">
            <?php foreach ($USES as $u): ?>
              <label class="opt">
                <input type="checkbox" name="uses[]" value="<?= e($u) ?>"
                       <?= in_array($u, $preUse, true) ? ' checked' : '' ?>>
                <span><?= e($u) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <p class="hint">This drives the whole layout, so it is the most useful thing you can tell us.</p>
        </div>
        <div class="field">
          <label for="staff">How many staff will normally work inside?</label>
          <input class="input" type="text" id="staff" name="staff" maxlength="40"
                 inputmode="numeric" placeholder="Two, sometimes three at events">
        </div>
        <div class="field">
          <label for="tow_vehicle">What will be towing it?</label>
          <input class="input" type="text" id="tow_vehicle" name="tow_vehicle" maxlength="120"
                 placeholder="Make and model, for example Ford Ranger">
          <p class="hint">Towing weight catches more people out than anything else. We will check it for you.</p>
        </div>
      </fieldset>

      <!-- ── step 3 ────────────────────────────────────────────────── -->
      <fieldset class="step" data-step="3">
        <legend class="sr">Fit-out</legend>
        <div class="field">
          <label>Appliances you need</label>
          <div class="opts">
            <?php foreach ($APPLIANCES as $ap): ?>
              <label class="opt"><input type="checkbox" name="appliances[]" value="<?= e($ap) ?>"><span><?= e($ap) ?></span></label>
            <?php endforeach; ?>
          </div>
          <p class="hint">Tick anything you know you want. We will advise on the rest.</p>
        </div>
        <div class="field">
          <label for="equipment">Equipment required</label>
          <textarea class="textarea" id="equipment" name="equipment" rows="3" maxlength="1200"
                    placeholder="Anything not covered above, including equipment you already own."></textarea>
          <p class="hint">If you already own appliances, send the make, model and dimensions.</p>
        </div>

        <?php
        $ynu = ['Yes', 'No', 'Not sure'];
        $services = [
          'gas'      => 'Gas required?',
          'electric' => 'Electric required?',
          'water'    => 'Water system required?',
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
          <label for="hatch">Serving hatch requirements</label>
          <input class="input" type="text" id="hatch" name="hatch" maxlength="200"
                 placeholder="One large hatch on the nearside, counter underneath...">
        </div>
        <div class="field">
          <label for="doors">Entrance door requirements</label>
          <input class="input" type="text" id="doors" name="doors" maxlength="200"
                 placeholder="Rear door, or side door away from the serving side...">
        </div>
      </fieldset>

      <!-- ── step 4 ────────────────────────────────────────────────── -->
      <fieldset class="step" data-step="4">
        <legend class="sr">Budget and timing</legend>
        <div class="field">
          <label for="budget">Budget</label>
          <select class="select" id="budget" name="budget">
            <option value="">Rather not say yet</option>
            <option>Under £10,000</option>
            <option>£10,000 to £20,000</option>
            <option>£20,000 to £30,000</option>
            <option>£30,000 to £45,000</option>
            <option>Over £45,000</option>
          </select>
          <p class="hint">An honest range saves us both time. We will tell you straight
             if it will not stretch to what you want.</p>
        </div>
        <div class="field">
          <label for="required_date">When do you need it?</label>
          <input class="input" type="date" id="required_date" name="required_date">
          <p class="hint">Current lead time is <?= e($CFG['lead_time']) ?>. April to July books up first.</p>
        </div>
        <div class="field">
          <label>How did you hear about us?</label>
          <div class="opts">
            <?php foreach (['Google','Facebook','Instagram','Recommendation','Saw one of your trailers','Other'] as $src): ?>
              <label class="opt"><input type="radio" name="source" value="<?= e($src) ?>"><span><?= e($src) ?></span></label>
            <?php endforeach; ?>
          </div>
        </div>
      </fieldset>

      <!-- ── step 5 ────────────────────────────────────────────────── -->
      <fieldset class="step" data-step="5">
        <legend class="sr">Details and photos</legend>
        <div class="field">
          <label for="message">Tell us about the job <span class="req" aria-hidden="true">*</span></label>
          <textarea class="textarea" id="message" name="message" required maxlength="4000"
                    placeholder="Describe what you need. For a repair, tell us what is wrong and how long it has been off the road."></textarea>
          <p class="err" data-err="message" hidden>A few lines here makes the quote much more accurate.</p>
        </div>

        <div class="field">
          <label for="photos">Photos</label>
          <label class="drop" id="drop" for="photos">
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
            <strong>Tap to add photos</strong><br>
            <span class="hint">Photos help us quote properly. Up to
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
      </fieldset>

      <div class="form__nav">
        <button class="btn btn--ghost" type="button" id="prevBtn" hidden>Back</button>
        <button class="btn btn--accent" type="button" id="nextBtn">Next step</button>
        <button class="btn btn--accent btn--lg" type="submit" id="sendBtn" hidden>Send my enquiry</button>
      </div>
    </form>

  </div>
</section>

<script src="/assets/js/quote-form.js?v=1" defer></script>

<?php require __DIR__ . '/inc/footer.php'; ?>
