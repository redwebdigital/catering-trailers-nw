<?php
declare(strict_types=1);
$CURRENT = 'tracking';
$TITLE   = 'Google & Tracking';
$SUBTITLE = 'These are wired into every page automatically. Fill one in and it starts working, leave it blank and nothing loads.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$fields = [
  'track.ga4' => ['label' => 'Google Analytics measurement ID', 'type' => 'text',
      'placeholder' => 'G-XXXXXXXXXX',
      'hint' => 'Starts with G. Found in Analytics under Admin, then Data streams.'],
  'track.gtm' => ['label' => 'Google Tag Manager ID', 'type' => 'text',
      'placeholder' => 'GTM-XXXXXXX',
      'hint' => 'Only if you use Tag Manager. If you use both, put the Analytics tag inside Tag Manager rather than here as well.'],
  'track.gsc' => ['label' => 'Search Console verification code', 'type' => 'text',
      'hint' => 'Only the content value from the meta tag Google gives you, not the whole tag.'],
  'track.meta_pixel' => ['label' => 'Meta (Facebook) Pixel ID', 'type' => 'text',
      'placeholder' => '000000000000000', 'hint' => 'Digits only.'],
  'track.maps_url' => ['label' => 'Google Business Profile or Maps link', 'type' => 'url',
      'hint' => 'Used on the contact page and to point customers at your reviews.'],

  '_h' => ['label' => 'Anything else', 'type' => 'heading',
      'hint' => 'For a tag with no field above. Paste the complete snippet including its script tags.'],
  'track.custom_head' => ['label' => 'Custom code in the head', 'type' => 'code', 'rows' => 5],
  'track.custom_body' => ['label' => 'Custom code before the closing body tag', 'type' => 'code', 'rows' => 5],
];

settings_page('tracking', $fields, 'Tracking settings saved and live on every page.');
?>
<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <?php render_fields($fields); ?>
    <?php save_bar(); ?>
  </div>
</form>

<div class="note note--warn">
  <strong>Worth knowing before you fill these in.</strong>
  Analytics and the Meta Pixel collect visitor data, and UK law requires a cookie banner asking
  permission before they load. Your site currently sets no cookies at all and needs no banner.
  Tell me when you add tracking and I will build a consent banner that holds these tags back
  until a visitor agrees.
</div>

<?php require_once __DIR__ . '/inc/foot.php'; ?>
