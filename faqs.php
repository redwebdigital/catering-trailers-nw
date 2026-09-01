<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/faqs';

/* Grouped for the reader; flattened for the schema, so the two always agree. */
$GROUPS = [
  'New Catering Trailers' => [
    'How much does a new catering trailer cost?' =>
      'The cost depends on the trailer size, axle configuration, internal layout, appliances, electrical system, gas installation, water system and overall level of fit-out. A simple trailer and a fully equipped commercial mobile kitchen can have very different specifications, so quotations are prepared around the individual project.',
    'Can you build a catering trailer around my menu?' =>
      'Yes. Your menu is one of the most important things to consider because it determines the appliances, refrigeration, extraction, preparation space and storage you are likely to need.',
    'What size catering trailer do I need?' =>
      'That depends on your menu, number of staff, equipment and available towing capacity. Smaller businesses may only require a compact trailer while more complex menus may need substantially more working space.',
    'Can I choose single or twin axle?' =>
      'The axle arrangement depends on trailer size, finished weight and specification. We can discuss the most suitable option while planning the trailer.',
    'Can I supply my own appliances?' =>
      'Potentially. If you already own equipment, send us the make, model and dimensions so it can be considered when developing the layout.',
    'Can you build a trailer for coffee?' =>
      'Yes. Catering trailers can be designed around coffee machines, grinders, refrigeration, water systems, sinks, storage and customer serving areas.',
    'Can you build mobile bar trailers?' =>
      'Yes. Trailers can also be configured for bar and drinks service depending on your requirements.',
  ],
  'Repairs' => [
    'Do you repair catering trailers?' =>
      'Yes. Repair work can include body damage, serving hatches, doors, interiors, floors, chassis-related work and other trailer repairs.',
    'Can you repair accident damage?' =>
      'Yes, depending on the extent of the damage. Send photographs with your enquiry so we can understand the condition of the trailer.',
    'Can you repair a catering trailer chassis?' =>
      'Chassis-related work depends on the type and severity of the damage. The trailer needs to be properly assessed before the appropriate repair can be determined.',
    'Can you repair serving hatches?' =>
      'Yes. Problems may involve hatch panels, frames, hinges, supports, seals or surrounding bodywork.',
  ],
  'Refurbishments' => [
    'Can you refurbish an old catering trailer?' =>
      'Yes. If the basic trailer remains suitable, refurbishment can include new interior surfaces, worktops, equipment layout, hatches, doors, electrics, water systems and other upgrades.',
    'Can you add another serving hatch?' =>
      'Potentially, depending on the trailer construction and available structure. The trailer will need to be assessed before structural changes are confirmed.',
    'Can you change the equipment inside my trailer?' =>
      'Yes, depending on available space and the electrical, gas, water and extraction requirements of the new equipment.',
  ],
  'Quotes' => [
    'What do you need from me to prepare a quote?' =>
      'Useful information includes your menu, preferred trailer size, equipment list, staff numbers, serving hatch requirements, gas requirements, electrical requirements and any photographs or sketches you already have.',
    'Can I upload photos?' =>
      'Yes. Photos are especially useful for repair and refurbishment enquiries.',
    'How do I request a quote?' =>
      'Complete the Request a Quote form with as much detail as possible. Your enquiry will then be reviewed before a quotation or further questions are sent to you.',
  ],
];

$FLAT = [];
foreach ($GROUPS as $qa) { $FLAT += $qa; }

$PAGE = [
  'title'       => 'Catering Trailer FAQs | Catering Trailers NW',
  'description' => 'Answers to common questions about catering trailer builds, costs, sizes, equipment, repairs, refurbishments and quotes.',
  'path'        => $SLUG,
  'nav'         => 'faqs',
  'crumbs'      => ['Home' => '/', 'FAQs' => $SLUG],
  'schema'      => [
    schema_faq($FLAT),
    schema_breadcrumbs(['Home' => '/', 'FAQs' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';

$anchors = ['New Catering Trailers' => 'new', 'Repairs' => 'repairs',
            'Refurbishments' => 'refurb', 'Quotes' => 'quotes'];
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Questions</p>
      <h1><?= e(page_h1($SLUG, 'Catering Trailer FAQs')) ?></h1>
      <p class="lede">Buying or modifying a catering trailer involves a lot of decisions.
         Below are answers to some of the questions customers commonly ask.</p>
      <ul class="taglist" style="margin-top:1.6rem">
        <?php foreach ($anchors as $label => $id): ?>
          <li><a href="#<?= e($id) ?>" style="color:inherit;text-decoration:none"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php $alt = false; foreach ($GROUPS as $heading => $qa): $alt = !$alt; ?>
<section class="band<?= $alt ? '' : ' band--well' ?>" id="<?= e($anchors[$heading]) ?>"
         aria-labelledby="h-<?= e($anchors[$heading]) ?>">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <h2 id="h-<?= e($anchors[$heading]) ?>"><?= e($heading) ?></h2>
    </div>
    <div class="faq rise" style="margin-top:1.6rem">
      <?php foreach ($qa as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="band" aria-labelledby="more-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <h2 id="more-h">Read More</h2>
      <p class="lede">
        <a href="/new-catering-trailers" style="color:var(--accent-hover)">New catering trailers</a>,
        <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">trailer repairs</a>,
        <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">refurbishments and upgrades</a> and
        <a href="/catering-trailer-hire" style="color:var(--accent-hover)">trailer hire</a>.
        There are longer guides on the
        <a href="/blog" style="color:var(--accent-hover)">advice pages</a>.
      </p>
    </div>
  </div>
</section>

<section class="cta band" aria-labelledby="fcta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Not covered here?</p>
    <h2 id="fcta-h">Still Have a Question?</h2>
    <p>Send us your requirements and we will come back to you.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/contact">Contact Us</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
