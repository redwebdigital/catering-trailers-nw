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

/* FAQs shown on a page, editable in the admin area. Keyed by page slug so any
   page can own a set, not just the hire page. */
'011_page_faqs' => "
CREATE TABLE IF NOT EXISTS page_faqs (
  id          $auto,
  page_slug   VARCHAR(190),
  q           TEXT,
  a           TEXT,
  enabled     INTEGER DEFAULT 1,
  sort_order  INTEGER DEFAULT 0
)",

'012_page_faq_index' => "
CREATE INDEX IF NOT EXISTS idx_faq_page ON page_faqs (page_slug, sort_order)",

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

/* ------------------------------------------------------------- hire seeds */

/**
 * Put the hire page's editable content into the database if it is not there.
 *
 * Migrations create tables but cannot portably carry many rows, so the starting
 * content is seeded here instead. Safe to call repeatedly: it only ever fills
 * gaps, so anything the owner has edited or deleted is left exactly as it is.
 */
function seed_hire(): void
{
    try {
        // hire types, reusing the builder options table so the existing admin
        // add/remove/reorder machinery works on them with no new code
        $have = (int)q_val("SELECT COUNT(*) FROM builder_options WHERE group_key = 'hire_type'");
        if ($have === 0) {
            $types = ['Catering trailer', 'Food trailer', 'Mobile bar', 'Other'];
            foreach ($types as $i => $label) {
                q("INSERT INTO builder_options (group_key, label, value, enabled, sort_order)
                   VALUES ('hire_type', ?, ?, 1, ?)", [$label, $label, $i + 1]);
            }
        }

        // enquiry types, shared by the quote form and the contact form
        $haveEnq = (int)q_val("SELECT COUNT(*) FROM builder_options WHERE group_key = 'enquiry_type'");
        if ($haveEnq === 0) {
            $types = ['New Catering Trailer', 'Repair', 'Refurbishment', 'Trailer Hire',
                      'Mobile Bar', 'Other'];
            foreach ($types as $i => $label) {
                q("INSERT INTO builder_options (group_key, label, value, enabled, sort_order)
                   VALUES ('enquiry_type', ?, ?, 1, ?)", [$label, $label, $i + 1]);
            }
        }

        $slug = '/catering-trailer-hire';
        $haveFaq = (int)q_val("SELECT COUNT(*) FROM page_faqs WHERE page_slug = ?", [$slug]);
        if ($haveFaq === 0) {
            foreach (hire_faq_defaults() as $i => [$q, $a]) {
                q("INSERT INTO page_faqs (page_slug, q, a, enabled, sort_order)
                   VALUES (?,?,?,1,?)", [$slug, $q, $a, $i + 1]);
            }
        }

        if (setting('hire.areas') === null) {
            setting_set('hire.areas', implode("\n", [
                'Warrington', 'Manchester', 'Liverpool', 'Cheshire', 'Widnes', 'Runcorn',
                'St Helens', 'Wigan', 'Bolton', 'Northwich', 'Knutsford', 'Altrincham',
            ]), 'hire');
        }
        if (setting('hire.enabled') === null) { setting_set('hire.enabled', '1', 'hire'); }
        if (setting('hire.email')   === null) { setting_set('hire.email', '', 'hire'); }

        // give the page a row of its own so its SEO fields are editable
        // alongside every other page, rather than being a special case
        $known = (int)q_val("SELECT COUNT(*) FROM pages WHERE slug = ?", [$slug]);
        if ($known === 0) {
            $next = (int)q_val("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM pages");
            q("INSERT INTO pages (slug,label,file,robots_index,robots_follow,schema_type,sort_order,updated_at)
               VALUES (?,?,?,'index','follow','WebPage',?,?)",
              [$slug, 'Trailer Hire', 'catering-trailer-hire.php', $next, date('c')]);
        }
    } catch (Throwable $e) {
        error_log('CTNW hire seed failed: ' . $e->getMessage());
    }
}

/**
 * Site-wide switches that need a starting value.
 *
 * Only fills a setting that has never been written, so a deploy can never flip
 * something the owner has chosen — least of all the holding page.
 */
function seed_site_defaults(): void
{
    try {
        $defaults = [
            'site.coming_soon'     => '0',
            'site.cs_heading'      => 'Something new is on the way',
            'site.cs_message'      => 'Our website is being updated. We are still building, repairing and refurbishing catering trailers in the meantime, so please do get in touch.',
            'site.cs_show_contact' => '1',
        ];
        foreach ($defaults as $k => $v) {
            if (setting($k) === null) { setting_set($k, $v, 'site'); }
        }

        settings_all(true);
    } catch (Throwable $e) {
        error_log('CTNW site defaults seed failed: ' . $e->getMessage());
    }
}

/**
 * Fill in the SEO fields for every page.
 *
 * Only ever writes to a column that is still empty, so the moment the owner
 * types their own title or description, this stops touching that field. New
 * pages added to the seed data reach an existing site on the next admin visit.
 */
function seed_pages_seo(): void
{
    $data = require __DIR__ . '/seed-data.php';
    $base = rtrim((string)(setting('biz.base_url') ?: 'https://cateringtrailersnw.co.uk'), '/');

    try {
        /* Every page now carries a written title that handles its own branding,
           so the global suffix would append the business name a second time and
           push titles past what Google will show. Cleared once, and only while
           it still holds the value it shipped with, so a suffix the owner has
           deliberately chosen is left alone. */
        if (trim((string)setting('seo.title_suffix', '')) === '| Catering Trailers NW'
            || (string)setting('seo.title_suffix', '') === ' | Catering Trailers NW') {
            setting_set('seo.title_suffix', '', 'seo');
            settings_all(true);
        }

        /* Copy that was seeded at install and has not been touched since is
           brought up to the current wording. Matching on the exact old value
           means anything the owner has rewritten is left alone. */
        $rewrites = [
            'content.hero_heading' => [
                'Bespoke Catering Trailers Built in the North West',
                'Bespoke Catering Trailers Built for Your Business',
            ],
            'content.hero_sub' => [
                'Built to your menu, not off a shelf. New builds, repairs and refits for street food traders, burger vans and coffee trailers.',
                'Professional catering trailers designed around your menu, equipment and working layout. Whether you are starting a new mobile food business, replacing an older unit or expanding an existing operation, we can help you create a trailer designed around the way you actually work.',
            ],
            'content.builder_heading' => [
                'From your menu to your pitch',
                'From Your Menu to Your Pitch',
            ],
        ];
        $touched = false;
        foreach ($rewrites as $key => [$old, $new]) {
            if (trim((string)setting($key, '')) === $old) {
                setting_set($key, $new, 'content');
                $touched = true;
            }
        }
        if ($touched) { settings_all(true); }

        /* The five build stages, same rule: only replaced while they still
           carry the titles they were installed with. */
        $stages = [
            'We take your spec'          => ['Your Requirements',      'Tell us about your menu, equipment, pitch, trailer size and how you intend to use the trailer.'],
            'Drawings and a fixed price' => ['Layout & Specification', 'We develop the proposed trailer specification and layout around your requirements.'],
            'Chassis and shell'          => ['Chassis & Shell',        'The chassis, body, hatch and door openings are prepared to suit the agreed build.'],
            'Fit-out, gas and electrics' => ['Fit-Out',                'Work surfaces, equipment, gas, electrics, water systems and other internal features are installed as required.'],
            'Handover'                   => ['Handover',               'Once the trailer is complete, the finished build is checked and prepared for handover.'],
        ];
        foreach ($stages as $old => [$title, $body]) {
            q("UPDATE builder_stages SET title = ?, body = ? WHERE title = ?", [$title, $body, $old]);
        }

        foreach ($data as $slug => $v) {
            $row = q_one("SELECT * FROM pages WHERE slug = ?", [$slug]);

            if (!$row) {
                $next = (int)q_val("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM pages");
                q("INSERT INTO pages (slug, label, file, sort_order, updated_at) VALUES (?,?,?,?,?)",
                  [$slug, $v['label'] ?? $slug, $v['file'] ?? '', $next, date('c')]);
                $row = q_one("SELECT * FROM pages WHERE slug = ?", [$slug]);
                if (!$row) continue;
            }

            $set = [];
            $vals = [];
            $wanted = [
                'seo_title'  => $v['seo_title']  ?? '',
                'meta_desc'  => $v['meta_desc']  ?? '',
                'h1'         => $v['h1']         ?? '',
                'focus_kw'   => $v['focus_kw']   ?? '',
                'hero_intro' => $v['hero_intro'] ?? '',
                'canonical'  => $base . ($slug === '/' ? '/' : $slug),
                'og_title'   => $v['seo_title']  ?? '',
                'og_desc'    => $v['meta_desc']  ?? '',
                'og_image'   => '/assets/img/og-default.jpg',
            ];
            foreach ($wanted as $col => $val) {
                if ($val === '') continue;
                if (trim((string)($row[$col] ?? '')) !== '') continue;   // already set: leave it
                $set[] = "$col = ?";
                $vals[] = $val;
            }

            // robots and schema type carry a default, so only correct them where
            // this page is meant to differ from it
            if (($v['robots_index'] ?? 'index') !== ($row['robots_index'] ?? 'index')) {
                $set[] = 'robots_index = ?'; $vals[] = $v['robots_index'] ?? 'index';
            }
            if (!empty($v['schema_type']) && ($row['schema_type'] ?? 'WebPage') === 'WebPage'
                && $v['schema_type'] !== 'WebPage') {
                $set[] = 'schema_type = ?'; $vals[] = $v['schema_type'];
            }
            if (trim((string)($row['label'] ?? '')) === '' && !empty($v['label'])) {
                $set[] = 'label = ?'; $vals[] = $v['label'];
            }

            if ($set) {
                $set[] = 'updated_at = ?'; $vals[] = date('c');
                $vals[] = $slug;
                q("UPDATE pages SET " . implode(', ', $set) . " WHERE slug = ?", $vals);
            }
        }
    } catch (Throwable $e) {
        error_log('CTNW SEO seed failed: ' . $e->getMessage());
    }
}

/**
 * The FAQs the hire page ships with.
 *
 * Also used as the fallback when the database is unreachable, so the page and
 * its FAQ schema always agree with each other.
 *
 * @return array<int, array{0:string,1:string}>
 */
function hire_faq_defaults(): array
{
    return [
        ['How much does catering trailer hire cost?',
         'Pricing depends on the size and specification of the trailer, hire duration, required equipment, delivery location and any additional requirements. Send us your requirements through the quote form and we will provide a tailored quotation.'],
        ['Can I hire a catering trailer for one day?',
         'One-day and event hire enquiries are welcome. Availability will depend on the trailer required, location and dates.'],
        ['Can I hire a catering trailer for a festival?',
         'Yes, catering trailers can be suitable for festivals, markets and outdoor events. Tell us your menu, expected customer numbers, power requirements and event dates so we can assess what is suitable.'],
        ['Do catering trailers include equipment?',
         'Equipment varies depending on the trailer and hire requirements. Tell us what equipment you need when requesting a quote.'],
        ['Do you deliver catering trailers?',
         'Delivery may be available depending on the location, trailer and hire arrangement. Include the delivery postcode with your enquiry.'],
        ['Can I hire a mobile bar for a wedding?',
         'Mobile bar hire enquiries are welcome for weddings and private events. Let us know the event date, location, expected guest numbers and the type of drinks service you plan to provide.'],
        ['Can I hire a trailer for several weeks or months?',
         'Longer-term hire enquiries are welcome and are quoted individually based on the trailer, duration and requirements.'],
        ['Can I hire a trailer while mine is being repaired?',
         'Potentially, depending on availability. Catering Trailers NW also provides trailer repairs and refurbishments, so mention your existing trailer when making your enquiry.'],
        ['Should I hire or buy a catering trailer?',
         'Hire can work well for temporary requirements, events or short-term use. If you plan to trade regularly, a bespoke new catering trailer designed around your own menu and equipment may be more suitable. We can provide quotations for both options.'],
    ];
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
