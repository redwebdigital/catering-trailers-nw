/* =========================================================================
   The multi-step quote form.

   Progressive enhancement: with JavaScript off, every fieldset is visible and
   the form still posts to the PHP handler in one go. The steps, the validation
   and the photo previews are all additive.
   ========================================================================= */
(function () {
  'use strict';

  var form = document.getElementById('quoteForm');
  if (!form) return;

  var steps   = Array.prototype.slice.call(form.querySelectorAll('.step'));
  var marks   = Array.prototype.slice.call(document.querySelectorAll('.stepper__i'));
  var prevBtn = document.getElementById('prevBtn');
  var nextBtn = document.getElementById('nextBtn');
  var sendBtn = document.getElementById('sendBtn');
  var okNote  = document.getElementById('formOk');
  var badNote = document.getElementById('formBad');
  var at = 0;

  /* ---------- step machinery --------------------------------------------- */
  function show(i) {
    at = Math.max(0, Math.min(steps.length - 1, i));
    steps.forEach(function (s, n) { s.classList.toggle('now', n === at); });
    marks.forEach(function (m, n) {
      m.classList.toggle('now', n === at);
      m.classList.toggle('done', n < at);
    });
    prevBtn.hidden = at === 0;
    nextBtn.hidden = at === steps.length - 1;
    sendBtn.hidden = at !== steps.length - 1;

    // move focus to the step so screen readers follow, without yanking the
    // page when the form is already in view
    var head = steps[at].querySelector('input, select, textarea');
    if (head && at > 0) head.focus({ preventScroll: true });

    var top = form.getBoundingClientRect().top + window.scrollY - 90;
    if (window.scrollY > top) window.scrollTo({ top: top, behavior: 'smooth' });
  }

  /* ---------- validation -------------------------------------------------- */
  function setErr(name, on, text) {
    var el = form.querySelector('[data-err="' + name + '"]');
    if (!el) return;
    if (text) el.textContent = text;
    el.hidden = !on;
  }

  function validEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[a-z]{2,}$/i.test(v);
  }

  function checkStep(i) {
    var ok = true;
    var scope = steps[i];

    scope.querySelectorAll('[required]').forEach(function (f) {
      var name = f.name.replace('[]', '');
      var good;

      if (f.type === 'checkbox') {
        good = f.checked;
      } else if (f.type === 'radio') {
        good = !!form.querySelector('[name="' + f.name + '"]:checked');
      } else if (f.type === 'email') {
        good = validEmail(f.value.trim());
      } else {
        good = f.value.trim().length > 1;
      }

      if (!good) {
        ok = false;
        setErr(name, true);
        f.setAttribute('aria-invalid', 'true');
      } else {
        setErr(name, false);
        f.removeAttribute('aria-invalid');
      }
    });

    if (!ok) {
      var first = scope.querySelector('[aria-invalid="true"]');
      if (first) first.focus();
    }
    return ok;
  }

  nextBtn.addEventListener('click', function () { if (checkStep(at)) show(at + 1); });
  prevBtn.addEventListener('click', function () { show(at - 1); });

  // Enter advances rather than submitting a half-filled form
  form.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter') return;
    if (e.target.tagName === 'TEXTAREA') return;
    e.preventDefault();
    if (at < steps.length - 1) { if (checkStep(at)) show(at + 1); }
    else form.requestSubmit();
  });

  /* ---------- photo picker ------------------------------------------------ */
  var input  = document.getElementById('photos');
  var drop   = document.getElementById('drop');
  var thumbs = document.getElementById('thumbs');
  var MAX_FILES = 6;
  var MAX_BYTES = 8 * 1024 * 1024;
  var picked = [];

  function syncInput() {
    // rebuild the FileList the form will actually post
    var dt = new DataTransfer();
    picked.forEach(function (f) { dt.items.add(f); });
    input.files = dt.files;
  }

  function paint() {
    thumbs.innerHTML = '';
    picked.forEach(function (file, i) {
      var box = document.createElement('div');
      box.className = 'thumb';
      var img = document.createElement('img');
      img.alt = '';
      var url = URL.createObjectURL(file);
      img.src = url;
      img.onload = function () { URL.revokeObjectURL(url); };
      var x = document.createElement('button');
      x.type = 'button';
      x.innerHTML = '&times;';
      x.setAttribute('aria-label', 'Remove photo ' + (i + 1));
      x.addEventListener('click', function () {
        picked.splice(i, 1);
        syncInput();
        paint();
      });
      box.appendChild(img);
      box.appendChild(x);
      thumbs.appendChild(box);
    });
  }

  function accept(files) {
    var problems = [];
    Array.prototype.slice.call(files).forEach(function (f) {
      if (picked.length >= MAX_FILES) { problems.push('Only ' + MAX_FILES + ' photos, sorry.'); return; }
      if (!/^image\//.test(f.type))   { problems.push(f.name + ' is not an image.'); return; }
      if (f.size > MAX_BYTES)         { problems.push(f.name + ' is over 8MB.'); return; }
      picked.push(f);
    });
    setErr('photos', problems.length > 0, problems.slice(0, 2).join(' '));
    syncInput();
    paint();
  }

  if (input && drop && thumbs && window.DataTransfer) {
    input.addEventListener('change', function () { accept(input.files); });

    ['dragenter', 'dragover'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('over'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('over'); });
    });
    drop.addEventListener('drop', function (e) {
      if (e.dataTransfer && e.dataTransfer.files) accept(e.dataTransfer.files);
    });
  }

  /* ---------- submit ------------------------------------------------------ */
  form.addEventListener('submit', function (e) {
    if (!checkStep(at)) { e.preventDefault(); return; }
    e.preventDefault();

    sendBtn.disabled = true;
    sendBtn.textContent = 'Sending...';
    okNote.hidden = true;
    badNote.hidden = true;

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'fetch' }
    })
      .then(function (r) { return r.json().catch(function () { return { ok: r.ok }; }); })
      .then(function (res) {
        if (!res.ok) throw new Error(res.error || 'failed');
        form.hidden = true;
        okNote.hidden = false;
        okNote.scrollIntoView({ behavior: 'smooth', block: 'center' });
        okNote.setAttribute('tabindex', '-1');
        okNote.focus({ preventScroll: true });
        if (window.dataLayer) window.dataLayer.push({ event: 'quote_submitted' });
      })
      .catch(function () {
        badNote.hidden = false;
        badNote.scrollIntoView({ behavior: 'smooth', block: 'center' });
        sendBtn.disabled = false;
        sendBtn.textContent = 'Send my enquiry';
      });
  });

  show(0);
})();
