<?php
declare(strict_types=1);
$CURRENT = '';
$TITLE   = 'Dashboard';

require_once __DIR__ . '/inc/head.php';

$total   = (int)q_val("SELECT COUNT(*) FROM enquiries");
$new     = (int)q_val("SELECT COUNT(*) FROM enquiries WHERE status = 'New'");
$won     = (int)q_val("SELECT COUNT(*) FROM enquiries WHERE status = 'Won'");
$week    = (int)q_val("SELECT COUNT(*) FROM enquiries WHERE created_at > ?", [date('c', strtotime('-7 days'))]);
$recent  = q_all("SELECT * FROM enquiries ORDER BY id DESC LIMIT 8");
$SUBTITLE = null;

/* Things worth telling the owner about, rather than making them go looking. */
$todo = [];
if (str_contains((string)$CFG['phone_display'], '000 000')) {
    $todo[] = ['Your phone number is still the placeholder', 'It appears on every page and in your Google listing.', '/admin/business.php'];
}
if (!setting('seo.google_place_id')) {
    $todo[] = ['Google reviews are not connected', 'Add your Place ID and real reviews appear on the homepage.', '/admin/settings.php'];
}
if (!setting('track.ga4') && !setting('track.gtm')) {
    $todo[] = ['No analytics installed', 'You cannot tell which pages bring enquiries without it.', '/admin/tracking.php'];
}
$noAlt = (int)q_val("SELECT COUNT(*) FROM media WHERE alt = '' OR alt IS NULL");
if ($noAlt > 0) {
    $todo[] = [$noAlt . ' image' . ($noAlt === 1 ? '' : 's') . ' without alt text', 'Bad for accessibility and for Google Images.', '/admin/media.php'];
}
if (is_file(dirname(__DIR__) . '/admin/install.php')) {
    $todo[] = ['The installer is still on the server', 'It locks itself, but delete admin/install.php anyway.', null];
}
?>

<div class="grid grid--4" style="margin-bottom:1.4rem">
  <div class="stat <?= $new ? 'stat--accent' : '' ?>"><b><?= $new ?></b><span>new, not yet contacted</span></div>
  <div class="stat"><b><?= $week ?></b><span>in the last 7 days</span></div>
  <div class="stat"><b><?= $total ?></b><span>enquiries all time</span></div>
  <div class="stat"><b><?= $won ?></b><span>marked won</span></div>
</div>

<?php if ($todo): ?>
  <div class="card">
    <h2>Worth doing</h2>
    <?php foreach ($todo as [$what, $why, $link]): ?>
      <div style="display:flex;gap:1rem;align-items:baseline;padding:.6rem 0;border-top:1px solid var(--line)">
        <div style="flex:1">
          <strong><?= e($what) ?></strong><br>
          <span class="muted" style="font-size:.9rem"><?= e($why) ?></span>
        </div>
        <?php if ($link): ?>
          <a class="btn btn--ghost btn--sm" href="<?= e($link) ?>">Fix</a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<div class="card">
  <h2>Latest enquiries</h2>
  <?php if (!$recent): ?>
    <p class="muted">None yet. They appear here the moment somebody sends one, and are saved
       even if the notification email fails.</p>
  <?php else: ?>
    <div class="tablewrap">
      <table>
        <thead><tr><th>When</th><th>Name</th><th>Wants</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recent as $r): ?>
          <tr>
            <td class="num"><?= e(date('j M, H:i', strtotime((string)$r['created_at']))) ?></td>
            <td><strong><?= e($r['name'] ?: '—') ?></strong></td>
            <td><?= e(trim(implode(' · ', array_filter([$r['job_type'], $r['body_length'], $r['axle']])))) ?: '—' ?></td>
            <td><span class="status status--<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
            <td class="right"><a class="btn btn--ghost btn--sm" href="/admin/enquiries.php?id=<?= (int)$r['id'] ?>">Open</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<div class="grid grid--2">
  <div class="card">
    <h2>Quick edits</h2>
    <div class="btnrow">
      <a class="btn btn--ghost btn--sm" href="/admin/business.php">Phone &amp; address</a>
      <a class="btn btn--ghost btn--sm" href="/admin/builder.php">Quote builder</a>
      <a class="btn btn--ghost btn--sm" href="/admin/content.php">Homepage wording</a>
      <a class="btn btn--ghost btn--sm" href="/admin/seo.php">Page titles</a>
    </div>
  </div>
  <div class="card">
    <h2>Your website</h2>
    <p class="muted" style="font-size:.92rem">Everything you change here is live immediately. There
       is no publish step and no deploy needed for settings.</p>
    <a class="btn btn--accent btn--sm" href="/" target="_blank" rel="noopener">View the site ↗</a>
  </div>
</div>

<?php require __DIR__ . '/inc/foot.php'; ?>
