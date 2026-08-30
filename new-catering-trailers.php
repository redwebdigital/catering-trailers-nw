<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$FAQ = [
  'What sizes do you build?' =>
    'From 2.4m up to 4.2m as standard, single or twin axle. If you need something outside that, ask, because most of what we do is bespoke anyway.',
  'Can I supply my own appliances?' =>
    'Yes. Plenty of traders bring a griddle or a coffee machine they already trust. We will fit it and certify around it.',
  'Do you deliver?' =>
    'Yes, anywhere in the North West, and further by arrangement. Most people collect so we can walk them round it properly.',
  'What is included in the price?' =>
    'The trailer, the fit-out, the appliances we supply, the gas and electrical work, both certificates and the handover. No surprises at the end.',
];

$PAGE = [
  'title'       => 'New Catering Trailers | Bespoke Builds | Catering Trailers NW',
  'description' => 'Bespoke catering trailers built to order in the North West. 2.4m to 4.2m, single or twin axle, stainless fit-out, Gas Safe and electrical certificates supplied.',
  'path'        => '/new-catering-trailers',
  'nav'         => 'new',
  'schema'      => [
    schema_service('New bespoke catering trailer manufacture',
      'Custom catering trailers built to order for street food traders, burger vans and coffee trailers across the North West.',
      '/new-catering-trailers'),
    schema_faq($FAQ),
    schema_breadcrumbs(['Home' => '/', 'New Catering Trailers' => '/new-catering-trailers']),
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">New catering trailers</p>
      <h1>Built around your menu, not around a catalogue</h1>
      <p class="lede">Every trailer that leaves our workshop is drawn for one trader and
         one menu. Where the griddle sits, which side the hatch opens, how much counter you
         get before the fryer. It all comes from what you actually serve.</p>
      <div class="btn-row" style="margin-top:1.8rem">
        <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
        <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
      </div>
    </div>
  </div>
</section>

<section class="band--tight">
  <div class="wrap rise">
    <?= picture('catering-trailer-interior-swirl-stainless',
        'Brand new catering trailer interior with swirl finish stainless steel walls, extraction canopy and stainless counter run',
        ['widths'=>[480,800,1200],'sizes'=>'100vw','eager'=>true,'ratio'=>'16/9']) ?>
    <p style="margin-top:.9rem;font:500 .74rem/1.5 var(--mono);letter-spacing:.06em;color:var(--steel)">
      Swirl finish stainless throughout. Wipes clean, hides day to day marking,
      and it is what makes a trailer look worth what you paid for it.
    </p>
  </div>
</section>

<section class="band" aria-labelledby="spec-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">What you get</p>
      <h2 id="spec-h">Standard on every build</h2>
      <p class="lede">Not an options list. This is what every trailer leaves with.</p></div>

    <div class="why rise stagger" style="margin-top:2.4rem">
      <div class="why__item"><span class="why__n">01</span><h3>Galvanised chassis</h3>
        <p>Hot dip galvanised and warranted <?= e($CFG['chassis_warranty']) ?> against
           corrosion. This is the part that decides what your trailer is worth in five years.</p></div>
      <div class="why__item"><span class="why__n">02</span><h3>Insulated body</h3>
        <p>Composite panels with an aluminium frame. Warm in February, workable in August,
           and it wipes down.</p></div>
      <div class="why__item"><span class="why__n">03</span><h3>Stainless throughout</h3>
        <p>Counters, splashbacks and shelving in stainless with sealed joints, because
           that is what passes a hygiene inspection.</p></div>
      <div class="why__item"><span class="why__n">04</span><h3>Gas Safe pipework</h3>
        <p>Installed and tested by a Gas Safe registered engineer, with the certificate
           in your hand on collection day.</p></div>
      <div class="why__item"><span class="why__n">05</span><h3>Certified electrics</h3>
        <p>Consumer unit, RCD protection, sockets and lighting, tested and signed off
           with an electrical certificate.</p></div>
      <div class="why__item"><span class="why__n">06</span><h3>Water and hygiene</h3>
        <p>Hot and cold water, twin sink and a separate wash hand basin. The three things
           an environmental health officer checks first.</p></div>
    </div>
  </div>
</section>

<section class="band band--well" aria-labelledby="size-h">
  <div class="wrap">
    <div class="rise"><p class="kicker">Sizes</p>
      <h2 id="size-h">Picking a length</h2>
      <p class="lede">Bigger is not automatically better. A trailer you cannot tow or
         cannot pitch is worth less than a smaller one you can.</p></div>

    <div class="gal rise stagger" style="margin-top:2.4rem;grid-template-columns:repeat(auto-fit,minmax(230px,1fr))">
      <figure style="padding:1.4rem">
        <h3 style="margin:0 0 .3rem">2.4m</h3>
        <p style="color:var(--text-secondary);font-size:.95rem;margin:0">
          Coffee, desserts, a tight single-operator setup. Easiest to tow and to pitch.</p>
      </figure>
      <figure style="padding:1.4rem">
        <h3 style="margin:0 0 .3rem">3.0m</h3>
        <p style="color:var(--text-secondary);font-size:.95rem;margin:0">
          The one most people end up on. Room for a griddle and a fryer with counter left over.</p>
      </figure>
      <figure style="padding:1.4rem">
        <h3 style="margin:0 0 .3rem">3.5m</h3>
        <p style="color:var(--text-secondary);font-size:.95rem;margin:0">
          Two people working comfortably, or a bigger cook line. Usually twin axle.</p>
      </figure>
      <figure style="padding:1.4rem">
        <h3 style="margin:0 0 .3rem">4.2m</h3>
        <p style="color:var(--text-secondary);font-size:.95rem;margin:0">
          Full kitchens, pizza ovens, high volume events. Check your towing weight first.</p>
      </figure>
    </div>

    <p class="lede rise" style="margin-top:1.8rem">Not sure? Tell us your menu and your
       tow vehicle on the <a href="/request-a-quote" style="color:var(--accent-hover)">quote form</a>
       and we will tell you which size actually suits you, even if it is a smaller one.</p>
  </div>
</section>

<section class="band" aria-labelledby="nfaq-h">
  <div class="wrap wrap--narrow">
    <div class="rise"><p class="kicker">New build questions</p>
      <h2 id="nfaq-h">Before you commit</h2></div>
    <div class="faq rise" style="margin-top:2rem">
      <?php foreach ($FAQ as $q => $a): ?>
        <details><summary><?= e($q) ?></summary><div class="ans"><?= e($a) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p class="lede rise" style="margin-top:1.6rem">
      More answers on the <a href="/faqs" style="color:var(--accent-hover)">FAQs page</a>,
      or see <a href="/gallery" style="color:var(--accent-hover)">trailers we have built</a>.
    </p>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
