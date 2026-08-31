<?php
declare(strict_types=1);
$CURRENT = 'social';
$TITLE   = 'Social Media';
$SUBTITLE = 'Links appear in the footer and are published to search engines as your official profiles.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$fields = [
  'social.facebook'  => ['label' => 'Facebook',  'type' => 'url', 'placeholder' => 'https://facebook.com/yourpage'],
  'social.instagram' => ['label' => 'Instagram', 'type' => 'url', 'placeholder' => 'https://instagram.com/yourhandle'],
  'social.tiktok'    => ['label' => 'TikTok',    'type' => 'url', 'placeholder' => 'https://tiktok.com/@yourhandle'],
  'social.youtube'   => ['label' => 'YouTube',   'type' => 'url', 'placeholder' => 'https://youtube.com/@yourchannel'],
  'social.linkedin'  => ['label' => 'LinkedIn',  'type' => 'url', 'placeholder' => 'https://linkedin.com/company/yourcompany'],
];

settings_page('social', $fields, 'Social links saved.');
?>
<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <p class="card__hint">Leave any blank and it simply will not appear. Full web addresses, not usernames.</p>
    <?php render_fields($fields); ?>
    <?php save_bar(); ?>
  </div>
</form>
<?php require_once __DIR__ . '/inc/foot.php'; ?>
