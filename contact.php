<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/bootstrap.php';

$a = $CFG['address'];

$PAGE = [
  'title'       => 'Contact Us | Catering Trailers NW | ' . $a['locality'],
  'description' => 'Call, WhatsApp or email Catering Trailers NW about a new catering trailer, a repair or a refit. Based in ' . $a['locality'] . ', covering the North West.',
  'path'        => '/contact',
  'nav'         => 'contact',
  'schema'      => [
    schema_breadcrumbs(['Home' => '/', 'Contact Us' => '/contact']),
    ['@type' => 'ContactPage', 'url' => url('/contact'), 'mainEntity' => ['@id' => url('/#business')]],
  ],
];

require __DIR__ . '/inc/header.php';
?>

<section class="band band--tight">
  <div class="wrap">
    <div class="rise" style="max-width:62ch">
      <p class="kicker">Contact us</p>
      <h1>Talk to someone who builds them</h1>
      <p class="lede">No call centre and no contact form that disappears into nothing.
         Call and you get the workshop.</p>
    </div>
  </div>
</section>

<section class="band" style="padding-top:0">
  <div class="wrap">
    <div class="process rise">

      <div>
        <div class="why" style="grid-template-columns:1fr">
          <div class="why__item">
            <span class="why__n">Fastest</span>
            <h3>Call us</h3>
            <p>Best for repairs and anything urgent. If your trailer is off the road,
               this is the one to use.</p>
            <a class="btn btn--accent" style="justify-self:start;margin-top:.5rem"
               href="<?= e(tel_href()) ?>" data-track="call-contact"><?= e($CFG['phone_display']) ?></a>
          </div>
          <div class="why__item">
            <span class="why__n">Easiest</span>
            <h3>WhatsApp</h3>
            <p>Best for sending photographs of damage. A wide shot and a close-up of the
               problem tells us more than ten minutes on the phone.</p>
            <a class="btn btn--wa" style="justify-self:start;margin-top:.5rem"
               href="<?= e(whatsapp_href()) ?>" target="_blank" rel="noopener">Message us</a>
          </div>
          <div class="why__item">
            <span class="why__n">Detailed</span>
            <h3>Email</h3>
            <p>Best for specifications, drawings and insurance paperwork.</p>
            <a class="btn btn--ghost" style="justify-self:start;margin-top:.5rem"
               href="mailto:<?= e($CFG['email']) ?>"><?= e($CFG['email']) ?></a>
          </div>
          <div class="why__item">
            <span class="why__n">Best</span>
            <h3>Send us your spec</h3>
            <p>Five short steps and we come back with a real price and a real build date,
               usually within one working day.</p>
            <a class="btn btn--accent" style="justify-self:start;margin-top:.5rem"
               href="/request-a-quote">Request a Quote</a>
          </div>
        </div>
      </div>

      <div>
        <h2 style="font-size:clamp(1.3rem,1.1rem + 1vw,1.75rem)">The workshop</h2>
        <address style="font-style:normal;font-size:1.06rem;line-height:1.8;color:var(--text-secondary);margin:1rem 0 1.6rem">
          <?= e($CFG['name']) ?><br>
          <?= e($a['street']) ?><br>
          <?= e($a['locality']) ?><br>
          <?= e($a['region']) ?><br>
          <?= e($a['postcode']) ?>
        </address>

        <h2 style="font-size:clamp(1.3rem,1.1rem + 1vw,1.75rem);margin-top:2rem">Opening hours</h2>
        <table style="width:100%;border-collapse:collapse;margin-top:1rem;font-size:.98rem">
          <tbody>
          <?php foreach ($CFG['hours_display'] as $days => $time): ?>
            <tr style="border-bottom:1px solid var(--hairline)">
              <th scope="row" style="text-align:left;padding:.7rem 0;font-weight:600"><?= e($days) ?></th>
              <td style="text-align:right;padding:.7rem 0;color:var(--text-secondary);font-family:var(--mono);font-size:.9rem"><?= e($time) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <p class="lede" style="margin-top:1.6rem;font-size:1rem">
          Visiting the workshop is welcome, but ring first. We are often out delivering or
          collecting and it is a wasted trip if nobody is in.
        </p>

        <h2 style="font-size:clamp(1.3rem,1.1rem + 1vw,1.75rem);margin-top:2rem">Areas we cover</h2>
        <div class="areas" style="margin-top:1rem;grid-template-columns:repeat(auto-fit,minmax(160px,1fr))">
          <?php foreach ($CFG['areas'] as $slug => $area): ?>
            <a class="area" href="/areas/catering-trailers-<?= e($slug) ?>"><b><?= e($area['name']) ?></b></a>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
