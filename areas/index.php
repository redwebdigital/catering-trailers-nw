<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/inc/bootstrap.php';

$SLUG = '/areas';

/**
 * Every town we answer enquiries from. Where a town has a page of its own it is
 * linked; the rest are listed honestly as places we take enquiries from, rather
 * than given a thin page each purely to have one.
 */
$AREAS = [
  ['Warrington', 'warrington',
   'Catering Trailers NW is available for catering trailer enquiries in Warrington and surrounding areas. Services include new bespoke catering trailers, repairs and refurbishments.'],
  ['Manchester', 'manchester',
   'We welcome enquiries from mobile catering businesses across Manchester and Greater Manchester for new catering trailers, repairs and refurbishment projects.'],
  ['Liverpool', 'liverpool',
   'Customers across Liverpool and Merseyside can contact Catering Trailers NW about new builds, repairs, upgrades and catering trailer projects.'],
  ['Cheshire', 'cheshire',
   'We work with catering trailer customers across Cheshire, including businesses looking for new mobile catering units and existing trailers requiring repairs or upgrades.'],
  ['Widnes', null,
   'Catering trailer build, repair and refurbishment enquiries are welcome from Widnes and surrounding areas.'],
  ['Runcorn', null,
   'If you are based in Runcorn and need a catering trailer built, repaired or refurbished, send us your requirements.'],
  ['St Helens', null,
   'Catering Trailers NW welcomes new build, repair and refurbishment enquiries from customers in St Helens.'],
  ['Wigan', 'wigan',
   'We can handle catering trailer enquiries from Wigan and surrounding areas.'],
  ['Bolton', 'bolton',
   'Businesses in Bolton can contact us regarding new catering trailers, repairs and refurbishments.'],
  ['Northwich', null,
   'Catering trailer enquiries are welcome from Northwich and surrounding Cheshire areas.'],
  ['Knutsford', null,
   'We can discuss bespoke catering trailers, repairs and refurbishment work with customers around Knutsford.'],
  ['Altrincham', null,
   'Mobile catering businesses in Altrincham can contact us regarding new trailers and existing trailer work.'],
];

$PAGE = [
  'title'       => 'Areas We Cover | Catering Trailers North West',
  'description' => 'Catering trailer builds, repairs and refurbishments across Warrington, Manchester, Liverpool, Cheshire and the wider North West.',
  'path'        => $SLUG,
  'nav'         => '',
  'crumbs'      => ['Home' => '/', 'Areas We Cover' => $SLUG],
  'schema'      => [schema_breadcrumbs(['Home' => '/', 'Areas We Cover' => $SLUG])],
];

require dirname(__DIR__) . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">Areas we cover</p>
      <h1><?= e(page_h1($SLUG, 'Catering Trailers Across the North West')) ?></h1>
      <p class="lede">Catering Trailers NW works with mobile food businesses, hospitality
         operators and catering companies across Warrington and the wider North West.</p>
      <p class="lede">Whether you are looking for a
         <a href="/new-catering-trailers" style="color:var(--accent-hover)">new catering trailer</a>,
         <a href="/catering-trailer-repairs" style="color:var(--accent-hover)">repairs</a> or
         <a href="/refurbishments-upgrades" style="color:var(--accent-hover)">refurbishment work</a>,
         send us your requirements and location.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      </div>
    </div>
  </div>
</section>

<?php $alt = true; foreach ($AREAS as [$name, $slug, $copy]): $alt = !$alt; ?>
<section class="band<?= $alt ? ' band--well' : '' ?>" style="padding-block:clamp(2.2rem,5vw,3.4rem)"
         aria-labelledby="a-<?= e(strtolower(str_replace(' ', '-', $name))) ?>">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <h2 id="a-<?= e(strtolower(str_replace(' ', '-', $name))) ?>"><?= e($name) ?></h2>
      <p class="lede" style="margin-bottom:1rem"><?= e($copy) ?></p>
      <?php if ($slug !== null): ?>
        <a class="btn btn--ghost btn--sm" href="/areas/catering-trailers-<?= e($slug) ?>">Catering trailers in <?= e($name) ?></a>
      <?php else: ?>
        <a class="btn btn--ghost btn--sm" href="/request-a-quote">Request a Quote</a>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="band" aria-labelledby="out-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Further afield</p>
      <h2 id="out-h">Outside These Areas?</h2>
      <p class="lede">If your town is not listed, you can still send us an enquiry.</p>
      <p class="lede">Depending on the project, we may be able to work with customers further
         afield. Tell us where you are and what you need.</p>
    </div>
  </div>
</section>

<section class="cta band" aria-labelledby="arcta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Wherever you are</p>
    <h2 id="arcta-h">Tell Us Where You Are and What You Need</h2>
    <p>Send us your requirements, your location and photographs where relevant.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/new-catering-trailers">View New Catering Trailers</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require dirname(__DIR__) . '/inc/footer.php';
