<?php
/**
 * Coming soon mode.
 *
 * When the owner switches this on, every public page is replaced by a holding
 * page. Three things matter here:
 *
 *   - It answers 503, not 200. A holding page served as 200 invites Google to
 *     index it in place of the real pages and lose the rankings the site
 *     already has. 503 with Retry-After says "temporarily away", which is what
 *     is actually true, and existing pages are held rather than replaced.
 *   - The admin area is never gated, so the owner cannot lock themselves out.
 *   - A preview link lets the owner walk the real site while it is switched on,
 *     without turning it off for everyone else.
 */

declare(strict_types=1);

const CS_COOKIE = 'ctnw_preview';

/** Is the holding page switched on at all? */
function coming_soon_on(): bool
{
    return db_ready() && (string)setting('site.coming_soon', '0') === '1';
}

/** The secret that unlocks a preview. Generated once, on demand. */
function coming_soon_key(): string
{
    $k = trim((string)setting('site.preview_key', ''));
    if ($k === '') {
        $k = bin2hex(random_bytes(8));
        try { setting_set('site.preview_key', $k, 'site'); settings_all(true); }
        catch (Throwable $e) { /* read-only database: preview simply stays off */ }
    }
    return $k;
}

/** Is this visitor holding a valid preview pass? */
function coming_soon_preview(): bool
{
    $key = trim((string)setting('site.preview_key', ''));
    if ($key === '') return false;

    // arriving with the link: remember it for a day so links work while browsing
    if (isset($_GET['preview']) && hash_equals($key, (string)$_GET['preview'])) {
        setcookie(CS_COOKIE, $key, [
            'expires'  => time() + 86400,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => (($_SERVER['HTTPS'] ?? '') === 'on'),
        ]);
        return true;
    }
    return isset($_COOKIE[CS_COOKIE]) && hash_equals($key, (string)$_COOKIE[CS_COOKIE]);
}

/**
 * Show the holding page and stop, unless this visitor may pass.
 * Called from the shared public header, so the admin area never reaches it.
 */
function coming_soon_gate(): void
{
    if (!coming_soon_on() || coming_soon_preview()) return;

    global $CFG;

    http_response_code(503);
    header('Retry-After: 86400');
    header('Cache-Control: no-store, max-age=0');
    header('X-Robots-Tag: noindex');

    $heading = trim((string)setting('site.cs_heading', '')) ?: 'Something new is on the way';
    $message = trim((string)setting('site.cs_message', ''))
        ?: 'Our website is being updated. We are still building, repairing and refurbishing catering trailers in the meantime, so please do get in touch.';
    $showContact = (string)setting('site.cs_show_contact', '1') === '1';

    $email = trim((string)($CFG['enquiry_inbox'] ?? ''));
    $phone = trim((string)($CFG['phone_display'] ?? ''));
    $logo  = trim((string)setting('seo.logo', '')) ?: '/assets/img/logo.png';

    require __DIR__ . '/coming-soon-page.php';
    exit;
}
