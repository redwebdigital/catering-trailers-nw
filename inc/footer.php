<?php
/** Shared footer, sticky mobile call to action, and scripts. */
declare(strict_types=1);

$a = $CFG['address'];
$addressLine = trim($a['street'] . ', ' . $a['locality'] . ', ' . $a['postcode']);
?>
</main>

<?php
/* A page that ends with its own tailored call to action suppresses this one,
   rather than closing with two competing asks stacked on top of each other. */
$hideCta  = !empty($PAGE['hide_cta']);
$noPhone  = !empty($PAGE['no_phone']);
?>
<?php if (!$hideCta): ?>
<section class="cta band" aria-labelledby="cta-h">
  <div class="wrap cta__in rise">
    <p class="kicker" style="justify-content:center">Tell us what you need</p>
    <h2 id="cta-h">Get a proper quote, not a guess</h2>
    <p>Send us your sizes, your menu and your budget and we will come back with a real
       figure and a real date.<?= has_phone() ? ' If your trailer is off the road today, call instead and we will pick up.' : '' ?></p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn--accent btn--lg" href="/request-a-quote">Request a Quote</a>
      <?php if (has_phone()): ?>
      <a class="btn btn--ghost btn--lg" href="<?= e(tel_href()) ?>" data-track="call-cta">
        Call <?= e($CFG['phone_display']) ?>
      </a>
      <?php endif; ?>
      <a class="btn btn--wa btn--lg" href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">
        WhatsApp us
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<footer class="foot">
  <div class="wrap">
    <div class="foot__grid">

      <div class="foot__brand">
        <picture><source srcset="/assets/img/logo.webp" type="image/webp"><img src="/assets/img/logo.png" alt="<?= e($CFG['name']) ?>" width="210" height="40" loading="lazy" decoding="async"></picture>
        <p><?= e(copytext('footer_text', 'Bespoke catering trailers built in the North West, plus repairs, refits and accident work on any make of trailer.')) ?></p>
      </div>

      <div>
        <h3 class="foot__h">What we do</h3>
        <ul>
          <li><a href="/new-catering-trailers">New Catering Trailers</a></li>
          <li><a href="/catering-trailer-repairs">Trailer Repairs</a></li>
          <li><a href="/refurbishments-upgrades">Refurbishments &amp; Upgrades</a></li>
          <li><a href="/gallery">Our Builds</a></li>
        </ul>
      </div>

      <div>
        <h3 class="foot__h">Company</h3>
        <ul>
          <li><a href="/about">About Us</a></li>
          <li><a href="/faqs">FAQs</a></li>
          <li><a href="/blog">Blog</a></li>
          <li><a href="/contact">Contact Us</a></li>
          <li><a href="/request-a-quote">Request a Quote</a></li>
        </ul>
      </div>

      <div>
        <h3 class="foot__h">Get in touch</h3>
        <ul>
          <?php if (has_phone()): ?>
            <li><a href="<?= e(tel_href()) ?>" data-track="call-footer"><?= e($CFG['phone_display']) ?></a></li>
          <?php endif; ?>
          <li><a href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a></li>
          <li><a href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
          <?php if (!empty($CFG['mobile'])): ?>
            <li><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string)$CFG['mobile'])) ?>">
              <?= e($CFG['mobile']) ?> (mobile)</a></li>
          <?php endif; ?>
        </ul>

        <?php
        $nets = array_filter([
            'Facebook'  => $CFG['social']['facebook']  ?? '',
            'Instagram' => $CFG['social']['instagram'] ?? '',
            'TikTok'    => $CFG['social']['tiktok']    ?? '',
            'YouTube'   => $CFG['social']['youtube']   ?? '',
            'LinkedIn'  => $CFG['social']['linkedin']  ?? '',
        ]);
        if ($nets): ?>
          <h3 class="foot__h" style="margin-top:1.6rem">Follow us</h3>
          <ul style="display:flex;flex-wrap:wrap;gap:.5rem 1.1rem">
            <?php foreach ($nets as $label => $href): ?>
              <li><a href="<?= e($href) ?>" target="_blank" rel="noopener me"><?= e($label) ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <h3 class="foot__h" style="margin-top:1.6rem">Workshop</h3>
        <address style="font-style:normal;font-size:.95rem;color:var(--text-secondary)">
          <?= e($a['street']) ?><br>
          <?= e($a['locality']) ?><br>
          <?= e($a['postcode']) ?>
        </address>
      </div>

    </div>

    <div style="margin-top:2.4rem">
      <h3 class="foot__h">Areas we cover</h3>
      <ul style="display:flex;flex-wrap:wrap;gap:.5rem 1.4rem;list-style:none;margin:0;padding:0">
        <?php foreach ($CFG['areas'] as $slug => $area): ?>
          <li><a href="/areas/catering-trailers-<?= e($slug) ?>">Catering Trailers <?= e($area['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="foot__legal">
      <p>&copy; <?= date('Y') ?> <?= e($CFG['legal_name']) ?>. All rights reserved.</p>
      <p>
        <?php if ($CFG['company_number']): ?>
          Company no. <?= e($CFG['company_number']) ?>.
        <?php endif; ?>
        <?php if ($CFG['vat_number']): ?>
          VAT no. <?= e($CFG['vat_number']) ?>.
        <?php endif; ?>
        <a href="/privacy">Privacy</a>
      </p>
    </div>

    <?php if ($CFG['show_imagery_notice']): ?>
      <p class="foot__notice">
        Some interior imagery on this website is illustrative and shows the
        specification we build to. Exterior photographs are of our own trailers.
      </p>
    <?php endif; ?>
  </div>
</footer>

<!-- sticky mobile call to action -->
<?php $stickyPhone = !$noPhone && has_phone(); ?>
<div class="sticky<?= $stickyPhone ? '' : ' sticky--quote' ?>" id="sticky" aria-label="Quick contact">
  <?php if ($stickyPhone): ?>
  <a class="s-call" href="<?= e(tel_href()) ?>" data-track="call-sticky">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/></svg>
    Call
  </a>
  <a class="s-wa" href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15L2 22l5.2-1.4A10 10 0 1 0 12 2m5.1 14.1c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.6-.1-.4-.1-.9-.3-1.5-.5-2.6-1.1-4.3-3.7-4.4-3.9s-1-1.4-1-2.7.6-1.9.9-2.1c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.3 0 .5l-.3.4-.3.3c-.1.1-.3.3-.1.6s.6 1 1.3 1.6c.9.8 1.6 1 1.9 1.2s.4.1.6-.1l.8-1c.2-.2.3-.2.6-.1l2 .9c.2.1.4.2.4.3s0 .7-.2 1.3"/></svg>
    WhatsApp
  </a>
  <?php endif; ?>
  <a class="s-quote" href="<?= e($PAGE['cta_href'] ?? '/request-a-quote') ?>"><?= e($PAGE['sticky_label'] ?? 'Get a Quote') ?></a>
</div>

<?php if ($gtm = setting('track.gtm')): ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e((string)$gtm) ?>"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php endif; ?>
<?= (string)setting('track.custom_body', '') ?>

<script src="/assets/js/site.js?v=1" defer></script>
<?php if ($PAGE['hero_scrub']): ?>
  <script src="/assets/js/hero.js?v=1" defer></script>
<?php endif; ?>

</body>
</html>
