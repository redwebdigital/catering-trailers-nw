<?php
/**
 * Privacy policy.
 *
 * Written from what this website actually does rather than from a template.
 * The tracking sections appear only when that tracking is genuinely switched on
 * in the admin area, so the policy cannot claim something that is not happening,
 * and cannot go stale when the owner turns something on or off.
 */

declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$SLUG = '/privacy';
$a    = $CFG['address'];

$ga4    = trim((string)setting('track.ga4', ''));
$gtm    = trim((string)setting('track.gtm', ''));
$pixel  = trim((string)setting('track.meta_pixel', ''));
$anyAnalytics = ($ga4 !== '' || $gtm !== '');
$anyTracking  = $anyAnalytics || $pixel !== '';

$PAGE = [
  'title'       => 'Privacy Policy | Catering Trailers NW',
  'description' => 'Privacy information for visitors and customers of Catering Trailers NW.',
  'path'        => $SLUG,
  'nav'         => '',
  'crumbs'      => ['Home' => '/', 'Privacy' => $SLUG],
  'hide_cta'    => true,
  'schema'      => [schema_breadcrumbs(['Home' => '/', 'Privacy' => $SLUG])],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap wrap--narrow">
    <div class="rise">
      <p class="kicker">Privacy</p>
      <h1><?= e(page_h1($SLUG, 'Privacy Policy')) ?></h1>
      <p class="lede">This page explains what personal information
         <?= e($CFG['name']) ?> collects through this website, why we collect it, how long we
         keep it and what you can ask us to do with it.</p>
      <p class="hint">Last updated <?= e(date('j F Y', filemtime(__FILE__))) ?>.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap wrap--narrow prose rise">

    <h2>Who we are</h2>
    <p><?= e($CFG['name']) ?> builds, repairs and refurbishes catering trailers.
       We are the data controller for the information described on this page.</p>
    <p>
      <?= e($a['street']) ?>, <?= e($a['locality']) ?>, <?= e($a['postcode']) ?><br>
      <a href="mailto:<?= e($CFG['enquiry_inbox']) ?>"><?= e($CFG['enquiry_inbox']) ?></a>
      <?php if (!empty($CFG['phone_display'])): ?>
        &middot; <?= e($CFG['phone_display']) ?>
      <?php endif; ?>
    </p>

    <h2>What we collect, and when</h2>

    <h3>Enquiries and quote requests</h3>
    <p>When you complete the
       <a href="/request-a-quote">quote form</a>,
       the <a href="/contact">contact form</a> or the
       <a href="/catering-trailer-hire">hire enquiry form</a>, we receive whatever you type
       into it. Depending on the form, that can include:</p>
    <ul>
      <li>your name and, if you give one, your business name</li>
      <li>your email address</li>
      <li>your phone number, which is optional on the contact and hire forms</li>
      <li>your town or postcode</li>
      <li>details of the trailer, menu, equipment, dates and budget you describe</li>
      <li>anything else you choose to write in the message box</li>
    </ul>
    <p>We use this to answer your enquiry, prepare a quotation and carry out any work you go
       on to order. The lawful basis is our legitimate interest in responding to people who
       contact us about our services, and, once you place an order, performance of a contract
       with you.</p>

    <h3>Photographs and files you upload</h3>
    <p>The forms let you attach images. Those files are stored on our web hosting in a folder
       that is not reachable from the public internet, and are also attached to the enquiry
       email sent to us. We use them only to understand and quote for your job. Please do not
       upload photographs containing other people's personal information.</p>

    <h3>Technical information</h3>
    <p>Our web host records standard server logs, which include the IP address making each
       request. We also store the IP address alongside each enquiry, which lets us identify
       and block automated abuse of the forms. This is our legitimate interest in keeping the
       site working and our inbox usable.</p>

    <h3>Cookies</h3>
    <?php if ($anyTracking): ?>
      <p>The tracking described below sets cookies in your browser. You can block or delete
         cookies in your browser settings; the site will still work.</p>
    <?php else: ?>
      <p>This website does not set any advertising or analytics cookies for visitors. The
         only cookie the site can set is a session cookie for the private staff area, which
         is never issued to ordinary visitors.</p>
    <?php endif; ?>

    <h2>Analytics and tracking</h2>
    <?php if (!$anyTracking): ?>
      <p>At the time this page was generated, no analytics or advertising tracking is enabled
         on this website. We do not use Google Analytics, Google Tag Manager or the Meta
         Pixel. This section updates automatically if that changes.</p>
    <?php else: ?>
      <p>The following third-party services are currently enabled on this website:</p>
      <ul>
        <?php if ($ga4 !== ''): ?>
          <li><strong>Google Analytics.</strong> Provided by Google, this records which pages
              are visited and how people arrive at the site, so we can see what is useful.
              Google acts as our processor for this data.</li>
        <?php endif; ?>
        <?php if ($gtm !== ''): ?>
          <li><strong>Google Tag Manager.</strong> A container used to load the measurement
              tags listed here.</li>
        <?php endif; ?>
        <?php if ($pixel !== ''): ?>
          <li><strong>Meta Pixel.</strong> Provided by Meta, used to measure the response to
              our advertising. It can be used by Meta to show you advertising elsewhere.</li>
        <?php endif; ?>
      </ul>
      <p>These services are operated by companies outside our control and may transfer data
         outside the UK under their own safeguards. Their own privacy notices explain what
         they do with it.</p>
    <?php endif; ?>

    <h2>Who else sees your information</h2>
    <ul>
      <li><strong>Our web host</strong> stores this website, its database and the files you
          upload, and delivers the email our forms send.</li>
      <li><strong>Our email provider</strong> receives and stores enquiry emails.</li>
      <?php if ($anyTracking): ?>
        <li><strong>The analytics providers listed above.</strong></li>
      <?php endif; ?>
      <li><strong>Suppliers and subcontractors</strong>, but only where a specific job makes
          that necessary, and only the details needed for it.</li>
    </ul>
    <p>We do not sell your personal information, and we do not share it for anyone else's
       marketing.</p>

    <h2>How long we keep it</h2>
    <ul>
      <li><strong>Enquiries that do not become work:</strong> kept while they may still be
          useful, and reviewed periodically.</li>
      <li><strong>Enquiries that become an order:</strong> kept for as long as we may need
          them for the job, any warranty question and our accounting and tax obligations.</li>
      <li><strong>Uploaded photographs:</strong> deleted along with the enquiry they belong
          to.</li>
      <li><strong>Server logs:</strong> kept for the short period set by our web host.</li>
    </ul>

    <h2>Your rights</h2>
    <p>Under UK data protection law you can ask us to:</p>
    <ul>
      <li>give you a copy of the personal information we hold about you</li>
      <li>correct anything that is wrong</li>
      <li>delete your information, where we do not need to keep it</li>
      <li>restrict or object to how we use it</li>
      <li>provide it in a portable format</li>
    </ul>
    <p>Email <a href="mailto:<?= e($CFG['enquiry_inbox']) ?>"><?= e($CFG['enquiry_inbox']) ?></a>
       and we will respond within one month. There is no charge.</p>
    <p>If you are unhappy with how we have handled your information you can complain to the
       Information Commissioner's Office at
       <a href="https://ico.org.uk" rel="noopener">ico.org.uk</a>.</p>

    <h2>Changes to this page</h2>
    <p>The tracking sections above reflect what is switched on right now, so this page changes
       when our setup changes. Any larger revision will be noted by the date at the top.</p>

  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
