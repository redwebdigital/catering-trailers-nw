/* Admin behaviour: mobile nav, delete confirmation, character counters,
   live search previews, drag-to-reorder, and unsaved-change warnings. */
(function () {
  'use strict';

  /* ---------- mobile sidebar --------------------------------------------- */
  var toggle = document.getElementById('navtoggle');
  var side = document.getElementById('sidebar');
  if (toggle && side) {
    toggle.addEventListener('click', function () {
      var open = side.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(open));
    });
    side.addEventListener('click', function (e) {
      if (e.target.closest('a')) { side.classList.remove('open'); toggle.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* ---------- warn before deleting --------------------------------------- */
  document.addEventListener('click', function (e) {
    var el = e.target.closest('[data-confirm]');
    if (!el) return;
    if (!window.confirm(el.getAttribute('data-confirm'))) { e.preventDefault(); e.stopPropagation(); }
  }, true);

  /* ---------- character counters ----------------------------------------- */
  // <input data-count="60"> gets a live counter that warns before it truncates
  document.querySelectorAll('[data-count]').forEach(function (input) {
    var ideal = parseInt(input.getAttribute('data-count'), 10);
    var out = document.createElement('span');
    out.className = 'counter';
    input.insertAdjacentElement('afterend', out);
    function tick() {
      var n = input.value.length;
      out.textContent = n + ' / ' + ideal + (n > ideal ? ' — Google will truncate this' : '');
      out.className = 'counter' + (n > ideal ? ' over' : (n > ideal * 0.9 ? ' warn' : ''));
    }
    input.addEventListener('input', tick);
    tick();
  });

  /* ---------- live Google-style preview ----------------------------------- */
  var serp = document.getElementById('serp');
  if (serp) {
    var tEl = document.getElementById('serp-title');
    var dEl = document.getElementById('serp-desc');
    var uEl = document.getElementById('serp-url');
    var src = {
      title: document.querySelector('[name="seo_title"]'),
      desc: document.querySelector('[name="meta_desc"]'),
      slug: document.querySelector('[name="slug"]')
    };
    function clip(s, n) { s = (s || '').trim(); return s.length > n ? s.slice(0, n - 1) + '…' : s; }
    function paint() {
      if (tEl) tEl.textContent = clip(src.title && src.title.value, 60) || 'Page title appears here';
      if (dEl) dEl.textContent = clip(src.desc && src.desc.value, 158) || 'The meta description appears here, and is what people read before deciding whether to click.';
      if (uEl && src.slug) {
        var slug = (src.slug.value || '').replace(/^\/+/, '');
        uEl.textContent = serp.getAttribute('data-domain') + (slug ? ' › ' + slug.split('/').join(' › ') : '');
      }
    }
    Object.keys(src).forEach(function (k) { if (src[k]) src[k].addEventListener('input', paint); });
    paint();
  }

  /* ---------- drag to reorder -------------------------------------------- */
  document.querySelectorAll('[data-sortable]').forEach(function (list) {
    var dragging = null;
    list.querySelectorAll('.row').forEach(function (row) {
      var handle = row.querySelector('.drag');
      if (!handle) return;
      handle.setAttribute('draggable', 'true');
      handle.addEventListener('dragstart', function (e) {
        dragging = row; row.style.opacity = '.45';
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', ''); } catch (err) {}
      });
      handle.addEventListener('dragend', function () {
        if (dragging) dragging.style.opacity = '';
        dragging = null; renumber(list);
      });
    });
    list.addEventListener('dragover', function (e) {
      e.preventDefault();
      if (!dragging) return;
      var over = e.target.closest('.row');
      if (!over || over === dragging) return;
      var r = over.getBoundingClientRect();
      list.insertBefore(dragging, (e.clientY - r.top) / r.height > 0.5 ? over.nextSibling : over);
    });
  });

  function renumber(list) {
    list.querySelectorAll('.row').forEach(function (row, i) {
      var f = row.querySelector('[name$="[sort_order]"], .sortfield');
      if (f) f.value = i;
    });
    markDirty();
  }

  /* ---------- add a blank option row -------------------------------------- */
  document.querySelectorAll('[data-addrow]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var list = document.getElementById(btn.getAttribute('data-addrow'));
      var tpl = document.getElementById(btn.getAttribute('data-template'));
      if (!list || !tpl) return;
      var idx = 'new' + Date.now();
      list.insertAdjacentHTML('beforeend', tpl.innerHTML.replace(/__IDX__/g, idx));
      markDirty();
    });
  });

  /* ---------- remove a row ------------------------------------------------ */
  document.addEventListener('click', function (e) {
    var rm = e.target.closest('[data-removerow]');
    if (!rm) return;
    e.preventDefault();
    var row = rm.closest('.row');
    if (!row) return;
    var label = row.querySelector('input') ? row.querySelector('input').value : 'this option';
    if (!window.confirm('Remove "' + (label || 'this option') + '"? It disappears from the website when you save.')) return;
    row.remove();
    markDirty();
  });

  /* ---------- unsaved changes -------------------------------------------- */
  var dirty = false;
  function markDirty() { dirty = true; }
  document.querySelectorAll('form[data-warn]').forEach(function (form) {
    form.addEventListener('input', markDirty);
    form.addEventListener('change', markDirty);
    form.addEventListener('submit', function () { dirty = false; });
  });
  window.addEventListener('beforeunload', function (e) {
    if (!dirty) return;
    e.preventDefault();
    e.returnValue = '';
  });

  /* ---------- media upload drop zone -------------------------------------- */
  var drop = document.getElementById('drop');
  if (drop) {
    var input = drop.querySelector('input[type=file]');
    ['dragenter', 'dragover'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('over'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('over'); });
    });
    drop.addEventListener('drop', function (e) {
      if (input && e.dataTransfer && e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        drop.closest('form').submit();
      }
    });
    if (input) input.addEventListener('change', function () { drop.closest('form').submit(); });
  }
})();
