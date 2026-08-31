<?php
declare(strict_types=1);
$CURRENT = 'builder';
$TITLE   = 'Quote Builder';
$SUBTITLE = 'Everything in the configurator on your homepage. The website reads these options live, so nothing is hard-coded.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$GROUPS = [
    'length' => ['Body lengths', 'Shown under BODY LENGTH. The drawing width controls how long the trailer is drawn, in the drawing’s own units.'],
    'axle'   => ['Axle options', 'Shown under AXLE. Use the value "twin" on any option that should draw a second axle.'],
    'use'    => ['Fit-out / business types', 'Shown under FIT-OUT FOR. Customers can pick more than one.'],
];

/* ------------------------------------------------------------------ save */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string)($_POST['action'] ?? '');

    if ($action === 'options') {
        $rows = (array)($_POST['opt'] ?? []);
        $keep = [];
        db()->beginTransaction();
        try {
            foreach ($rows as $id => $r) {
                $label = trim((string)($r['label'] ?? ''));
                if ($label === '') continue;                    // blank row = ignore
                $group = (string)($r['group_key'] ?? '');
                if (!isset($GROUPS[$group])) continue;

                $value = trim((string)($r['value'] ?? '')) ?: $label;
                $data = [
                    $group, $label, $value,
                    trim((string)($r['price_from'] ?? '')),
                    trim((string)($r['icon'] ?? '')),
                    (int)($r['draw_width'] ?? 0),
                    isset($r['enabled']) ? 1 : 0,
                    (int)($r['sort_order'] ?? 0),
                ];
                if (str_starts_with((string)$id, 'new')) {
                    q("INSERT INTO builder_options
                       (group_key,label,value,price_from,icon,draw_width,enabled,sort_order)
                       VALUES (?,?,?,?,?,?,?,?)", $data);
                    $keep[] = (int)db()->lastInsertId();
                } else {
                    $data[] = (int)$id;
                    q("UPDATE builder_options SET group_key=?,label=?,value=?,price_from=?,icon=?,
                       draw_width=?,enabled=?,sort_order=? WHERE id=?", $data);
                    $keep[] = (int)$id;
                }
            }
            // anything the form no longer contains was removed by the user
            if ($keep) {
                $in = implode(',', array_fill(0, count($keep), '?'));
                q("DELETE FROM builder_options WHERE id NOT IN ($in)", $keep);
            } else {
                q("DELETE FROM builder_options");
            }
            db()->commit();
            flash('Configurator options saved. They are live on the website now.');
        } catch (Throwable $e) {
            db()->rollBack();
            flash('Could not save: ' . $e->getMessage(), 'err');
        }
        header('Location: /admin/builder.php'); exit;
    }

    if ($action === 'stages') {
        $rows = (array)($_POST['stage'] ?? []);
        $keep = [];
        db()->beginTransaction();
        try {
            foreach ($rows as $id => $r) {
                $title = trim((string)($r['title'] ?? ''));
                if ($title === '') continue;
                $data = [$title, trim((string)($r['body'] ?? '')),
                         isset($r['enabled']) ? 1 : 0, (int)($r['sort_order'] ?? 0)];
                if (str_starts_with((string)$id, 'new')) {
                    q("INSERT INTO builder_stages (title,body,enabled,sort_order) VALUES (?,?,?,?)", $data);
                    $keep[] = (int)db()->lastInsertId();
                } else {
                    $data[] = (int)$id;
                    q("UPDATE builder_stages SET title=?,body=?,enabled=?,sort_order=? WHERE id=?", $data);
                    $keep[] = (int)$id;
                }
            }
            if ($keep) {
                $in = implode(',', array_fill(0, count($keep), '?'));
                q("DELETE FROM builder_stages WHERE id NOT IN ($in)", $keep);
            } else {
                q("DELETE FROM builder_stages");
            }
            db()->commit();
            flash('Build stages saved.');
        } catch (Throwable $e) {
            db()->rollBack();
            flash('Could not save: ' . $e->getMessage(), 'err');
        }
        header('Location: /admin/builder.php'); exit;
    }

    if ($action === 'text') {
        settings_set_many([
            'content.builder_heading' => trim((string)($_POST['heading'] ?? '')),
            'content.builder_intro'   => trim((string)($_POST['intro'] ?? '')),
            'content.builder_button'  => trim((string)($_POST['button'] ?? '')),
            'biz.enquiry_inbox'       => trim((string)($_POST['inbox'] ?? '')),
        ], 'content');
        flash('Section wording saved.');
        header('Location: /admin/builder.php'); exit;
    }
}

$options = q_all("SELECT * FROM builder_options ORDER BY group_key, sort_order, id");
$stages  = q_all("SELECT * FROM builder_stages ORDER BY sort_order, id");
$byGroup = [];
foreach ($options as $o) { $byGroup[$o['group_key']][] = $o; }

function opt_row(array $o, string $group, $id): void { ?>
  <div class="row">
    <span class="drag" title="Drag to reorder">⠿</span>
    <input class="input" name="opt[<?= e((string)$id) ?>][label]" value="<?= e($o['label'] ?? '') ?>" placeholder="Label shown to customers">
    <input class="input" name="opt[<?= e((string)$id) ?>][value]" value="<?= e($o['value'] ?? '') ?>" placeholder="Stored value">
    <input class="input" name="opt[<?= e((string)$id) ?>][price_from]" value="<?= e($o['price_from'] ?? '') ?>" placeholder="Price from (optional)">
    <input class="input" name="opt[<?= e((string)$id) ?>][draw_width]" value="<?= e((string)($o['draw_width'] ?? 0)) ?>" placeholder="Draw" title="Drawing width, lengths only" inputmode="numeric">
    <span style="display:flex;gap:.35rem;align-items:center;justify-content:flex-end">
      <input type="checkbox" name="opt[<?= e((string)$id) ?>][enabled]" value="1" <?= ($o['enabled'] ?? 1) ? 'checked' : '' ?> title="Show on the website" style="accent-color:var(--accent)">
      <button type="button" class="btn btn--danger btn--sm" data-removerow title="Remove">&times;</button>
    </span>
    <input type="hidden" class="sortfield" name="opt[<?= e((string)$id) ?>][sort_order]" value="<?= (int)($o['sort_order'] ?? 0) ?>">
    <input type="hidden" name="opt[<?= e((string)$id) ?>][group_key]" value="<?= e($group) ?>">
    <input type="hidden" name="opt[<?= e((string)$id) ?>][icon]" value="<?= e($o['icon'] ?? '') ?>">
  </div>
<?php }
?>

<!-- ── wording ──────────────────────────────────────────────────────── -->
<form method="post" class="card" data-warn>
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="text">
  <h2>Section wording</h2>
  <p class="card__hint">The heading and introduction above the configurator on your homepage.</p>

  <div class="field">
    <label for="heading">Heading</label>
    <input class="input" id="heading" name="heading" data-count="60"
           value="<?= e((string)setting('content.builder_heading', 'From your menu to your pitch')) ?>">
  </div>
  <div class="field">
    <label for="intro">Introduction</label>
    <textarea class="textarea" id="intro" name="intro" rows="2" data-count="160"><?= e((string)setting('content.builder_intro', '')) ?></textarea>
  </div>
  <div class="grid grid--2">
    <div class="field">
      <label for="button">Button text</label>
      <input class="input" id="button" name="button" data-count="24"
             value="<?= e((string)setting('content.builder_button', 'Send this spec')) ?>">
    </div>
    <div class="field">
      <label for="inbox">Send these enquiries to</label>
      <input class="input" type="email" id="inbox" name="inbox"
             value="<?= e((string)setting('biz.enquiry_inbox', $CFG['enquiry_inbox'])) ?>">
      <span class="hint">Every enquiry is also saved in the Enquiries section, whether the email arrives or not.</span>
    </div>
  </div>
  <?php save_bar('Save wording'); ?>
</form>

<!-- ── options ──────────────────────────────────────────────────────── -->
<form method="post" data-warn>
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="options">

  <?php foreach ($GROUPS as $gk => [$gLabel, $gHint]): ?>
    <div class="card">
      <h2><?= e($gLabel) ?></h2>
      <p class="card__hint"><?= e($gHint) ?></p>

      <div class="row row--head">
        <span></span><span>Label</span><span>Value</span><span>Price from</span><span>Draw</span><span class="right">On</span>
      </div>

      <div class="rows" id="rows-<?= e($gk) ?>" data-sortable>
        <?php foreach (($byGroup[$gk] ?? []) as $o) { opt_row($o, $gk, $o['id']); } ?>
      </div>

      <template id="tpl-<?= e($gk) ?>">
        <?php opt_row(['label'=>'','value'=>'','price_from'=>'','draw_width'=>0,'enabled'=>1,'sort_order'=>99,'icon'=>''], $gk, '__IDX__'); ?>
      </template>

      <button class="btn btn--ghost btn--sm" type="button"
              data-addrow="rows-<?= e($gk) ?>" data-template="tpl-<?= e($gk) ?>">Add an option</button>
    </div>
  <?php endforeach; ?>

  <div class="sticky-save"><button class="btn btn--accent" type="submit">Save all options</button></div>
</form>

<!-- ── stages ───────────────────────────────────────────────────────── -->
<form method="post" data-warn>
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="stages">
  <div class="card">
    <h2>Build process stages</h2>
    <p class="card__hint">The numbered list beside the configurator. Drag to reorder, untick to hide one without deleting it.</p>

    <div class="rows" id="rows-stages" data-sortable>
      <?php foreach ($stages as $s): ?>
        <div class="row" style="grid-template-columns:26px 1fr 2fr 44px">
          <span class="drag" title="Drag to reorder">⠿</span>
          <input class="input" name="stage[<?= (int)$s['id'] ?>][title]" value="<?= e($s['title']) ?>" placeholder="Stage title">
          <input class="input" name="stage[<?= (int)$s['id'] ?>][body]" value="<?= e($s['body']) ?>" placeholder="What happens at this stage">
          <span style="display:flex;gap:.35rem;align-items:center;justify-content:flex-end">
            <input type="checkbox" name="stage[<?= (int)$s['id'] ?>][enabled]" value="1" <?= $s['enabled'] ? 'checked' : '' ?> style="accent-color:var(--accent)">
            <button type="button" class="btn btn--danger btn--sm" data-removerow>&times;</button>
          </span>
          <input type="hidden" class="sortfield" name="stage[<?= (int)$s['id'] ?>][sort_order]" value="<?= (int)$s['sort_order'] ?>">
        </div>
      <?php endforeach; ?>
    </div>

    <template id="tpl-stages">
      <div class="row" style="grid-template-columns:26px 1fr 2fr 44px">
        <span class="drag">⠿</span>
        <input class="input" name="stage[__IDX__][title]" value="" placeholder="Stage title">
        <input class="input" name="stage[__IDX__][body]" value="" placeholder="What happens at this stage">
        <span style="display:flex;gap:.35rem;align-items:center;justify-content:flex-end">
          <input type="checkbox" name="stage[__IDX__][enabled]" value="1" checked style="accent-color:var(--accent)">
          <button type="button" class="btn btn--danger btn--sm" data-removerow>&times;</button>
        </span>
        <input type="hidden" class="sortfield" name="stage[__IDX__][sort_order]" value="99">
      </div>
    </template>

    <button class="btn btn--ghost btn--sm" type="button" data-addrow="rows-stages" data-template="tpl-stages">Add a stage</button>
    <?php save_bar('Save stages'); ?>
  </div>
</form>

<?php require_once __DIR__ . '/inc/foot.php'; ?>
