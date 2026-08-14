/**
 * Careers page — job alerts.
 *
 * The openings themselves are rendered by the embedded widget (which brings its
 * own filtering), so this file only wires the "notify me" form. It is written to
 * bind nothing and fail silently when that form is not on the page.
 */
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

    // Posts straight to the CRM's keyless embed endpoint, so job alerts work
    // on this page (and on any site that copies this form) without the proxy.
    var endpoint = alertForm.getAttribute('data-endpoint') || '/api/careers.php?action=subscribe';

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
