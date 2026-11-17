<?php
$page        = 'data-protection-addendum';
$title       = 'Data Protection Addendum | HealthO Pro';
$description = 'HealthO Pro Data Protection Addendum: how Healthocare Private Limited handles, secures and safeguards the patient and practice data healthcare providers entrust to the platform, under the DPDP Act 2023, IT Act 2000 and GDPR.';
require __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/legal-related.php';
$tel = str_replace(' ', '', PHONE_SUPPORT);
?>
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Data Protection Addendum</div>
    <h1>Data Protection <span class="text-grad">Addendum</span></h1>
    <p>How HealthO Pro handles, secures and safeguards the patient and practice data that healthcare providers entrust to our platform.</p>
    <p class="legal-meta">Issued by Healthocare Private Limited · Applies to HealthO Pro Subscribers · Last updated: November 2026</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="prose legal-doc">
      <h2 class="legal-glance-title">This addendum at a glance</h2>
      <p>A short summary for busy clinic, lab and hospital teams. The full terms that follow are what legally apply.</p>
      <div class="legal-cards">
        <div class="legal-card"><span class="legal-ic">1</span><h4>Your data stays yours</h4><p>You decide why and how patient data is used. We process it only to run HealthO Pro for you, and only on your documented instructions.</p></div>
        <div class="legal-card"><span class="legal-ic">2</span><h4>Security built in</h4><p>Encryption in transit and at rest, role-based access, audit logs, regular backups and continuous monitoring protect every record.</p></div>
        <div class="legal-card"><span class="legal-ic">3</span><h4>Fast breach alerts</h4><p>If a personal data breach affects your account, we will tell you within 72 hours of becoming aware and help you meet your own reporting duties.</p></div>
        <div class="legal-card"><span class="legal-ic">4</span><h4>Clean exit</h4><p>When your subscription ends you can export your data. We then delete it from live systems within 30 days, subject to legal retention needs.</p></div>
      </div>

      <?php legal_framework_notice($page); ?>

      <nav class="legal-toc" aria-label="Contents">
        <h3>Contents</h3>
        <ol>
          <li><a href="#s1"><span>01</span>Scope and how this addendum fits</a></li>
          <li><a href="#s2"><span>02</span>Key terms</a></li>
          <li><a href="#s3"><span>03</span>Roles of the parties</a></li>
          <li><a href="#s4"><span>04</span>What data we process, and why</a></li>
          <li><a href="#s5"><span>05</span>Your responsibilities as Data Fiduciary</a></li>
          <li><a href="#s6"><span>06</span>Our commitments as Data Processor</a></li>
          <li><a href="#s7"><span>07</span>Confidentiality of personnel</a></li>
          <li><a href="#s8"><span>08</span>Sub-processors</a></li>
          <li><a href="#s9"><span>09</span>Hosting and cross-border transfers</a></li>
          <li><a href="#s10"><span>10</span>Requests from data principals</a></li>
          <li><a href="#s11"><span>11</span>Breach notification</a></li>
          <li><a href="#s12"><span>12</span>Audits and compliance evidence</a></li>
          <li><a href="#s13"><span>13</span>Retention, return and deletion</a></li>
          <li><a href="#s14"><span>14</span>Liability, term and precedence</a></li>
          <li><a href="#sa"><span>A</span>Schedule A – Processing details</a></li>
          <li><a href="#sb"><span>B</span>Schedule B – Security controls</a></li>
          <li><a href="#sc"><span>C</span>Schedule C – Sub-processor register</a></li>
          <li><a href="#legal-related"><span>§</span>Related legal documents</a></li>
        </ol>
      </nav>

      <div class="legal-parties">
        <div class="legal-party"><small>Data Processor</small><b>Healthocare Private Limited</b><p>Provider of the HealthO Pro platform<br>Plot No. 23, Sr. No. 136, Opp. AIG Hospital, Gachibowli, Serilingampally, K.V. Rangareddy, Telangana – 500032<br>CIN: U62090TS2023PTC180399</p></div>
        <div class="legal-amp">&amp;</div>
        <div class="legal-party"><small>Data Fiduciary / Controller</small><b>The Subscriber</b><p>The clinic, laboratory, hospital, pharmacy or other healthcare provider named in the Order Form</p></div>
      </div>

      <h2 id="s1"><span class="legal-n">01</span>Scope and how this addendum fits</h2>
      <p>This Data Protection Addendum (the <strong>“Addendum”</strong>) is part of the HealthO Pro <a href="/subscription-terms">Platform Subscription Terms</a>, or any other agreement under which the Subscriber uses HealthO Pro (together, the <strong>“Agreement”</strong>). It explains how Healthocare Private Limited (<strong>“HealthO”</strong>, <strong>“we”</strong>, <strong>“us”</strong>) handles personal data on the Subscriber’s behalf.</p>
      <p>The Addendum is designed to meet the requirements of India’s Digital Personal Data Protection Act, 2023 and its rules, the Information Technology Act, 2000 and related rules on sensitive personal data, and, where a Subscriber or its patients fall within its reach, the EU General Data Protection Regulation. Anything not changed here continues to be governed by the Agreement.</p>
      <div class="legal-note legal-note--blue"><strong>HealthO vs HealthO Pro.</strong> HealthO is our app for patients. HealthO Pro is our platform for healthcare providers. This Addendum covers only data processed through <strong>HealthO Pro</strong> for a Subscriber. Where a patient separately uses the HealthO app, that relationship is governed by the HealthO <a href="/privacy-policy">Privacy Policy</a>.</div>

      <h2 id="s2"><span class="legal-n">02</span>Key terms</h2>
      <p>Capitalised words not defined below take the meaning given in the Agreement or in Applicable Data Protection Law.</p>
      <dl class="legal-terms">
        <dt>Applicable Data Protection Law</dt><dd>All privacy and data protection laws that apply to the processing, including the DPDP Act 2023, the IT Act 2000 and its rules, ABDM health data policies where relevant, and the GDPR where it applies.</dd>
        <dt>Data Fiduciary</dt><dd>The party that decides the purpose and means of processing. Under the GDPR this is the “controller”. For this Addendum, the Subscriber.</dd>
        <dt>Data Processor</dt><dd>The party that processes personal data on behalf of the Data Fiduciary. For this Addendum, HealthO.</dd>
        <dt>Data Principal</dt><dd>The individual the personal data relates to, such as a patient, a patient’s guardian, a referring doctor or a Subscriber staff member. Under the GDPR, the “data subject”.</dd>
        <dt>Health Data</dt><dd>Personal data about a person’s physical or mental health, including diagnoses, lab results, prescriptions, imaging, medical history and billing records linked to care.</dd>
        <dt>Subscriber Data</dt><dd>All personal data that the Subscriber or its users enter into, upload to, or generate through HealthO Pro.</dd>
        <dt>Security Incident</dt><dd>Any confirmed unauthorised access to, or loss, disclosure, alteration or destruction of, Subscriber Data. Unsuccessful attempts (such as blocked logins or port scans) are not Security Incidents.</dd>
        <dt>Sub-processor</dt><dd>A third party that HealthO engages to process Subscriber Data in order to deliver HealthO Pro.</dd>
      </dl>

      <h2 id="s3"><span class="legal-n">03</span>Roles of the parties</h2>
      <p>The Subscriber is the Data Fiduciary for Subscriber Data and HealthO acts as its Data Processor. HealthO is an independent Data Fiduciary only for the limited account, billing and usage information it needs to run its own business, which it handles under its own <a href="/privacy-policy">privacy policy</a>.</p>

      <h2 id="s4"><span class="legal-n">04</span>What data we process, and why</h2>
      <p>We process Subscriber Data only to provide, maintain, support and secure HealthO Pro for the Subscriber, and as the Subscriber otherwise instructs in writing (including through its configuration of the platform). Schedule A sets out the categories of data, data principals and processing activities.</p>
      <p>We will not sell Subscriber Data, use it for advertising, or use identifiable Health Data to train general-purpose models. We may create aggregated, de-identified statistics that cannot reasonably be linked back to any person or Subscriber, and use them to improve our services.</p>
      <p>Processing continues for as long as the Agreement is in force, and afterwards only for the wind-down period in Section 13.</p>

      <h2 id="s5"><span class="legal-n">05</span>Your responsibilities as Data Fiduciary</h2>
      <ul class="legal-check">
        <li>Have a valid legal basis, including consent where required, for collecting each item of Subscriber Data and for asking HealthO to process it.</li>
        <li>Give patients and other data principals clear notice of how their data is used, including use of cloud software such as HealthO Pro.</li>
        <li>Make sure your instructions to us are lawful and that the data you upload is accurate and limited to what you need.</li>
        <li>Manage your users responsibly: assign the right roles, protect login credentials, enable two-factor authentication, and remove access promptly when staff leave.</li>
        <li>Meet your own legal duties to regulators and data principals, including breach reporting and responding to rights requests.</li>
      </ul>

      <h2 id="s6"><span class="legal-n">06</span>Our commitments as Data Processor</h2>
      <ol class="legal-num">
        <li data-n="6.1">Process Subscriber Data only on the Subscriber’s documented instructions, unless the law requires otherwise. If we believe an instruction breaks the law, we will tell the Subscriber and may pause that processing.</li>
        <li data-n="6.2">Maintain the technical and organisational security measures described in Schedule B, and review them at least once a year.</li>
        <li data-n="6.3">Give access to Subscriber Data only to personnel and Sub-processors who need it to deliver the service.</li>
        <li data-n="6.4">Help the Subscriber, as far as is reasonable, with data protection impact assessments, regulator consultations and security obligations relating to HealthO Pro.</li>
        <li data-n="6.5">Keep records of our processing activities as required by Applicable Data Protection Law.</li>
        <li data-n="6.6">Tell the Subscriber promptly if a government authority asks us for Subscriber Data, unless the law prevents us. We will challenge requests that appear unlawful or too broad.</li>
      </ol>

      <h2 id="s7"><span class="legal-n">07</span>Confidentiality of personnel</h2>
      <p>Everyone at HealthO who can access Subscriber Data is bound by written confidentiality terms that continue after their engagement ends, completes privacy and security training when they join and every year after, and has access removed immediately when it is no longer needed.</p>

      <h2 id="s8"><span class="legal-n">08</span>Sub-processors</h2>
      <p>The Subscriber gives general authorisation for HealthO to use the Sub-processors listed in Schedule C. Before we add or replace a Sub-processor we will:</p>
      <ul class="legal-check">
        <li>check its security and privacy practices;</li>
        <li>put a written contract in place with data protection terms at least as protective as this Addendum; and</li>
        <li>give the Subscriber at least 15 days’ notice by email or in-app notice.</li>
      </ul>
      <p>The Subscriber may object on reasonable data protection grounds during the notice period. We will then try to resolve the concern. If we cannot, the Subscriber may end the affected part of the service and receive a pro-rata refund of prepaid fees for it. HealthO remains responsible for the work of its Sub-processors.</p>

      <h2 id="s9"><span class="legal-n">09</span>Hosting and cross-border transfers</h2>
      <p>For Subscribers in India, Subscriber Data is hosted in data centres located in India by default. We will not transfer it to a country that the Government of India has restricted under the DPDP Act.</p>
      <p>Where a transfer out of the European Economic Area, the UK or another region with transfer rules is involved, we will rely on a lawful transfer mechanism, such as adequacy decisions or the European Commission’s Standard Contractual Clauses, together with any supplementary measures that are needed.</p>

      <h2 id="s10"><span class="legal-n">10</span>Requests from data principals</h2>
      <p>HealthO Pro includes tools that let the Subscriber find, correct, export and delete patient records. If a data principal contacts us directly about Subscriber Data, we will pass the request to the Subscriber within 5 business days and will not respond ourselves unless the Subscriber authorises it. Where the platform tools are not enough, we will help the Subscriber respond within the time the law allows.</p>

      <h2 id="s11"><span class="legal-n">11</span>Breach notification</h2>
      <table>
        <thead><tr><th style="width:28%">Stage</th><th>What HealthO will do</th></tr></thead>
        <tbody>
          <tr><td><strong>Notify</strong></td><td>Tell the Subscriber’s nominated contact without undue delay, and in any case <strong>within 72 hours</strong> of confirming a Security Incident that affects its data.</td></tr>
          <tr><td><strong>Inform</strong></td><td>Share what we know: what happened, what data and how many people are likely affected, likely consequences, and the steps taken. We will send updates as the investigation progresses.</td></tr>
          <tr><td><strong>Contain</strong></td><td>Take prompt steps to contain the incident, reduce harm and prevent it happening again.</td></tr>
          <tr><td><strong>Support</strong></td><td>Help the Subscriber notify the Data Protection Board of India, CERT-In, other regulators or affected individuals, as required.</td></tr>
        </tbody>
      </table>
      <p>Notifying a Security Incident does not mean that HealthO accepts fault or liability for it.</p>

      <h2 id="s12"><span class="legal-n">12</span>Audits and compliance evidence</h2>
      <p>On written request, and no more than once in any 12-month period (unless a regulator requires it or a significant Security Incident has occurred), HealthO will provide:</p>
      <ul class="legal-check">
        <li>copies of current third-party security certifications or audit reports, such as ISO/IEC 27001 or SOC 2, where available;</li>
        <li>answers to a reasonable security questionnaire; and</li>
        <li>where the above is not enough, an on-site or remote audit by the Subscriber or an independent auditor bound by confidentiality, on at least 30 days’ notice, during business hours and without disrupting other customers.</li>
      </ul>
      <p>Each party bears its own audit costs, unless the audit shows a material breach of this Addendum by HealthO.</p>

      <h2 id="s13"><span class="legal-n">13</span>Retention, return and deletion</h2>
      <ol class="legal-num">
        <li data-n="13.1">During the subscription, the Subscriber can export its data at any time in common formats such as CSV, PDF and, where supported, HL7/FHIR.</li>
        <li data-n="13.2">For 30 days after the Agreement ends, the account stays available in read-only export mode.</li>
        <li data-n="13.3">After that, we delete Subscriber Data from live systems within a further 30 days. Encrypted backups are overwritten in their normal cycle, within 90 days.</li>
        <li data-n="13.4">We may keep data longer only where the law requires it. Any data we keep stays protected by this Addendum.</li>
      </ol>
      <div class="legal-note"><strong>Medical record retention.</strong> Indian rules and professional guidance require many providers to keep patient records for a set number of years. Responsibility for meeting those retention periods stays with the Subscriber, so please export your records before deletion.</div>

      <h2 id="s14"><span class="legal-n">14</span>Liability, term and precedence</h2>
      <p>Each party’s liability under this Addendum is subject to the limits and exclusions in the Agreement. This Addendum stays in force for as long as HealthO processes Subscriber Data. If this Addendum conflicts with the Agreement on data protection matters, this Addendum takes priority. If the Standard Contractual Clauses apply and conflict with this Addendum, the Clauses take priority.</p>

      <span class="legal-annex" id="sa">Schedule A</span>
      <h2 class="legal-annex-h">Processing details</h2>
      <table>
        <thead><tr><th style="width:30%">Item</th><th>Details</th></tr></thead>
        <tbody>
          <tr><td><strong>Data Fiduciary</strong></td><td>The Subscriber named in the Order Form. Contact: the account owner or Data Protection Officer it nominates.</td></tr>
          <tr><td><strong>Data Processor</strong></td><td>Healthocare Private Limited, Plot No. 23, Sr. No. 136, Opp. AIG Hospital, Gachibowli, Serilingampally, K.V. Rangareddy, Telangana – 500032. CIN: U62090TS2023PTC180399. Legal queries: <a href="mailto:legal@healtho.pro">legal@healtho.pro</a>.</td></tr>
          <tr><td><strong>Data principals</strong></td><td>Patients and their guardians or caregivers; referring and consulting doctors; Subscriber staff and authorised users; corporate or insurance contacts.</td></tr>
          <tr><td><strong>Categories of personal data</strong></td><td>Identity and contact details; demographics; patient ID and ABHA number (where used); appointment and visit records; billing and payment status; staff user credentials and activity logs.</td></tr>
          <tr><td><strong>Sensitive data</strong></td><td>Health Data, including test orders and results, reports, clinical notes, prescriptions, vitals, images and attachments; and in limited cases, biometric or genetic data where the Subscriber collects it.</td></tr>
          <tr><td><strong>Processing activities</strong></td><td>Hosting and storage; patient registration; appointment and workflow management; report generation and delivery (including WhatsApp, SMS and email notifications); billing; analytics dashboards for the Subscriber; integrations the Subscriber enables; backup, support and troubleshooting.</td></tr>
          <tr><td><strong>Frequency</strong></td><td>Continuous, for the term of the Agreement.</td></tr>
          <tr><td><strong>Retention</strong></td><td>As set out in Section 13.</td></tr>
        </tbody>
      </table>

      <span class="legal-annex" id="sb">Schedule B</span>
      <h2 class="legal-annex-h">Security controls</h2>
      <div class="legal-cards legal-cards--3">
        <div class="legal-card"><span class="legal-ic">◆</span><h4>Encryption</h4><p>TLS 1.2+ for all data in transit; AES-256 encryption for databases, file storage and backups; managed key storage with rotation.</p></div>
        <div class="legal-card"><span class="legal-ic">◆</span><h4>Access control</h4><p>Role-based permissions; least-privilege access for staff; two-factor authentication; session time-outs; quarterly access reviews.</p></div>
        <div class="legal-card"><span class="legal-ic">◆</span><h4>Monitoring &amp; logging</h4><p>Audit trails of user actions on patient records; centralised logging; 24×7 alerts for suspicious activity; log retention for at least 180 days, in line with CERT-In directions.</p></div>
        <div class="legal-card"><span class="legal-ic">◆</span><h4>Resilience</h4><p>Daily automated backups, stored separately; tested restore procedures; documented business continuity and disaster recovery plans.</p></div>
        <div class="legal-card"><span class="legal-ic">◆</span><h4>Secure development</h4><p>Code review, dependency scanning and separated environments; annual third-party penetration testing; prompt patching of critical issues.</p></div>
        <div class="legal-card"><span class="legal-ic">◆</span><h4>People &amp; process</h4><p>Background checks where lawful; confidentiality agreements; mandatory training; a documented incident response plan tested every year.</p></div>
      </div>

      <span class="legal-annex" id="sc">Schedule C</span>
      <h2 class="legal-annex-h">Sub-processor register</h2>
      <p>The categories of Sub-processors we use are listed below. The current list of named providers is available on request from <a href="mailto:legal@healtho.pro">legal@healtho.pro</a>.</p>
      <table>
        <thead><tr><th>Sub-processor</th><th>Purpose</th><th>Data location</th></tr></thead>
        <tbody>
          <tr><td>Cloud hosting provider</td><td>Infrastructure, storage and backups</td><td>India</td></tr>
          <tr><td>Messaging provider</td><td>SMS and WhatsApp report and appointment notifications</td><td>India</td></tr>
          <tr><td>Email delivery provider</td><td>Transactional email</td><td>As notified on request</td></tr>
          <tr><td>Payment gateway</td><td>Processing subscription and patient payments</td><td>India</td></tr>
          <tr><td>Support desk tool</td><td>Customer support tickets</td><td>As notified on request</td></tr>
        </tbody>
      </table>

      <?php legal_framework_related($page); ?>

      <h2>Contact</h2>
      <p>Questions about this Addendum or how we handle data: <a href="mailto:legal@healtho.pro">legal@healtho.pro</a> · <a href="mailto:support@healtho.pro">support@healtho.pro</a> · <a href="tel:<?= $tel ?>"><?= PHONE_SUPPORT ?></a>. See also our <a href="/subscription-terms">Platform Subscription Terms</a> and <a href="/privacy-policy">Privacy Policy</a>.</p>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
