<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/inc/bootstrap.php';
require_once dirname(__DIR__) . '/inc/auth.php';

if (!db_ready()) { header('Location: /admin/install.php'); exit; }

admin_session_start();
if (admin_is_logged_in()) { header('Location: /admin/'); exit; }

$err  = '';
$next = (string)($_GET['next'] ?? '/admin/');
// only ever redirect inside our own admin area
if (!preg_match('#^/admin(/|$)#', $next)) { $next = '/admin/'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (login_locked()) {
        $err = 'Too many attempts. Wait fifteen minutes and try again.';
    } elseif (admin_login((string)($_POST['password'] ?? ''))) {
        header('Location: ' . $next);
        exit;
    } else {
        $left = max(0, MAX_ATTEMPTS - login_attempts_recent());
        $err  = 'That password is not right.' . ($left <= 3 && $left > 0 ? " $left attempts left." : '');
    }
}
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Sign in · <?= e($CFG['name']) ?></title>
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Source+Sans+3:wght@400;600&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="/admin/assets/admin.css?v=1">
</head>
<body>
<div class="login">
  <form class="login__box" method="post" autocomplete="off">
    <?= csrf_field() ?>
    <picture><source srcset="/assets/img/logo.webp" type="image/webp">
      <img src="/assets/img/logo.png" alt="<?= e($CFG['name']) ?>" width="190" height="37"></picture>

    <h1 style="font-size:1.15rem;text-align:center">Admin sign in</h1>

    <?php if ($err): ?><div class="note note--err"><?= e($err) ?></div><?php endif; ?>

    <div class="field">
      <label for="password">Password</label>
      <input class="input" type="password" id="password" name="password"
             required autofocus autocomplete="current-password">
    </div>

    <button class="btn btn--accent" type="submit" style="width:100%">Sign in</button>

    <p class="muted" style="text-align:center;margin:1.2rem 0 0;font-size:.85rem">
      <a href="/">Back to the website</a>
    </p>
  </form>
</div>
</body>
</html>
