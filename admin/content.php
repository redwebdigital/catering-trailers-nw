<?php
declare(strict_types=1);
$CURRENT = 'content';
$TITLE   = 'Site Content';
$SUBTITLE = 'The sales copy that earns enquiries. Deliberately not every paragraph, only the lines that do the work.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$fields = [
  '_h1' => ['label' => 'Homepage hero', 'type' => 'heading',
            'hint' => 'The first thing a visitor reads, and worth more than the rest of the page put together.'],
  'content.hero_kicker'  => ['label' => 'Small label above the heading', 'type' => 'text', 'count' => 30],
  'content.hero_heading' => ['label' => 'Hero heading', 'type' => 'text', 'count' => 60,
      'hint' => 'Only used if the Home page H1 under Pages & SEO is left blank. That field wins, so edit it there unless you have cleared it.'],
  'content.hero_sub'     => ['label' => 'Hero introduction', 'type' => 'textarea', 'rows' => 3, 'count' => 180],
  'content.hero_cta'     => ['label' => 'Main button text', 'type' => 'text', 'count' => 24],

  '_h2' => ['label' => 'Trust statements', 'type' => 'heading',
            'hint' => 'The three points under the hero buttons. Short and specific beats vague and reassuring.'],
  'content.proof_1' => ['label' => 'Point one',   'type' => 'text', 'count' => 70],
  'content.proof_2' => ['label' => 'Point two',   'type' => 'text', 'count' => 70],
  'content.proof_3' => ['label' => 'Point three', 'type' => 'text', 'count' => 70],

  '_h3' => ['label' => 'Quote builder section', 'type' => 'heading',
            'hint' => 'The options themselves live under Quote Builder.'],
  'content.builder_heading' => ['label' => 'Section heading', 'type' => 'text', 'count' => 60],
  'content.builder_intro'   => ['label' => 'Introduction', 'type' => 'textarea', 'rows' => 2, 'count' => 160],
  'content.builder_button'  => ['label' => 'Button text', 'type' => 'text', 'count' => 24],

  '_h4' => ['label' => 'Other pages', 'type' => 'heading',
            'hint' => 'Leave any of these blank to keep the wording already on the page.'],
  'content.about_intro'   => ['label' => 'About page introduction', 'type' => 'textarea', 'rows' => 4],
  'content.contact_intro' => ['label' => 'Contact page introduction', 'type' => 'textarea', 'rows' => 3],
  'content.repairs_intro' => ['label' => 'Repairs page introduction', 'type' => 'textarea', 'rows' => 3],
  'content.footer_text'   => ['label' => 'Footer description', 'type' => 'textarea', 'rows' => 3],

  '_h5' => ['label' => 'Honesty notice', 'type' => 'heading'],
  'content.imagery_notice_on' => ['label' => 'Show the illustrative imagery note in the footer', 'type' => 'checkbox',
      'hint' => 'Turn off once every generated interior photo has been replaced with a real one.'],
];

settings_page('content', $fields, 'Content saved and live.');
?>
<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <?php render_fields($fields); ?>
    <?php save_bar(); ?>
  </div>
</form>
<?php require_once __DIR__ . '/inc/foot.php'; ?>
