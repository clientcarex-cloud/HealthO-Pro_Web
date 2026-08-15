/**
 * Careers page — filtering the openings and the job-alert form.
 *
 * The openings are already in the HTML (rendered server-side from the CRM), so
 * filtering only shows and hides what is there: with JavaScript off the visitor
 * still sees every role. Each block binds nothing when its markup is absent.
 */

/* ── Faceted search over the openings ────────────────────────────────────── */
/**
 * Everything below filters the cards that are ALREADY in the HTML. Nothing is
 * fetched and nothing is re-rendered, so the openings survive with JavaScript
 * off — which is also what Google Jobs reads.
 *
 * Five facets (department, job type, work mode, location, experience band) plus
 * a free-text search, and they compose: values inside one facet are OR'd
 * (Sales OR Engineering), separate facets are AND'd (Sales AND Remote). That is
 * the behaviour every job board has trained candidates to expect.
 *
 * The counts on each chip are computed with that chip's OWN facet excluded from
 * the filter, so they answer "how many would I get if I added this" rather than
 * "how many are showing" — the latter reads as zeros everywhere as soon as one
 * chip is on. A chip that can only ever return nothing is disabled, so nobody
 * has to discover a dead end by clicking it.
 */
(function () {
  'use strict';

  var list = document.getElementById('crJobs');
  var bar  = document.getElementById('crFilters');
  if (!list || !bar) { return; }

  var FACETS   = ['department', 'type', 'mode', 'location', 'experience'];
  var PAGE     = 12;   // cards revealed per "show more" — 50+ roles is a long scroll
  var cards    = Array.prototype.slice.call(list.querySelectorAll('.cr-job'));
  var search   = document.getElementById('crSearch');
  var clearBtn = document.getElementById('crSearchClear');
  var sortSel  = document.getElementById('crSort');
  var facetBox = document.getElementById('crFacets');
  var toggle   = document.getElementById('crFacetToggle');
  var badge    = document.getElementById('crFacetCount');
  var activeUI = document.getElementById('crActive');
  var pillBox  = document.getElementById('crActivePills');
  var clearAll = document.getElementById('crClear');
  var countEl  = document.getElementById('crCount');
  var noResult = document.getElementById('crNoResult');
  var reset    = document.getElementById('crReset');
  var moreWrap = document.getElementById('crMore');
  var moreBtn  = document.getElementById('crMoreBtn');
  var chips    = Array.prototype.slice.call(bar.querySelectorAll('.cr-chip[data-facet]'));

  var state = { q: '', sort: 'relevance', shown: PAGE };
  FACETS.forEach(function (facet) { state[facet] = []; });

  // Read each card once. Touching the DOM inside the filter loop is what makes
  // a list this size feel sluggish on a phone.
  var rows = cards.map(function (card, index) {
    return {
      card:     card,
      order:    index,                                        // the CRM's own ordering
      haystack: card.getAttribute('data-search') || '',
      title:    card.getAttribute('data-title') || '',
      posted:   parseInt(card.getAttribute('data-posted'), 10) || 0,
      featured: card.getAttribute('data-featured') === '1',
      values:   FACETS.reduce(function (map, facet) {
        map[facet] = card.getAttribute('data-' + facet) || '';
        return map;
      }, {})
    };
  });

  /* ─────────────────────────────── matching ─────────────────────────────── */

  /** Every search word must appear somewhere in the card — an AND, not a phrase. */
  function words(term) {
    return term.toLowerCase().split(/\s+/).filter(Boolean);
  }

  function matchesText(row, terms) {
    for (var i = 0; i < terms.length; i++) {
      if (row.haystack.indexOf(terms[i]) === -1) { return false; }
    }
    return true;
  }

  function matchesFacets(row, skip) {
    for (var i = 0; i < FACETS.length; i++) {
      var facet = FACETS[i];
      if (facet === skip) { continue; }

      var chosen = state[facet];
      if (chosen.length && chosen.indexOf(row.values[facet]) === -1) { return false; }
    }
    return true;
  }

  /** Rows passing everything except (optionally) one facet's own chips. */
  function select(skip) {
    var terms = words(state.q);

    return rows.filter(function (row) {
      return matchesText(row, terms) && matchesFacets(row, skip);
    });
  }

  function comparator() {
    if (state.sort === 'newest') {
      return function (a, b) { return b.posted - a.posted || a.order - b.order; };
    }
    if (state.sort === 'title') {
      return function (a, b) { return a.title < b.title ? -1 : (a.title > b.title ? 1 : 0); };
    }
    // "Most relevant" keeps the CRM's own ordering — featured and urgent roles
    // are already sorted to the top there, and a recruiter's pinning should not
    // be silently overridden by the website.
    return function (a, b) { return a.order - b.order; };
  }

  /* ─────────────────────────────── rendering ────────────────────────────── */

  function chipLabel(chip) {
    var clone = chip.cloneNode(true);
    var n     = clone.querySelector('.cr-chipn');
    if (n) { clone.removeChild(n); }

    return clone.textContent.trim();
  }

  function paintChips() {
    var perFacet = {};

    FACETS.forEach(function (facet) {
      var pool   = select(facet);
      var counts = {};

      pool.forEach(function (row) {
        var value = row.values[facet];
        if (value) { counts[value] = (counts[value] || 0) + 1; }
      });

      perFacet[facet] = counts;
    });

    chips.forEach(function (chip) {
      var facet = chip.getAttribute('data-facet');
      var value = chip.getAttribute('data-value');
      var count = perFacet[facet][value] || 0;
      var on    = state[facet].indexOf(value) !== -1;
      var badgeEl = chip.querySelector('.cr-chipn');

      if (badgeEl) { badgeEl.textContent = count ? String(count) : ''; }
      chip.setAttribute('aria-pressed', on ? 'true' : 'false');
      // An active chip is never disabled: it must always be clickable to undo.
      chip.disabled = !on && count === 0;
    });
  }

  function paintActive() {
    var total = 0;
    var html  = '';

    chips.forEach(function (chip) {
      var facet = chip.getAttribute('data-facet');
      var value = chip.getAttribute('data-value');

      if (state[facet].indexOf(value) === -1) { return; }

      total++;
      html += '<span class="cr-pill">' + escapeHtml(chipLabel(chip))
        + '<button type="button" data-drop-facet="' + escapeHtml(facet) + '"'
        + ' data-drop-value="' + escapeHtml(value) + '"'
        + ' aria-label="Remove filter ' + escapeHtml(chipLabel(chip)) + '">&times;</button></span>';
    });

    if (pillBox) { pillBox.innerHTML = html; }
    if (activeUI) { activeUI.hidden = total === 0 && state.q === ''; }

    if (badge) {
      badge.hidden = total === 0;
      badge.textContent = String(total);
    }
  }

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function apply(keepPage) {
    if (!keepPage) { state.shown = PAGE; }

    var matched = select(null).sort(comparator());

    // Re-order in one pass. appendChild moves an existing node, so the cards are
    // never removed from the document and never re-created.
    var frag = document.createDocumentFragment();
    matched.forEach(function (row) { frag.appendChild(row.card); });
    list.appendChild(frag);

    var visible = Math.min(state.shown, matched.length);
    var shownSet = matched.slice(0, visible);

    rows.forEach(function (row) { row.card.hidden = true; });
    shownSet.forEach(function (row) { row.card.hidden = false; });

    if (countEl) {
      countEl.hidden = false;
      countEl.innerHTML = matched.length === rows.length
        ? 'Showing all <strong>' + rows.length + '</strong> opening' + (rows.length === 1 ? '' : 's')
        : 'Showing <strong>' + matched.length + '</strong> of ' + rows.length + ' openings';
    }

    if (noResult) { noResult.hidden = matched.length !== 0; }

    if (moreWrap && moreBtn) {
      var remaining = matched.length - visible;
      moreWrap.hidden = remaining <= 0;
      moreBtn.textContent = 'Show ' + Math.min(PAGE, remaining) + ' more'
        + (remaining > PAGE ? ' of ' + remaining : '');
    }

    if (clearBtn) { clearBtn.hidden = state.q === ''; }

    paintChips();
    paintActive();
    syncUrl();
  }

  /* ──────────────────────────────── url sync ────────────────────────────── */

  /**
   * Filters live in the query string, so a candidate can bookmark "remote
   * engineering roles", send that link to a friend, and get it back unchanged
   * after opening a posting and pressing Back.
   */
  function syncUrl() {
    if (!window.history || !window.history.replaceState) { return; }

    var params = [];
    if (state.q) { params.push('q=' + encodeURIComponent(state.q)); }

    FACETS.forEach(function (facet) {
      if (state[facet].length) {
        params.push(facet + '=' + encodeURIComponent(state[facet].join('|')));
      }
    });

    if (state.sort !== 'relevance') { params.push('sort=' + encodeURIComponent(state.sort)); }

    var url = window.location.pathname + (params.length ? '?' + params.join('&') : '') + window.location.hash;

    try { window.history.replaceState(null, '', url); } catch (e) { /* ignore */ }
  }

  function readUrl() {
    var query = window.location.search.replace(/^\?/, '');
    if (!query) { return; }

    query.split('&').forEach(function (pair) {
      var bits  = pair.split('=');
      var key   = decodeURIComponent(bits[0] || '');
      var value = decodeURIComponent((bits[1] || '').replace(/\+/g, ' '));

      if (key === 'q') { state.q = value; }
      else if (key === 'sort') { state.sort = value; }
      else if (FACETS.indexOf(key) !== -1 && value) {
        // Only values that exist on this page — a stale link must not filter
        // everything away with a department that has since been removed.
        state[key] = value.split('|').filter(function (candidate) {
          return chips.some(function (chip) {
            return chip.getAttribute('data-facet') === key && chip.getAttribute('data-value') === candidate;
          });
        });
      }
    });

    if (search) { search.value = state.q; }
    if (sortSel) { sortSel.value = state.sort; }
    if (facetBox && FACETS.some(function (f) { return state[f].length; })) {
      facetBox.classList.add('is-open');
      if (toggle) { toggle.setAttribute('aria-expanded', 'true'); }
    }
  }

  /* ──────────────────────────────── binding ─────────────────────────────── */

  bar.addEventListener('click', function (event) {
    var chip = event.target.closest ? event.target.closest('.cr-chip[data-facet]') : null;

    if (chip && !chip.disabled) {
      var facet = chip.getAttribute('data-facet');
      var value = chip.getAttribute('data-value');
      var at    = state[facet].indexOf(value);

      if (at === -1) { state[facet].push(value); } else { state[facet].splice(at, 1); }
      apply();
      return;
    }

    var drop = event.target.closest ? event.target.closest('[data-drop-facet]') : null;

    if (drop) {
      var dropFacet = drop.getAttribute('data-drop-facet');
      var dropAt    = state[dropFacet].indexOf(drop.getAttribute('data-drop-value'));

      if (dropAt !== -1) { state[dropFacet].splice(dropAt, 1); }
      apply();
    }
  });

  if (search) {
    var debounce;
    search.addEventListener('input', function () {
      window.clearTimeout(debounce);
      debounce = window.setTimeout(function () {
        state.q = search.value.trim();
        apply();
      }, 130);
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      state.q = '';
      if (search) { search.value = ''; search.focus(); }
      apply();
    });
  }

  if (sortSel) {
    sortSel.addEventListener('change', function () {
      state.sort = sortSel.value;
      apply();
    });
  }

  if (toggle && facetBox) {
    toggle.addEventListener('click', function () {
      var open = facetBox.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  if (moreBtn) {
    moreBtn.addEventListener('click', function () {
      state.shown += PAGE;
      apply(true);
    });
  }

  function clearEverything() {
    state.q = '';
    FACETS.forEach(function (facet) { state[facet] = []; });
    if (search) { search.value = ''; }
    apply();
  }

  if (clearAll) { clearAll.addEventListener('click', clearEverything); }
  if (reset) { reset.addEventListener('click', clearEverything); }

  // The bar ships hidden so it can never sit there inert for a visitor whose
  // JavaScript failed to run; this is the moment it is actually usable.
  readUrl();
  bar.hidden = false;
  apply();
}());

/* ── Job alerts ──────────────────────────────────────────────────────────── */
(function () {
  'use strict';

  var alertForm = document.getElementById('crAlertForm');
  if (!alertForm) { return; }

  alertForm.addEventListener('submit', function (event) {
    event.preventDefault();

    var message = document.getElementById('crAlertMsg');
    var button  = alertForm.querySelector('button');
    var email   = alertForm.querySelector('input[name="email"]');

    function say(text, ok) {
      if (!message) { return; }
      message.hidden = false;
      message.textContent = text;
      message.style.color = ok ? 'var(--green-dark)' : '#B91C1C';
    }

    if (!email.value || email.value.indexOf('@') === -1) {
      say('Please enter a valid email address.', false);
      return;
    }

    button.disabled = true;
    button.textContent = 'Subscribing…';

    // Posts straight to the CRM's keyless embed endpoint, so job alerts work on
    // this page — and on any site that copies this form — with no proxy and no
    // credentials.
    var endpoint = alertForm.getAttribute('data-endpoint');

    fetch(endpoint, { method: 'POST', body: new FormData(alertForm), credentials: 'omit' })
      .then(function (response) { return response.json(); })
      .then(function (data) {
        button.disabled = false;
        button.textContent = 'Notify me';

        if (data && data.ok) {
          alertForm.reset();
          say(data.message || 'You are on the list.', true);
        } else {
          say((data && data.error) || 'Could not subscribe right now, please try again.', false);
        }
      })
      .catch(function () {
        button.disabled = false;
        button.textContent = 'Notify me';
        say('Could not subscribe right now, please try again.', false);
      });
  });
}());
