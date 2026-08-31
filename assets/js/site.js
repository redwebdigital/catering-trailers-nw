/* =========================================================================
   Site-wide behaviour: navigation, entrance choreography, the sticky mobile
   call to action, the technical elevation drawing, the spec builder, and the
   multi-step quote form.

   Everything honours reduced motion, in both directions, live.
   ========================================================================= */
(function () {
  'use strict';

  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  /* ---------- mobile navigation ------------------------------------------ */
  var burger = document.getElementById('burger');
  var nav = document.getElementById('nav');

  if (burger && nav) {
    burger.addEventListener('click', function () {
      var open = burger.getAttribute('aria-expanded') === 'true';
      burger.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('open', !open);
    });
    nav.addEventListener('click', function (e) {
      if (e.target.closest('a')) {
        burger.setAttribute('aria-expanded', 'false');
        nav.classList.remove('open');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('open')) {
        burger.setAttribute('aria-expanded', 'false');
        nav.classList.remove('open');
        burger.focus();
      }
    });
  }

  /* ---------- entrance choreography --------------------------------------
     IntersectionObserver adds .in; once the transition has finished we add
     .settled, which retires the stagger delays. Without that retirement every
     later hover on a staggered sibling lags by its delay, forever.          */
  var risers = document.querySelectorAll('.rise');

  if (reduce.matches || !('IntersectionObserver' in window)) {
    risers.forEach(function (el) { el.classList.add('in', 'settled'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        el.classList.add('in');
        io.unobserve(el);
        setTimeout(function () { el.classList.add('settled'); }, 1300);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    risers.forEach(function (el) { io.observe(el); });
  }

  /* ---------- the technical elevation ------------------------------------
     Measure each path so the dash animation is exact rather than guessed.  */
  var elev = document.querySelector('.elev');
  if (elev) {
    elev.querySelectorAll('path, rect, circle, ellipse, line, polyline').forEach(function (p) {
      if (typeof p.getTotalLength !== 'function') return;
      try {
        var len = Math.ceil(p.getTotalLength());
        if (len) p.style.setProperty('--len', len);
      } catch (e) { /* non-renderable node, skip */ }
    });

    if (reduce.matches || !('IntersectionObserver' in window)) {
      elev.classList.add('drawn');
    } else {
      new IntersectionObserver(function (es, obs) {
        if (es[0].isIntersecting) { elev.classList.add('drawn'); obs.disconnect(); }
      }, { threshold: 0.25 }).observe(elev);
    }
  }

  /* ---------- the spec builder -------------------------------------------
     The one interactive moment. Choosing a length, an axle and appliances
     redraws the elevation live, then carries the choices into the quote
     form. The visitor performs "built to your menu" rather than reading it. */
  var spec = document.getElementById('spec');
  if (spec) {
    // Defaults come from the markup, which comes from the admin area, so adding
    // or renaming an option never needs a code change.
    var state = {
      length: spec.getAttribute('data-default-length') || '3.0',
      axle:   spec.getAttribute('data-default-axle') || 'single',
      use:    []
    };
    var defaultWidth = parseInt(spec.getAttribute('data-default-width'), 10) || 244;

    function labelFor(group, value) {
      var el = spec.querySelector('.chip[data-group="' + group + '"][data-value="' + value + '"]');
      return el ? el.textContent.trim() : value;
    }
    function widthFor(value) {
      var el = spec.querySelector('.chip[data-group="length"][data-value="' + value + '"]');
      var w = el ? parseInt(el.getAttribute('data-draw'), 10) : NaN;
      return isNaN(w) || w <= 0 ? defaultWidth : w;
    }

    var $ = function (id) { return document.getElementById(id); };
    var svgBody = $('specBody'), svgFill = $('specFill'), svgHatch = $('specHatch'),
        svgChassis = $('specChassis'), svgAxle1 = $('specAxle1'), svgAxle2 = $('specAxle2'),
        svgDimW = $('specDimW'), svgDimLbl = $('specDimLbl'),
        svgDimH = $('specDimH'), svgDimHLbl = $('specDimHLbl'),
        out = $('specOut'), link = $('specGo');

    var X = 46, TOP = 118, H = 70;                 // body origin and height

    function render() {
      var w = widthFor(state.length);
      var r  = X + w;                              // right edge of the body
      var bot = TOP + H;                           // 188

      if (svgBody) svgBody.setAttribute('width', w);
      if (svgFill) svgFill.setAttribute('width', w);

      // the serving hatch stays proportional to the body it sits on
      var hx = X + w * 0.24, hw = w * 0.53;
      if (svgHatch) svgHatch.setAttribute('d',
        'M' + hx + ' 170 H' + (hx + hw) + ' V132 H' + hx + ' Z' +
        ' M' + hx + ' 132 L' + (hx - 30) + ' 104 H' + (hx + hw - 30) + ' L' + (hx + hw) + ' 132');

      if (svgChassis) svgChassis.setAttribute('d',
        'M' + X + ' ' + bot + ' H' + r + ' M' + X + ' ' + bot + ' L16 202 H4 M26 202 V213');

      // axle centres: single sits under the load, twin straddles it
      var c1 = state.axle === 'twin' ? X + w * 0.55 : X + w * 0.62;
      var c2 = c1 + 44;
      if (svgAxle1) {
        svgAxle1.querySelectorAll('circle').forEach(function (c) { c.setAttribute('cx', c1); });
      }
      if (svgAxle2) {
        svgAxle2.style.display = state.axle === 'twin' ? '' : 'none';
        svgAxle2.querySelectorAll('circle').forEach(function (c) { c.setAttribute('cx', c2); });
      }

      if (svgDimW) svgDimW.setAttribute('d',
        'M' + X + ' 224 H' + r + ' M' + X + ' 218 V230 M' + r + ' 218 V230');
      if (svgDimLbl) {
        svgDimLbl.setAttribute('x', X + w / 2);
        svgDimLbl.textContent = labelFor('length', state.length);
      }
      if (svgDimH) svgDimH.setAttribute('d',
        'M' + (r + 16) + ' ' + TOP + ' V' + bot +
        ' M' + (r + 10) + ' ' + TOP + ' H' + (r + 22) +
        ' M' + (r + 10) + ' ' + bot + ' H' + (r + 22));
      if (svgDimHLbl) svgDimHLbl.setAttribute('x', r + 30);

      var uses = state.use.length ? state.use.join(', ') : 'not chosen yet';
      if (out) {
        var strip = function (t) { return String(t).replace(/[<>&]/g, ''); };
        out.innerHTML =
          '<b>' + strip(labelFor('length', state.length)) + '</b> body  ·  <b>' +
          strip(labelFor('axle', state.axle)) + '</b> axle  ·  ' +
          'Fit-out: <b>' + strip(uses) + '</b>';
      }
      if (link) {
        link.href = '/request-a-quote?len=' + encodeURIComponent(state.length) +
                    '&axle=' + encodeURIComponent(state.axle) +
                    '&use=' + encodeURIComponent(state.use.join('|'));
      }
    }

    spec.addEventListener('click', function (e) {
      var chip = e.target.closest('.chip');
      if (!chip) return;
      var group = chip.getAttribute('data-group');
      var value = chip.getAttribute('data-value');

      if (group === 'use') {                       // multi-select
        var i = state.use.indexOf(value);
        if (i > -1) state.use.splice(i, 1); else state.use.push(value);
        chip.setAttribute('aria-pressed', String(i === -1));
      } else {                                     // single-select
        state[group] = value;
        spec.querySelectorAll('.chip[data-group="' + group + '"]').forEach(function (c) {
          c.setAttribute('aria-pressed', String(c === chip));
        });
      }
      render();
    });

    render();
  }

  /* ---------- sticky mobile call to action -------------------------------
     Appears once the visitor is past the hero, hides again at the footer so
     it never covers the real form.                                          */
  var sticky = document.getElementById('sticky');
  if (sticky) {
    var foot = document.querySelector('.foot');
    var lastY = window.scrollY;
    var ticking = false;

    function evaluate() {
      ticking = false;
      var y = window.scrollY;
      var past = y > window.innerHeight * 0.6;
      var atFoot = false;
      if (foot) {
        var fr = foot.getBoundingClientRect();
        atFoot = fr.top < window.innerHeight - 40;
      }
      sticky.classList.toggle('up', past && !atFoot);
      lastY = y;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(evaluate); }
    }, { passive: true });
    evaluate();
  }

  /* ---------- pause every animation on a hidden tab -----------------------
     animation-play-state does not inherit, so it is set on a body class that
     reaches elements and pseudo-elements directly.                          */
  document.addEventListener('visibilitychange', function () {
    document.body.classList.toggle('paused', document.hidden);
  });

  /* ---------- reduced motion, live, in BOTH directions -------------------- */
  function pinToFinalStates() {
    document.querySelectorAll('.rise').forEach(function (el) {
      el.classList.add('in', 'settled');
    });
    if (elev) elev.classList.add('drawn');
  }
  if (reduce.addEventListener) {
    reduce.addEventListener('change', function (e) {
      if (e.matches) pinToFinalStates();
    });
  }
})();
