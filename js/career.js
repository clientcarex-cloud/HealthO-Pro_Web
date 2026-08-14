/**
 * Job detail — application submit.
 *
 * The form posts multipart (the CV rides along) straight to the CRM's keyless
 * embed endpoint, named on the form itself. Validation here is only there to
 * save the candidate a round trip; the CRM validates everything again before it
 * stores anything.
 */
(function () {
  'use strict';

  var form = document.getElementById('jdApply');
  if (!form) { return; }

  var status = document.getElementById('jdStatus');
  var button = document.getElementById('jdSubmit');
  var done   = document.getElementById('jdDone');
  var file   = document.getElementById('jdFile');
  var maxMb  = parseFloat(form.getAttribute('data-max-mb') || '5');
  var allowed = (form.getAttribute('data-allowed') || 'pdf,doc,docx').split(',');

  function say(text, ok) {
    if (!status) { return; }
    status.className = 'jd-status ' + (ok ? 'ok' : 'err');
    status.textContent = text;
    status.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // Carry the campaign the visitor arrived on into the CRM, so recruiters can
  // see which channel actually produces applications.
  var utm = document.getElementById('jdUtm');
  if (utm) {
    var params = new URLSearchParams(window.location.search);
    var parts  = [];
    ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'].forEach(function (key) {
      if (params.get(key)) { parts.push(key + '=' + params.get(key)); }
    });
    if (!parts.length && document.referrer && document.referrer.indexOf(window.location.host) === -1) {
      parts.push('referrer=' + document.referrer);
    }
    utm.value = parts.join('&').slice(0, 500);
  }

  if (file) {
    file.addEventListener('change', function () {
      var label = document.getElementById('jdFileName');
      if (!label) { return; }

      if (!file.files.length) {
        label.textContent = '';
        return;
      }

      var picked = file.files[0];
      label.textContent = picked.name + ' (' + (picked.size / 1048576).toFixed(1) + ' MB)';
    });
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();

    var name  = form.querySelector('[name="name"]');
    var email = form.querySelector('[name="email"]');
    var phone = form.querySelector('[name="phone"]');

    if (!name.value.trim()) { return say('Please enter your full name.', false); }
    if (!email.value.trim() || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value.trim())) {
      return say('Please enter a valid email address.', false);
    }
    if (phone && phone.required && !phone.value.trim()) {
      return say('Please enter your mobile number.', false);
    }

    if (file && file.files.length) {
      var picked = file.files[0];
      var ext = (picked.name.split('.').pop() || '').toLowerCase();

      if (allowed.indexOf(ext) === -1) {
        return say('Please attach a ' + allowed.join(', ').toUpperCase() + ' file.', false);
      }
      if (picked.size > maxMb * 1048576) {
        return say('Your file is larger than ' + maxMb + ' MB. Please attach a smaller one.', false);
      }
    } else if (file && file.required) {
      return say('Please attach your resume.', false);
    }

    // Any required screening question the browser can validate for us.
    if (!form.checkValidity()) {
      var invalid = form.querySelector(':invalid');
      if (invalid) { invalid.focus(); }
      return say('Please complete the highlighted fields.', false);
    }

    button.disabled = true;
    button.textContent = 'Submitting…';
    if (status) { status.className = 'jd-status'; }

    fetch(form.getAttribute('data-endpoint'), { method: 'POST', body: new FormData(form), credentials: 'omit' })
      .then(function (response) { return response.json().catch(function () { return null; }); })
      .then(function (data) {
        button.disabled = false;
        button.textContent = 'Submit application';

        if (data && data.ok) {
          var ref = document.getElementById('jdRef');
          if (ref) { ref.textContent = data.reference || '—'; }

          form.hidden = true;
          if (status) { status.className = 'jd-status'; }
          if (done) {
            done.hidden = false;
            done.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }

          if (typeof gtag === 'function') {
            gtag('event', 'job_application', { event_category: 'careers', event_label: form.querySelector('[name="slug"]').value });
          }
          return;
        }

        say((data && data.error) || 'We could not submit your application. Please email your CV to sales@healtho.pro.', false);
      })
      .catch(function () {
        button.disabled = false;
        button.textContent = 'Submit application';
        say('Something went wrong on the way. Please try again, or email your CV to sales@healtho.pro.', false);
      });
  });
})();
