<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/catering-trailer-repairs';

$FAQ = [
  'Do you repair catering trailers?' =>
    'Yes. Repair work can include body damage, serving hatches, doors, interiors, floors, chassis-related work and other trailer repairs.',
  'Can you repair accident damage?' =>
    'Yes, depending on the extent of the damage. Send photographs with your enquiry so we can understand the condition of the trailer.',
  'Can you repair a catering trailer chassis?' =>
    'Chassis-related work depends on the type and severity of the damage. The trailer needs to be properly assessed before the appropriate repair can be determined.',
  'Can you repair serving hatches?' =>
    'Yes. Problems may involve hatch panels, frames, hinges, supports, seals or surrounding bodywork.',
];

$PAGE = [
  'title'       => 'Catering Trailer Repairs | North West',
  'description' => 'Professional catering trailer repairs across the North West, including body damage, chassis repairs, doors, serving hatches, interiors and upgrades.',
  'path'        => $SLUG,
  'nav'         => 'repairs',
  'crumbs'      => ['Home' => '/', 'Trailer Repairs' => $SLUG],
  'schema'      => [
    schema_service('Catering trailer repairs',
      'Repairs to catering and food trailers including body damage, serving hatches, doors, interiors, chassis-related work and accident damage.',
      $SLUG),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'Trailer Repairs' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Trailer repairs</p>
      <h1><?= e(page_h1($SLUG, 'Catering Trailer Repairs')) ?></h1>
      <p class="lede"><?= e(page_hero($SLUG, 'Damaged, worn or ageing catering trailer?')) ?></p>
      <p class="lede">We provide repairs for catering and food trailers including body damage,
         hatches, doors, interiors, chassis-related work and other trailer repairs.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Repair Quote</a>
        <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href('Hi, my catering trailer needs a repair. I am sending photos.')) ?>"
           target="_blank" rel="noopener">Send photos on WhatsApp</a>
      </div>
    </div>
  </div>
</section>

<!-- ── what happens to trailers ──────────────────────────────────────── -->
<section class="band" aria-labelledby="pro-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Why trailers need work</p>
      <h2 id="pro-h">Professional Catering Trailer Repairs</h2>
      <p class="lede">Commercial catering trailers are exposed to regular towing, busy service
         periods, heat, moisture, heavy equipment and everyday wear.</p>
      <p class="lede">Over time this can result in damaged panels, worn fittings, damaged
         doors, hatch problems, flooring issues and other faults.</p>
      <p class="lede">We can inspect the trailer, discuss what has happened and prepare a
         repair specification based on the condition of the unit.</p>
    </div>
  </div>
</section>

<!-- ── what we can help with ─────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="help-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Scope of work</p>
      <h2 id="help-h">Catering Trailer Repairs We Can Help With</h2>
      <p class="lede">Repairs can include:</p>
    </div>

    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['External body damage','Internal panel damage','Serving hatch repairs',
                      'Serving hatch replacement','Entrance door repairs','Door replacement',
                      'Damaged trims','Damaged corners','Flooring repairs',
                      'Interior wall repairs','Worktop repairs','Storage repairs',
                      'Lighting faults','Electrical upgrades','Water system repairs',
                      'Gas pipework replacement where required','Chassis-related repairs',
                      'Accident damage','General refurbishment work'] as $r): ?>
        <li><?= e($r) ?></li>
      <?php endforeach; ?>
    </ul>

    <div class="callout rise" style="margin-top:1.8rem;max-width:70ch">
      <p style="margin:0">The work required will depend on the condition and construction of
         your trailer.</p>
    </div>
  </div>
</section>

<!-- ── accident damage ───────────────────────────────────────────────── -->
<section class="band" aria-labelledby="acc-h">
  <div class="wrap">
    <div class="grid2" style="gap:2.6rem;align-items:start">
      <div class="rise">
        <p class="kicker">After a knock</p>
        <h2 id="acc-h">Accident-Damaged Catering Trailers</h2>
        <p class="lede">If your catering trailer has been involved in an accident, damage can
           extend beyond what is immediately visible.</p>
        <h3 style="margin-top:1.6rem">Possible areas requiring inspection</h3>
        <ul class="clean-list clean-list--cols">
          <?php foreach (['Chassis','Body structure','Wall panels','Flooring','Gas pipework',
                          'Electrical wiring','Serving hatches','Doors','Interior fittings',
                          'Equipment'] as $a): ?>
            <li><?= e($a) ?></li>
          <?php endforeach; ?>
        </ul>
        <p class="lede" style="margin-top:1.4rem">Where appropriate, we can assess the damage
           and prepare a detailed repair quotation. Photos are extremely useful for initial
           enquiries.</p>
      </div>
      <div class="rise">
        <?= picture('catering-trailer-rear-door',
            'Rear of a catering trailer showing the entrance door, frame and body panels',
            ['class'=>'elev','ratio'=>'4/3']) ?>
      </div>
    </div>
  </div>
</section>

<!-- ── chassis ───────────────────────────────────────────────────────── -->
<section class="band band--panel" aria-labelledby="chassis-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Underneath</p>
      <h2 id="chassis-h">Catering Trailer Chassis Repairs</h2>
      <p class="lede">The chassis is one of the most important parts of any trailer.</p>
      <p class="lede">Damage, corrosion or distortion should be properly assessed before
         cosmetic repairs are considered.</p>
      <p class="lede">Depending on the extent of the problem, work may involve repairing
         individual areas or more extensive chassis work. Any repair proposal will depend on
         the trailer and the damage found.</p>
    </div>
  </div>
</section>

<!-- ── hatches ───────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="hatch-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Serving hatches</p>
      <h2 id="hatch-h">Serving Hatch Repairs</h2>
      <p class="lede">Serving hatches are regularly opened and closed and can suffer damage to
         frames, hinges, supports, panels and seals.</p>
      <h3 style="margin-top:1.6rem">Problems may include</h3>
      <ul class="clean-list clean-list--cols">
        <?php foreach (['Hatch not closing correctly','Damaged frame','Damaged gas struts',
                        'Water ingress','Bent hatch panel','Broken hinges',
                        'Damaged counter area'] as $h): ?>
          <li><?= e($h) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">We can inspect the hatch and determine whether
         repair, modification or replacement is the better option.</p>
    </div>
  </div>
</section>

<!-- ── doors ─────────────────────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="door-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Doors</p>
      <h2 id="door-h">Door Repairs</h2>
      <p class="lede">Catering trailer doors can become damaged through impact, repeated use
         or movement in the trailer structure.</p>
      <h3 style="margin-top:1.6rem">Repairs may involve</h3>
      <ul class="clean-list clean-list--cols">
        <?php foreach (['Door frames','Hinges','Handles','Locks','Panels','Seals',
                        'Complete door replacement'] as $d): ?>
          <li><?= e($d) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<!-- ── gas and electrical ────────────────────────────────────────────── -->
<section class="band" aria-labelledby="gas-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Services</p>
      <h2 id="gas-h">Gas, Electrical &amp; Interior Damage</h2>
      <p class="lede">If a trailer has suffered physical damage, gas pipework, wiring and
         internal equipment may also have been affected.</p>
      <div class="callout" style="margin-top:1.4rem">
        <p style="margin:0">Damaged or distorted gas pipework should not simply be ignored
           because the exterior of the trailer still looks usable. Where required, affected
           components should be inspected and replaced appropriately.</p>
      </div>
    </div>
  </div>
</section>

<!-- ── repair or refurbish ───────────────────────────────────────────── -->
<section class="band band--well" aria-labelledby="ref-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Worth considering</p>
      <h2 id="ref-h">Repair or Refurbish?</h2>
      <p class="lede">Sometimes it makes sense to carry out upgrades at the same time as
         repairs. For example, while repairing damaged panels or floors you may also want to
         improve:</p>
      <ul class="clean-list clean-list--cols" style="margin-top:1rem">
        <?php foreach (['Worktops','Storage','Lighting','Electrics','Water systems',
                        'Serving hatches','Interior wall finishes'] as $u): ?>
          <li><?= e($u) ?></li>
        <?php endforeach; ?>
      </ul>
      <p class="lede" style="margin-top:1.4rem">We can discuss repair and refurbishment options
         together. If the trailer is beyond sensible repair, a
         <a href="/new-catering-trailers" style="color:var(--accent-hover)">new build</a> may
         be the better route, and we will say so.</p>
      <div class="btn-row" style="margin-top:1.6rem">
        <a class="btn btn--ghost" href="/refurbishments-upgrades">View Refurbishments &amp; Upgrades</a>
      </div>
    </div>
  </div>
</section>

<!-- ── FAQs ──────────────────────────────────────────────────────────── -->
<section class="band" aria-labelledby="rfaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">Repair questions</p>
      <h2 id="rfaq-h">Questions About Repairs</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── final CTA ─────────────────────────────────────────────────────── -->
<section class="cta band" aria-labelledby="rcta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Getting started</p>
    <h2 id="rcta-h">Send Us Photos of Your Trailer</h2>
    <p>The easiest way to begin is to send us clear photographs of the damage along with a
       short explanation of what happened.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Repair Quote</a>
      <a class="btn btn--ghost btn--lg" href="/refurbishments-upgrades">Refurbishments &amp; Upgrades</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
