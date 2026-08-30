# Catering Trailers NW

The website for cateringtrailersnw.co.uk. Plain PHP, no framework, no build step,
no npm. Everything is editable by hand and deploys by copying files.

---

## Before it goes live

Open **`inc/config.php`** and replace everything marked `[PLACEHOLDER]`.
That one file feeds every page, the click-to-call buttons, the WhatsApp links
and the search-engine schema.

| Setting | What it is |
|---|---|
| `phone_display` / `phone_e164` | Your number, twice. Display version and the `+44...` version for click-to-call. |
| `whatsapp` | International format, no `+` and no spaces, e.g. `447700900123`. |
| `email` / `enquiry_inbox` | Public address, and where quote enquiries are delivered. |
| `address` / `geo` | Workshop address and coordinates. Both appear in the local search schema. |
| `company_number` / `vat_number` | Leave empty to hide them from the footer. |
| `hours` / `hours_display` | Opening hours. The first drives schema, the second is what visitors read. |
| `google_place_id` | Turns on the live Google reviews block. Empty shows an honest placeholder. |
| `lead_time`, `deposit_percent`, `chassis_warranty` | Trade facts quoted across the site. |

Two other things need a human:

1. **`about.php`** has a marked placeholder block. Replace it with the real story
   of the business. It is the page buyers read before spending five figures.
2. **Testimonials** on the homepage are a marked placeholder. Paste real customer
   quotes into the commented-out block below it. Nothing invented gets published.

---

## Layout

```
index.php                     home, with the scroll-scrubbed hero
new-catering-trailers.php     ·
catering-trailer-repairs.php  · service pages
refurbishments-upgrades.php   ·
gallery.php  about.php  faqs.php  contact.php  privacy.php
request-a-quote.php           the five-step form
quote-handler.php             receives it, validates, emails, stores photos
thank-you.php  404.php
sitemap.php                   generates /sitemap.xml from the page lists
blog/index.php                blog listing
blog/<slug>.php               one file per article
areas/index.php               areas hub
areas/catering-trailers-*.php one file per location

inc/config.php                >>> every business detail lives here <<<
inc/bootstrap.php             helpers: escaping, URLs, responsive images, schema
inc/header.php inc/footer.php shared chrome
inc/posts.php                 the blog registry
inc/areas-data.php            per-area copy
inc/post-top.php  post-bottom.php   article wrapper
inc/area-page.php             area page renderer

assets/css/site.css           one stylesheet
assets/js/hero.js             the scroll-scrub engine
assets/js/site.js             nav, entrances, spec builder
assets/js/quote-form.js       the multi-step form
assets/img/gallery/           trailer photos, WebP + JPEG at several widths
assets/video/hero-scrub.mp4   the hero film
```

---

## Adding things

**A new location page.** Add a row to `areas` in `inc/config.php`, add a matching
entry to `inc/areas-data.php` with real local detail, then create
`areas/catering-trailers-<slug>.php` containing:

```php
<?php
declare(strict_types=1);
$AREA = '<slug>';
require __DIR__ . '/../inc/area-page.php';
```

It joins the footer, the areas hub and the sitemap automatically. Write genuinely
different copy for each one. Location pages that are the same text with the town
swapped are treated as duplicates and will not rank.

**A blog post.** Add an entry to `inc/posts.php`, then copy an existing file in
`blog/` and change `$SLUG`.

**Gallery photos.** Drop the originals in `review/real-photos/`, add them to the
`MAP` in `review/build-images.py`, run it, then add a row to `$BUILDS` in
`gallery.php`. It writes WebP and JPEG at four widths and strips metadata.

---

## Deploying to Hostinger

The repo root is the web root. `public_html` should contain `index.php` at its
top level, not a folder containing it.

**Option A, git (preferred).** In hPanel go to Advanced → Git, point it at this
repository and set the deploy path to `public_html`. After that, deploying is:

```bash
git push origin main
```

then hit Deploy in hPanel, or set up the webhook so pushes deploy themselves.

**Option B, upload.** Zip the *contents* of this folder (so `index.php` is at the
top of the zip, not a folder containing it) and upload through the hPanel file
manager.

### After the first deploy

- Set PHP to 8.1 or newer in hPanel.
- Confirm `quote-uploads/` and `logs/` exist and are writable (`755`). Both carry
  their own `.htaccess` denying web access, and the handler recreates them if missing.
- Send a real test enquiry and check it arrives. If mail does not arrive, create
  the mailbox `website@cateringtrailersnw.co.uk` in hPanel, because that is the
  envelope sender and some hosts reject mail from an address that does not exist.
- Submit `https://cateringtrailersnw.co.uk/sitemap.xml` to Google Search Console.

---

## The form

`quote-handler.php` validates every field, checks uploads by their actual content
type rather than their filename, stores them under generated names in
`quote-uploads/`, and emails the enquiry with the photos attached.

It also writes every submission to `logs/enquiries.log` whether or not the email
succeeds, so nothing is ever lost to a mail problem. That log contains customer
contact details: it is blocked from the web, and it should be cleared periodically.

Protections in place: a honeypot field, a minimum completion time, a per-IP rate
limit of five an hour, header-injection escaping on everything that touches the
email, and `php_flag engine off` in the uploads directory.

---

## Testing

`review/` sits outside this folder and never deploys. It holds the raw generated
footage, the source photographs, and the test harnesses:

```bash
# serve locally (the built-in server needs the router, it ignores .htaccess)
php -S 127.0.0.1:8321 -t site review/router.php

# then, with Chrome running headless on port 9222
node review/cdp-test.mjs      # 32 checks: scrub, flick test, gates, SEO, a11y
node review/legibility.mjs    # worst-frame contrast under every hero caption
node review/form-test.mjs     # walks the five-step form
node review/spec-test.mjs     # the spec builder
```
