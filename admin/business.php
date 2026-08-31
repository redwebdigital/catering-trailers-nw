<?php
declare(strict_types=1);
$CURRENT = 'business';
$TITLE   = 'Business Details';
$SUBTITLE = 'Change these once and they update everywhere: header, footer, contact page, buttons, schema and the quote form.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$fields = [
  'biz.name'          => ['label' => 'Business name', 'type' => 'text'],
  'biz.legal_name'    => ['label' => 'Registered company name', 'type' => 'text',
                          'hint' => 'Shown in the footer copyright. Leave the same if there is no separate legal name.'],

  '_h1' => ['label' => 'How customers reach you', 'type' => 'heading'],
  'biz.phone_display' => ['label' => 'Main phone number', 'type' => 'tel',
                          'hint' => 'As you want it written, e.g. 01925 123456'],
  'biz.phone_e164'    => ['label' => 'Same number for click-to-call', 'type' => 'tel',
                          'hint' => 'International form, e.g. +441925123456. This is what the call buttons dial.'],
  'biz.mobile'        => ['label' => 'Mobile number', 'type' => 'tel',
                          'hint' => 'Optional. Shown on the contact page when set.'],
  'biz.whatsapp'      => ['label' => 'WhatsApp number', 'type' => 'text',
                          'hint' => 'International, digits only, no plus sign. e.g. 447700900123'],
  'biz.email'         => ['label' => 'Public email address', 'type' => 'email'],
  'biz.enquiry_inbox' => ['label' => 'Send enquiries to', 'type' => 'email',
                          'hint' => 'Where quote and contact forms are delivered. Can differ from the public address.'],
  'biz.mail_from'     => ['label' => 'Send enquiries from', 'type' => 'email',
                          'hint' => 'Must be a real mailbox on your hosting or some mail servers reject the message.'],

  '_h2' => ['label' => 'Where you are', 'type' => 'heading'],
  'biz.address_street'   => ['label' => 'Street address', 'type' => 'text'],
  'biz.address_locality' => ['label' => 'Town', 'type' => 'text'],
  'biz.address_region'   => ['label' => 'County', 'type' => 'text'],
  'biz.address_postcode' => ['label' => 'Postcode', 'type' => 'text'],
  'biz.address_country'  => ['label' => 'Country code', 'type' => 'text', 'hint' => 'GB for the United Kingdom.'],
  'biz.geo_lat' => ['label' => 'Latitude', 'type' => 'text',
                    'hint' => 'Used by local search. Right-click your workshop in Google Maps to copy the coordinates.'],
  'biz.geo_lng' => ['label' => 'Longitude', 'type' => 'text'],

  '_h3' => ['label' => 'Opening hours', 'type' => 'heading'],
  'biz.hours_display' => ['label' => 'Hours', 'type' => 'textarea', 'rows' => 5,
      'hint' => 'One line per row, in the form  Monday to Friday | 8am to 5pm  — the part before the bar is the label, after it the time.'],

  '_h4' => ['label' => 'Registration', 'type' => 'heading'],
  'biz.company_number' => ['label' => 'Company number', 'type' => 'text', 'hint' => 'Leave blank to hide from the footer.'],
  'biz.vat_number'     => ['label' => 'VAT number', 'type' => 'text', 'hint' => 'Leave blank to hide from the footer.'],

  '_h5' => ['label' => 'Trade facts quoted across the site', 'type' => 'heading'],
  'biz.lead_time'        => ['label' => 'Current lead time', 'type' => 'text', 'hint' => 'e.g. 6 to 10 weeks'],
  'biz.chassis_warranty' => ['label' => 'Chassis warranty', 'type' => 'text', 'hint' => 'e.g. 10 year'],
  'biz.build_warranty'   => ['label' => 'Build warranty', 'type' => 'text', 'hint' => 'e.g. 12 month'],

  '_h6' => ['label' => 'Domain', 'type' => 'heading'],
  'biz.domain'   => ['label' => 'Website domain', 'type' => 'text', 'hint' => 'No https://, e.g. cateringtrailersnw.co.uk'],
  'biz.base_url' => ['label' => 'Full website address', 'type' => 'url',
                     'hint' => 'With https:// and no trailing slash. Used for canonical tags, the sitemap and sharing links.'],
];

settings_page('business', $fields, 'Business details saved. They are live on the website now.');
?>

<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <?php render_fields($fields); ?>
    <?php save_bar(); ?>
  </div>
</form>

<?php require_once __DIR__ . '/inc/foot.php'; ?>
