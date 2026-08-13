(function () {
  'use strict';

  var grid    = document.getElementById('ptGrid');
  var loading = document.getElementById('ptLoading');
  var empty   = document.getElementById('ptEmpty');
  var count   = document.getElementById('ptCount');
  var qSearch = document.getElementById('ptSearch');
  var qCountry = document.getElementById('ptCountry');
  var qState   = document.getElementById('ptState');

  var partners = [];

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function initials(name) {
    return esc(String(name || '?').trim().charAt(0).toUpperCase());
  }

  function locText(p) {
    return [p.city, p.state, p.country].filter(Boolean).join(', ');
  }

  function card(p) {
    var logo = p.logo_url
      ? '<img class="pt-logo" src="' + esc(p.logo_url) + '" alt="' + esc(p.company_name) + ' logo" loading="lazy">'
      : '<span class="pt-logo pt-logo-ph">' + initials(p.company_name) + '</span>';

    var site = '';
    if (p.website) {
      var href = /^https?:\/\//i.test(p.website) ? p.website : 'https://' + p.website;
      site = '<a class="pt-link" href="' + esc(href) + '" target="_blank" rel="noopener nofollow">Website ↗</a>';
    }

    return '<article class="pt-card">' +
      '<div class="pt-card-head">' + logo +
        '<div><div class="pt-name">' + esc(p.company_name) + '</div>' +
        (locText(p) ? '<div class="pt-loc"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>' + esc(locText(p)) + '</div>' : '') +
        '</div></div>' +
      (p.about ? '<p class="pt-about">' + esc(p.about) + '</p>' : '') +
      '<div class="pt-meta">' +
        '<span class="pt-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 13 4 4L19 7"/></svg>Certified</span>' +
        '<span class="pt-chip">' + esc(p.type_label || 'Channel Partner') + '</span>' +
        (p.since ? '<span class="pt-chip since">Since ' + esc(p.since) + '</span>' : '') +
        site +
      '</div>' +
    '</article>';
  }

  function fillSelect(sel, values, placeholder) {
    var current = sel.value;
    sel.innerHTML = '<option value="">' + placeholder + '</option>' + values.map(function (v) {
      return '<option value="' + esc(v) + '">' + esc(v) + '</option>';
    }).join('');
    if (values.indexOf(current) !== -1) sel.value = current;
  }

  function render() {
    var q = (qSearch.value || '').toLowerCase().trim();
    var c = qCountry.value;
    var s = qState.value;

    var shown = partners.filter(function (p) {
      if (c && p.country !== c) return false;
      if (s && p.state !== s) return false;
      if (q) {
        var hay = (p.company_name + ' ' + locText(p) + ' ' + (p.type_label || '')).toLowerCase();
        if (hay.indexOf(q) === -1) return false;
      }
      return true;
    });

    grid.innerHTML = shown.map(card).join('');
    grid.hidden  = shown.length === 0;
    empty.hidden = shown.length !== 0;
    count.textContent = shown.length
      ? 'Showing ' + shown.length + ' of ' + partners.length + ' certified partner' + (partners.length === 1 ? '' : 's')
      : '';
  }

  function rebuildStates() {
    var c = qCountry.value;
    var states = [];
    partners.forEach(function (p) {
      if (p.state && (!c || p.country === c) && states.indexOf(p.state) === -1) states.push(p.state);
    });
    states.sort();
    fillSelect(qState, states, 'All States');
  }

  fetch('api/partners.php?action=directory')
    .then(function (r) { return r.json(); })
    .then(function (data) {
      loading.hidden = true;
      if (!data || !data.ok || !Array.isArray(data.partners)) {
        empty.hidden = false;
        empty.textContent = 'Partners are being updated — please check back soon.';
        return;
      }
      partners = data.partners;

      var countries = [];
      partners.forEach(function (p) {
        if (p.country && countries.indexOf(p.country) === -1) countries.push(p.country);
      });
      countries.sort();
      fillSelect(qCountry, countries, 'All Countries');
      rebuildStates();
      render();
    })
    .catch(function () {
      loading.hidden = true;
      empty.hidden = false;
      empty.textContent = 'Partners are being updated — please check back soon.';
    });

  qSearch.addEventListener('input', render);
  qCountry.addEventListener('change', function () { rebuildStates(); render(); });
  qState.addEventListener('change', render);
})();
