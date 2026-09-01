<?php
declare(strict_types=1);
$CURRENT  = 'hire';
$TITLE    = 'Trailer Hire';
$SUBTITLE = 'The hire page, its wording, the types you offer and the questions people ask. Turn the whole page off here if you stop offering hire.';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

const HIRE_SLUG = '/catering-trailer-hire';

/* ------------------------------------------------- hire types and FAQs --
   Handled before settings_page(), which redirects as soon as it sees a POST. */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') !== '') {
    csrf_check();
    $do = (string)$_POST['do'];

    if ($do === 'types') {
        foreach ((array)($_POST['type'] ?? []) as $id => $row) {
            $label = trim(mb_substr((string)($row['label'] ?? ''), 0, 120));
            $on    = isset($row['enabled']) ? 1 : 0;
            $sort  = (int)($row['sort_order'] ?? 0);

            if (str_starts_with((string)$id, 'new')) {
                if ($label !== '') {
                    q("INSERT INTO builder_options (group_key, label, value, enabled, sort_order)
                       VALUES ('hire_type', ?, ?, ?, ?)", [$label, $label, $on, $sort]);
                }
                continue;
            }
            if (!empty($row['delete'])) {
                q("DELETE FROM builder_options WHERE id = ? AND group_key = 'hire_type'", [(int)$id]);
                continue;
            }
            if ($label === '') continue;
            q("UPDATE builder_options SET label = ?, value = ?, enabled = ?, sort_order = ?
               WHERE id = ? AND group_key = 'hire_type'", [$label, $label, $on, $sort, (int)$id]);
        }
        flash('Hire types updated.');
    }

    if ($do === 'faqs') {
        foreach ((array)($_POST['faq'] ?? []) as $id => $row) {
            $qq   = trim(mb_substr((string)($row['q'] ?? ''), 0, 300));
            $aa   = trim(mb_substr((string)($row['a'] ?? ''), 0, 4000));
            $on   = isset($row['enabled']) ? 1 : 0;
            $sort = (int)($row['sort_order'] ?? 0);

            if (str_starts_with((string)$id, 'new')) {
                if ($qq !== '' && $aa !== '') {
                    q("INSERT INTO page_faqs (page_slug, q, a, enabled, sort_order)
                       VALUES (?,?,?,?,?)", [HIRE_SLUG, $qq, $aa, $on, $sort]);
                }
                continue;
            }
            if (!empty($row['delete'])) {
                q("DELETE FROM page_faqs WHERE id = ? AND page_slug = ?", [(int)$id, HIRE_SLUG]);
                continue;
            }
            if ($qq === '') continue;
            q("UPDATE page_faqs SET q = ?, a = ?, enabled = ?, sort_order = ?
               WHERE id = ? AND page_slug = ?", [$qq, $aa, $on, $sort, (int)$id, HIRE_SLUG]);
        }
        flash('Questions updated. Only the visible ones go into the FAQ markup Google reads.');
    }

    header('Location: /admin/hire.php');
    exit;
}

/* -------------------------------------------------------------- settings */
$fields = [
  '_h0' => ['label' => 'The page', 'type' => 'heading'],
  'hire.enabled' => ['label' => 'Show the trailer hire page', 'type' => 'checkbox',
      'hint' => 'Turn this off and the page returns "not found", drops out of the sitemap and disappears from the menu.'],
  'hire.email' => ['label' => 'Send hire enquiries to', 'type' => 'email',
      'hint' => 'Leave blank to use the usual enquiry inbox. Useful if hire is handled by someone else.'],

  '_h1' => ['label' => 'Opening', 'type' => 'heading',
      'hint' => 'The first screen. Say what you hire and who it suits.'],
  'content.hire_h1'      => ['label' => 'Main heading (H1)', 'type' => 'text', 'count' => 70],
  'content.hire_intro_1' => ['label' => 'Opening line', 'type' => 'textarea', 'rows' => 2, 'count' => 180],
  'content.hire_intro_2' => ['label' => 'Second line',  'type' => 'textarea', 'rows' => 2, 'count' => 200],
  'content.hire_intro_3' => ['label' => 'Third line',   'type' => 'textarea', 'rows' => 2, 'count' => 180],

  '_h2' => ['label' => 'Buttons and pricing wording', 'type' => 'heading'],
  'content.hire_cta_primary' => ['label' => 'Main button text', 'type' => 'text', 'count' => 24],
  'content.hire_pricing_note' => ['label' => 'What you say about price', 'type' => 'textarea', 'rows' => 3,
      'hint' => 'Shown instead of a price list. Only put figures here if you are willing to be held to them.'],

  '_h3' => ['label' => 'Where you cover', 'type' => 'heading',
      'hint' => 'One place per line. These appear on the page and nowhere else, so keep them honest.'],
  'hire.areas' => ['label' => 'Areas', 'type' => 'textarea', 'rows' => 7],
];

settings_page('hire', $fields, 'Hire page saved and live.');

$types = q_all("SELECT * FROM builder_options WHERE group_key = 'hire_type' ORDER BY sort_order, id");
$faqs  = q_all("SELECT * FROM page_faqs WHERE page_slug = ? ORDER BY sort_order, id", [HIRE_SLUG]);
$off   = (string)setting('hire.enabled', '1') === '0';
?>

<?php if ($off): ?>
  <div class="note note--warn">
    <strong>The hire page is currently switched off.</strong> Visitors get a "not found" page
    and it has been removed from the sitemap and the menu.
  </div>
<?php endif; ?>

<form method="post" data-warn>
  <?= csrf_field() ?>
  <div class="card">
    <?php render_fields($fields); ?>
    <?php save_bar(); ?>
  </div>
</form>

<div class="card">
  <h2>Page title and description</h2>
  <p class="card__hint">
    The SEO title, meta description, social sharing image and whether Google may index this
    page are edited with every other page, under
    <a href="/admin/seo.php">Pages &amp; SEO</a>. It is one list so nothing gets forgotten.
  </p>
</div>

<!-- ------------------------------------------------------- hire types -->
<form method="post" data-warn>
  <?= csrf_field() ?>
  <input type="hidden" name="do" value="types">
  <div class="card">
    <h2>What people can ask to hire</h2>
    <p class="card__hint">
      These fill the dropdown on the hire enquiry form. Add or remove them here and the form
      changes with them — nothing needs rebuilding.
    </p>

    <div class="tablewrap">
      <table>
        <thead><tr><th>Name</th><th style="width:6rem">Order</th><th style="width:6rem">Shown</th><th style="width:5rem">Delete</th></tr></thead>
        <tbody>
          <?php foreach ($types as $t): ?>
            <tr>
              <td><input class="input" type="text" name="type[<?= (int)$t['id'] ?>][label]"
                         value="<?= e((string)$t['label']) ?>" maxlength="120"></td>
              <td><input class="input" type="number" name="type[<?= (int)$t['id'] ?>][sort_order]"
                         value="<?= (int)$t['sort_order'] ?>"></td>
              <td style="text-align:center"><input type="checkbox" name="type[<?= (int)$t['id'] ?>][enabled]"
                         value="1"<?= $t['enabled'] ? ' checked' : '' ?>></td>
              <td style="text-align:center"><input type="checkbox" name="type[<?= (int)$t['id'] ?>][delete]" value="1"></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td><input class="input" type="text" name="type[new1][label]" placeholder="Add another type" maxlength="120"></td>
            <td><input class="input" type="number" name="type[new1][sort_order]" value="<?= count($types) + 1 ?>"></td>
            <td style="text-align:center"><input type="checkbox" name="type[new1][enabled]" value="1" checked></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php save_bar('Save hire types'); ?>
  </div>
</form>

<!-- ------------------------------------------------------------- FAQs -->
<form method="post" data-warn>
  <?= csrf_field() ?>
  <input type="hidden" name="do" value="faqs">
  <div class="card">
    <h2>Questions people ask</h2>
    <p class="card__hint">
      These show on the page and are the only ones described to Google, so an answer here has
      to match what a visitor can actually read. Answer plainly and avoid promising
      availability.
    </p>

    <?php foreach ($faqs as $f): ?>
      <div class="field" style="border-top:1px solid var(--line);padding-top:1rem">
        <label for="faq_q_<?= (int)$f['id'] ?>">Question</label>
        <input class="input" type="text" id="faq_q_<?= (int)$f['id'] ?>"
               name="faq[<?= (int)$f['id'] ?>][q]" value="<?= e((string)$f['q']) ?>" maxlength="300">
        <label for="faq_a_<?= (int)$f['id'] ?>" style="margin-top:.6rem">Answer</label>
        <textarea class="textarea" id="faq_a_<?= (int)$f['id'] ?>" rows="3"
                  name="faq[<?= (int)$f['id'] ?>][a]" maxlength="4000"><?= e((string)$f['a']) ?></textarea>
        <div style="display:flex;gap:1.4rem;align-items:center;margin-top:.55rem;flex-wrap:wrap">
          <label class="check"><input type="checkbox" name="faq[<?= (int)$f['id'] ?>][enabled]"
                 value="1"<?= $f['enabled'] ? ' checked' : '' ?>><span>Show this one</span></label>
          <label class="check"><input type="checkbox" name="faq[<?= (int)$f['id'] ?>][delete]" value="1">
                 <span>Delete</span></label>
          <span class="hint">Order
            <input class="input" type="number" style="width:5rem;display:inline-block"
                   name="faq[<?= (int)$f['id'] ?>][sort_order]" value="<?= (int)$f['sort_order'] ?>"></span>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="field" style="border-top:2px solid var(--line);padding-top:1rem">
      <label for="faq_q_new">Add a question</label>
      <input class="input" type="text" id="faq_q_new" name="faq[new1][q]" maxlength="300"
             placeholder="Can I hire a trailer with a generator?">
      <label for="faq_a_new" style="margin-top:.6rem">Answer</label>
      <textarea class="textarea" id="faq_a_new" rows="3" name="faq[new1][a]" maxlength="4000"></textarea>
      <input type="hidden" name="faq[new1][enabled]" value="1">
      <input type="hidden" name="faq[new1][sort_order]" value="<?= count($faqs) + 1 ?>">
    </div>

    <?php save_bar('Save questions'); ?>
  </div>
</form>

<?php require_once __DIR__ . '/inc/foot.php'; ?>
