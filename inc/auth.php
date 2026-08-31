<?php
/**
 * Admin authentication.
 *
 * The password is never stored, only a password_hash(). That hash lives in
 * private/secrets.php, outside the web root and out of Git, so no credential
 * ever reaches the repository or the browser.
 *
 * Protections: hardened session cookies, session fixation prevented by
 * regenerating the id on login, CSRF tokens on every state-changing form,
 * per-IP rate limiting with a lockout, and constant-time comparison.
 */

declare(strict_types=1);

require_once __DIR__ . '/db.php';

const ADMIN_SESSION   = 'ctnw_admin';
const MAX_ATTEMPTS    = 8;     // per IP
const ATTEMPT_WINDOW  = 900;   // 15 minutes
const IDLE_TIMEOUT    = 7200;  // 2 hours

function admin_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;

    $https = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_name(ADMIN_SESSION);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/admin',
        'domain'   => '',
        'secure'   => $https,      // never sent over plain http when we have TLS
        'httponly' => true,        // unreadable from JavaScript
        'samesite' => 'Lax',       // survives a normal link, blocks cross-site posts
    ]);
    session_start();
}

/* ------------------------------------------------------------ rate limit */

function client_ip(): string
{
    return (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

function login_attempts_recent(): int
{
    try {
        return (int)q_val(
            "SELECT COUNT(*) FROM login_attempts WHERE ip = ? AND ok = 0 AND at > ?",
            [client_ip(), time() - ATTEMPT_WINDOW]
        );
    } catch (Throwable $e) { return 0; }
}

function login_locked(): bool { return login_attempts_recent() >= MAX_ATTEMPTS; }

function login_record(bool $ok): void
{
    try {
        q("INSERT INTO login_attempts (ip, at, ok) VALUES (?,?,?)",
          [client_ip(), time(), $ok ? 1 : 0]);
        // keep the table from growing forever
        q("DELETE FROM login_attempts WHERE at < ?", [time() - 86400]);
    } catch (Throwable $e) { /* never block login on logging */ }
}

/* ----------------------------------------------------------------- login */

function admin_login(string $password): bool
{
    admin_session_start();

    if (login_locked()) return false;

    $hash = secrets()['admin']['hash'] ?? '';
    if ($hash === '') { login_record(false); return false; }

    if (!password_verify($password, $hash)) {
        login_record(false);
        usleep(random_int(150000, 400000));   // blunt timing probes
        return false;
    }

    // Rehash if PHP's default cost has moved on since the password was set.
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        $s = secrets();
        $s['admin']['hash'] = password_hash($password, PASSWORD_DEFAULT);
        secrets_save($s);
    }

    session_regenerate_id(true);              // defeat session fixation
    $_SESSION['ok']   = true;
    $_SESSION['at']   = time();
    $_SESSION['ua']   = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200);
    login_record(true);
    return true;
}

function admin_logout(): void
{
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function admin_is_logged_in(): bool
{
    admin_session_start();
    if (empty($_SESSION['ok'])) return false;

    // idle timeout
    if (time() - (int)($_SESSION['at'] ?? 0) > IDLE_TIMEOUT) {
        admin_logout();
        return false;
    }
    // a stolen cookie replayed from another browser is rejected
    if (($_SESSION['ua'] ?? '') !== substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200)) {
        admin_logout();
        return false;
    }
    $_SESSION['at'] = time();
    return true;
}

/** Guard at the top of every admin page. */
function require_admin(): void
{
    if (admin_is_logged_in()) return;
    $to = urlencode($_SERVER['REQUEST_URI'] ?? '/admin/');
    header('Location: /admin/login.php?next=' . $to, true, 302);
    exit;
}

/* ------------------------------------------------------------------ CSRF */

function csrf_token(): string
{
    admin_session_start();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

/** Call first in any POST handler. Dies rather than proceeding unverified. */
function csrf_check(): void
{
    admin_session_start();
    $sent = (string)($_POST['_csrf'] ?? '');
    $have = (string)($_SESSION['csrf'] ?? '');
    if ($have === '' || !hash_equals($have, $sent)) {
        http_response_code(400);
        exit('Bad or expired form token. Go back, reload the page and try again.');
    }
}

/* --------------------------------------------------------------- helpers */

function admin_set_password(string $plain): bool
{
    $s = secrets();
    $s['admin']['hash'] = password_hash($plain, PASSWORD_DEFAULT);
    return secrets_save($s);
}

/** One-request flash message. */
function flash(?string $msg = null, string $type = 'ok'): ?array
{
    admin_session_start();
    if ($msg !== null) { $_SESSION['flash'] = ['msg' => $msg, 'type' => $type]; return null; }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}
