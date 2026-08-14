/**
 * Careers listing — filtering and job alerts.
 *
 * The openings are already in the HTML (rendered server-side from the CRM), so
 * this only ever hides and shows what is on the page — no request is needed to
 * filter, and the page works with JavaScript disabled.
 */
(function () {
  'use strict';

  var list = document.getElementById('crList');
  if (!list) { return; }

  var cards   = Array.prototype.slice.call(list.querySelectorAll('.cr-job'));
  var search  = document.getElementById('crSearch');
  var dept    = document.getElementById('crDept');
  var loc     = document.getElementById('crLoc');
  var type    = document.getElementById('crType');
  var count   = document.getElementById('crCount');
  var empty   = document.getElementById('crEmpty');
  var pills   = document.getElementById('crPills');

  var family = '';
  var mode   = '';

  function render() {
    var q = (search && search.value || '').toLowerCase().trim();
    var d = dept && dept.value || '';
    var l = loc && loc.value || '';
    var t = type && type.value || '';
    var shown = 0;

    cards.forEach(function (card) {
      var ok = true;

      if (family && card.getAttribute('data-family') !== family) { ok = false; }
      if (ok && mode && card.getAttribute('data-mode') !== mode) { ok = false; }
      if (ok && d && card.getAttribute('data-dept') !== d) { ok = false; }
      if (ok && l && card.getAttribute('data-loc') !== l) { ok = false; }
      if (ok && t && card.getAttribute('data-type') !== t) { ok = false; }
      if (ok && q && (card.getAttribute('data-search') || '').indexOf(q) === -1) { ok = false; }

      card.hidden = !ok;
      if (ok) { shown++; }
    });

    if (empty) { empty.hidden = shown !== 0; }
    if (count) {
      count.textContent = shown === cards.length
        ? 'Showing all ' + cards.length + ' opening' + (cards.length === 1 ? '' : 's')
        : 'Showing ' + shown + ' of ' + cards.length + ' openings';
    }
  }

  [search, dept, loc, type].forEach(function (control) {
    if (!control) { return; }
    control.addEventListener(control.tagName === 'INPUT' ? 'input' : 'change', render);
  });

  if (pills) {
    pills.addEventListener('click', function (event) {
      var pill = event.target.closest('.cr-pill');
      if (!pill) { return; }

      // The pills are one row of mutually exclusive shortcuts: picking one
      // clears whatever the previous one had set.
      family = pill.getAttribute('data-family') || '';
      mode   = pill.getAttribute('data-mode') || '';

      Array.prototype.forEach.call(pills.children, function (button) {
        button.classList.toggle('active', button === pill);
      });

      render();
    });
  }

  // A deep link such as /careers?q=nurse&dept=Engineering lands pre-filtered,
  // which is what the job-alert emails link to.
  var params = new URLSearchParams(window.location.search);
  if (search && params.get('q')) { search.value = params.get('q'); }
  if (dept && params.get('dept')) { dept.value = params.get('dept'); }
  if (type && params.get('type')) { type.value = params.get('type'); }

  render();

  /* ── Job alerts ── */

  var alertForm = document.getElementById('crAlertForm');

  if (alertForm) {
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

      fetch('/api/careers.php?action=subscribe', { method: 'POST', body: new FormData(alertForm) })
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
  }
})();
