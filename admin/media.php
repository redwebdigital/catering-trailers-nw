<?php
declare(strict_types=1);
$CURRENT = 'media';
$TITLE   = 'Media';
$SUBTITLE = 'Images for the website. Every upload is resized and a WebP copy made, so pages stay fast.';

require_once __DIR__ . '/inc/head.php';

const UPLOAD_DIR = '/assets/img/uploads';
const MAX_BYTES  = 12582912;          // 12MB before resizing
const MAX_W      = 1920;

$dir = dirname(__DIR__) . UPLOAD_DIR;
if (!is_dir($dir)) { @mkdir($dir, 0755, true); }

/* --------------------------------------------------------------- actions */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $do = (string)($_POST['do'] ?? '');

    if ($do === 'meta') {
        $id = (int)($_POST['id'] ?? 0);
        q("UPDATE media SET title=?, alt=?, caption=? WHERE id=?", [
            mb_substr(trim((string)($_POST['title'] ?? '')), 0, 190),
            mb_substr(trim((string)($_POST['alt'] ?? '')), 0, 255),
            mb_substr(trim((string)($_POST['caption'] ?? '')), 0, 2000),
            $id,
        ]);
        flash('Image details saved.');
        header('Location: /admin/media.php?id=' . $id); exit;
    }

    if ($do === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $m  = q_one("SELECT * FROM media WHERE id = ?", [$id]);
        if ($m) {
            $base = pathinfo((string)$m['filename'], PATHINFO_FILENAME);
            foreach (glob($dir . '/' . $base . '.*') ?: [] as $f) { @unlink($f); }
            q("DELETE FROM media WHERE id = ?", [$id]);
            flash('Image deleted.', 'warn');
        }
        header('Location: /admin/media.php'); exit;
    }

    // upload
    if (!empty($_FILES['files']['name'][0])) {
        $ok = 0; $bad = [];
        $finfo = class_exists('finfo') ? new finfo(FILEINFO_MIME_TYPE) : null;
        $count = min(count($_FILES['files']['name']), 20);

        for ($i = 0; $i < $count; $i++) {
            if (($_FILES['files']['error'][$i] ?? 1) !== UPLOAD_ERR_OK) { $bad[] = 'upload error'; continue; }
            $tmp = (string)$_FILES['files']['tmp_name'][$i];
            if (!is_uploaded_file($tmp)) { $bad[] = 'rejected'; continue; }
            if (($_FILES['files']['size'][$i] ?? 0) > MAX_BYTES) { $bad[] = $_FILES['files']['name'][$i] . ' is too big'; continue; }

            // trust the bytes, never the filename
            $mime = $finfo ? (string)$finfo->file($tmp) : (string)mime_content_type($tmp);
            $ext = match ($mime) {
                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', default => null,
            };
            if ($ext === null) { $bad[] = $_FILES['files']['name'][$i] . ' is not a JPEG, PNG or WebP'; continue; }
            $dims = @getimagesize($tmp);
            if (!$dims) { $bad[] = $_FILES['files']['name'][$i] . ' is not a real image'; continue; }

            // readable name from the original, plus randomness so nothing collides
            $stem = preg_replace('/[^a-z0-9]+/', '-',
                     strtolower(pathinfo((string)$_FILES['files']['name'][$i], PATHINFO_FILENAME)));
            $stem = trim((string)$stem, '-') ?: 'image';
            $base = mb_substr($stem, 0, 60) . '-' . bin2hex(random_bytes(3));
            $dest = $dir . '/' . $base . '.' . $ext;

            if (!@move_uploaded_file($tmp, $dest)) { $bad[] = 'could not save ' . $stem; continue; }
            @chmod($dest, 0644);

            [$w, $h] = $dims;
            $webp = 0;
            if (function_exists('imagecreatefromstring')) {
                $raw = @file_get_contents($dest);
                $im  = $raw ? @imagecreatefromstring($raw) : false;
                if ($im) {
                    if ($w > MAX_W) {
                        $nh = (int)round($h * MAX_W / $w);
                        $rs = imagecreatetruecolor(MAX_W, $nh);
                        imagealphablending($rs, false); imagesavealpha($rs, true);
                        imagecopyresampled($rs, $im, 0, 0, 0, 0, MAX_W, $nh, $w, $h);
                        imagedestroy($im); $im = $rs; $w = MAX_W; $h = $nh;
                        if ($ext === 'png') imagepng($im, $dest, 8);
                        elseif ($ext === 'webp') imagewebp($im, $dest, 82);
                        else imagejpeg($im, $dest, 86);
                    }
                    if (function_exists('imagewebp') && $ext !== 'webp') {
                        $webp = imagewebp($im, $dir . '/' . $base . '.webp', 82) ? 1 : 0;
                    }
                    imagedestroy($im);
                }
            }

            q("INSERT INTO media (created_at,filename,title,alt,caption,width,height,bytes,has_webp)
               VALUES (?,?,?,'','',?,?,?,?)",
              [date('c'), $base . '.' . $ext, $stem, $w, $h, (int)@filesize($dest), $webp]);
            $ok++;
        }
        flash($ok . ' image' . ($ok === 1 ? '' : 's') . ' uploaded.'
              . ($bad ? ' Skipped: ' . implode('; ', array_slice($bad, 0, 3)) : ''),
              $bad ? 'warn' : 'ok');
        header('Location: /admin/media.php'); exit;
    }
    flash('No files were chosen.', 'warn');
    header('Location: /admin/media.php'); exit;
}

$editId = (int)($_GET['id'] ?? 0);
$items  = q_all("SELECT * FROM media ORDER BY id DESC");
?>

<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <label class="drop" id="drop" for="files">
    <input type="file" id="files" name="files[]" accept="image/jpeg,image/png,image/webp" multiple>
    <strong>Drop images here, or click to choose</strong><br>
    <span class="hint">JPEG, PNG or WebP. Anything wider than 1920px is resized, and a WebP copy is made automatically.</span>
  </label>
  <noscript><button class="btn btn--accent btn--sm" type="submit" style="margin-top:.8rem">Upload</button></noscript>
</form>

<?php if ($editId):
  $m = q_one("SELECT * FROM media WHERE id = ?", [$editId]);
  if ($m): ?>
  <div class="card">
    <h2>Image details</h2>
    <div class="grid grid--2">
      <div>
        <img src="<?= e(UPLOAD_DIR . '/' . $m['filename']) ?>" alt="" style="border-radius:6px">
        <p class="mono muted" style="font-size:.76rem;margin-top:.6rem">
          <?= e(UPLOAD_DIR . '/' . $m['filename']) ?><br>
          <?= (int)$m['width'] ?>×<?= (int)$m['height'] ?> · <?= number_format(((int)$m['bytes']) / 1024, 0) ?> KB
          <?= $m['has_webp'] ? ' · WebP copy made' : '' ?>
        </p>
      </div>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="do" value="meta">
        <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
        <div class="field">
          <label for="title">Title</label>
          <input class="input" id="title" name="title" value="<?= e((string)$m['title']) ?>">
        </div>
        <div class="field">
          <label for="alt">Alt text</label>
          <input class="input" id="alt" name="alt" value="<?= e((string)$m['alt']) ?>">
          <span class="hint">Describe what is in the picture, for people using a screen reader and for Google Images. Leave blank only if the image is purely decorative.</span>
        </div>
        <div class="field">
          <label for="caption">Caption</label>
          <textarea class="textarea" id="caption" name="caption" rows="2"><?= e((string)$m['caption']) ?></textarea>
        </div>
        <div class="btnrow">
          <button class="btn btn--accent" type="submit">Save details</button>
          <a class="btn btn--ghost" href="/admin/media.php">Done</a>
        </div>
      </form>
    </div>
    <form method="post" style="margin-top:1.2rem;border-top:1px solid var(--line);padding-top:1rem">
      <?= csrf_field() ?>
      <input type="hidden" name="do" value="delete">
      <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
      <button class="btn btn--danger btn--sm" type="submit"
              data-confirm="Delete this image for good? Any page still using it will show a broken image.">Delete image</button>
    </form>
  </div>
<?php endif; endif; ?>

<div class="card">
  <h2><?= count($items) ?> image<?= count($items) === 1 ? '' : 's' ?></h2>
  <?php if (!$items): ?>
    <p class="muted">Nothing uploaded yet. The photographs already on your website live in the
       code and do not need managing here; this library is for new images you add.</p>
  <?php else: ?>
    <div class="medialist">
      <?php foreach ($items as $m): ?>
        <a class="mediaitem" href="/admin/media.php?id=<?= (int)$m['id'] ?>" style="text-decoration:none">
          <img src="<?= e(UPLOAD_DIR . '/' . $m['filename']) ?>" alt="<?= e((string)$m['alt']) ?>" loading="lazy">
          <div class="mediaitem__body">
            <div class="mediaitem__name"><?= e((string)$m['filename']) ?></div>
            <?php if (!$m['alt']): ?>
              <div class="counter over" style="margin-top:.3rem">no alt text</div>
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/inc/foot.php'; ?>
