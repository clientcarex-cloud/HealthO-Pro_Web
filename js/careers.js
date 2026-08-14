/**
 * Careers page — filtering the openings and the job-alert form.
 *
 * The openings are already in the HTML (rendered server-side from the CRM), so
 * filtering only shows and hides what is there: with JavaScript off the visitor
 * still sees every role. Each block binds nothing when its markup is absent.
 */

/* ── Filter the openings ─────────────────────────────────────────────────── */
(function () {
  'use strict';

  var filters = document.getElementById('crFilters');
  var list    = document.getElementById('crJobs');
  if (!list) { return; }

  var cards    = Array.prototype.slice.call(list.querySelectorAll('.cr-job'));
  var search   = document.getElementById('crSearch');
  var noResult = document.getElementById('crNoResult');
  var reset    = document.getElementById('crReset');
  var active   = { type: '', department: '' };

  function apply() {
    var term    = search && search.value ? search.value.trim().toLowerCase() : '';
    var visible = 0;

    cards.forEach(function (card) {
      var show = (!active.type || card.getAttribute('data-type') === active.type)
        && (!active.department || card.getAttribute('data-department') === active.department)
        && (!term || (card.getAttribute('data-search') || '').indexOf(term) !== -1);

      card.hidden = !show;
      if (show) { visible++; }
    });

    if (noResult) { noResult.hidden = visible !== 0; }
  }

  if (filters) {
    filters.addEventListener('click', function (event) {
      var chip = event.target.closest ? event.target.closest('.cr-chip') : null;
      if (!chip) { return; }

      var kind = chip.getAttribute('data-filter');

      if (kind === 'all') {
        active = { type: '', department: '' };
      } else {
        var value = chip.getAttribute('data-value') || '';
        // Clicking the chip that is already on clears it, so a visitor is never
        // stuck inside a filter with no way back to the full list.
        active[kind] = active[kind] === value ? '' : value;
        // Department and type narrow together; the other axis stays as it is.
      }

      Array.prototype.forEach.call(filters.querySelectorAll('.cr-chip'), function (other) {
        var otherKind = other.getAttribute('data-filter');
        var on = otherKind === 'all'
          ? (!active.type && !active.department)
          : active[otherKind] === (other.getAttribute('data-value') || '');

        other.setAttribute('aria-pressed', on ? 'true' : 'false');
      });

      apply();
    });
  }

  if (search) {
    search.addEventListener('input', apply);
  }

  if (reset) {
    reset.addEventListener('click', function () {
      active = { type: '', department: '' };
      if (search) { search.value = ''; }
      if (filters) {
        Array.prototype.forEach.call(filters.querySelectorAll('.cr-chip'), function (chip) {
          chip.setAttribute('aria-pressed', chip.getAttribute('data-filter') === 'all' ? 'true' : 'false');
        });
      }
      apply();
    });
  }
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
