<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/inc/auth.php';

// POST only, with a token: a stray <img src="/admin/logout.php"> cannot sign you out.
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check(); }
admin_logout();
header('Location: /admin/login.php');
