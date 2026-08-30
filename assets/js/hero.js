/* =========================================================================
   The scroll-scrubbed hero.

   Scroll through the pinned region maps 0..1 and drives the film's time.
   The page settles exactly when the film reaches its composed resting frame.

   Engineering rules, all load-bearing:
     - fetch the video as a Blob (many hosts lack HTTP Range; seeks would
       otherwise clamp to zero on the live site while working locally)
     - lerp the displayed time in a rAF loop that RESTS when converged
     - gate seeks so they never overlap, with an escape so it cannot deadlock
     - write to the DOM only on change
     - five static-hero gates, decided LIVE, matching the CSS character for
       character
     - the page is complete and correct if the video never loads at all
   ========================================================================= */
(function () {
  'use strict';

  var stage = document.getElementById('stage');
  if (!stage) return;

  var pin      = document.getElementById('heroPin');
  var video    = document.getElementById('heroVideo');
  var poster   = document.getElementById('heroPoster');
  var ring     = document.getElementById('heroRing');
  var cue      = document.getElementById('heroCue');
  var bandEls  = Array.prototype.slice.call(document.querySelectorAll('.cap'));

  var VIDEO_URL   = stage.getAttribute('data-video');
  var POSTER_URL  = stage.getAttribute('data-poster');
  var POSTER_ALT  = stage.getAttribute('data-poster-fallback');
  var VIDEO_BYTES = parseInt(stage.getAttribute('data-bytes'), 10) || 2153390;

  /* Each caption owns a band of scroll progress. Paced in scroll distance,
     validated by the flick test, never in seconds. */
  var bands = bandEls.map(function (el) {
    return {
      el: el,
      a: parseFloat(el.getAttribute('data-a')),
      b: parseFloat(el.getAttribute('data-b')),
      ramp: parseFloat(el.getAttribute('data-ramp')) || 0,
      op: -1,        // cached opacity, so we only touch the DOM on change
      k: -1          // cached assembly progress
    };
  });

  /* ---------- split the headline text into word and character spans ------
     Seeded so the "random" offsets are identical on every load.            */
  function rng(seed) {
    var s = seed >>> 0;
    return function () {
      s = (s * 1664525 + 1013904223) >>> 0;
      return s / 4294967296;
    };
  }

  function split(el, seed) {
    var text = el.textContent;
    var rand = rng(seed);
    var mode = el.getAttribute('data-split') || 'char';

    var sr = document.createElement('span');
    sr.className = 'sr';
    sr.textContent = text;

    var vis = document.createElement('span');
    vis.setAttribute('aria-hidden', 'true');

    var words = text.split(/(\s+)/);
    var ci = 0;
    var total = text.replace(/\s/g, '').length;

    words.forEach(function (word, wi) {
      if (/^\s+$/.test(word)) { vis.appendChild(document.createTextNode(word)); return; }
      var w = document.createElement('span');
      w.className = 'w';

      if (mode === 'word') {
        w.style.setProperty('--th', (wi / words.length * 0.5).toFixed(3));
        w.textContent = word;
      } else {
        for (var i = 0; i < word.length; i++) {
          var c = document.createElement('span');
          c.className = 'c';
          c.textContent = word[i];
          if (mode === 'grid') {
            // ordered stagger, reading order
            c.style.setProperty('--th', (ci / total * 0.55 + rand() * 0.06).toFixed(3));
            c.style.setProperty('--jx', ((rand() - 0.5) * 34).toFixed(1) + 'px');
            c.style.setProperty('--jy', '0px');
            c.style.setProperty('--jr', '0deg');
          } else {
            c.style.setProperty('--th', (rand() * 0.55).toFixed(3));
            c.style.setProperty('--jx', ((rand() - 0.5) * 46).toFixed(1) + 'px');
            c.style.setProperty('--jy', ((rand() - 0.5) * 34).toFixed(1) + 'px');
            c.style.setProperty('--jr', ((rand() - 0.5) * 22).toFixed(1) + 'deg');
          }
          w.appendChild(c);
          ci++;
        }
      }
      vis.appendChild(w);
    });

    el.textContent = '';
    el.appendChild(sr);
    el.appendChild(vis);
  }

  var splitSeed = 7;
  document.querySelectorAll('[data-split]').forEach(function (el) {
    split(el, splitSeed++);
  });

  /* ---------- maths ------------------------------------------------------ */
  function clamp(v, lo, hi) { return Math.min(hi, Math.max(lo, v)); }
  function smoothstep(p, e0, e1) {
    var t = clamp((p - e0) / (e1 - e0), 0, 1);
    return t * t * (3 - 2 * t);
  }

  function heroProgress() {
    if (!pin) return 0;
    var r = pin.getBoundingClientRect();
    var range = pin.offsetHeight - window.innerHeight;
    if (range <= 0) return 0;
    return clamp(-r.top / range, 0, 1);
  }

  /* ---------- caption bands ---------------------------------------------- */
  var loadK = 0;   // band one's one-time assembly on load, hands over to scroll

  function updateCaptions(p) {
    for (var i = 0; i < bands.length; i++) {
      var bd = bands[i];
      var f = Math.min(0.02, (bd.b - bd.a) / 3);
      var inEase  = (i === 0)               ? 1 : smoothstep(p, bd.a, bd.a + f);
      var outEase = (i === bands.length - 1) ? 1 : (1 - smoothstep(p, bd.b - f, bd.b));
      var op = inEase * outEase;

      var ramp = bd.ramp || Math.min(0.025, (bd.b - bd.a) * 0.35);
      var k = clamp((p - bd.a) / ramp, 0, 1);
      if (i === 0) k = Math.max(k, loadK);

      // delta-gate every write; per-frame DOM touches are half of choppy
      if (Math.abs(op - bd.op) > 0.004) {
        bd.op = op;
        bd.el.style.opacity = op.toFixed(3);
        bd.el.classList.toggle('is-live', op > 0.5);
      }
      if (Math.abs(k - bd.k) > 0.008) {
        bd.k = k;
        bd.el.style.setProperty('--k', k.toFixed(3));
      }
    }
  }

  /* ---------- gated seeks ------------------------------------------------ */
  var seekBusy = false;
  var pendingTime = null;

  function requestSeek(t) {
    if (!video || !video.duration || isNaN(video.duration)) return;
    if (seekBusy) { pendingTime = t; return; }
    seekBusy = true;
    try { video.currentTime = t; }
    catch (err) { seekBusy = false; }
  }

  if (video) {
    video.addEventListener('seeked', function () {
      seekBusy = false;
      if (pendingTime !== null) {
        var t = pendingTime;
        pendingTime = null;
        requestSeek(t);
      }
    });
    // the deadlock escape: a seek that errors never fires 'seeked'
    video.addEventListener('error', function () {
      seekBusy = false;
      pendingTime = null;
      failVideo();
    });
  }

  /* ---------- the drive loop, which rests -------------------------------- */
  var target = 0, shown = 0, rafId = null, lastTick = 0;
  var heroOnScreen = true;

  function tick(now) {
    var dt = Math.min(100, now - (lastTick || now));
    lastTick = now;
    var k = 0.16;
    // frame-rate independent: normalised to a 60fps reference, so a 120Hz
    // screen converges at the same speed as a 60Hz one
    shown += (target - shown) * (1 - Math.pow(1 - k, dt / 16.667));

    if (Math.abs(target - shown) < 0.0005) {
      shown = target;
      rafId = null;
      lastTick = 0;
    } else {
      rafId = requestAnimationFrame(tick);
    }

    if (video && video.duration) requestSeek(shown * video.duration);
    updateCaptions(shown);
  }

  function onScroll() {
    target = heroProgress();
    if (rafId === null && heroOnScreen) rafId = requestAnimationFrame(tick);
  }

  if (pin && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (es) {
      heroOnScreen = es[0].isIntersecting;
      if (heroOnScreen && rafId === null && scrubOn) rafId = requestAnimationFrame(tick);
    }, { rootMargin: '120px' }).observe(pin);
  }

  /* ---------- loading the film ------------------------------------------- */
  var started = false;

  function failVideo() {
    stage.classList.add('video-failed');
    if (ring) ring.style.opacity = '0';
    if (cue) cue.hidden = false;
  }

  function startBlobFetch() {
    if (started) return;
    started = true;
    loadHeroBlob().catch(failVideo);
  }

  function loadHeroBlob() {
    var ctrl = new AbortController();
    var watchdog = setTimeout(function () { ctrl.abort(); }, 20000);

    return fetch(VIDEO_URL, { signal: ctrl.signal }).then(function (res) {
      if (!res.ok) throw new Error('HTTP ' + res.status);
      var total = Number(res.headers.get('Content-Length')) || VIDEO_BYTES;

      if (!res.body || !res.body.getReader) {          // no streams: plain path
        clearTimeout(watchdog);
        return res.blob();
      }

      var reader = res.body.getReader();
      var chunks = [];
      var got = 0, lastRing = 0;

      return (function pump() {
        return reader.read().then(function (r) {
          if (r.done) { clearTimeout(watchdog); return new Blob(chunks); }
          clearTimeout(watchdog);
          watchdog = setTimeout(function () { ctrl.abort(); }, 20000);
          chunks.push(r.value);
          got += r.value.length;
          var frac = Math.min(1, got / total);
          var now = performance.now();
          if (now - lastRing > 100 || frac === 1) {     // throttled, but the
            lastRing = now;                             // final write always lands
            if (ring) ring.style.setProperty('--ld', Math.round(126 * (1 - frac)));
          }
          return pump();
        });
      })();
    }).then(function (blob) {
      if (ring) ring.style.setProperty('--ld', 0);
      video.src = URL.createObjectURL(blob);
      video.load();
      video.addEventListener('canplay', function () {
        requestSeek(heroProgress() * video.duration);
        stage.classList.add('video-ready');
      }, { once: true });
    });
  }

  function initHeroOnce() {
    // The poster wins the bandwidth race by design: paint it, then fetch the
    // film. WebP first, with the JPEG as the fallback for anything that cannot
    // decode it. Either way the blob fetch starts once the poster resolves.
    var img = new Image();
    img.onload = function () {
      if (poster) poster.style.backgroundImage = "url('" + img.src + "')";
      startBlobFetch();
    };
    img.onerror = function () {
      if (POSTER_ALT && img.src.indexOf(POSTER_ALT) === -1) {
        img.src = POSTER_ALT;                 // one retry on the fallback
        return;
      }
      startBlobFetch();
    };
    img.src = POSTER_URL;
    setTimeout(startBlobFetch, 4000);   // a hung poster never blocks forever

    // band one assembles once on load, then hands over to scroll
    var t0 = performance.now();
    (function ramp(now) {
      loadK = clamp((now - t0) / 900, 0, 1);
      updateCaptions(shown);
      if (loadK < 1) requestAnimationFrame(ramp);
    })(t0);
  }

  /* ---------- THE FIVE STATIC-HERO GATES ---------------------------------
     These strings must match the CSS media queries character for character,
     or one side loads assets the other side hides.                          */
  var GATES = [
    '(max-width: 720px)',
    '(orientation: portrait) and (max-width: 1024px)',
    '(orientation: portrait) and (pointer: coarse)',
    '(orientation: landscape) and (pointer: coarse) and (max-height: 560px)',
    '(prefers-reduced-motion: reduce)'
  ];

  var scrubOn = false;
  var inited = false;

  function enableScrub() {
    if (scrubOn) return;
    scrubOn = true;
    if (!inited) { inited = true; initHeroOnce(); }
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    bands.forEach(function (b) { b.op = -1; b.k = -1; });   // reset caches
    updateCaptions(heroProgress());
    onScroll();     // re-seek to the current position; without this the frame
                    // sits stale until the visitor scrolls
  }

  function disableScrub() {
    if (!scrubOn) return;
    scrubOn = false;
    window.removeEventListener('scroll', onScroll);
    window.removeEventListener('resize', onScroll);
    if (rafId !== null) { cancelAnimationFrame(rafId); rafId = null; }
  }

  function applyHeroMode() {
    var isStatic = GATES.some(function (q) { return window.matchMedia(q).matches; });
    if (isStatic) disableScrub(); else enableScrub();
  }

  // keep the query lists referenced; unreferenced ones have historically
  // lost their listeners in older browsers
  var MQLS = GATES.map(function (q) { return window.matchMedia(q); });
  MQLS.forEach(function (m) {
    if (m.addEventListener) m.addEventListener('change', applyHeroMode);
    else if (m.addListener) m.addListener(applyHeroMode);
  });

  applyHeroMode();
})();
