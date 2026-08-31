<?php
declare(strict_types=1);
$CURRENT = 'seo';
$TITLE   = 'Pages & SEO';

require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/fields.php';

$SCHEMA_TYPES = ['WebPage','Service','AboutPage','ContactPage','FAQPage','CollectionPage','Blog','ItemList'];

/* ------------------------------------------------------------------ save */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);

    if (($_POST['do'] ?? '') === 'add') {
        $slug = '/' . trim(trim((string)($_POST['new_slug'] ?? '')), '/');
        $label = trim((string)($_POST['new_label'] ?? ''));
        if ($slug === '/' || $label === '') {
            flash('Give the page a name and an address.', 'err');
        } elseif (q_one("SELECT id FROM pages WHERE slug = ?", [$slug])) {
            flash('There is already a page at that address.', 'err');
        } else {
            q("INSERT INTO pages (slug,label,file,robots_index,robots_follow,schema_type,sort_order,updated_at)
               VALUES (?,?,'','index','follow','WebPage',99,?)", [$slug, $label, date('c')]);
            flash('Page added. Its SEO can be set now, ready for when the page itself is built.');
        }
        header('Location: /admin/seo.php'); exit;
    }

    if (($_POST['do'] ?? '') === 'delete' && $id) {
        q("DELETE FROM pages WHERE id = ?", [$id]);
        flash('Page removed from the SEO manager. The page itself is untouched.', 'warn');
        header('Location: /admin/seo.php'); exit;
    }

    if ($id) {
        $clean = static function (string $k, int $max = 500): string {
            $v = (string)($_POST[$k] ?? '');
            $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
            return mb_substr(trim($v), 0, $max);
        };
        q("UPDATE pages SET slug=?, label=?, seo_title=?, meta_desc=?, h1=?, focus_kw=?,
             hero_head=?, hero_intro=?, canonical=?, og_title=?, og_desc=?, og_image=?,
             robots_index=?, robots_follow=?, schema_type=?, updated_at=?
           WHERE id=?", [
            '/' . trim($clean('slug', 190), '/'),
            $clean('label', 120), $clean('seo_title'), $clean('meta_desc', 400),
            $clean('h1'), $clean('focus_kw', 190), $clean('hero_head'), $clean('hero_intro', 800),
            $clean('canonical'), $clean('og_title'), $clean('og_desc', 400), $clean('og_image'),
            in_array($_POST['robots_index'] ?? '', ['index','noindex'], true) ? $_POST['robots_index'] : 'index',
            in_array($_POST['robots_follow'] ?? '', ['follow','nofollow'], true) ? $_POST['robots_follow'] : 'follow',
            in_array($_POST['schema_type'] ?? '', $SCHEMA_TYPES, true) ? $_POST['schema_type'] : 'WebPage',
            date('c'), $id,
        ]);
        flash('Saved. Live on the page now.');
        header('Location: /admin/seo.php?id=' . $id); exit;
    }
}

$editId = (int)($_GET['id'] ?? 0);
$pages  = q_all("SELECT * FROM pages ORDER BY sort_order, id");

/* ------------------------------------------------------------------ edit */
if ($editId) {
    $p = q_one("SELECT * FROM pages WHERE id = ?", [$editId]);
    if (!$p) { echo '<div class="note note--err">No such page.</div>'; require __DIR__ . '/inc/foot.php'; exit; }
    $domain = preg_replace('#^https?://#', '', (string)$CFG['base_url']);
    ?>
    <p><a class="btn btn--ghost btn--sm" href="/admin/seo.php">&larr; All pages</a></p>

    <form method="post" data-warn>
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">

      <div class="card">
        <h2>How this page looks in Google</h2>
        <p class="card__hint">Updates as you type. This is what decides whether somebody clicks.</p>
        <div class="serp" id="serp" data-domain="<?= e($domain) ?>">
          <div class="serp__url" id="serp-url"></div>
          <p class="serp__title" id="serp-title"></p>
          <p class="serp__desc" id="serp-desc"></p>
        </div>
      </div>

      <div class="card">
        <h2>Search listing</h2>
        <div class="field">
          <label for="seo_title">SEO title</label>
          <input class="input" id="seo_title" name="seo_title" value="<?= e((string)$p['seo_title']) ?>" data-count="60">
          <span class="hint">Leave blank to keep what the page already sets. Put the words people search for near the front.</span>
        </div>
        <div class="field">
          <label for="meta_desc">Meta description</label>
          <textarea class="textarea" id="meta_desc" name="meta_desc" rows="3" data-count="158"><?= e((string)$p['meta_desc']) ?></textarea>
          <span class="hint">Not a ranking factor, but it is your advert. Say what they get and why you.</span>
        </div>
        <div class="grid grid--2">
          <div class="field">
            <label for="slug">URL</label>
            <input class="input" id="slug" name="slug" value="<?= e((string)$p['slug']) ?>">
            <span class="hint">Changing this on a live page loses its Google ranking unless a redirect is added. Ask me first.</span>
          </div>
          <div class="field">
            <label for="focus_kw">Focus keyword</label>
            <input class="input" id="focus_kw" name="focus_kw" value="<?= e((string)$p['focus_kw']) ?>"
                   placeholder="catering trailers warrington">
            <span class="hint">For your own reference: the single search this page is trying to win.</span>
          </div>
        </div>
      </div>

      <div class="card">
        <h2>On the page</h2>
        <div class="field">
          <label for="h1">Main heading (H1)</label>
          <input class="input" id="h1" name="h1" value="<?= e((string)$p['h1']) ?>" data-count="70">
          <span class="hint">Blank keeps the current heading.</span>
        </div>
        <div class="grid grid--2">
          <div class="field">
            <label for="hero_head">Hero heading</label>
            <input class="input" id="hero_head" name="hero_head" value="<?= e((string)$p['hero_head']) ?>">
          </div>
          <div class="field">
            <label for="label">Name in the admin list</label>
            <input class="input" id="label" name="label" value="<?= e((string)$p['label']) ?>">
          </div>
        </div>
        <div class="field">
          <label for="hero_intro">Hero introduction</label>
          <textarea class="textarea" id="hero_intro" name="hero_intro" rows="3"><?= e((string)$p['hero_intro']) ?></textarea>
        </div>
      </div>

      <div class="card">
        <h2>Sharing</h2>
        <p class="card__hint">What appears when the page is posted on Facebook, WhatsApp or LinkedIn.</p>
        <div class="field">
          <label for="og_title">Share title</label>
          <input class="input" id="og_title" name="og_title" value="<?= e((string)$p['og_title']) ?>">
        </div>
        <div class="field">
          <label for="og_desc">Share description</label>
          <textarea class="textarea" id="og_desc" name="og_desc" rows="2"><?= e((string)$p['og_desc']) ?></textarea>
        </div>
        <div class="field">
          <label for="og_image">Share image</label>
          <input class="input" id="og_image" name="og_image" value="<?= e((string)$p['og_image']) ?>"
                 placeholder="/assets/img/og-default.jpg">
          <span class="hint">1200 by 630 works best. Upload under Media and paste the path here.</span>
        </div>
      </div>

      <div class="card">
        <h2>Technical</h2>
        <div class="grid grid--3">
          <div class="field">
            <label for="robots_index">Search engines</label>
            <select class="select" id="robots_index" name="robots_index">
              <option value="index"<?= $p['robots_index'] === 'index' ? ' selected' : '' ?>>Index — allow in results</option>
              <option value="noindex"<?= $p['robots_index'] === 'noindex' ? ' selected' : '' ?>>Noindex — keep out of results</option>
            </select>
          </div>
          <div class="field">
            <label for="robots_follow">Links</label>
            <select class="select" id="robots_follow" name="robots_follow">
              <option value="follow"<?= $p['robots_follow'] === 'follow' ? ' selected' : '' ?>>Follow</option>
              <option value="nofollow"<?= $p['robots_follow'] === 'nofollow' ? ' selected' : '' ?>>Nofollow</option>
            </select>
          </div>
          <div class="field">
            <label for="schema_type">Schema type</label>
            <select class="select" id="schema_type" name="schema_type">
              <?php foreach ($SCHEMA_TYPES as $t): ?>
                <option value="<?= e($t) ?>"<?= $p['schema_type'] === $t ? ' selected' : '' ?>><?= e($t) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="field">
          <label for="canonical">Canonical URL</label>
          <input class="input" id="canonical" name="canonical" value="<?= e((string)$p['canonical']) ?>"
                 placeholder="leave blank unless this page duplicates another">
          <span class="hint">Blank is right for almost every page. It is generated automatically.</span>
        </div>
      </div>

      <div class="sticky-save btnrow">
        <button class="btn btn--accent" type="submit">Save SEO</button>
        <a class="btn btn--ghost" href="<?= e((string)$p['slug']) ?>" target="_blank" rel="noopener">View page ↗</a>
      </div>
    </form>
    <?php
    require __DIR__ . '/inc/foot.php';
    exit;
}
?>

<div class="tablewrap" style="margin-bottom:1.2rem">
  <table>
    <thead><tr><th>Page</th><th>URL</th><th>Title</th><th>Description</th><th>Search</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($pages as $p):
        $tl = mb_strlen((string)$p['seo_title']);
        $dl = mb_strlen((string)$p['meta_desc']); ?>
        <tr>
          <td><strong><?= e($p['label']) ?></strong></td>
          <td class="num"><?= e($p['slug']) ?></td>
          <td><?= $tl ? '<span class="counter' . ($tl > 60 ? ' over' : '') . '">' . $tl . '</span>' : '<span class="muted">page default</span>' ?></td>
          <td><?= $dl ? '<span class="counter' . ($dl > 158 ? ' over' : '') . '">' . $dl . '</span>' : '<span class="muted">page default</span>' ?></td>
          <td><?= $p['robots_index'] === 'noindex'
                 ? '<span class="status status--Lost">noindex</span>'
                 : '<span class="status status--Won">index</span>' ?></td>
          <td class="right nowrap">
            <a class="btn btn--ghost btn--sm" href="/admin/seo.php?id=<?= (int)$p['id'] ?>">Edit</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Add a page</h2>
  <p class="card__hint">For a page that does not exist yet, so its SEO is ready in advance. Adding it
     here does not build the page: tell me and I will create it.</p>
  <form method="post" class="grid grid--2">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="add">
    <div class="field">
      <label for="new_label">Page name</label>
      <input class="input" id="new_label" name="new_label" placeholder="Catering Trailers St Helens">
    </div>
    <div class="field">
      <label for="new_slug">URL</label>
      <input class="input" id="new_slug" name="new_slug" placeholder="areas/catering-trailers-st-helens">
    </div>
    <div><button class="btn btn--accent" type="submit">Add page</button></div>
  </form>
</div>

<?php require __DIR__ . '/inc/foot.php'; ?>
