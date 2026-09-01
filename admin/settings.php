<?php
declare(strict_types=1);
$CURRENT = 'settings';
$TITLE   = 'Site Settings';
$SUBTITLE = 'Site-wide defaults, structured data and the admin password.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

/* --------------------------------------------------- change the password */
if (($_POST['do'] ?? '') === 'password') {
    csrf_check();
    $cur = (string)($_POST['current'] ?? '');
    $new = (string)($_POST['new'] ?? '');
    $rep = (string)($_POST['repeat'] ?? '');

    if (!password_verify($cur, secrets()['admin']['hash'] ?? '')) {
        flash('Your current password is not right.', 'err');
    } elseif (strlen($new) < 12) {
        flash('The new password needs at least 12 characters.', 'err');
    } elseif ($new !== $rep) {
        flash('The two new passwords do not match.', 'err');
    } elseif (admin_set_password($new)) {
        flash('Password changed. It takes effect next time you sign in.');
    } else {
        flash('Could not write the new password. Check the private folder is writable.', 'err');
    }
    header('Location: /admin/settings.php'); exit;
}

require_once dirname(__DIR__) . '/inc/coming-soon.php';

$fields = [
  '_h0' => ['label' => 'Coming soon page', 'type' => 'heading',
            'hint' => 'Replaces the whole public website with a holding page carrying your logo. The admin area stays open, so you cannot lock yourself out.'],
  'site.coming_soon' => ['label' => 'Show the coming soon page instead of the website', 'type' => 'checkbox',
      'hint' => 'Visitors see the holding page. Search engines are told the site is temporarily away, so your existing pages are held rather than replaced.'],
  'site.cs_heading' => ['label' => 'Holding page heading', 'type' => 'text', 'count' => 60,
      'default' => 'Something new is on the way'],
  'site.cs_message' => ['label' => 'Holding page message', 'type' => 'textarea', 'rows' => 3, 'count' => 220,
      'default' => 'Our website is being updated. We are still building, repairing and refurbishing catering trailers in the meantime, so please do get in touch.'],
  'site.cs_show_contact' => ['label' => 'Show an email link on the holding page', 'type' => 'checkbox',
      'hint' => 'Uses the enquiry address from Business Details. No phone number is shown on the holding page.'],

  '_h1' => ['label' => 'Search defaults', 'type' => 'heading',
            'hint' => 'Used on any page that has not been given its own wording under Pages & SEO.'],
  'seo.title_suffix' => ['label' => 'Title suffix', 'type' => 'text',
      'hint' => 'Added to the end of page titles, e.g. " | Catering Trailers NW".'],
  'seo.default_desc' => ['label' => 'Default meta description', 'type' => 'textarea', 'rows' => 2, 'count' => 158],

  '_h2' => ['label' => 'Brand files', 'type' => 'heading'],
  'seo.logo'       => ['label' => 'Logo path', 'type' => 'text', 'hint' => 'e.g. /assets/img/logo.png'],
  'seo.favicon'    => ['label' => 'Favicon path', 'type' => 'text'],
  'seo.default_og' => ['label' => 'Default sharing image', 'type' => 'text',
      'hint' => '1200 by 630. Shown when a page has no image of its own.'],

  '_h3' => ['label' => 'Reviews', 'type' => 'heading'],
  'seo.google_place_id' => ['label' => 'Google Place ID', 'type' => 'text',
      'hint' => 'Switches on the Google reviews block on your homepage. Until this is set the section stays honestly empty rather than showing anything invented.'],
  'seo.google_reviews_url' => ['label' => 'Link to your reviews', 'type' => 'url'],

  '_h4' => ['label' => 'robots.txt', 'type' => 'heading',
            'hint' => 'The standard rules are generated for you, including your sitemap. Anything here is added underneath.'],
  'seo.robots_extra' => ['label' => 'Extra robots.txt rules', 'type' => 'code', 'rows' => 4],
];

settings_page('seo', $fields, 'Site settings saved.');

$counts = [
  'pages'     => (int)q_val("SELECT COUNT(*) FROM pages"),
  'enquiries' => (int)q_val("SELECT COUNT(*) FROM enquiries"),
  'media'     => (int)q_val("SELECT COUNT(*) FROM media"),
  'options'   => (int)q_val("SELECT COUNT(*) FROM builder_options"),
];
$csOn      = coming_soon_on();
$previewUrl = rtrim((string)$CFG['base_url'], '/') . '/?preview=' . coming_soon_key();
?>

<?php if ($csOn): ?>
  <div class="note note--warn">
    <strong>The public website is switched off.</strong> Everyone visiting
    <?= e($CFG['domain'] ?? 'the site') ?> sees the coming soon page. Untick the box below to
    put it back.
    <br><br>
    To check the real site while it is off, open this link once and your browser is let
    through for 24 hours:<br>
    <a class="mono" style="word-break:break-all" href="<?= e($previewUrl) ?>" target="_blank" rel="noopener"><?= e($previewUrl) ?></a>
  </div>
<?php endif; ?>

<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <?php render_fields($fields); ?>
    <?php if (!$csOn): ?>
      <p class="card__hint" style="margin-top:.4rem">
        Preview link, for checking the site while the holding page is up:
        <a class="mono" style="word-break:break-all" href="<?= e($previewUrl) ?>" target="_blank" rel="noopener"><?= e($previewUrl) ?></a>
      </p>
    <?php endif; ?>
    <?php save_bar(); ?>
  </div>
</form>

<div class="card">
  <h2>Structured data</h2>
  <p class="card__hint">Your LocalBusiness and Organisation markup is generated automatically from
     Business Details, so it can never drift out of step with what the pages say. Nothing to edit here.</p>
  <div class="grid grid--2">
    <div>
      <h3>Currently telling Google</h3>
      <p class="mono muted" style="font-size:.8rem;line-height:1.7">
        <?= e($CFG['name']) ?><br>
        <?= e($CFG['phone_e164']) ?><br>
        <?= e($CFG['address']['street']) ?>, <?= e($CFG['address']['locality']) ?>, <?= e($CFG['address']['postcode']) ?><br>
        <?= e((string)$CFG['geo']['lat']) ?>, <?= e((string)$CFG['geo']['lng']) ?>
      </p>
    </div>
    <div>
      <h3>Files</h3>
      <p class="muted" style="font-size:.9rem">
        <a href="/sitemap.xml" target="_blank" rel="noopener">sitemap.xml</a> — generated, always current<br>
        <a href="/robots.txt" target="_blank" rel="noopener">robots.txt</a> — generated from the settings above
      </p>
    </div>
  </div>
</div>

<div class="card">
  <h2>Change your admin password</h2>
  <form method="post" autocomplete="off">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="password">
    <div class="grid grid--3">
      <div class="field">
        <label for="current">Current password</label>
        <input class="input" type="password" id="current" name="current" autocomplete="current-password">
      </div>
      <div class="field">
        <label for="new">New password</label>
        <input class="input" type="password" id="new" name="new" minlength="12" autocomplete="new-password">
      </div>
      <div class="field">
        <label for="repeat">Repeat new password</label>
        <input class="input" type="password" id="repeat" name="repeat" minlength="12" autocomplete="new-password">
      </div>
    </div>
    <button class="btn btn--accent" type="submit">Change password</button>
  </form>
</div>

<div class="card">
  <h2>System</h2>
  <div class="grid grid--4">
    <div class="stat"><b><?= $counts['enquiries'] ?></b><span>enquiries stored</span></div>
    <div class="stat"><b><?= $counts['pages'] ?></b><span>pages managed</span></div>
    <div class="stat"><b><?= $counts['options'] ?></b><span>configurator options</span></div>
    <div class="stat"><b><?= $counts['media'] ?></b><span>images</span></div>
  </div>
  <p class="mono muted" style="font-size:.78rem;margin-top:1rem">
    database: <?= e(db_driver()) ?> · PHP <?= e(PHP_VERSION) ?> ·
    private folder: <?= e(private_dir()) ?>
  </p>
  <p class="muted" style="font-size:.88rem">
    Your database sits outside the website folder, so a deploy cannot overwrite it and nobody
    can reach it over the web. Worth downloading a copy occasionally as a backup.
  </p>
</div>

<?php require __DIR__ . '/inc/foot.php'; ?>
