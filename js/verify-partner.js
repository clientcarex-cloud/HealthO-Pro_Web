(function () {
  'use strict';

  var form   = document.getElementById('vfForm');
  var input  = document.getElementById('vfCode');
  var btn    = document.getElementById('vfBtn');
  var result = document.getElementById('vfResult');

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function fmtDate(d) {
    if (!d) return '';
    var t = new Date(d + 'T00:00:00');
    return isNaN(t) ? d : t.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  var ICONS = {
    ok:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 13 4 4L19 7"/></svg>',
    warn: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>',
    bad:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>'
  };

  function head(kind, title, sub) {
    return '<div class="vf-head ' + kind + '"><span class="vf-head-ic">' + ICONS[kind] + '</span>' +
      '<div><h3>' + esc(title) + '</h3><p>' + esc(sub) + '</p></div></div>';
  }

  function row(k, v) {
    return v ? '<div class="vf-row"><span class="k">' + esc(k) + '</span><span class="v">' + esc(v) + '</span></div>' : '';
  }

  function show(html) {
    result.innerHTML = '<div class="vf-card">' + html + '</div>';
    result.hidden = false;
    result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function render(data, code) {
    if (!data || data.ok === false) {
      show(head('warn', 'Verification unavailable', 'We could not reach the registry right now. Please try again in a few minutes.'));
      return;
    }

    if (!data.valid && !data.partner) {
      show(
        head('bad', 'Certificate not found', 'No certificate matches this number.') +
        '<div class="vf-body"><p class="vf-about">The number <strong>' + esc(code) + '</strong> does not exist in the HealthO Pro partner registry. ' +
        'Please re-check the number, or treat the document as not genuine and <a href="contact">report it to us</a>.</p></div>'
      );
      return;
    }

    var p   = data.partner || {};
    var loc = [p.city, p.state, p.country].filter(Boolean).join(', ');

    var kind, title, sub;
    if (data.valid) {
      kind = 'ok'; title = 'Verified — Active Partner';
      sub  = 'This certificate is genuine and the partnership is currently active.';
    } else if (data.status === 'expired') {
      kind = 'warn'; title = 'Certificate Expired';
      sub  = 'This certificate was genuine but its validity period has ended.';
    } else {
      kind = 'warn'; title = 'Partnership Suspended';
      sub  = 'This certificate exists but the partnership is not currently active.';
    }

    var logo = p.logo_url
      ? '<img class="vf-logo" src="' + esc(p.logo_url) + '" alt="' + esc(p.company_name) + ' logo">'
      : '<span class="vf-logo vf-logo-ph">' + esc(String(p.company_name || '?').charAt(0).toUpperCase()) + '</span>';

    var qrNote = '';
    if (data.qr_authentic === true) {
      qrNote = '<div class="vf-note"><svg viewBox="0 0 24 24" fill="none" stroke="var(--green-dark)" stroke-width="2.5"><path d="m5 13 4 4L19 7"/></svg><span>QR code authenticated — this scan came from an officially issued certificate document.</span></div>';
    } else if (new URLSearchParams(location.search).get('t')) {
      qrNote = '<div class="vf-note"><svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2.5"><path d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg><span>The scanned QR code does not match the current certificate issue. The partner record itself is shown above; the printed document may be outdated.</span></div>';
    }

    show(
      head(kind, title, sub) +
      '<div class="vf-body">' +
        '<div class="vf-partner">' + logo +
          '<div><div class="vf-pname">' + esc(p.company_name || '—') + '</div>' +
          (loc ? '<div class="vf-ploc">' + esc(loc) + '</div>' : '') + '</div>' +
        '</div>' +
        (p.about ? '<p class="vf-about">' + esc(p.about) + '</p>' : '') +
        '<div class="vf-rows">' +
          row('Certificate No.', p.partner_code) +
          row('Partner Type', p.type_label) +
          row('Partner Since', fmtDate(data.joined_date) || (p.since ? p.since : '')) +
          row('Valid Until', data.valid_until ? fmtDate(data.valid_until) : (data.valid ? 'Ongoing' : '')) +
          row('Status', data.valid ? 'Active' : (data.status ? data.status.charAt(0).toUpperCase() + data.status.slice(1) : '')) +
          row('Verified On', new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })) +
        '</div>' +
        qrNote +
      '</div>'
    );
  }

  function verify(code, token) {
    code = (code || '').trim().toUpperCase();
    if (!code) return;

    input.value = code;
    btn.disabled = true;
    btn.textContent = 'Verifying…';
    result.hidden = false;
    result.innerHTML = '<div class="vf-card"><div class="vf-body" style="text-align:center;color:var(--text-soft);">Checking the partner registry…</div></div>';

    var url = 'api/partners.php?action=verify&code=' + encodeURIComponent(code) + (token ? '&t=' + encodeURIComponent(token) : '');
    fetch(url)
      .then(function (r) { return r.json(); })
      .then(function (data) { render(data, code); })
      .catch(function () { render(null, code); })
      .then(function () { btn.disabled = false; btn.textContent = 'Verify'; });
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    // Manual entry: verify by number only (any stale QR token in the URL must not apply).
    verify(input.value, '');
  });

  // Auto-verify when arriving from a QR scan: /verify-partner?code=CCXP-...&t=...
  var params = new URLSearchParams(location.search);
  if (params.get('code')) {
    verify(params.get('code'), params.get('t') || '');
  }
})();
