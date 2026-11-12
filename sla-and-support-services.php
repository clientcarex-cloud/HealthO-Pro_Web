<?php
$page        = 'sla-and-support-services';
$title       = 'SLA & Support Services | HealthO Pro';
$description = 'HealthO Pro Service Level Agreement: uptime commitment, support hours and channels, incident severity levels, response and resolution times, and maintenance for HIMS, LIMS, CIMS and RIS customers.';
require __DIR__ . '/partials/head.php';
$tel = str_replace(' ', '', PHONE_SUPPORT);
?>
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>SLA &amp; Support Services</div>
    <h1>SLA &amp; <span class="text-grad">Support Services</span></h1>
    <p>Last updated: November 2026</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="prose">
      <p>This Service Level Agreement ("SLA") describes the availability, maintenance and support that Healthocare Private Limited ("HealthO Pro", "we", "us" or "our") provides to customers of HealthO Pro HIMS, LIMS, CIMS, RIS/RIMS and related modules (the "Customer", "you" or "your"). It forms part of your subscription or service agreement (the "Principal Agreement"), including our <a href="/subscription-terms">Platform Subscription Terms</a>. If this page and those Terms differ, the Terms prevail.</p>

      <h2>1. Objectives</h2>
      <ul>
        <li>Keep the software your hospital, laboratory, clinic or radiology center depends on available and dependable.</li>
        <li>Set clear, measurable expectations for how quickly we respond to and resolve issues.</li>
        <li>Define each party's responsibilities so that problems are reported, prioritised and closed efficiently.</li>
      </ul>

      <h2>2. Scope of Services</h2>
      <h3>Hosting and security</h3>
      <ul>
        <li>Cloud hosting of your HealthO Pro instance, with a dedicated database for your organisation.</li>
        <li>Encrypted data transmission (HTTPS), secure storage and regular automated backups.</li>
        <li>Server monitoring, security patching and protection against common threats.</li>
        <li>Secure handling of online payments through certified payment gateways.</li>
      </ul>
      <h3>Maintenance and updates</h3>
      <ul>
        <li>Regular software updates with bug fixes, security improvements and new features, at no extra cost within your plan.</li>
        <li>Advance notice of any planned maintenance that affects availability.</li>
      </ul>
      <h3>Support</h3>
      <ul>
        <li>Help through phone, WhatsApp, email and support tickets.</li>
        <li>Remote-desktop help for configuration, printer, barcode and machine-interfacing (LIS) setup.</li>
        <li>Online training for new staff and refresher sessions when you need them.</li>
        <li>On-site visits, custom development, bespoke report formats and data migration from other software are available as paid services, quoted separately.</li>
      </ul>

      <h2>3. Service Availability</h2>
      <p>We target <strong>99.5% monthly uptime</strong> for the HealthO Pro cloud application, measured over each calendar month and excluding the events listed in Section 8. If monthly availability falls below this target, you are eligible for service credits as set out in Section 5 of our <a href="/subscription-terms#s5">Platform Subscription Terms</a>.</p>
      <ul>
        <li><strong>Planned maintenance</strong> is scheduled outside peak hours, normally between 11:00 PM and 6:00 AM IST, and announced at least 48 hours ahead through in-app notices, email or WhatsApp.</li>
        <li><strong>Emergency maintenance</strong>, such as an urgent security fix, may be carried out with shorter notice. We will keep any disruption as short as possible.</li>
      </ul>

      <h2>4. Support Hours and Channels</h2>
      <table>
        <thead>
          <tr><th>Channel</th><th>Availability</th><th>Best for</th></tr>
        </thead>
        <tbody>
          <tr><td>Phone: <a href="tel:<?= $tel ?>"><?= PHONE_SUPPORT ?></a></td><td>Monday to Saturday, 9:00 AM to 7:00 PM IST</td><td>Urgent issues and anything blocking patient work</td></tr>
          <tr><td>WhatsApp: <a href="<?= WHATSAPP_URL ?>" target="_blank" rel="noopener"><?= PHONE_SALES ?></a></td><td>Monday to Saturday, 9:00 AM to 7:00 PM IST</td><td>Quick questions, screenshots and short how-tos</td></tr>
          <tr><td>Email: <a href="mailto:support@healtho.pro">support@healtho.pro</a></td><td>Received 24/7, handled during support hours</td><td>Detailed requests, change requests and documents</td></tr>
          <tr><td>Support ticket (in the software)</td><td>Raised 24/7, handled during support hours</td><td>Tracked issues with a full history</td></tr>
        </tbody>
      </table>
      <p>P1 (Critical) incidents reported by phone are handled 24×7, outside these hours and on Sundays and public holidays as well. Other requests received outside support hours are picked up on the next working day.</p>

      <h2>5. Incident Priority Levels</h2>
      <table>
        <thead>
          <tr><th>Priority</th><th>Definition</th><th>Examples</th></tr>
        </thead>
        <tbody>
          <tr><td><strong>P1: Critical</strong></td><td>The software is down or unusable for all users, or there is a risk to data integrity, with no workaround.</td><td>Unable to sign in; billing or registration completely stopped; data loss suspected.</td></tr>
          <tr><td><strong>P2: High</strong></td><td>A core function is badly affected for many users and no reasonable workaround exists.</td><td>Reports not printing or not being delivered; lab machine interface stopped; payments not saving.</td></tr>
          <tr><td><strong>P3: Normal</strong></td><td>A function is partly affected, or a workaround exists.</td><td>A single report format wrong; an MIS report showing incorrect totals; slowness in one screen.</td></tr>
          <tr><td><strong>P4: Low</strong></td><td>A minor issue, a cosmetic problem, a how-to question or a feature request.</td><td>Label alignment; a spelling error; a request for a new field or report.</td></tr>
        </tbody>
      </table>

      <h2>6. Response and Resolution Times</h2>
      <table>
        <thead>
          <tr><th>Priority</th><th>First response</th><th>Target resolution or workaround</th></tr>
        </thead>
        <tbody>
          <tr><td>P1: Critical</td><td>Within 1 hour, 24×7</td><td>Within 4 hours</td></tr>
          <tr><td>P2: High</td><td>Within 4 business hours</td><td>Within 1 working day</td></tr>
          <tr><td>P3: Normal</td><td>Within 1 business day</td><td>Within 3 working days</td></tr>
          <tr><td>P4: Low</td><td>Within 2 business days</td><td>Scheduled in a future release, or as agreed</td></tr>
        </tbody>
      </table>
      <p>Times run from when we receive a complete report (see Section 7). Resolution means the issue is fixed or a workaround is in place that lets your work continue; a permanent fix may follow in a later update. Issues that depend on third parties, such as a telecom operator, WhatsApp, a payment gateway or an equipment manufacturer, are escalated to them, and their timelines may be outside our control.</p>

      <h2>7. Reporting an Incident</h2>
      <p>To help us resolve issues quickly, please include:</p>
      <ul>
        <li>your organisation name, branch and the name and mobile number of the person reporting;</li>
        <li>what happened and what you expected to happen;</li>
        <li>the screen or module affected, with patient ID, visit ID or bill number where relevant;</li>
        <li>screenshots or a short screen recording, and any error message shown; and</li>
        <li>how many users are affected and when the issue started.</li>
      </ul>
      <p>We will give you a ticket number, keep you updated on progress and confirm with you before closing the ticket. If you are not satisfied with how an issue is being handled, you can escalate it by email to <a href="mailto:support@healtho.pro">support@healtho.pro</a> with "Escalation" in the subject, and a senior team member will review it.</p>

      <h2>8. Exclusions</h2>
      <p>This SLA does not cover unavailability or issues caused by:</p>
      <ul>
        <li>planned or emergency maintenance carried out as described in Section 3;</li>
        <li>your internet connection, local network, computers, printers, barcode scanners or lab and imaging equipment;</li>
        <li>failures of third-party services such as SMS operators, WhatsApp, email providers, payment gateways or government portals;</li>
        <li>misuse, unauthorised changes, or use that does not follow our documentation;</li>
        <li>suspension of services for overdue payment or breach of the Principal Agreement;</li>
        <li>events beyond our reasonable control, including natural disasters, power or telecom failures, cyber-attacks on third-party infrastructure and government action.</li>
      </ul>

      <h2>9. Customer Responsibilities</h2>
      <ul>
        <li>Pay subscription and service invoices on time, as set out in your Principal Agreement.</li>
        <li>Nominate one or more trained contact persons for support communication.</li>
        <li>Keep a stable internet connection and supported, up-to-date browsers and devices.</li>
        <li>Keep user credentials secure and manage staff access and permissions.</li>
        <li>Check the accuracy of data entered, results released and reports issued from the software, which remain your clinical and legal responsibility.</li>
        <li>Be reasonably available to help us diagnose and test a fix during an incident.</li>
      </ul>

      <h2>10. Our Responsibilities</h2>
      <ul>
        <li>Meet the response and resolution targets in Section 6.</li>
        <li>Notify you in advance of planned maintenance and major changes.</li>
        <li>Keep you informed during incidents and explain the cause of any P1 incident once it is resolved.</li>
        <li>Protect your data as described in our <a href="/data-processing-agreement">Data Processing Agreement</a> and <a href="/privacy-policy">Privacy Policy</a>.</li>
      </ul>

      <h2>11. Confidentiality</h2>
      <p>Both parties will keep confidential any non-public information shared during support, including patient data, business data and system details, and will use it only to provide or receive the services. Our support team accesses your data only as needed to resolve your request.</p>

      <h2>12. Changes to this SLA</h2>
      <p>We may update this SLA to reflect improvements to our services. Changes will be posted on this page, and any change that reduces the service levels in your current agreement will not apply until your next renewal.</p>

      <h2>Contact</h2>
      <p>Support: <a href="tel:<?= $tel ?>"><?= PHONE_SUPPORT ?></a> · <a href="mailto:support@healtho.pro">support@healtho.pro</a></p>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
