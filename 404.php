<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

if (http_response_code() === 200) {
    http_response_code(404);
}

$PAGE = [
  'title'       => 'Page not found | Catering Trailers NW',
  'description' => 'That page does not exist. Find catering trailer builds, repairs and refits, or get in touch.',
  'path'        => '/404',
  'nav'         => '',
];

require __DIR__ . '/inc/header.php';
?>
<meta name="robots" content="noindex, follow">

<section class="band">
  <div class="wrap wrap--narrow rise" style="text-align:center">
    <p class="kicker" style="justify-content:center">Error 404</p>
    <h1>That page is not here</h1>
    <p class="lede" style="margin-inline:auto">Either it moved or the link was wrong.
       Nothing is broken on your end.</p>

    <div class="btn-row" style="justify-content:center;margin-top:1.8rem">
      <a class="btn btn--accent btn--lg" href="/">Back to the home page</a>
      <?php if (has_phone()): ?>
      <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
      <?php endif; ?>
    </div>

    <div class="areas" style="margin-top:3rem;text-align:left">
      <a class="area" href="/new-catering-trailers"><b>New Trailers</b><span>Builds</span></a>
      <a class="area" href="/catering-trailer-repairs"><b>Repairs</b><span>Any make</span></a>
      <a class="area" href="/refurbishments-upgrades"><b>Refurbishments</b><span>Refits</span></a>
      <a class="area" href="/gallery"><b>Our Builds</b><span>Gallery</span></a>
      <a class="area" href="/faqs"><b>FAQs</b><span>Answers</span></a>
      <a class="area" href="/request-a-quote"><b>Request a Quote</b><span>5 steps</span></a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
