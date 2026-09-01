<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/contact';
$a = $CFG['address'];

/* Enquiry types are shared with the quote form, so adding one in the admin
   area under Quote Builder adds it here too. */
$TYPES = array_values(array_filter(array_map(
    fn($r) => trim((string)($r['label'] ?? '')), builder_options('enquiry_type')
))) ?: ['New Catering Trailer', 'Repair', 'Refurbishment', 'Trailer Hire',
        'Mobile Bar', 'General Enquiry'];

$PAGE = [
  'title'       => 'Contact Catering Trailers NW | Send an Enquiry',
  'description' => 'Contact Catering Trailers NW about new catering trailers, repairs, refurbishments, trailer hire and mobile bar requirements.',
  'path'        => $SLUG,
  'nav'         => 'contact',
  'crumbs'      => ['Home' => '/', 'Contact' => $SLUG],
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'Contact' => $SLUG]),
    ['@type' => 'ContactPage', 'url' => url($SLUG), 'mainEntity' => ['@id' => url('/#business')]],
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Contact us</p>
      <h1><?= e(page_h1($SLUG, 'Contact Catering Trailers NW')) ?></h1>
      <p class="lede"><?= e(page_hero($SLUG,
         'Have a question about a new catering trailer, repair, refurbishment or hire requirement?')) ?></p>
      <p class="lede">Send us the details below and we will review your enquiry.</p>
    </div>
  </div>
</section>

<!-- ── what we can help with ─────────────────────────────────────────── -->
<section class="band" aria-labelledby="help-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">Tell us which</p>
      <h2 id="help-h">What Can We Help With?</h2></div>

    <div class="why rise stagger" style="margin-top:2.2rem">
      <div class="why__item"><span class="why__n">01</span><h3>New Catering Trailer</h3>
        <p>Tell us about your menu, preferred size, equipment and what type of business you
           are planning.
           <a href="/new-catering-trailers" style="color:var(--accent-hover)">New trailers</a>.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Catering Trailer Repair</h3>
        <p>Send photographs and describe the damage or problem.
           <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">Repairs</a>.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Refurbishment</h3>
        <p>Tell us what you would like to change or upgrade.
           <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">Refurbishments</a>.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Trailer Hire</h3>
        <p>Provide your required dates, location and intended use.
           <a href="/catering-trailer-hire" style="color:var(--accent-hover)">Trailer hire</a>.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Mobile Bar</h3>
        <p>Tell us whether you are looking to hire or purchase a mobile bar trailer.</p></div>
    </div>
  </div>
</section>

<!-- ── the form ──────────────────────────────────────────────────────── -->
<section class="band band--well" id="enquiry" aria-labelledby="form-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">Send an enquiry</p>
      <h2 id="form-h">Send Us the Details</h2></div>

    <div id="formOk" class="note note--ok rise" hidden>
      <strong>Thank you, that has reached us.</strong> We will review your enquiry and come
      back to you by email.
    </div>
    <div id="formBad" class="note note--bad rise" hidden>
      That did not send. Please check the required fields and try again, or email
      <?= e($CFG['enquiry_inbox']) ?>.
    </div>

    <form class="form rise" id="hireForm" action="/quote-handler.php" method="post"
          enctype="multipart/form-data" novalidate style="margin-top:1.6rem">

      <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label for="company_website">Leave this empty</label>
        <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
      </div>
      <input type="hidden" name="started_at" value="<?= time() ?>">
      <input type="hidden" name="enquiry_source" value="contact">

      <div class="grid2">
        <div class="field">
          <label for="name">Name <span class="req" aria-hidden="true">*</span></label>
          <input class="input" type="text" id="name" name="name" required autocomplete="name" maxlength="120">
          <p class="err" data-err="name" hidden>Please tell us your name.</p>
        </div>
        <div class="field">
          <label for="company">Company</label>
          <input class="input" type="text" id="company" name="company" autocomplete="organization" maxlength="140">
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="email">Email <span class="req" aria-hidden="true">*</span></label>
          <input class="input" type="email" id="email" name="email" required
                 autocomplete="email" inputmode="email" maxlength="180">
          <p class="err" data-err="email" hidden>That email address does not look right.</p>
        </div>
        <div class="field">
          <label for="phone">Phone</label>
          <input class="input" type="tel" id="phone" name="phone" autocomplete="tel"
                 inputmode="tel" maxlength="30">
          <p class="hint">Optional.</p>
        </div>
      </div>

      <div class="grid2">
        <div class="field">
          <label for="job_type">Enquiry type <span class="req" aria-hidden="true">*</span></label>
          <select class="select" id="job_type" name="job_type" required>
            <?php foreach ($TYPES as $t): ?>
              <option value="<?= e($t) ?>"><?= e($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label for="town">Postcode</label>
          <input class="input" type="text" id="town" name="town" maxlength="80"
                 autocomplete="postal-code" placeholder="WA1">
        </div>
      </div>

      <div class="field">
        <label for="message">Message <span class="req" aria-hidden="true">*</span></label>
        <textarea class="textarea" id="message" name="message" required maxlength="4000"
                  placeholder="Tell us what you need. For a repair, describe the damage and how it happened."></textarea>
        <p class="err" data-err="message" hidden>A few lines here helps us reply properly.</p>
      </div>

      <div class="field">
        <label for="photos">Images</label>
        <label class="drop" id="drop" for="photos">
          <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
          <strong>Tap to add images</strong><br>
          <span class="hint">Optional. Up to <?= (int)$CFG['uploads']['max_files'] ?>,
            <?= (int)($CFG['uploads']['max_bytes']/1048576) ?>MB each.</span>
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
        <button class="btn btn--accent btn--lg" type="submit" id="sendBtn">Send Enquiry</button>
      </div>
    </form>
  </div>
</section>

<!-- ── photos ────────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="photo-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Repairs and refurbishments</p>
      <h2 id="photo-h">Sending Photos?</h2>
      <p class="lede">For repairs and refurbishments, clear photographs can help us understand
         your trailer before responding.</p>
      <h3 style="margin-top:1.4rem">Try to include</h3>
      <ul class="clean-list">
        <?php foreach (['Full trailer','Damaged area','Inside of trailer',
                        'Chassis if relevant','Doors or hatches involved'] as $p): ?>
          <li><?= e($p) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ── our details, all from the admin area ──────────────────────────── -->
<section class="band band--well" aria-labelledby="det-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Our details</p>
      <h2 id="det-h">Catering Trailers NW</h2>

      <div class="grid2" style="gap:2rem;margin-top:1.6rem">
        <div>
          <h3><?= e($CFG['name']) ?></h3>
          <p class="lede" style="margin:0">
            <?php if (!empty($CFG['phone_display'])): ?>
              <a href="<?= e(tel_href()) ?>" data-track="call-contact"><?= e($CFG['phone_display']) ?></a><br>
            <?php endif; ?>
            <a href="mailto:<?= e($CFG['enquiry_inbox']) ?>"><?= e($CFG['enquiry_inbox']) ?></a>
          </p>

          <h3 style="margin-top:1.4rem">Address</h3>
          <p class="lede" style="margin:0">
            <?= e($a['street']) ?><br>
            <?= e($a['locality']) ?><br>
            <?= e($a['postcode']) ?>
          </p>
        </div>

        <div>
          <?php if (!empty($CFG['hours_display'])): ?>
            <h3>Opening hours</h3>
            <ul class="clean-list">
              <?php foreach ($CFG['hours_display'] as $days => $time): ?>
                <li><?= e(is_string($days) ? $days . ': ' . $time : (string)$time) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php $social = array_filter((array)($CFG['social'] ?? [])); ?>
          <?php if ($social): ?>
            <h3 style="margin-top:1.4rem">Find us</h3>
            <ul class="clean-list">
              <?php foreach ($social as $network => $href): ?>
                <li><a href="<?= e((string)$href) ?>" target="_blank" rel="noopener"><?= e(ucfirst((string)$network)) ?></a></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="/assets/js/quote-form.js?v=1" defer></script>

<?php require __DIR__ . '/inc/footer.php'; ?>
