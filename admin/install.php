<?php
/**
 * One-time installer.
 *
 * Creates the schema and seeds it from the site as it stands today, so
 * installing changes nothing a visitor can see. Once an admin password exists
 * this page refuses to run again, so it cannot be used to take the site over.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/bootstrap.php';
require_once dirname(__DIR__) . '/inc/auth.php';

$CFG_DEFAULTS = require dirname(__DIR__) . '/inc/config.php';

// Already installed? Only a logged-in admin may re-run it.
$locked = !empty(secrets()['admin']['hash']);
if ($locked && !admin_is_logged_in()) {
    http_response_code(403);
    exit('Already installed. Sign in at /admin/ to make changes.');
}

$errors = [];
$done   = false;
$report = [];

/* ---------------------------------------------------------- requirements */
$checks = [
    'PHP 8.0 or newer'      => PHP_VERSION_ID >= 80000,
    'PDO'                   => class_exists('PDO'),
    'PDO SQLite'            => in_array('sqlite', PDO::getAvailableDrivers(), true),
    'GD or Imagick (images)'=> extension_loaded('gd') || extension_loaded('imagick'),
    'Private folder writable' => is_writable(private_dir()),
];
$fatal = !$checks['PHP 8.0 or newer'] || !$checks['PDO'] || !$checks['PDO SQLite']
      || !$checks['Private folder writable'];

/* ---------------------------------------------------------------- submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$fatal) {
    $pw  = (string)($_POST['password'] ?? '');
    $pw2 = (string)($_POST['password2'] ?? '');

    if (strlen($pw) < 12)      { $errors[] = 'Use at least 12 characters. This is the key to your whole website.'; }
    if ($pw !== $pw2)          { $errors[] = 'The two passwords do not match.'; }
    if (preg_match('/^[a-z]+$/i', $pw)) { $errors[] = 'Mix in a number or symbol.'; }

    if (!$errors) {
        try {
            // secrets first, so db() knows where to look
            $s = secrets();
            $s['db'] = $s['db'] ?? ['driver' => 'sqlite', 'path' => private_dir() . '/ctnw.sqlite'];
            $s['pepper'] = $s['pepper'] ?? bin2hex(random_bytes(16));
            secrets_save($s);

            $report['migrations'] = migrate();
            $report['seeded']     = seed_everything($CFG_DEFAULTS);

            admin_set_password($pw);
            $done = true;
        } catch (Throwable $e) {
            $errors[] = 'Install failed: ' . $e->getMessage();
        }
    }
}

/* ------------------------------------------------------------------ seed */
function seed_everything(array $d): array
{
    $out = [];

    /* business settings, taken from config.php so nothing visibly changes */
    $seed = [
        'biz.name'             => $d['name'],
        'biz.legal_name'       => $d['legal_name'],
        'biz.domain'           => $d['domain'],
        'biz.base_url'         => $d['base_url'],
        'biz.phone_display'    => $d['phone_display'],
        'biz.phone_e164'       => $d['phone_e164'],
        'biz.mobile'           => '',
        'biz.whatsapp'         => $d['whatsapp'],
        'biz.email'            => $d['email'],
        'biz.enquiry_inbox'    => $d['enquiry_inbox'],
        'biz.mail_from'        => $d['mail_from'] ?? $d['enquiry_inbox'],
        'biz.address_street'   => $d['address']['street'],
        'biz.address_locality' => $d['address']['locality'],
        'biz.address_region'   => $d['address']['region'],
        'biz.address_postcode' => $d['address']['postcode'],
        'biz.address_country'  => $d['address']['country'],
        'biz.geo_lat'          => (string)$d['geo']['lat'],
        'biz.geo_lng'          => (string)$d['geo']['lng'],
        'biz.company_number'   => $d['company_number'],
        'biz.vat_number'       => $d['vat_number'],
        'biz.lead_time'        => $d['lead_time'],
        'biz.chassis_warranty' => $d['chassis_warranty'],
        'biz.build_warranty'   => $d['build_warranty'],
    ];
    $hours = [];
    foreach ($d['hours_display'] as $days => $time) { $hours[] = $days . ' | ' . $time; }
    $seed['biz.hours_display'] = implode("\n", $hours);
    settings_set_many($seed, 'business');
    $out['business'] = count($seed);

    settings_set_many([
        'social.facebook'  => $d['social']['facebook'] ?? '',
        'social.instagram' => $d['social']['instagram'] ?? '',
        'social.tiktok'    => '',
        'social.youtube'   => '',
        'social.linkedin'  => '',
    ], 'social');

    settings_set_many([
        'track.ga4'        => '', 'track.gtm' => '', 'track.gsc' => '',
        'track.maps_url'   => '', 'track.meta_pixel' => '', 'track.custom_head' => '',
        'track.custom_body'=> '',
    ], 'tracking');

    settings_set_many([
        'seo.title_suffix'   => ' | ' . $d['name'],
        'seo.default_desc'   => 'Bespoke catering trailers built in the North West, plus repairs and refits on any trailer.',
        'seo.default_og'     => '/assets/img/og-default.jpg',
        'seo.logo'           => '/assets/img/logo.png',
        'seo.favicon'        => '/favicon.svg',
        'seo.google_place_id'=> $d['google_place_id'] ?? '',
        'seo.google_reviews_url' => $d['google_reviews_url'] ?? '',
        'seo.robots_extra'   => '',
    ], 'seo');

    settings_set_many([
        'content.hero_heading'   => 'Bespoke Catering Trailers Built in the North West',
        'content.hero_sub'       => 'Built to your menu, not off a shelf. New builds, repairs and refits for street food traders, burger vans and coffee trailers.',
        'content.hero_cta'       => 'Request a Quote',
        'content.hero_kicker'    => 'Built in the North West',
        'content.proof_1'        => 'Gas Safe and electrical certificates handed over with the keys',
        'content.proof_2'        => $d['chassis_warranty'] . ' anti corrosion chassis warranty',
        'content.proof_3'        => 'Ready to trade the day you collect it',
        'content.footer_text'    => 'Bespoke catering trailers built in the North West, plus repairs, refits and accident work on any make of trailer.',
        'content.imagery_notice_on' => $d['show_imagery_notice'] ? '1' : '0',
        'content.builder_heading'   => 'From your menu to your pitch',
        'content.builder_intro'     => 'Five stages. You know where your trailer is at every one of them.',
        'content.builder_button'    => 'Send this spec',
    ], 'content');
    $out['content'] = 13;

    /* the configurator, seeded to exactly what the site shows today */
    if ((int)q_val("SELECT COUNT(*) FROM builder_options") === 0) {
        $opts = [
            ['length', '2.4m', '2.4', '', 196, 0],
            ['length', '3.0m', '3.0', '', 244, 1],
            ['length', '3.5m', '3.5', '', 285, 2],
            ['length', '4.2m', '4.2', '', 342, 3],
            ['axle',   'Single', 'single', '', 0, 0],
            ['axle',   'Twin',   'twin',   '', 0, 1],
            ['use',    'Burgers',       'Burgers',       '', 0, 0],
            ['use',    'Coffee',        'Coffee',        '', 0, 1],
            ['use',    'Pizza',         'Pizza',         '', 0, 2],
            ['use',    'Fried chicken', 'Fried chicken', '', 0, 3],
            ['use',    'Desserts',      'Desserts',      '', 0, 4],
        ];
        foreach ($opts as [$g, $label, $val, $price, $w, $sort]) {
            q("INSERT INTO builder_options (group_key,label,value,price_from,icon,draw_width,enabled,sort_order)
               VALUES (?,?,?,?,'',?,1,?)", [$g, $label, $val, $price, $w, $sort]);
        }
        $out['builder_options'] = count($opts);
    }

    if ((int)q_val("SELECT COUNT(*) FROM builder_stages") === 0) {
        $stages = [
            ['We take your spec', 'Your menu, your appliances, your pitch and your tow vehicle. Twenty minutes on the phone saves weeks later.'],
            ['Drawings and a fixed price', 'A layout drawing and a written quote with nothing hidden. Change it as many times as you like before you commit.'],
            ['Chassis and shell', 'Galvanised chassis, insulated body, hatch and door openings cut and framed. Photographs sent as it goes.'],
            ['Fit-out, gas and electrics', 'Stainless surfaces, appliances installed, gas pipework and electrics run, then tested and certified.'],
            ['Handover', 'We walk you round it, hand you both certificates, and show you how everything works before you tow away.'],
        ];
        foreach ($stages as $i => [$t, $b]) {
            q("INSERT INTO builder_stages (title, body, enabled, sort_order) VALUES (?,?,1,?)", [$t, $b, $i]);
        }
        $out['builder_stages'] = count($stages);
    }

    /* the pages the SEO manager edits */
    if ((int)q_val("SELECT COUNT(*) FROM pages") === 0) {
        $pages = [
            ['/', 'Home', 'index.php'],
            ['/new-catering-trailers', 'New Catering Trailers', 'new-catering-trailers.php'],
            ['/catering-trailer-repairs', 'Repairs', 'catering-trailer-repairs.php'],
            ['/refurbishments-upgrades', 'Refurbishments', 'refurbishments-upgrades.php'],
            ['/gallery', 'Gallery', 'gallery.php'],
            ['/about', 'About', 'about.php'],
            ['/faqs', 'FAQs', 'faqs.php'],
            ['/contact', 'Contact', 'contact.php'],
            ['/request-a-quote', 'Request a Quote', 'request-a-quote.php'],
            ['/blog', 'Blog', 'blog/index.php'],
            ['/areas', 'Areas We Cover', 'areas/index.php'],
            ['/privacy', 'Privacy', 'privacy.php'],
        ];
        foreach ($pages as $i => [$slug, $label, $file]) {
            q("INSERT INTO pages (slug,label,file,robots_index,robots_follow,schema_type,sort_order,updated_at)
               VALUES (?,?,?,'index','follow','WebPage',?,?)", [$slug, $label, $file, $i, date('c')]);
        }
        $out['pages'] = count($pages);
    }

    return $out;
}
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Install admin · Catering Trailers NW</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Source+Sans+3:wght@400;600&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="/admin/assets/admin.css?v=1">
</head>
<body>
<div class="login">
  <div class="login__box" style="width:min(560px,100%)">

    <h1 style="margin-top:0">Set up your admin area</h1>

    <?php if ($done): ?>
      <div class="note note--ok">
        <strong>Installed.</strong> Your admin area is ready and the website looks exactly as it did.
      </div>
      <p class="muted mono" style="font-size:.82rem">
        migrations: <?= e(implode(', ', $report['migrations'] ?: ['none needed'])) ?><br>
        seeded: <?= e(json_encode($report['seeded'])) ?>
      </p>
      <p><strong>Delete <code>admin/install.php</code> now.</strong> It locks itself once a password
         exists, but there is no reason to leave it on the server.</p>
      <a class="btn btn--accent" href="/admin/">Go to the dashboard</a>

    <?php else: ?>

      <h2 style="font-size:1rem">Checks</h2>
      <table style="min-width:0;margin-bottom:1.4rem">
        <?php foreach ($checks as $label => $ok): ?>
          <tr><td><?= e($label) ?></td>
              <td class="right" style="color:<?= $ok ? '#7FD9A3' : '#FF7A85' ?>">
                <?= $ok ? 'ok' : 'missing' ?></td></tr>
        <?php endforeach; ?>
      </table>

      <?php if ($fatal): ?>
        <div class="note note--err">This host is missing something required. Nothing has been changed.</div>
      <?php else: ?>

        <?php foreach ($errors as $err): ?>
          <div class="note note--err"><?= e($err) ?></div>
        <?php endforeach; ?>

        <p class="muted">Choose the password for <code>/admin</code>. It is stored only as a
           hash, in a file outside the web root, and never appears in the code or the repository.</p>

        <form method="post" autocomplete="off">
          <div class="field">
            <label for="password">Admin password</label>
            <input class="input" type="password" id="password" name="password"
                   required minlength="12" autocomplete="new-password">
            <span class="hint">At least 12 characters. Use a phrase you will remember.</span>
          </div>
          <div class="field">
            <label for="password2">Repeat it</label>
            <input class="input" type="password" id="password2" name="password2"
                   required minlength="12" autocomplete="new-password">
          </div>
          <button class="btn btn--accent" type="submit">Create the admin area</button>
        </form>
      <?php endif; ?>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
