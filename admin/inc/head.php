<?php
/**
 * Admin chrome. Every admin page requires this, which also enforces the guard,
 * so there is no way to render an admin screen without being logged in.
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/inc/bootstrap.php';
require_once dirname(__DIR__, 2) . '/inc/auth.php';

require_admin();

/* Apply anything a deploy has brought with it. Both calls are cheap and do
   nothing once they have run, so the owner never has to touch an installer
   again to pick up a new feature. Admin pages only: no public request pays
   for this. */
try { migrate(); seed_hire(); } catch (Throwable $e) {
    error_log('CTNW admin migrate failed: ' . $e->getMessage());
}

$ADMIN_NAV = [
    ''            => ['Dashboard',        'index.php',    'M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z'],
    'enquiries'   => ['Enquiries',        'enquiries.php','M3 5h18v14H3zM3 5l9 7 9-7'],
    'builder'     => ['Quote Builder',    'builder.php',  'M4 7h16M4 12h16M4 17h10'],
    'hire'        => ['Trailer Hire',     'hire.php',     'M3 17h2a3 3 0 006 0h4a3 3 0 006 0h1v-5l-3-4h-4V5H3zM6 17a1.5 1.5 0 103 0 1.5 1.5 0 00-3 0zM16 17a1.5 1.5 0 103 0 1.5 1.5 0 00-3 0z'],
    'seo'         => ['Pages & SEO',      'seo.php',      'M12 3a9 9 0 100 18 9 9 0 000-18zM3 12h18M12 3c3 3.5 3 14.5 0 18'],
    'media'       => ['Media',            'media.php',    'M3 5h18v14H3zM3 16l5-5 4 4 3-3 6 6'],
    'business'    => ['Business Details', 'business.php', 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6'],
    'social'      => ['Social Media',     'social.php',   'M18 8a3 3 0 100-6 3 3 0 000 6zM6 15a3 3 0 100-6 3 3 0 000 6zM18 22a3 3 0 100-6 3 3 0 000 6zM8.6 13.5l6.8 4M15.4 6.5l-6.8 4'],
    'tracking'    => ['Google & Tracking','tracking.php', 'M3 12h4l3 8 4-16 3 8h4'],
    'content'     => ['Site Content',     'content.php',  'M4 4h16v16H4zM8 8h8M8 12h8M8 16h5'],
    'settings'    => ['Site Settings',    'settings.php', 'M12 15a3 3 0 100-6 3 3 0 000 6zM19.4 15a1.6 1.6 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.6 1.6 0 00-1.8-.3 1.6 1.6 0 00-1 1.5V21a2 2 0 11-4 0v-.1A1.6 1.6 0 008 19.4a1.6 1.6 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.6 1.6 0 00.3-1.8 1.6 1.6 0 00-1.5-1H2a2 2 0 110-4h.1A1.6 1.6 0 004.6 8a1.6 1.6 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.6 1.6 0 001.8.3H9a1.6 1.6 0 001-1.5V2a2 2 0 114 0v.1a1.6 1.6 0 001 1.5 1.6 1.6 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.6 1.6 0 00-.3 1.8V9a1.6 1.6 0 001.5 1H22a2 2 0 110 4h-.1a1.6 1.6 0 00-1.5 1z'],
];

$CURRENT = $CURRENT ?? '';
$TITLE   = $TITLE ?? 'Admin';
$f = flash();

$newCount = 0;
try { $newCount = (int)q_val("SELECT COUNT(*) FROM enquiries WHERE status = 'New'"); } catch (Throwable $e) {}
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($TITLE) ?> · <?= e($CFG['name']) ?> admin</title>
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Source+Sans+3:wght@400;600&family=JetBrains+Mono:wght@500&display=swap">
<link rel="stylesheet" href="/admin/assets/admin.css?v=1">
</head>
<body>

<a class="skip" href="#main">Skip to content</a>

<button class="navtoggle" id="navtoggle" type="button" aria-expanded="false" aria-controls="sidebar">
  <span aria-hidden="true">☰</span> Menu
</button>

<div class="shell">
  <aside class="side" id="sidebar">
    <a class="side__brand" href="/" target="_blank" rel="noopener">
      <picture><source srcset="/assets/img/logo.webp" type="image/webp">
        <img src="/assets/img/logo.png" alt="<?= e($CFG['name']) ?>" width="180" height="35"></picture>
      <span class="side__view">View site ↗</span>
    </a>

    <nav class="side__nav" aria-label="Admin">
      <?php foreach ($ADMIN_NAV as $key => [$label, $href, $path]): ?>
        <a href="/admin/<?= e($href) ?>"<?= $CURRENT === $key ? ' aria-current="page"' : '' ?>>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?= e($path) ?>"/></svg>
          <span><?= e($label) ?></span>
          <?php if ($key === 'enquiries' && $newCount > 0): ?>
            <b class="pill"><?= $newCount ?></b>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <form class="side__out" method="post" action="/admin/logout.php">
      <?= csrf_field() ?>
      <button class="btn btn--ghost btn--sm" type="submit">Log out</button>
    </form>
  </aside>

  <main class="main" id="main">
    <header class="topbar">
      <h1><?= e($TITLE) ?></h1>
      <?php if (!empty($SUBTITLE)): ?><p class="topbar__sub"><?= e($SUBTITLE) ?></p><?php endif; ?>
    </header>

    <?php if ($f): ?>
      <div class="note note--<?= e($f['type']) ?>" role="status"><?= e($f['msg']) ?></div>
    <?php endif; ?>
