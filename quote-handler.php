<?php
/**
 * Quote enquiry handler.
 *
 * Accepts the multi-step form, validates everything, stores the photographs
 * out of reach of the web, and emails the enquiry with them attached.
 *
 * Security posture:
 *   - every field is validated and length-capped before use
 *   - nothing user-supplied is ever placed in a mail header unescaped
 *   - uploads are checked by real content type, not by the name they arrived
 *     with, and are re-saved under a generated name with a safe extension
 *   - the uploads directory is blocked in .htaccess and carries its own deny
 *   - a honeypot field plus a minimum completion time filters the bots
 *   - a light per-IP rate limit stops someone hammering the inbox
 */

declare(strict_types=1);

require_once __DIR__ . '/inc/bootstrap.php';   // $CFG, overlaid with admin settings

$isAjax = (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch');

/* Where a non-JavaScript visitor is sent back to when something is wrong.
   Set once the form is identified, so they land on the page they came from. */
$RETURN_PATH = '/request-a-quote';

/** Reply and stop. */
function respond(bool $ok, string $error = '', int $code = 200): never
{
    global $isAjax, $RETURN_PATH;
    if ($isAjax) {
        http_response_code($ok ? 200 : $code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($ok ? ['ok' => true] : ['ok' => false, 'error' => $error]);
    } else {
        $okPath = (($_POST['enquiry_source'] ?? '') === 'holding') ? '/?sent=1' : '/thank-you';
        header('Location: ' . ($ok ? $okPath : $RETURN_PATH . '?error=1'), true, 303);
    }
    exit;
}

// ── method ───────────────────────────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(false, 'Method not allowed', 405);
}

/* While the holding page is up the only form a visitor can see is the one on it,
   so that is the only one accepted. Anything else arriving is stale or
   automated, and is refused rather than left to fill the inbox. */
require_once __DIR__ . '/inc/coming-soon.php';
$fromHolding = (($_POST['enquiry_source'] ?? '') === 'holding');
if (coming_soon_on() && !coming_soon_preview() && !$fromHolding) {
    respond(false, 'The website is temporarily unavailable.', 503);
}

// ── bot filters ──────────────────────────────────────────────────────────
if (trim((string)($_POST['company_website'] ?? '')) !== '') {
    respond(true);                       // honeypot: look successful, send nothing
}
$startedAt = (int)($_POST['started_at'] ?? 0);
if ($startedAt > 0 && (time() - $startedAt) < 4) {
    respond(true);                       // filled faster than a human can read it
}

// ── rate limit, per IP, 5 enquiries an hour ──────────────────────────────
$ip = (string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
$rateDir = sys_get_temp_dir() . '/ctnw-rate';
@mkdir($rateDir, 0700, true);
$rateFile = $rateDir . '/' . hash('sha256', $ip) . '.txt';
$hits = [];
if (is_readable($rateFile)) {
    $hits = array_filter(
        array_map('intval', explode(',', (string)file_get_contents($rateFile))),
        fn($t) => $t > time() - 3600
    );
}
if (count($hits) >= 5) {
    respond(false, 'Too many enquiries. Please call us instead.', 429);
}
$hits[] = time();
@file_put_contents($rateFile, implode(',', $hits), LOCK_EX);

// ── field helpers ────────────────────────────────────────────────────────
/** Trim, strip control characters, and cap the length. */
function field(string $key, int $max = 200): string
{
    $v = (string)($_POST[$key] ?? '');
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
    return mb_substr(trim($v), 0, $max);
}

/** A value safe to place in a mail header: no CR, no LF, no header injection. */
function headerSafe(string $v): string
{
    return trim(str_replace(["\r", "\n", "\0"], ' ', $v));
}

/**
 * The quote form's secondary answers, as readable lines for the enquiry record.
 * Kept out of the columns because they are notes, not things worth filtering on.
 */
function quote_extra_lines(string $tow): string
{
    $map = [
        'Business'          => field('company', 140),
        'Staff inside'      => field('staff', 40),
        'Equipment needed'  => field('equipment', 1200),
        'Gas required'      => field('gas', 20),
        'Electric required' => field('electric', 20),
        'Water required'    => field('water', 20),
        'Serving hatch'     => field('hatch', 200),
        'Entrance doors'    => field('doors', 200),
        'Gas/electric notes'=> field('power_notes', 200),
        'Tows with'         => $tow,
    ];
    $out = [];
    foreach ($map as $label => $value) {
        if ($value !== '') $out[] = $label . ': ' . $value;
    }
    return implode("\n", $out);
}

$name    = field('name', 120);
$phone   = field('phone', 30);
$email   = field('email', 180);
$town    = field('town', 80);
$jobType = field('job_type', 40);
$size    = field('size', 40);
$axle    = field('axle', 40);
$use     = field('intended_use', 200);
$tow     = field('tow_vehicle', 120);
$power   = field('power', 40);
$pNotes  = field('power_notes', 200);
$budget  = field('budget', 40);
$reqDate = field('required_date', 20);
$source  = field('source', 60);          // "how did you hear about us", quote form only
$message = field('message', 4000);
$consent = ($_POST['consent'] ?? '') === 'yes';

/* Which form this came from. Kept separate from $source above, which is the
   customer's answer to how they found us and means something quite different. */
$enqSource = field('enquiry_source', 40);
if (!in_array($enqSource, ['quote', 'hire', 'contact', 'holding', 'general', 'new-trailer', 'repair'], true)) {
    $enqSource = 'quote';
}
$isHire    = $enqSource === 'hire';
$isContact = $enqSource === 'contact';
$isHolding = $enqSource === 'holding';
if ($isHire)    { $RETURN_PATH = '/catering-trailer-hire'; }
if ($isContact) { $RETURN_PATH = '/contact'; }
if ($isHolding) { $RETURN_PATH = '/'; }

/* The stepped quote form is the only one that insists on a phone number. The
   hire and contact forms ask for a written reply by email, so a number there is
   a convenience rather than a requirement. */
$phoneOptional = $isHire || $isContact || $isHolding;

/* Hire enquiries ask a different set of questions. They are gathered into the
   enquiry's "extra" field as readable lines, so the admin area shows them
   without needing a column per question. */
$hireExtra = [];
if ($isHire) {
    $untilDate = field('until_date', 20);
    if ($untilDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $untilDate)) $untilDate = '';

    $map = [
        'Company'            => field('company', 140),
        'Event/business type'=> field('event_type', 140),
        'Required until'     => $untilDate,
        'Expected customers' => field('customers', 60),
        'Equipment required' => field('equipment', 1200),
        'Gas required'       => field('gas', 20),
        'Electrical supply'  => field('electric', 20),
        'Water required'     => field('water', 20),
        'Delivery required'  => field('delivery', 20),
        'Interested in buying a new trailer' => field('buy_interest', 60),
        'Additional information' => field('extra_notes', 2000),
    ];
    foreach ($map as $label => $value) {
        if ($value !== '') $hireExtra[] = $label . ': ' . $value;
    }
}

$appliances = [];
if (isset($_POST['appliances']) && is_array($_POST['appliances'])) {
    foreach (array_slice($_POST['appliances'], 0, 30) as $a) {
        $a = mb_substr(trim((string)$a), 0, 60);
        if ($a !== '') $appliances[] = $a;
    }
}

/* What the trailer is for, now a tick list rather than free text. Falls back to
   the old single field so an older cached page still submits correctly. */
$uses = [];
if (isset($_POST['uses']) && is_array($_POST['uses'])) {
    foreach (array_slice($_POST['uses'], 0, 20) as $u) {
        $u = mb_substr(trim((string)$u), 0, 60);
        if ($u !== '') $uses[] = $u;
    }
}
if ($uses) { $use = implode(', ', $uses); }

// ── validation ───────────────────────────────────────────────────────────
$errors = [];
if (mb_strlen($name) < 2)                              $errors[] = 'name';
// The hire page asks for a written quotation by email, so a phone number is
// optional there. When one is given it still has to look like a real number.
if ($phoneOptional) {
    if ($phone !== '' && preg_match_all('/\d/', $phone) < 9) $errors[] = 'phone';
} elseif (preg_match_all('/\d/', $phone) < 9)          $errors[] = 'phone';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))        $errors[] = 'email';
if (mb_strlen($message) < 5)                           $errors[] = 'message';
if (!$consent)                                         $errors[] = 'consent';
if ($reqDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $reqDate)) $reqDate = '';

if ($errors) {
    respond(false, 'Please check: ' . implode(', ', $errors), 422);
}

// ── uploads ──────────────────────────────────────────────────────────────
$saved = [];
$uploadNote = '';

if (!empty($_FILES['photos']['name'][0])) {
    $u = $CFG['uploads'];
    $dir = $u['dir'];

    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    // belt and braces alongside the .htaccess rule at the site root
    if (is_dir($dir) && !file_exists($dir . '/.htaccess')) {
        @file_put_contents($dir . '/.htaccess', "Require all denied\nphp_flag engine off\n");
    }

    $finfo = class_exists('finfo') ? new finfo(FILEINFO_MIME_TYPE) : null;
    $count = min(count($_FILES['photos']['name']), (int)$u['max_files']);
    $rejected = 0;

    for ($i = 0; $i < $count; $i++) {
        if (($_FILES['photos']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) { $rejected++; continue; }

        $tmp = (string)$_FILES['photos']['tmp_name'][$i];
        if (!is_uploaded_file($tmp))                    { $rejected++; continue; }
        if (($_FILES['photos']['size'][$i] ?? 0) > $u['max_bytes']) { $rejected++; continue; }

        // trust the bytes, never the filename
        $mime = $finfo ? (string)$finfo->file($tmp) : (string)mime_content_type($tmp);
        if (!in_array($mime, $u['mime'], true))         { $rejected++; continue; }

        // a real raster image, not something wearing an image content type
        $dims = @getimagesize($tmp);
        if ($dims === false && $mime !== 'image/heic')  { $rejected++; continue; }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/heic' => 'heic',
            default      => null,
        };
        if ($ext === null) { $rejected++; continue; }

        $safeName = date('Ymd-His') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $safeName;

        if (@move_uploaded_file($tmp, $dest)) {
            @chmod($dest, 0644);
            $saved[] = ['path' => $dest, 'name' => $safeName, 'mime' => $mime];
        } else {
            $rejected++;
        }
    }

    if ($rejected > 0) {
        $uploadNote = $rejected . ' file(s) were rejected as too large or not an image.';
    }
}

// ── compose ──────────────────────────────────────────────────────────────
if ($isHire) {
    $lines = [
        'NEW HIRE ENQUIRY',
        str_repeat('=', 52),
        '',
        'Name:            ' . $name,
        'Company:         ' . (field('company', 140) ?: '-'),
        'Phone:           ' . ($phone ?: 'not given'),
        'Email:           ' . $email,
        'Location:        ' . ($town ?: '-'),
        '',
        'Wants:           ' . ($jobType ?: '-'),
        'Required from:   ' . ($reqDate ?: 'not given'),
        '',
    ];
    foreach ($hireExtra as $l) { $lines[] = $l; }
    $lines[] = '';
    $lines[] = 'MENU / INTENDED USE';
    $lines[] = str_repeat('-', 52);
    $lines[] = $message;
    $lines[] = '';
    $lines[] = str_repeat('-', 52);
    $lines[] = 'Files attached:  ' . count($saved);
    if ($uploadNote !== '') $lines[] = 'Note: ' . $uploadNote;
    $lines[] = 'Submitted:       ' . date('D j M Y, H:i');
    $lines[] = 'Source IP:       ' . $ip;
    $lines[] = '';
    $lines[] = 'Nothing has been promised to this customer about availability.';
    $body = implode("\r\n", $lines);
} elseif ($isHolding) {
    $lines = [
        'ENQUIRY VIA THE COMING SOON PAGE',
        str_repeat('=', 52),
        '',
        'Name:            ' . $name,
        'Phone:           ' . ($phone ?: 'not given'),
        'Email:           ' . $email,
        '',
        'MESSAGE',
        str_repeat('-', 52),
        $message,
        '',
        str_repeat('-', 52),
        'Sent while the website was showing the holding page.',
        'Submitted:       ' . date('D j M Y, H:i'),
        'Source IP:       ' . $ip,
    ];
    $body = implode("\r\n", $lines);
} elseif ($isContact) {
    $lines = [
        'NEW WEBSITE ENQUIRY',
        str_repeat('=', 52),
        '',
        'Name:            ' . $name,
        'Company:         ' . (field('company', 140) ?: '-'),
        'Phone:           ' . ($phone ?: 'not given'),
        'Email:           ' . $email,
        'Postcode:        ' . ($town ?: '-'),
        'Enquiry type:    ' . ($jobType ?: '-'),
        '',
        'MESSAGE',
        str_repeat('-', 52),
        $message,
        '',
        str_repeat('-', 52),
        'Images attached: ' . count($saved),
    ];
    if ($uploadNote !== '') $lines[] = 'Note: ' . $uploadNote;
    $lines[] = 'Submitted:       ' . date('D j M Y, H:i');
    $lines[] = 'Source IP:       ' . $ip;
    $body = implode("\r\n", $lines);
} else {
$lines = [
    'NEW QUOTE ENQUIRY',
    str_repeat('=', 52),
    '',
    'Name:            ' . $name,
    'Phone:           ' . $phone,
    'Email:           ' . $email,
    'Town:            ' . ($town ?: '-'),
    '',
    'Job type:        ' . ($jobType ?: '-'),
    'Size:            ' . ($size ?: 'not specified'),
    'Axle:            ' . ($axle ?: 'not specified'),
    'Intended use:    ' . ($use ?: '-'),
    'Tow vehicle:     ' . ($tow ?: '-'),
    '',
    'Power:           ' . ($power ?: '-'),
    'Appliances:      ' . ($appliances ? implode(', ', $appliances) : 'none selected'),
    '',
];
foreach (explode("\n", quote_extra_lines($tow)) as $l) {
    if (trim($l) !== '') $lines[] = $l;
}
$lines = array_merge($lines, [
    '',
    'Budget:          ' . ($budget ?: 'not given'),
    'Required date:   ' . ($reqDate ?: 'not given'),
    'Heard via:       ' . ($source ?: '-'),
    '',
    'MESSAGE',
    str_repeat('-', 52),
    $message,
    '',
    str_repeat('-', 52),
    'Photos attached: ' . count($saved),
]);
if ($uploadNote !== '') $lines[] = 'Note: ' . $uploadNote;
$lines[] = 'Submitted:       ' . date('D j M Y, H:i');
$lines[] = 'Source IP:       ' . $ip;

$body = implode("\r\n", $lines);
}

// ── send ─────────────────────────────────────────────────────────────────
// Hire enquiries can be routed to their own inbox from the admin area; when
// that is left blank they go to the usual one.
$to = $CFG['enquiry_inbox'];
if ($isHire && db_ready()) {
    $hireTo = trim((string)setting('hire.email', ''));
    if ($hireTo !== '' && filter_var($hireTo, FILTER_VALIDATE_EMAIL)) $to = $hireTo;
}

$subjectRaw = match (true) {
    $isHire    => 'Hire enquiry: '    . ($jobType ?: 'trailer') . ' - ' . $name,
    $isContact => 'Website enquiry: ' . ($jobType ?: 'general') . ' - ' . $name,
    $isHolding => 'Enquiry via coming soon page - ' . $name,
    default    => 'Quote enquiry: '   . ($jobType ?: 'trailer') . ' - ' . $name,
};
$subject   = '=?UTF-8?B?' . base64_encode(headerSafe($subjectRaw)) . '?=';

// The envelope sender must be a real mailbox on this domain or the host will
// refuse it. Set in config as 'mail_from'. The customer's own address rides in
// Reply-To, which is what "reply" should actually use, so sending from the same
// inbox it is delivered to does not get in the way of answering anyone.
$fromMailbox = $CFG['mail_from'] ?? ('enquiries@' . $CFG['domain']);
$boundary    = 'ctnw' . bin2hex(random_bytes(12));

$headers = [
    'MIME-Version: 1.0',
    'From: ' . headerSafe($CFG['name']) . ' <' . $fromMailbox . '>',
    'Reply-To: ' . headerSafe($name) . ' <' . headerSafe($email) . '>',
    'X-Mailer: catering-trailers-nw',
    'Content-Type: multipart/mixed; boundary="' . $boundary . '"',
];

$parts = [];
$parts[] = "--{$boundary}\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "Content-Transfer-Encoding: 8bit\r\n\r\n"
         . $body . "\r\n";

$attachedBytes = 0;
foreach ($saved as $file) {
    // keep the whole message inside what a shared host will accept
    if ($attachedBytes > 18 * 1024 * 1024) break;
    $raw = @file_get_contents($file['path']);
    if ($raw === false) continue;
    $attachedBytes += strlen($raw);
    $parts[] = "--{$boundary}\r\n"
             . 'Content-Type: ' . $file['mime'] . '; name="' . $file['name'] . "\"\r\n"
             . "Content-Transfer-Encoding: base64\r\n"
             . 'Content-Disposition: attachment; filename="' . $file['name'] . "\"\r\n\r\n"
             . chunk_split(base64_encode($raw)) . "\r\n";
}
$parts[] = "--{$boundary}--";

/**
 * Save first, send second. An enquiry that reaches the database can never be
 * lost to a mail server problem, and the admin area becomes the record of truth.
 */
$enquiryId = null;
try {
    if (db_ready()) {
        $storedSource = match (true) {
            $isHire    => 'Trailer Hire',
            $isContact => 'Contact form',
            $isHolding => 'Coming soon page',
            default    => $enqSource,
        };
        $storedExtra = match (true) {
            $isHire    => implode("\n", $hireExtra),
            $isContact => trim(($cCompany = field('company', 140)) !== '' ? 'Company: ' . $cCompany : ''),
            $isHolding => 'Sent from the coming soon page while the website was offline.',
            default    => quote_extra_lines($tow),
        };

        q("INSERT INTO enquiries
           (created_at, source, name, phone, email, town, job_type, body_length, axle,
            fit_out, appliances, power, budget, required_date, message, extra, files, status, ip, mailed)
           VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'New',?,0)", [
            date('c'), $storedSource, $name, $phone, $email, $town, $jobType, $size, $axle,
            $use, implode(', ', $appliances), $power, $budget, $reqDate, $message,
            $storedExtra,
            implode('|', array_column($saved, 'name')), $ip,
        ]);
        $enquiryId = (int)db()->lastInsertId();
    }
} catch (Throwable $e) {
    // a database problem must never stop the customer's enquiry being emailed
    error_log('CTNW enquiry save failed: ' . $e->getMessage());
}

$sent = @mail($to, $subject, implode('', $parts), implode("\r\n", $headers), '-f' . $fromMailbox);

if ($enquiryId && $sent) {
    try { q("UPDATE enquiries SET mailed = 1 WHERE id = ?", [$enquiryId]); } catch (Throwable $e) {}
}

/**
 * Acknowledge a hire enquiry to the customer.
 *
 * Deliberately confirms receipt and nothing else: no trailer is reserved and
 * no date is promised until somebody has actually checked. Sent best-effort,
 * and its failure never affects the reply the customer sees, because their
 * enquiry has already been recorded either way.
 */
if ($isHire) {
    $ackLines = [
        'Hello ' . $name . ',',
        '',
        'Thank you for your hire enquiry. This email confirms we have received it.',
        '',
        'We will review your requirements and come back to you by email with a tailored',
        'quotation. Please note that sending an enquiry does not reserve a trailer or',
        'confirm availability for your dates.',
        '',
        'WHAT YOU SENT US',
        str_repeat('-', 52),
        'Looking for:     ' . ($jobType ?: '-'),
        'Required from:   ' . ($reqDate ?: 'not given'),
        'Location:        ' . ($town ?: 'not given'),
        '',
        $message,
        '',
        str_repeat('-', 52),
        'If anything above is wrong, reply to this email and tell us.',
        '',
        $CFG['name'],
        $CFG['base_url'],
    ];
    $ackHeaders = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . headerSafe($CFG['name']) . ' <' . $fromMailbox . '>',
        'Reply-To: ' . $to,
        'Auto-Submitted: auto-replied',
        'X-Mailer: catering-trailers-nw',
    ];
    @mail(
        headerSafe($email),
        '=?UTF-8?B?' . base64_encode('We have your hire enquiry - ' . $CFG['name']) . '?=',
        implode("\r\n", $ackLines),
        implode("\r\n", $ackHeaders),
        '-f' . $fromMailbox
    );
}

// ── always keep our own copy, so nothing is lost if mail fails ───────────
$logDir = __DIR__ . '/logs';
@mkdir($logDir, 0700, true);
if (!file_exists($logDir . '/.htaccess')) {
    @file_put_contents($logDir . '/.htaccess', "Require all denied\n");
}
@file_put_contents(
    $logDir . '/enquiries.log',
    '[' . date('c') . '] ' . ($sent ? 'SENT' : 'MAIL-FAILED') . ' ' . $email . ' '
        . str_replace(["\r", "\n"], ' | ', $body) . "\n",
    FILE_APPEND | LOCK_EX
);

// Safely in the database means the customer has been served, whatever the mail
// server did. Only fail outright when we have neither.
if (!$sent && !$enquiryId) {
    respond(false, 'Mail could not be sent', 500);
}

respond(true);
