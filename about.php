<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/about';

$PAGE = [
  'title'       => 'About Catering Trailers NW | Trailer Builders',
  'description' => 'Learn about Catering Trailers NW and our approach to building, repairing and refurbishing professional catering trailers.',
  'path'        => $SLUG,
  'nav'         => 'about',
  'crumbs'      => ['Home' => '/', 'About' => $SLUG],
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'About' => $SLUG]),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:64ch">
      <p class="kicker">About us</p>
      <h1><?= e(page_h1($SLUG, 'About Catering Trailers NW')) ?></h1>
      <p class="lede"><?= e(page_hero($SLUG,
         'We build, repair and refurbish catering trailers with one main goal: creating practical trailers that work properly for the businesses using them.')) ?></p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
        <a class="btn btn--ghost btn--lg" href="/gallery">View Our Builds</a>
      </div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="real-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">How we think about it</p>
      <h2 id="real-h">Built for Real Catering Businesses</h2>
      <p class="lede">A catering trailer is a working environment.</p>
      <p class="lede">When service gets busy, several people may be preparing food, operating
         equipment, storing ingredients and serving customers at the same time. A poor layout
         quickly becomes frustrating.</p>
      <p class="lede">That is why we believe the trailer should be designed around the business
         rather than simply filling an empty shell with appliances.</p>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="menu-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Where we start</p>
      <h2 id="menu-h">We Start With Your Menu</h2>
      <p class="lede">Your menu helps determine almost everything else inside the trailer.</p>
    </div>

    <div class="grid2" style="gap:2.4rem;margin-top:2rem">
      <div class="rise">
        <h3>A burger business may require</h3>
        <ul class="clean-list">
          <?php foreach (['Griddle','Fryers','Refrigeration','Freezer space',
                          'Preparation area','Extraction'] as $b): ?>
            <li><?= e($b) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="rise">
        <h3>A coffee business may instead need</h3>
        <ul class="clean-list">
          <?php foreach (['Coffee machine','Grinder','Refrigeration','Sink','Water system',
                          'Cup storage','Serving counter'] as $c): ?>
            <li><?= e($c) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <p class="lede rise" style="margin-top:1.8rem;max-width:70ch">Understanding what you sell
       allows us to think about the trailer in the right way.</p>
  </div>
</section>

<section class="band" aria-labelledby="lay-h">
  <div class="wrap">
    <div class="rise" style="max-width:70ch">
      <p class="kicker">Planning a build</p>
      <h2 id="lay-h">Practical Layouts</h2>
      <p class="lede">When discussing a build we consider:</p>
    </div>
    <ul class="taglist rise stagger" style="margin-top:1.4rem">
      <?php foreach (['Menu','Appliances','Staff numbers','Work surfaces','Storage',
                      'Customer serving position','Gas','Electricity','Water','Refrigeration',
                      'Trailer weight','Towing requirements'] as $l): ?>
        <li><?= e($l) ?></li>
      <?php endforeach; ?>
    </ul>
    <p class="lede rise" style="margin-top:1.6rem;max-width:70ch">The aim is to create a
       sensible working layout rather than simply trying to fit as much equipment as possible
       into the available space.</p>
  </div>
</section>

<section class="band band--panel" aria-labelledby="what-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">What we do</p>
      <h2 id="what-h">New Builds, Repairs and Refurbishments</h2></div>

    <div class="why rise stagger" style="margin-top:2.2rem">
      <div class="why__item"><span class="why__n">01</span><h3>New Builds</h3>
        <p>For customers who need a new trailer, we can develop a specification around the
           business from the beginning.</p>
        <p><a href="/new-catering-trailers" style="color:var(--accent-hover)">View New Catering Trailers</a></p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Repairs</h3>
        <p>We can also assess damaged and ageing catering trailers for repair.</p>
        <p><a href="/catering-trailer-repairs" style="color:var(--accent-hover)">View Catering Trailer Repairs</a></p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Refurbishments</h3>
        <p>Existing trailers can often be upgraded with new interiors, equipment arrangements,
           hatches, worktops and services.</p>
        <p><a href="/refurbishments-upgrades" style="color:var(--accent-hover)">View Refurbishments</a></p></div>
    </div>
  </div>
</section>

<section class="band" aria-labelledby="nw-h">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Where we work</p>
      <h2 id="nw-h">Based in the North West</h2>
      <p class="lede">Catering Trailers NW works with customers across Warrington, Cheshire,
         Greater Manchester, Merseyside and surrounding areas.</p>
      <p class="lede">If you are further away, you are still welcome to send us your
         requirements. See the
         <a href="/areas" style="color:var(--accent-hover)">areas we cover</a>.</p>
    </div>
  </div>
</section>

<section class="cta band" aria-labelledby="acta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Get in touch</p>
    <h2 id="acta-h">Tell Us About Your Trailer</h2>
    <p>Whether you need a new build, repair or refurbishment, send us as much detail as
       possible.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="/contact">Contact Us</a>
    </div>
  </div>
</section>

<?php
$PAGE['hide_cta'] = true;
require __DIR__ . '/inc/footer.php';
