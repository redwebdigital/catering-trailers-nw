<?php
/**
 * The holding page itself. Standalone: no site header, footer or stylesheet, so
 * it renders in one request and cannot be broken by a change elsewhere.
 *
 * Expects $heading, $message, $showContact, $email, $phone, $logo and $CFG.
 */
declare(strict_types=1);
?>
<!doctype html>
<html lang="en-GB">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<title><?= e($heading) ?> | <?= e($CFG['name']) ?></title>
<meta name="description" content="<?= e($message) ?>">
<meta name="theme-color" content="#0B1A2B">
<link rel="icon" href="<?= e((string)setting('seo.favicon', '/favicon.svg')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700&family=Source+Sans+3:wght@400;600&display=swap">
<style>
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0;
    background:#0B1A2B;
    color:#F4F7FA;
    font:400 1rem/1.65 'Source Sans 3',system-ui,-apple-system,'Segoe UI',sans-serif;
    -webkit-font-smoothing:antialiased;
    display:grid;
    place-items:center;
    padding:clamp(1.4rem,5vw,3rem);
    position:relative;
    overflow-x:hidden;
  }

  /* a soft pool of light behind the card, nothing else — the logo already
     carries the red arc and a second one competed with it */
  body::after{
    content:"";
    position:fixed;inset:0;
    background:radial-gradient(115% 75% at 50% 18%, rgba(26,48,73,.8), transparent 64%);
    pointer-events:none;
    z-index:0;              /* behind the card, or it tints the whole page */
  }

  .card{
    position:relative;
    z-index:1;
    width:100%;
    max-width:640px;
    text-align:center;
    animation:lift .9s cubic-bezier(.16,1,.3,1) both;
  }

  .logo{
    display:block;
    width:min(430px,82vw);
    height:auto;
    margin:0 auto clamp(1.8rem,5vw,2.6rem);
    filter:drop-shadow(0 8px 26px rgba(0,0,0,.35));
  }

  .rule{
    width:64px;height:3px;
    margin:0 auto clamp(1.4rem,4vw,2rem);
    background:#DE000F;
    border-radius:2px;
    transform-origin:center;
    animation:draw 1.1s .25s cubic-bezier(.16,1,.3,1) both;
  }

  h1{
    font-family:Archivo,'Arial Narrow',sans-serif;
    font-weight:700;
    font-size:clamp(1.75rem,5.6vw,2.9rem);
    line-height:1.08;
    letter-spacing:-.02em;
    text-wrap:balance;
    margin:0 0 1rem;
  }

  p.msg{
    font-size:clamp(1rem,2.4vw,1.13rem);
    color:#A9BBCD;
    max-width:46ch;
    margin:0 auto;
    text-wrap:pretty;
  }

  /* ---------- enquiry form ---------- */
  .enq{
    margin-top:clamp(1.9rem,5vw,2.6rem);
    text-align:left;
  }
  .enq__lead{
    margin:0 0 1rem;
    text-align:center;
    font-size:.95rem;
    color:#A9BBCD;
  }
  .row{display:grid;gap:.7rem;grid-template-columns:1fr 1fr}
  @media (max-width:520px){.row{grid-template-columns:1fr}}

  .enq input[type=text],
  .enq input[type=email],
  .enq input[type=tel],
  .enq textarea{
    width:100%;
    padding:.8rem .9rem;
    margin-bottom:.7rem;
    background:rgba(255,255,255,.05);
    border:1px solid rgba(143,163,184,.34);
    border-radius:3px;
    color:#F4F7FA;
    font:400 1rem/1.5 'Source Sans 3',system-ui,sans-serif;
    transition:border-color .18s ease, background .18s ease;
  }
  .row input{margin-bottom:0}
  .enq textarea{resize:vertical;min-height:110px}
  .enq ::placeholder{color:#7C8FA3;opacity:1}
  .enq input:focus,.enq textarea:focus{
    outline:none;
    border-color:#DE000F;
    background:rgba(255,255,255,.08);
  }

  .consent{
    display:flex;
    align-items:flex-start;
    gap:.6rem;
    margin:.5rem 0 1.1rem;
    font-size:.85rem;
    line-height:1.5;
    color:#A9BBCD;
    cursor:pointer;
  }
  .consent input{margin-top:.2rem;accent-color:#DE000F;flex:none;width:16px;height:16px}

  button.primary{
    display:block;
    width:100%;
    padding:.95rem 1.4rem;
    border:0;
    border-radius:3px;
    background:#DE000F;
    color:#fff;
    font:600 1rem/1 'Source Sans 3',system-ui,sans-serif;
    cursor:pointer;
    transition:background .2s ease, transform .2s ease;
  }
  button.primary:hover{background:#FF1F2E;transform:translateY(-1px)}
  button.primary[disabled]{opacity:.6;cursor:default;transform:none}
  .enq :focus-visible{outline:2px solid #FF1F2E;outline-offset:3px}

  .note{
    padding:.9rem 1.1rem;
    border-radius:3px;
    border-left:3px solid;
    font-size:.94rem;
    text-align:left;
    margin:0 0 1rem;
  }
  .note--ok{background:rgba(31,168,85,.12);border-left-color:#1FA855;color:#D9F2E2}
  .note--bad{background:rgba(222,0,15,.12);border-left-color:#DE000F;color:#FBD9DC}
  .note a{color:inherit}

  .hp{position:absolute;left:-9999px}
  .sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}

  .foot{
    margin-top:clamp(2.2rem,6vw,3.2rem);
    font:500 .72rem/1.5 'Source Sans 3',system-ui,sans-serif;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:#5E7488;
  }

  @keyframes lift{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:none}}
  @keyframes draw{from{transform:scaleX(0)}to{transform:scaleX(1)}}

  @media (prefers-reduced-motion:reduce){
    .card,.rule{animation:none}
    .contact a{transition:none}
  }
</style>
</head>
<body>

  <main class="card">
    <?php
    /* The site logo is 91% opaque, which reads grey on this background, so the
       holding page uses a solid version of the same artwork. If the owner has
       pointed the logo setting somewhere else, that wins. */
    $useDefault = ($logo === '/assets/img/logo.png' || $logo === '');
    ?>
    <picture>
      <?php if ($useDefault): ?>
        <source srcset="/assets/img/logo-solid.webp" type="image/webp">
      <?php endif; ?>
      <img class="logo" src="<?= e($useDefault ? '/assets/img/logo-solid@1x.png' : $logo) ?>"
           <?= $useDefault ? 'srcset="/assets/img/logo-solid@1x.png 460w, /assets/img/logo-solid.png 920w"' : '' ?>
           sizes="(max-width:520px) 82vw, 430px"
           alt="<?= e($CFG['name']) ?>" width="460" height="153"
           fetchpriority="high" decoding="async">
    </picture>

    <div class="rule" aria-hidden="true"></div>

    <h1><?= e($heading) ?></h1>
    <p class="msg"><?= e($message) ?></p>

    <?php if ($showContact): ?>
      <div class="enq">
        <p class="note note--ok" id="csOk"<?= $sent ? '' : ' hidden' ?>>
          <strong>Thank you.</strong> Your enquiry has reached us and we will reply by email.
        </p>
        <p class="note note--bad" id="csBad" hidden>
          That did not send. Please email
          <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a> instead.
        </p>

        <form id="csForm" action="/quote-handler.php" method="post"<?= $sent ? ' hidden' : '' ?>>
          <div class="hp" aria-hidden="true">
            <label for="company_website">Leave this empty</label>
            <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
          </div>
          <input type="hidden" name="started_at" value="<?= time() ?>">
          <input type="hidden" name="enquiry_source" value="holding">

          <p class="enq__lead">Send us a message and we will come straight back to you.</p>

          <div class="row">
            <label class="sr" for="cs_name">Your name</label>
            <input id="cs_name" name="name" type="text" required maxlength="120"
                   autocomplete="name" placeholder="Your name">

            <label class="sr" for="cs_email">Email address</label>
            <input id="cs_email" name="email" type="email" required maxlength="180"
                   autocomplete="email" inputmode="email" placeholder="Email address">
          </div>

          <label class="sr" for="cs_phone">Phone number, optional</label>
          <input id="cs_phone" name="phone" type="tel" maxlength="30"
                 autocomplete="tel" inputmode="tel" placeholder="Phone number (optional)">

          <label class="sr" for="cs_message">Your message</label>
          <textarea id="cs_message" name="message" required rows="4" maxlength="4000"
                    placeholder="What can we help with? New trailer, repair, refurbishment or hire."></textarea>

          <label class="consent">
            <input type="checkbox" name="consent" value="yes" required>
            <span>I am happy for <?= e($CFG['name']) ?> to contact me about this enquiry.</span>
          </label>

          <button class="primary" type="submit" id="csSend">Send enquiry</button>
        </form>
      </div>
    <?php endif; ?>

    <p class="foot"><?= e($CFG['address']['locality'] ?? '') ?> &middot; North West</p>
  </main>

<script>
/* Sends without leaving the page. With scripting off the form posts normally
   and comes back to /?sent=1, which shows the same thank you. */
(function () {
  var form = document.getElementById('csForm');
  if (!form) return;
  var ok   = document.getElementById('csOk');
  var bad  = document.getElementById('csBad');
  var send = document.getElementById('csSend');

  form.addEventListener('submit', function (e) {
    if (!form.checkValidity()) return;          // let the browser show its own prompts
    e.preventDefault();

    send.disabled = true;
    send.textContent = 'Sending...';
    bad.hidden = true;

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'fetch' }
    })
      .then(function (r) { return r.json().catch(function () { return { ok: r.ok }; }); })
      .then(function (res) {
        if (!res.ok) throw new Error(res.error || 'failed');
        form.hidden = true;
        ok.hidden = false;
        ok.setAttribute('tabindex', '-1');
        ok.focus({ preventScroll: true });
      })
      .catch(function () {
        bad.hidden = false;
        send.disabled = false;
        send.textContent = 'Send enquiry';
      });
  });
})();
</script>

</body>
</html>
