<?php
declare(strict_types=1);
$CURRENT = 'enquiries';
$TITLE   = 'Enquiries';

require_once __DIR__ . '/inc/head.php';

const STATUSES = ['New', 'Contacted', 'Quoted', 'Won', 'Lost'];

/* ---------------------------------------------------------------- actions */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $do = (string)($_POST['do'] ?? '');

    if ($do === 'status' && $id) {
        $st = (string)($_POST['status'] ?? 'New');
        if (in_array($st, STATUSES, true)) {
            q("UPDATE enquiries SET status = ? WHERE id = ?", [$st, $id]);
            flash('Marked as ' . $st . '.');
        }
    } elseif ($do === 'note' && $id) {
        $body = trim((string)($_POST['body'] ?? ''));
        if ($body !== '') {
            q("INSERT INTO enquiry_notes (enquiry_id, created_at, body) VALUES (?,?,?)",
              [$id, date('c'), mb_substr($body, 0, 5000)]);
            flash('Note added.');
        }
    } elseif ($do === 'delete' && $id) {
        q("DELETE FROM enquiry_notes WHERE enquiry_id = ?", [$id]);
        q("DELETE FROM enquiries WHERE id = ?", [$id]);
        flash('Enquiry deleted.', 'warn');
        header('Location: /admin/enquiries.php'); exit;
    }
    header('Location: /admin/enquiries.php' . ($id ? '?id=' . $id : '')); exit;
}

$viewId = (int)($_GET['id'] ?? 0);

/* ---------------------------------------------------------------- detail */
if ($viewId) {
    $enq = q_one("SELECT * FROM enquiries WHERE id = ?", [$viewId]);
    if (!$enq) { echo '<div class="note note--err">That enquiry no longer exists.</div>';
                 require __DIR__ . '/inc/foot.php'; exit; }
    $notes = q_all("SELECT * FROM enquiry_notes WHERE enquiry_id = ? ORDER BY id DESC", [$viewId]);
    $files = array_filter(explode('|', (string)$enq['files']));
    ?>
    <p><a class="btn btn--ghost btn--sm" href="/admin/enquiries.php">&larr; All enquiries</a></p>

    <div class="grid grid--2">
      <div>
        <div class="card">
          <h2 style="margin-bottom:.2rem"><?= e($enq['name'] ?: 'No name given') ?></h2>
          <p class="muted mono" style="font-size:.8rem">
            <?= e(date('D j M Y, H:i', strtotime((string)$enq['created_at']))) ?>
            · <?= e($enq['source']) ?> · #<?= (int)$enq['id'] ?>
          </p>

          <div class="grid grid--2" style="margin-top:1.1rem">
            <div>
              <h3>Contact</h3>
              <p style="margin:0">
                <?php if ($enq['phone']): ?>
                  <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', (string)$enq['phone'])) ?>"><?= e($enq['phone']) ?></a><br>
                <?php endif; ?>
                <?php if ($enq['email']): ?>
                  <a href="mailto:<?= e($enq['email']) ?>"><?= e($enq['email']) ?></a><br>
                <?php endif; ?>
                <?= $enq['town'] ? e($enq['town']) : '' ?>
              </p>
            </div>
            <div>
              <h3>The job</h3>
              <p style="margin:0" class="muted">
                <?= $enq['job_type']    ? e($enq['job_type']) . '<br>' : '' ?>
                <?= $enq['body_length'] ? 'Length: ' . e($enq['body_length']) . '<br>' : '' ?>
                <?= $enq['axle']        ? 'Axle: ' . e($enq['axle']) . '<br>' : '' ?>
                <?= $enq['fit_out']     ? 'Fit-out: ' . e($enq['fit_out']) . '<br>' : '' ?>
                <?= $enq['power']       ? 'Power: ' . e($enq['power']) . '<br>' : '' ?>
                <?= $enq['budget']      ? 'Budget: ' . e($enq['budget']) . '<br>' : '' ?>
                <?= $enq['required_date'] ? 'Needed: ' . e($enq['required_date']) : '' ?>
              </p>
            </div>
          </div>

          <?php if ($enq['appliances']): ?>
            <h3 style="margin-top:1.2rem">Appliances</h3>
            <p class="muted" style="margin:0"><?= e($enq['appliances']) ?></p>
          <?php endif; ?>

          <?php if ($enq['message']): ?>
            <h3 style="margin-top:1.2rem">Message</h3>
            <p style="white-space:pre-wrap;margin:0"><?= e($enq['message']) ?></p>
          <?php endif; ?>

          <?php if (trim((string)$enq['extra']) !== ''): ?>
            <h3 style="margin-top:1.2rem">Everything else they told us</h3>
            <p style="white-space:pre-wrap;margin:0"><?= e($enq['extra']) ?></p>
          <?php endif; ?>

          <?php if ($files): ?>
            <h3 style="margin-top:1.2rem">Photos</h3>
            <p class="muted" style="font-size:.86rem">Stored out of public reach. Open from the file manager at <span class="mono">quote-uploads/</span>.</p>
            <ul class="mono" style="font-size:.8rem">
              <?php foreach ($files as $f): ?><li><?= e($f) ?></li><?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <p class="muted mono" style="font-size:.76rem;margin-top:1.4rem">
            email sent: <?= $enq['mailed'] ? 'yes' : 'no'; ?> · ip <?= e((string)$enq['ip']) ?>
          </p>
        </div>

        <div class="card">
          <h2>Notes</h2>
          <form method="post" style="margin-bottom:1rem">
            <?= csrf_field() ?>
            <input type="hidden" name="do" value="note">
            <input type="hidden" name="id" value="<?= (int)$enq['id'] ?>">
            <div class="field">
              <label for="body" class="sr">Note</label>
              <textarea class="textarea" id="body" name="body" rows="3"
                        placeholder="Called and left a message. Sending drawings Tuesday."></textarea>
            </div>
            <button class="btn btn--accent btn--sm" type="submit">Add note</button>
          </form>

          <?php if (!$notes): ?>
            <p class="muted">No notes yet.</p>
          <?php else: foreach ($notes as $n): ?>
            <div style="border-top:1px solid var(--line);padding:.8rem 0">
              <p class="muted mono" style="font-size:.74rem;margin:0 0 .3rem">
                <?= e(date('j M Y, H:i', strtotime((string)$n['created_at']))) ?></p>
              <p style="margin:0;white-space:pre-wrap"><?= e($n['body']) ?></p>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <div>
        <div class="card">
          <h2>Status</h2>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="do" value="status">
            <input type="hidden" name="id" value="<?= (int)$enq['id'] ?>">
            <div class="field">
              <label for="status" class="sr">Status</label>
              <select class="select" id="status" name="status" onchange="this.form.submit()">
                <?php foreach (STATUSES as $s): ?>
                  <option value="<?= e($s) ?>"<?= $enq['status'] === $s ? ' selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
              <span class="hint">Saves as soon as you change it.</span>
            </div>
            <noscript><button class="btn btn--accent btn--sm" type="submit">Update</button></noscript>
          </form>
        </div>

        <div class="card">
          <h2>Reply</h2>
          <p class="card__hint">Opens your email program with the address filled in.</p>
          <a class="btn btn--ghost" href="mailto:<?= e((string)$enq['email']) ?>?subject=<?= rawurlencode('Your catering trailer enquiry') ?>">Email <?= e($enq['name'] ?: 'them') ?></a>
        </div>

        <div class="card">
          <h2>Delete</h2>
          <p class="card__hint">Removes the enquiry and its notes for good. There is no undo.</p>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="do" value="delete">
            <input type="hidden" name="id" value="<?= (int)$enq['id'] ?>">
            <button class="btn btn--danger btn--sm" type="submit"
                    data-confirm="Delete this enquiry and all its notes? This cannot be undone.">Delete enquiry</button>
          </form>
        </div>
      </div>
    </div>
    <?php
    require __DIR__ . '/inc/foot.php';
    exit;
}

/* ------------------------------------------------------------------ list */
$filter = (string)($_GET['status'] ?? '');
$where  = in_array($filter, STATUSES, true) ? "WHERE status = ?" : '';
$args   = $where ? [$filter] : [];
$rows   = q_all("SELECT * FROM enquiries $where ORDER BY id DESC LIMIT 300", $args);

$counts = [];
foreach (q_all("SELECT status, COUNT(*) c FROM enquiries GROUP BY status") as $r) {
    $counts[$r['status']] = (int)$r['c'];
}
?>

<div class="btnrow" style="margin-bottom:1.2rem">
  <a class="btn btn--sm <?= $filter === '' ? 'btn--accent' : 'btn--ghost' ?>" href="/admin/enquiries.php">
    All <?= array_sum($counts) ?></a>
  <?php foreach (STATUSES as $s): ?>
    <a class="btn btn--sm <?= $filter === $s ? 'btn--accent' : 'btn--ghost' ?>"
       href="/admin/enquiries.php?status=<?= e($s) ?>"><?= e($s) ?> <?= (int)($counts[$s] ?? 0) ?></a>
  <?php endforeach; ?>
</div>

<?php if (!$rows): ?>
  <div class="card">
    <h2>Nothing here yet</h2>
    <p class="muted">Enquiries from the quote builder and the contact forms land here the moment
       somebody sends one. They are saved even if the notification email fails, so nothing is
       ever lost to a mail problem.</p>
  </div>
<?php else: ?>
  <div class="tablewrap">
    <table>
      <thead>
        <tr><th>When</th><th>Name</th><th>Contact</th><th>Wants</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td class="num"><?= e(date('j M y', strtotime((string)$r['created_at']))) ?><br>
                <span class="muted"><?= e(date('H:i', strtotime((string)$r['created_at']))) ?></span></td>
            <td><strong><?= e($r['name'] ?: '—') ?></strong>
                <?= $r['town'] ? '<br><span class="muted">' . e($r['town']) . '</span>' : '' ?></td>
            <td class="num"><?= e($r['phone']) ?><br><span class="muted"><?= e($r['email']) ?></span></td>
            <td><?= e(trim(implode(' · ', array_filter([$r['job_type'], $r['body_length'], $r['axle']])))) ?>
                <?= $r['fit_out'] ? '<br><span class="muted">' . e($r['fit_out']) . '</span>' : '' ?></td>
            <td><span class="status status--<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
            <td class="right"><a class="btn btn--ghost btn--sm" href="/admin/enquiries.php?id=<?= (int)$r['id'] ?>">Open</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/inc/foot.php'; ?>
