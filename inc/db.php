<?php
/**
 * Database layer.
 *
 * SQLite by default. The file lives OUTSIDE public_html, which matters for two
 * reasons: a Git deploy that cleans the web root cannot destroy your enquiries,
 * and the database is unreachable over HTTP by construction rather than by an
 * .htaccess rule somebody might later delete.
 *
 * MySQL is supported for later growth: set 'driver' => 'mysql' in the secrets
 * file with host/name/user/pass and everything else works unchanged.
 */

declare(strict_types=1);

/** Where private, non-web-reachable files live. */
function private_dir(): string
{
    static $dir = null;
    if ($dir !== null) return $dir;

    $webroot = dirname(__DIR__);              // .../public_html
    $preferred = dirname($webroot) . '/private';   // sibling of public_html

    // Preferred: outside the web root entirely.
    if (is_dir($preferred) || @mkdir($preferred, 0700, true)) {
        if (is_writable($preferred)) return $dir = $preferred;
    }

    // Fallback: inside the web root, hidden behind its own .htaccess. Not as
    // good, but better than failing outright on a locked-down host.
    $fallback = $webroot . '/private';
    if (!is_dir($fallback)) @mkdir($fallback, 0700, true);
    if (!is_file($fallback . '/.htaccess')) {
        @file_put_contents($fallback . '/.htaccess', "Require all denied\nphp_flag engine off\n");
    }
    return $dir = $fallback;
}

/** Secrets (db credentials, admin hash, pepper). Never in the repo. */
function secrets(): array
{
    static $s = null;
    if ($s !== null) return $s;
    $file = private_dir() . '/secrets.php';
    $s = is_file($file) ? (array)(require $file) : [];
    return $s;
}

function secrets_save(array $new): bool
{
    $file = private_dir() . '/secrets.php';
    $out  = "<?php\n// Written by the installer. Never commit this file.\nreturn "
          . var_export($new, true) . ";\n";
    $ok = @file_put_contents($file, $out, LOCK_EX) !== false;
    if ($ok) { @chmod($file, 0600); }
    return $ok;
}

/** The PDO handle. */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $s = secrets();
    $driver = $s['db']['driver'] ?? 'sqlite';

    if ($driver === 'mysql') {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4',
            $s['db']['host'] ?? 'localhost', $s['db']['name'] ?? '');
        $pdo = new PDO($dsn, $s['db']['user'] ?? '', $s['db']['pass'] ?? '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } else {
        $path = $s['db']['path'] ?? (private_dir() . '/ctnw.sqlite');
        $pdo = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA journal_mode = WAL');
        $pdo->exec('PRAGMA foreign_keys = ON');
        @chmod($path, 0600);
    }
    return $pdo;
}

function db_driver(): string
{
    return secrets()['db']['driver'] ?? 'sqlite';
}

/** True when the schema has been created and the admin password set. */
function db_ready(): bool
{
    try {
        $s = secrets();
        if (empty($s['admin']['hash'])) return false;
        db()->query('SELECT 1 FROM settings LIMIT 1');
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

/* ------------------------------------------------------------------ query */

function q(string $sql, array $args = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st;
}
function q_all(string $sql, array $args = []): array { return q($sql, $args)->fetchAll(); }
function q_one(string $sql, array $args = []): ?array
{
    $r = q($sql, $args)->fetch();
    return $r === false ? null : $r;
}
function q_val(string $sql, array $args = [])
{
    $r = q($sql, $args)->fetch(PDO::FETCH_NUM);
    return $r === false ? null : $r[0];
}

/* ------------------------------------------------------------- migrations */

/**
 * Migrations, applied in order and recorded so they run exactly once.
 * Add new ones to the end; never edit one that has shipped.
 */
function migrations(): array
{
    $auto = db_driver() === 'mysql'
        ? 'INTEGER PRIMARY KEY AUTO_INCREMENT'
        : 'INTEGER PRIMARY KEY AUTOINCREMENT';

    return [

'001_settings' => "
CREATE TABLE IF NOT EXISTS settings (
  k     VARCHAR(120) PRIMARY KEY,
  v     TEXT,
  grp   VARCHAR(40) DEFAULT 'general',
  updated_at VARCHAR(32)
)",

'002_pages' => "
CREATE TABLE IF NOT EXISTS pages (
  id          $auto,
  slug        VARCHAR(190) UNIQUE,
  label       VARCHAR(120),
  file        VARCHAR(190),
  seo_title   VARCHAR(255),
  meta_desc   TEXT,
  h1          VARCHAR(255),
  focus_kw    VARCHAR(190),
  hero_head   VARCHAR(255),
  hero_intro  TEXT,
  canonical   VARCHAR(255),
  og_title    VARCHAR(255),
  og_desc     TEXT,
  og_image    VARCHAR(255),
  robots_index  VARCHAR(10) DEFAULT 'index',
  robots_follow VARCHAR(10) DEFAULT 'follow',
  schema_type VARCHAR(60) DEFAULT 'WebPage',
  sort_order  INTEGER DEFAULT 0,
  updated_at  VARCHAR(32)
)",

'003_enquiries' => "
CREATE TABLE IF NOT EXISTS enquiries (
  id           $auto,
  created_at   VARCHAR(32),
  source       VARCHAR(40) DEFAULT 'quote',
  name         VARCHAR(190),
  phone        VARCHAR(60),
  email        VARCHAR(190),
  town         VARCHAR(120),
  job_type     VARCHAR(60),
  body_length  VARCHAR(40),
  axle         VARCHAR(40),
  fit_out      VARCHAR(255),
  appliances   TEXT,
  power        VARCHAR(60),
  budget       VARCHAR(60),
  required_date VARCHAR(40),
  message      TEXT,
  extra        TEXT,
  files        TEXT,
  status       VARCHAR(20) DEFAULT 'New',
  ip           VARCHAR(60),
  mailed       INTEGER DEFAULT 0
)",

'004_enquiry_notes' => "
CREATE TABLE IF NOT EXISTS enquiry_notes (
  id          $auto,
  enquiry_id  INTEGER NOT NULL,
  created_at  VARCHAR(32),
  body        TEXT
)",

'005_builder_options' => "
CREATE TABLE IF NOT EXISTS builder_options (
  id          $auto,
  group_key   VARCHAR(40),
  label       VARCHAR(120),
  value       VARCHAR(120),
  price_from  VARCHAR(60),
  icon        VARCHAR(60),
  draw_width  INTEGER DEFAULT 0,
  enabled     INTEGER DEFAULT 1,
  sort_order  INTEGER DEFAULT 0
)",

'006_builder_stages' => "
CREATE TABLE IF NOT EXISTS builder_stages (
  id          $auto,
  title       VARCHAR(190),
  body        TEXT,
  enabled     INTEGER DEFAULT 1,
  sort_order  INTEGER DEFAULT 0
)",

'007_media' => "
CREATE TABLE IF NOT EXISTS media (
  id          $auto,
  created_at  VARCHAR(32),
  filename    VARCHAR(255),
  title       VARCHAR(190),
  alt         VARCHAR(255),
  caption     TEXT,
  width       INTEGER,
  height      INTEGER,
  bytes       INTEGER,
  has_webp    INTEGER DEFAULT 0
)",

'008_login_attempts' => "
CREATE TABLE IF NOT EXISTS login_attempts (
  id         $auto,
  ip         VARCHAR(60),
  at         INTEGER,
  ok         INTEGER DEFAULT 0
)",

'009_enquiry_index' => "
CREATE INDEX IF NOT EXISTS idx_enq_created ON enquiries (created_at)",

'010_enquiry_status_index' => "
CREATE INDEX IF NOT EXISTS idx_enq_status ON enquiries (status)",

    ];
}

function migrate(): array
{
    $pdo = db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
        name VARCHAR(190) PRIMARY KEY, run_at VARCHAR(32))");

    $done = array_column($pdo->query("SELECT name FROM migrations")->fetchAll(), 'name');
    $applied = [];

    foreach (migrations() as $name => $sql) {
        if (in_array($name, $done, true)) continue;
        $pdo->exec($sql);
        q("INSERT INTO migrations (name, run_at) VALUES (?, ?)", [$name, date('c')]);
        $applied[] = $name;
    }
    return $applied;
}

/* --------------------------------------------------------------- settings */

/** All settings as a flat key => value map, read once per request. */
function settings_all(bool $fresh = false): array
{
    static $cache = null;
    if ($cache !== null && !$fresh) return $cache;
    try {
        $rows = q_all("SELECT k, v FROM settings");
    } catch (Throwable $e) {
        return $cache = [];
    }
    $out = [];
    foreach ($rows as $r) { $out[$r['k']] = $r['v']; }
    return $cache = $out;
}

function setting(string $key, $default = null)
{
    $all = settings_all();
    return array_key_exists($key, $all) && $all[$key] !== '' ? $all[$key] : $default;
}

function setting_set(string $key, $value, string $group = 'general'): void
{
    $now = date('c');
    if (db_driver() === 'mysql') {
        q("INSERT INTO settings (k, v, grp, updated_at) VALUES (?,?,?,?)
           ON DUPLICATE KEY UPDATE v = VALUES(v), grp = VALUES(grp), updated_at = VALUES(updated_at)",
          [$key, (string)$value, $group, $now]);
    } else {
        q("INSERT INTO settings (k, v, grp, updated_at) VALUES (?,?,?,?)
           ON CONFLICT(k) DO UPDATE SET v = excluded.v, grp = excluded.grp, updated_at = excluded.updated_at",
          [$key, (string)$value, $group, $now]);
    }
    settings_all(true);
}

function settings_set_many(array $pairs, string $group = 'general'): void
{
    db()->beginTransaction();
    try {
        foreach ($pairs as $k => $v) { setting_set($k, $v, $group); }
        db()->commit();
    } catch (Throwable $e) {
        db()->rollBack();
        throw $e;
    }
    settings_all(true);
}
