<?php
/**
 * Catering Trailers NW, single source of truth.
 *
 * ─────────────────────────────────────────────────────────────────────────
 *  EVERY business detail on the website comes from this one file.
 *  Change it here and all pages update. Nothing else needs editing.
 *
 *  >>> ITEMS MARKED  [PLACEHOLDER]  MUST BE REPLACED BEFORE GOING LIVE. <<<
 * ─────────────────────────────────────────────────────────────────────────
 */

declare(strict_types=1);

return [

    // ── Business identity ────────────────────────────────────────────────
    'name'        => 'Catering Trailers NW',
    'legal_name'  => 'Catering Trailers NW',            // [PLACEHOLDER] registered company name if different
    'tagline'     => 'Bespoke Catering Trailers Built in the North West',
    'domain'      => 'cateringtrailersnw.co.uk',
    'base_url'    => 'https://cateringtrailersnw.co.uk', // no trailing slash

    // ── Contact ──────────────────────────────────────────────────────────
    // No invented phone number ships here. Blank means the site shows no call
    // buttons at all, rather than a number that does not answer. Put the real
    // one in under Admin -> Business Details and it appears everywhere.
    'phone_display' => '',
    'phone_e164'    => '',                // used by click-to-call and schema
    'whatsapp'      => '447000000000',    // international, no + and no spaces
    'email'         => 'enquiries@cateringtrailersnw.co.uk',

    // Where quote enquiries are delivered. Can differ from the public address.
    'enquiry_inbox' => 'enquiries@cateringtrailersnw.co.uk',

    // The address the quote form sends FROM (the envelope sender). This mailbox
    // must actually exist on the hosting, or some mail servers reject the
    // message. The customer's own address goes in Reply-To, so hitting reply
    // still answers them, not this inbox.
    'mail_from' => 'enquiries@cateringtrailersnw.co.uk',

    // ── Address ──────────────────────────────────────────────────────────
    // [PLACEHOLDER] replace with the real workshop address.
    'address' => [
        'street'   => 'Unit 1, Example Industrial Estate',
        'locality' => 'Warrington',
        'region'   => 'Cheshire',
        'postcode' => 'WA1 1AA',
        'country'  => 'GB',
    ],

    // [PLACEHOLDER] workshop coordinates, used by LocalBusiness schema.
    'geo' => ['lat' => 53.3900, 'lng' => -2.5970],

    // ── Registration numbers (optional, omit to hide from the footer) ─────
    'company_number' => '',   // [PLACEHOLDER] e.g. '12345678'
    'vat_number'     => '',   // [PLACEHOLDER] e.g. 'GB123456789'

    // ── Opening hours ────────────────────────────────────────────────────
    // [PLACEHOLDER] confirm these are right.
    'hours' => [
        ['days' => ['Monday','Tuesday','Wednesday','Thursday','Friday'], 'open' => '08:00', 'close' => '17:00'],
        ['days' => ['Saturday'], 'open' => '09:00', 'close' => '13:00'],
    ],
    'hours_display' => [
        'Monday to Friday' => '8am to 5pm',
        'Saturday'         => '9am to 1pm',
        'Sunday'           => 'Closed',
    ],

    // ── Social and reviews ───────────────────────────────────────────────
    'social' => [
        'facebook'  => '',   // [PLACEHOLDER] full URL or leave empty to hide
        'instagram' => '',
    ],

    // Google Place ID powers the reviews block. Leave empty and the section
    // shows its "reviews coming soon" state instead of an empty shell.
    // Find yours at: https://developers.google.com/maps/documentation/places/web-service/place-id
    'google_place_id'    => '',   // [PLACEHOLDER]
    'google_reviews_url' => '',   // [PLACEHOLDER] direct "write a review" link

    // ── Trade facts used across the copy ─────────────────────────────────
    'lead_time'        => '6 to 10 weeks',
    'deposit_percent'  => 30,
    'chassis_warranty' => '10 year',
    'build_warranty'   => '12 month',

    // ── Areas served (drives the area pages and the schema) ───────────────
    'areas' => [
        'warrington' => ['name' => 'Warrington', 'county' => 'Cheshire'],
        'manchester' => ['name' => 'Manchester', 'county' => 'Greater Manchester'],
        'liverpool'  => ['name' => 'Liverpool',  'county' => 'Merseyside'],
        'cheshire'   => ['name' => 'Cheshire',   'county' => 'Cheshire'],
        'bolton'     => ['name' => 'Bolton',     'county' => 'Greater Manchester'],
        'wigan'      => ['name' => 'Wigan',      'county' => 'Greater Manchester'],
    ],

    // ── Imagery honesty ──────────────────────────────────────────────────
    // Shows the illustrative-imagery line in the footer. Set to false once
    // every generated image has been replaced with a real photograph.
    'show_imagery_notice' => true,

    // ── Form handling ────────────────────────────────────────────────────
    'uploads' => [
        'dir'        => __DIR__ . '/../quote-uploads',
        'max_files'  => 6,
        'max_bytes'  => 8 * 1024 * 1024,          // 8MB each
        'mime'       => ['image/jpeg', 'image/png', 'image/webp', 'image/heic'],
        'ext'        => ['jpg', 'jpeg', 'png', 'webp', 'heic'],
    ],
];
