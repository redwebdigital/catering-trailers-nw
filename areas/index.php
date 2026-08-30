<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/bootstrap.php';

$AREAS = require __DIR__ . '/../inc/areas-data.php';

$PAGE = [
  'title'       => 'Areas We Cover | North West | Catering Trailers NW',
  'description' => 'Catering trailer builds, repairs and refits across Warrington, Manchester, Liverpool, Cheshire, Bolton and Wigan. Delivery and collection throughout the North West.',
  'path'        => '/areas',
  'nav'         => '',
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'Areas We Cover' => '/areas']),
    [
      '@type' => 'ItemList',
      'name'  => 'Areas covered by Catering Trailers NW',
      'itemListElement' => array_values(array_map(
        fn($slug, $a) => [
          '@type'    => 'ListItem',
          'position' => array_search($slug, array_keys($AREAS), true) + 1,
          'name'     => 'Catering Trailers ' . $a['name'],
          'url'      => url('/areas/catering-trailers-' . $slug),
        ],
        array_keys($AREAS), array_values($AREAS)
      )),
    ],
  ],
];

require __DIR__ . '/../inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Areas we cover</p>
      <h1>Building and repairing across the North West</h1>
      <p class="lede">We deliver new trailers and collect units for repair throughout the
         region. Pick your area for what the trade looks like locally and who you register
         with. If your town is not listed, ask anyway, because we cover more ground than
         this page does.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap">
    <div class="gal rise stagger">
      <?php foreach ($AREAS as $slug => $a): ?>
        <figure style="display:flex;flex-direction:column;padding:1.5rem">
          <h2 style="font-size:1.24rem;margin:0 0 .3rem">
            <a href="/areas/catering-trailers-<?= e($slug) ?>"
               style="text-decoration:none;color:var(--text-primary)">
              Catering Trailers <?= e($a['name']) ?></a>
          </h2>
          <p style="font:500 .68rem/1 var(--mono);letter-spacing:.1em;text-transform:uppercase;
                    color:var(--steel);margin:0 0 .8rem"><?= e($a['county']) ?></p>
          <p style="color:var(--text-secondary);font-size:.95rem;margin:0 0 1rem;flex:1">
            <?= e($a['lead']) ?></p>
          <a class="door__go" href="/areas/catering-trailers-<?= e($slug) ?>"
             style="text-decoration:none">
            <?= e($a['name']) ?> details
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M8.8 2.4 14.4 8l-5.6 5.6-1.1-1.1 3.7-3.7H1.6V7.2h9.8L7.7 3.5z"/></svg>
          </a>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="band band--well">
  <div class="wrap wrap--narrow rise" style="text-align:center">
    <h2>Not on the list?</h2>
    <p class="lede" style="margin-inline:auto">We regularly work beyond these six. Tell us
       where you are and we will say straight away whether we can help.</p>
    <div class="btn-row" style="justify-content:center;margin-top:1.5rem">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../inc/footer.php'; ?>
