<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$PAGE = [
  'title'       => 'Thanks, we have your enquiry | Catering Trailers NW',
  'description' => 'Your enquiry has reached us. We will come back to you within one working day.',
  'path'        => '/thank-you',
  'nav'         => '',
];

require __DIR__ . '/inc/header.php';
?>
<meta name="robots" content="noindex, follow">

<section class="band">
  <div class="wrap wrap--narrow rise" style="text-align:center">
    <p class="kicker" style="justify-content:center">Enquiry received</p>
    <h1>Got it, thanks</h1>
    <p class="lede" style="margin-inline:auto">We will come back to you within one working
       day, usually sooner. If it is urgent, or your trailer is off the road today, call us
       instead and we will pick up.</p>

    <div class="btn-row" style="justify-content:center;margin-top:1.8rem">
      <a class="btn btn--accent btn--lg" href="<?= e(tel_href()) ?>">Call <?= e($CFG['phone_display']) ?></a>
      <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp us</a>
    </div>

    <div class="callout" style="margin-top:2.6rem;text-align:left">
      <p><strong>While you wait.</strong> If you have photographs of the trailer, the
         chassis or the damage and did not attach them, send them over on WhatsApp. A wide
         shot plus a close-up of the problem tells us more than a long phone call.</p>
    </div>

    <div style="margin-top:2.6rem;text-align:left">
      <p class="kicker">Worth a read</p>
      <div class="areas">
        <a class="area" href="/blog/catering-trailer-cost-uk"><b>What one costs</b><span>Blog</span></a>
        <a class="area" href="/blog/catering-trailer-certificates"><b>Certificates</b><span>Blog</span></a>
        <a class="area" href="/blog/can-my-car-tow-a-catering-trailer"><b>Towing weights</b><span>Blog</span></a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
