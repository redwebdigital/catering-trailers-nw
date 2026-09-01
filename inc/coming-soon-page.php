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

  .contact{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:.7rem;
    margin-top:clamp(1.8rem,5vw,2.6rem);
  }
  .contact a{
    display:inline-flex;
    align-items:center;
    gap:.55rem;
    padding:.85rem 1.4rem;
    border-radius:3px;
    font:600 .96rem/1 'Source Sans 3',system-ui,sans-serif;
    text-decoration:none;
    transition:transform .2s ease, background .2s ease, border-color .2s ease;
  }
  .contact a svg{width:17px;height:17px;fill:currentColor;flex:none}
  .contact .primary{background:#DE000F;color:#fff}
  .contact .primary:hover{background:#FF1F2E;transform:translateY(-1px)}
  .contact .ghost{
    background:transparent;color:#F4F7FA;
    border:1px solid rgba(143,163,184,.42);
  }
  .contact .ghost:hover{border-color:#8FA3B8;transform:translateY(-1px)}
  .contact a:focus-visible{outline:2px solid #FF1F2E;outline-offset:3px}

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

    <?php if ($showContact && ($email !== '' || $phone !== '')): ?>
      <div class="contact">
        <?php if ($email !== ''): ?>
          <a class="primary" href="mailto:<?= e($email) ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 5h18v14H3zm0 0 9 7 9-7"/></svg>
            Email us
          </a>
        <?php endif; ?>
        <?php if ($phone !== ''): ?>
          <a class="ghost" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $phone)) ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.24 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/></svg>
            <?= e($phone) ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <p class="foot"><?= e($CFG['address']['locality'] ?? '') ?> &middot; North West</p>
  </main>

</body>
</html>
