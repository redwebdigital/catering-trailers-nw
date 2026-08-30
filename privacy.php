<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$a = $CFG['address'];

$PAGE = [
  'title'       => 'Privacy Policy | Catering Trailers NW',
  'description' => 'How Catering Trailers NW handles the information you send through the website, how long we keep it, and your rights under UK GDPR.',
  'path'        => '/privacy',
  'nav'         => '',
  'schema'      => [schema_breadcrumbs(['Home' => '/', 'Privacy' => '/privacy'])],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Privacy</p>
      <h1>Privacy policy</h1>
      <p class="lede">Plain English, because a policy nobody can read protects nobody.
         Last updated <?= e(date('j F Y')) ?>.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap wrap--narrow prose rise">

    <h2>Who we are</h2>
    <p><?= e($CFG['legal_name']) ?>, <?= e($a['street']) ?>, <?= e($a['locality']) ?>,
       <?= e($a['postcode']) ?>. You can reach us at
       <a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a> or
       <?= e($CFG['phone_display']) ?>. We are the data controller for the information
       described here.</p>

    <h2>What we collect, and why</h2>
    <p>Only what you send us. If you fill in the quote form that means your name, phone
       number, email address, the town you are in, the details of the trailer or repair you
       are asking about, and any photographs you attach.</p>
    <p>We use it for one thing: to prepare your quote and reply to you. The lawful basis is
       taking steps at your request before entering into a contract, and our legitimate
       interest in responding to enquiries about our own services.</p>

    <h2>Photographs you upload</h2>
    <p>Photographs attached to the quote form are stored on our web hosting in a directory
       that is not reachable from the internet, and are emailed to us. We delete them once
       the enquiry is closed or the job is finished.</p>

    <h2>What we do not do</h2>
    <ul>
      <li>We do not sell or rent your details to anyone.</li>
      <li>We do not add you to a marketing list because you asked for a quote.</li>
      <li>We do not use advertising or tracking cookies on this website.</li>
      <li>We do not run analytics that profile you across other websites.</li>
    </ul>

    <h2>Cookies</h2>
    <p>This site sets no cookies of its own and there is no cookie banner because there is
       nothing to consent to. Fonts are loaded from Google Fonts, which means your browser
       requests those font files from Google and Google will see your IP address as part of
       that request. Nothing else leaves the site.</p>

    <h2>Who else sees your information</h2>
    <p>Our web host, which stores the website and delivers our email, and nobody else.
       If your enquiry is insurance work we will share what is needed with your insurer or
       their assessor, but only because that is the job you asked us to do.</p>

    <h2>How long we keep it</h2>
    <p>Quote enquiries that do not become jobs are kept for up to two years, so we can pick
       up the conversation if you come back to us. Records relating to work we actually
       carried out are kept for six years, which is what our insurers and HMRC expect.</p>

    <h2>Your rights</h2>
    <p>Under UK GDPR you can ask us for a copy of what we hold about you, ask us to correct
       it, ask us to delete it, or object to how we are using it. Email
       <a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a> and we will deal
       with it within one month.</p>
    <p>If you are not happy with how we have handled it you can complain to the Information
       Commissioner's Office at ico.org.uk, or on 0303 123 1113.</p>

    <h2>Security</h2>
    <p>The site is served over HTTPS. Form submissions are validated and rate limited, and
       uploaded files are checked before they are stored. No system is perfect, but we do
       not collect payment details or anything else through this website that would be
       worth attacking it for.</p>

  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
