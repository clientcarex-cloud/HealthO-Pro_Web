<?php
$page        = 'subscription-terms';
$title       = 'Platform Subscription Terms | HealthO Pro';
$description = 'HealthO Pro Platform Subscription Terms: the terms on which clinics, diagnostic labs, hospitals and other healthcare providers subscribe to and use the HealthO Pro cloud platform.';
require __DIR__ . '/partials/head.php';
$tel = str_replace(' ', '', PHONE_SUPPORT);
?>
<header class="page-hero">
  <div class="hero-glow glow-1"></div>
  <div class="hero-glow glow-2"></div>
  <div class="container">
    <div class="breadcrumb"><a href="/">Home</a><span>›</span>Platform Subscription Terms</div>
    <h1>Platform Subscription <span class="text-grad">Terms</span></h1>
    <p>The terms on which clinics, diagnostic labs, hospitals and other healthcare providers subscribe to and use the HealthO Pro cloud platform.</p>
    <p class="legal-meta">Issued by Healthocare Private Limited · Product: HealthO Pro (SaaS) · Last updated: November 2026</p>
  </div>
</header>

<section class="section section--soft">
  <div class="container">
    <div class="prose legal-doc">
      <h2 class="legal-glance-title">What you’re agreeing to, in brief</h2>
      <p>This summary helps you find what matters quickly. It does not replace the full terms below.</p>
      <div class="legal-cards">
        <div class="legal-card"><span class="legal-ic">✓</span><h4>Access, not ownership</h4><p>You get a subscription to use HealthO Pro for your practice. We own the software; you own your patient and business data.</p></div>
        <div class="legal-card"><span class="legal-ic">%</span><h4>99.5% uptime target</h4><p>We monitor the platform around the clock and give service credits if monthly availability drops below our commitment.</p></div>
        <div class="legal-card"><span class="legal-ic">₹</span><h4>Simple billing</h4><p>Fees are invoiced in advance for each billing cycle. Pay within 15 days. Taxes such as GST are added as applicable.</p></div>
        <div class="legal-card"><span class="legal-ic">↻</span><h4>Renewal &amp; exit</h4><p>Plans renew automatically unless either side gives 30 days’ notice. You can export your data before you leave.</p></div>
      </div>

      <nav class="legal-toc" aria-label="Contents">
        <h3>Contents</h3>
        <ol>
          <li><a href="#s1"><span>01</span>About these terms</a></li>
          <li><a href="#s2"><span>02</span>Definitions</a></li>
          <li><a href="#s3"><span>03</span>Your subscription</a></li>
          <li><a href="#s4"><span>04</span>Onboarding and support</a></li>
          <li><a href="#s5"><span>05</span>Service availability</a></li>
          <li><a href="#s6"><span>06</span>Acceptable use</a></li>
          <li><a href="#s7"><span>07</span>Fees and payment</a></li>
          <li><a href="#s8"><span>08</span>Your data and privacy</a></li>
          <li><a href="#s9"><span>09</span>Security and hosting</a></li>
          <li><a href="#s10"><span>10</span>Intellectual property</a></li>
          <li><a href="#s11"><span>11</span>Confidentiality</a></li>
          <li><a href="#s12"><span>12</span>Clinical responsibility</a></li>
          <li><a href="#s13"><span>13</span>Warranties and disclaimers</a></li>
          <li><a href="#s14"><span>14</span>Indemnities</a></li>
          <li><a href="#s15"><span>15</span>Limitation of liability</a></li>
          <li><a href="#s16"><span>16</span>Term, renewal and termination</a></li>
          <li><a href="#s17"><span>17</span>Law and disputes</a></li>
          <li><a href="#s18"><span>18</span>General</a></li>
        </ol>
      </nav>

      <h2 id="s1"><span class="legal-n">01</span>About these terms</h2>
      <p>These Platform Subscription Terms (the <strong>“Terms”</strong>) form a binding agreement between <strong>Healthocare Private Limited</strong>, a company incorporated in India (<strong>“HealthO”</strong>, <strong>“we”</strong>, <strong>“us”</strong>), and the healthcare provider that signs an Order Form or accepts these Terms online (the <strong>“Customer”</strong>, <strong>“you”</strong>).</p>
      <p>The Terms, each Order Form, the <a href="/data-protection-addendum">Data Protection Addendum</a> and any policies referred to here together make up the <strong>“Agreement”</strong>. If they conflict, this order applies: (1) the Order Form, (2) the Data Protection Addendum on data matters, (3) these Terms, (4) the policies.</p>
      <p>The person accepting these Terms confirms that they are authorised to bind the Customer.</p>
      <div class="legal-note legal-note--blue"><strong>Two products, one family.</strong> <strong>HealthO</strong> is our app for patients. <strong>HealthO Pro</strong> is our platform for healthcare providers. These Terms cover HealthO Pro only.</div>

      <h2 id="s2"><span class="legal-n">02</span>Definitions</h2>
      <dl class="legal-terms">
        <dt>Platform</dt><dd>The HealthO Pro web and mobile applications, APIs, integrations and related documentation, as updated from time to time.</dd>
        <dt>Order Form</dt><dd>The quotation, proposal or online checkout that sets out your plan, modules, number of users or locations, fees and Subscription Term.</dd>
        <dt>Subscription Term</dt><dd>The initial period in the Order Form plus any renewals.</dd>
        <dt>Authorised Users</dt><dd>Your employees, doctors, technicians and contractors whom you allow to use the Platform under your account.</dd>
        <dt>Customer Data</dt><dd>All data, including patient and Health Data, that you or your Authorised Users enter into or generate through the Platform.</dd>
        <dt>Scheduled Maintenance</dt><dd>Planned work announced at least 48 hours ahead and, wherever possible, carried out between 11:00 pm and 6:00 am IST.</dd>
        <dt>Unavailability</dt><dd>Time during which the core Platform cannot be accessed by any of your Authorised Users because of a fault on our side, not counting the exclusions in Section 5.</dd>
      </dl>

      <h2 id="s3"><span class="legal-n">03</span>Your subscription</h2>
      <ol class="legal-num">
        <li data-n="3.1">For the Subscription Term, we grant you a non-exclusive, non-transferable right for your Authorised Users to access and use the Platform for your internal healthcare operations, within the limits in your Order Form.</li>
        <li data-n="3.2">You may add users, locations or modules at any time. Additions are charged pro rata for the rest of the current billing cycle. Reductions take effect at the next renewal.</li>
        <li data-n="3.3">We continuously improve HealthO Pro and may add, change or retire features. We will not materially reduce the core functionality of your plan during a paid Subscription Term, and we will give at least 60 days’ notice before retiring a significant feature.</li>
        <li data-n="3.4">Third-party services you choose to connect (for example, payment gateways, messaging providers, lab machines, insurance portals or ABDM services) are governed by their own terms. We are not responsible for their availability or conduct.</li>
      </ol>

      <h2 id="s4"><span class="legal-n">04</span>Onboarding and support</h2>
      <table>
        <thead><tr><th style="width:24%">Priority</th><th>Example</th><th style="width:24%">First response</th></tr></thead>
        <tbody>
          <tr><td><strong>P1 – Critical</strong></td><td>Platform down, or reports cannot be generated at all</td><td>Within 1 hour, 24×7</td></tr>
          <tr><td><strong>P2 – High</strong></td><td>A key module is impaired with no workaround</td><td>Within 4 business hours</td></tr>
          <tr><td><strong>P3 – Normal</strong></td><td>Partial issue with a workaround, or a configuration request</td><td>Within 1 business day</td></tr>
          <tr><td><strong>P4 – Low</strong></td><td>Questions, training, feature suggestions</td><td>Within 2 business days</td></tr>
        </tbody>
      </table>
      <p>Support is available through in-app chat, email and phone during business hours (Monday to Saturday, 9:00 am to 7:00 pm IST), and for P1 issues at all times. Standard onboarding, data migration and training are included as described in your Order Form. Custom development, interfaces or on-site visits are quoted separately. See <a href="/sla-and-support-services">SLA &amp; Support Services</a> for more detail.</p>

      <h2 id="s5"><span class="legal-n">05</span>Service availability</h2>
      <p>We aim for the Platform to be available <strong>99.5% of the time</strong> in each calendar month. Availability is calculated as total minutes in the month, minus minutes of Unavailability, as a percentage of total minutes.</p>
      <h3>Service credits</h3>
      <table>
        <thead><tr><th>Monthly availability</th><th>Credit on that month’s subscription fee</th></tr></thead>
        <tbody>
          <tr><td>99.0% to below 99.5%</td><td>5%</td></tr>
          <tr><td>98.0% to below 99.0%</td><td>10%</td></tr>
          <tr><td>Below 98.0%</td><td>20%</td></tr>
        </tbody>
      </table>
      <p>To receive a credit, raise a request within 30 days of the end of the affected month. Credits are applied to your next invoice, cannot be exchanged for cash, and are your only financial remedy for Unavailability.</p>
      <p><strong>Exclusions.</strong> Unavailability does not include Scheduled Maintenance; problems with your internet, devices, browsers or local network; issues caused by third-party services you connect; misuse or unauthorised changes by your users; suspension under these Terms; or events outside our reasonable control (Section 18).</p>

      <h2 id="s6"><span class="legal-n">06</span>Acceptable use</h2>
      <p>You are responsible for everything that happens under your account. You and your Authorised Users must not:</p>
      <ul class="legal-check legal-check--x">
        <li>share login credentials, or let anyone who is not an Authorised User use the Platform;</li>
        <li>resell, sublicense or offer the Platform to third parties as a service bureau;</li>
        <li>copy, modify, decompile or try to derive the source code of the Platform, or build a competing product using it;</li>
        <li>upload malware, probe or disrupt our systems, or bypass security or usage limits;</li>
        <li>run load tests or security scans without our prior written approval;</li>
        <li>use the Platform for anything unlawful, including processing personal data without a valid legal basis or sending unsolicited marketing messages in breach of TRAI or other rules.</li>
      </ul>
      <p>We may suspend access, with notice where practical, if needed to stop a security threat, a serious breach of these Terms, or harm to other customers. We will restore access as soon as the issue is resolved.</p>

      <h2 id="s7"><span class="legal-n">07</span>Fees and payment</h2>
      <ol class="legal-num">
        <li data-n="7.1"><strong>Fees.</strong> You will pay the fees in your Order Form. Unless stated otherwise, fees are invoiced in advance for each billing cycle (monthly, quarterly or annually) and are payable within 15 days of the invoice date.</li>
        <li data-n="7.2"><strong>Usage-based charges.</strong> Charges for SMS, WhatsApp, e-mail credits, extra storage or similar usage items are billed in advance as prepaid packs or in arrears as stated in your Order Form.</li>
        <li data-n="7.3"><strong>Taxes.</strong> Fees do not include GST or other applicable taxes, which will be added to invoices. If the law requires you to deduct tax at source, you will give us the relevant certificate promptly.</li>
        <li data-n="7.4"><strong>Late payment.</strong> Overdue amounts may attract interest at 1.5% per month or the maximum allowed by law, whichever is lower. If an invoice is more than 30 days overdue, we may suspend access after giving 7 days’ written notice.</li>
        <li data-n="7.5"><strong>Price changes.</strong> We may change prices for a renewal term by giving at least 45 days’ notice before renewal. Prices are fixed during a paid term.</li>
        <li data-n="7.6"><strong>Disputes.</strong> If you dispute an invoice in good faith, tell us within 15 days. You must pay the undisputed part on time while we resolve it together.</li>
        <li data-n="7.7"><strong>Refunds.</strong> Fees paid are non-refundable, except where these Terms expressly provide for a refund.</li>
      </ol>

      <h2 id="s8"><span class="legal-n">08</span>Your data and privacy</h2>
      <ol class="legal-num">
        <li data-n="8.1">You own all Customer Data. You give us the right to host, process and transmit it only as needed to provide, secure and support the Platform for you.</li>
        <li data-n="8.2">For patient data, you are the Data Fiduciary and we are your Data Processor. Our <a href="/data-protection-addendum">Data Protection Addendum</a> forms part of this Agreement and sets out our detailed privacy obligations.</li>
        <li data-n="8.3">You are responsible for the accuracy and legality of Customer Data, and for obtaining any consents needed from patients and others.</li>
        <li data-n="8.4">We may use account and usage information (for example, how features are used and performance metrics) to operate, secure and improve our services, and we may create aggregated, de-identified statistics that do not identify you or any individual.</li>
        <li data-n="8.5">Do not upload data that the Platform is not intended to hold, such as payment card numbers outside our payment integration, or data subject to export controls.</li>
      </ol>

      <h2 id="s9"><span class="legal-n">09</span>Security and hosting</h2>
      <p>We host the Platform with reputable cloud infrastructure providers, in India for Indian customers by default. We protect it with industry-standard safeguards, including encryption in transit and at rest, role-based access controls, audit logs, daily backups, monitoring and regular vulnerability testing. Full details are in Schedule B of the <a href="/data-protection-addendum#sb">Data Protection Addendum</a>.</p>
      <p>You are responsible for security on your side, including your devices, networks, user access settings and passwords. We strongly recommend you turn on two-factor authentication for all users.</p>

      <h2 id="s10"><span class="legal-n">10</span>Intellectual property</h2>
      <p>HealthO and its licensors own all rights in the Platform, the HealthO and HealthO Pro names and logos, and all related software, designs, report templates and documentation. Nothing in this Agreement transfers those rights to you.</p>
      <p>If you send us suggestions or feedback, we may use them freely to improve our products, without any obligation to you.</p>
      <p>With your prior consent, we may name you as a customer and use your logo in our marketing. You can withdraw that consent at any time.</p>

      <h2 id="s11"><span class="legal-n">11</span>Confidentiality</h2>
      <p>Each party will keep confidential any non-public information it receives from the other that is marked as confidential or would reasonably be understood to be so, including pricing, product plans, security information and Customer Data. Each party will use it only for this Agreement and share it only with staff and advisers who need to know it and are bound by similar obligations.</p>
      <p>This does not apply to information that is already public through no fault of the receiving party, that it already knew or developed independently, or that it lawfully received from someone else. Information may be disclosed where required by law, after giving the other party notice where allowed. These obligations last during the Agreement and for 3 years after it ends, and for Customer Data and trade secrets, for as long as they remain confidential.</p>

      <h2 id="s12"><span class="legal-n">12</span>Clinical responsibility</h2>
      <div class="legal-note"><strong>HealthO Pro supports care; it does not provide it.</strong> The Platform is an administrative and workflow tool. Reference ranges, alerts, templates, AI-assisted suggestions and other outputs are aids only. All diagnosis, treatment, reporting and clinical decisions remain the sole responsibility of you and your qualified professionals, who must review every output before relying on it.</div>
      <p>You are responsible for holding all registrations, accreditations and licences needed to run your practice, and for following all medical, laboratory and pharmacy regulations that apply to you.</p>

      <h2 id="s13"><span class="legal-n">13</span>Warranties and disclaimers</h2>
      <p>We warrant that, during the Subscription Term, the Platform will work materially as described in its documentation and that we will provide our services with reasonable skill and care. If it does not, tell us and we will use reasonable efforts to fix the problem. If we cannot fix it within 30 days, you may end the affected subscription and receive a pro-rata refund of prepaid fees for the period after termination. This is your only remedy for breach of this warranty.</p>
      <p class="legal-caps">Except as expressly set out in this Agreement, the Platform is provided “as is” and “as available”. To the extent the law allows, HealthO makes no other warranties, express or implied, including any warranty of merchantability, fitness for a particular purpose, uninterrupted or error-free operation, or that the Platform will meet every one of your requirements.</p>

      <h2 id="s14"><span class="legal-n">14</span>Indemnities</h2>
      <h3>From HealthO</h3>
      <p>We will defend you against any third-party claim that your authorised use of the Platform infringes that party’s intellectual property rights, and pay any damages finally awarded or agreed in settlement. If such a claim arises, we may get you the right to continue using the Platform, modify it so it no longer infringes, or, if neither is reasonably possible, end the affected subscription and refund prepaid fees for the unused period. We have no obligation for claims caused by your changes, combination with items we did not supply, or use in breach of this Agreement.</p>
      <h3>From the Customer</h3>
      <p>You will defend us against any third-party claim (including from patients or regulators) arising from Customer Data, your clinical services, or your or your users’ breach of Section 6 or of applicable law, and pay any damages finally awarded or agreed in settlement.</p>
      <h3>Process</h3>
      <p>The party seeking protection must notify the other promptly, give it control of the defence and settlement, and provide reasonable help at its cost. No settlement that admits fault on behalf of the protected party may be made without its consent.</p>

      <h2 id="s15"><span class="legal-n">15</span>Limitation of liability</h2>
      <p class="legal-caps">Neither party will be liable for any indirect, incidental, special or consequential loss, or for loss of profits, revenue, goodwill or anticipated savings, however caused, even if advised that it was possible.</p>
      <p class="legal-caps">Each party’s total liability arising out of or relating to this Agreement in any 12-month period will not exceed the fees paid or payable by the Customer for the Platform in the 12 months before the event giving rise to the claim.</p>
      <p class="legal-caps">These limits do not apply to the Customer’s obligation to pay fees, either party’s indemnity obligations, breach of confidentiality, or liability that cannot be limited by law, such as for fraud or wilful misconduct.</p>

      <h2 id="s16"><span class="legal-n">16</span>Term, renewal and termination</h2>
      <ol class="legal-num">
        <li data-n="16.1"><strong>Term.</strong> This Agreement starts on the date you accept it and continues until all Subscription Terms have ended.</li>
        <li data-n="16.2"><strong>Renewal.</strong> Each Subscription Term renews automatically for the same length unless either party gives written notice at least 30 days before the end of the current term.</li>
        <li data-n="16.3"><strong>Termination for breach.</strong> Either party may end this Agreement by written notice if the other materially breaches it and does not fix the breach within 30 days of being told, or immediately if the other becomes insolvent or enters liquidation.</li>
        <li data-n="16.4"><strong>On termination.</strong> Your access ends and all unpaid fees become due. If you end the Agreement because of our uncured breach, we will refund prepaid fees for the unused period.</li>
        <li data-n="16.5"><strong>Data export.</strong> For 30 days after termination, you can export your Customer Data in read-only mode. After that we will delete it as described in the Data Protection Addendum. Help with exports beyond the standard tools may be charged at our standard rates.</li>
        <li data-n="16.6"><strong>Survival.</strong> Sections on fees owed, data, intellectual property, confidentiality, indemnities, liability, law and disputes, and general terms continue after termination.</li>
      </ol>

      <h2 id="s17"><span class="legal-n">17</span>Law and disputes</h2>
      <p>This Agreement is governed by the laws of India. The parties will first try to resolve any dispute through good-faith discussion between senior representatives within 30 days of written notice.</p>
      <p>If the dispute is not resolved, it will be finally settled by arbitration under the Arbitration and Conciliation Act, 1996, by a sole arbitrator appointed by mutual agreement. The seat and venue of arbitration will be Hyderabad, India, and proceedings will be in English. Subject to arbitration, the courts at Hyderabad have exclusive jurisdiction. Either party may seek urgent interim relief from a competent court.</p>
      <p>For Customers outside India, the governing law and contracting entity may differ, as stated in the Order Form.</p>

      <h2 id="s18"><span class="legal-n">18</span>General</h2>
      <dl class="legal-terms">
        <dt>Force majeure</dt><dd>Neither party is liable for delay or failure caused by events outside its reasonable control, such as natural disasters, epidemics, war, government action, widespread internet or power failures, or attacks on infrastructure providers. Payment obligations are not excused.</dd>
        <dt>Changes to terms</dt><dd>We may update these Terms by giving at least 30 days’ notice. Changes apply from your next renewal, unless required by law sooner. Continued use after that date means you accept the update.</dd>
        <dt>Assignment</dt><dd>Neither party may transfer this Agreement without the other’s consent, except to a successor in a merger, acquisition or sale of substantially all of its relevant business.</dd>
        <dt>Notices</dt><dd>Legal notices must be in writing and sent by email with confirmation, or by courier, to the addresses in the Order Form. Operational notices may be given in-app or by email.</dd>
        <dt>Relationship</dt><dd>The parties are independent contractors. Nothing creates a partnership, agency or employment relationship.</dd>
        <dt>Entire agreement</dt><dd>This Agreement is the full agreement on its subject and replaces earlier discussions. Pre-printed terms in your purchase orders do not apply.</dd>
        <dt>Severability &amp; waiver</dt><dd>If any part is found unenforceable, the rest stays in effect. Not enforcing a right does not waive it.</dd>
        <dt>Anti-bribery</dt><dd>Each party will comply with applicable anti-corruption laws and will not offer or accept improper payments in connection with this Agreement.</dd>
      </dl>

      <h2 id="contact"><span class="legal-n">✉</span>Contact us</h2>
      <table>
        <thead><tr><th style="width:30%">For</th><th>Reach us at</th></tr></thead>
        <tbody>
          <tr><td><strong>Support</strong></td><td><a href="mailto:support@healtho.pro">support@healtho.pro</a> · <a href="tel:<?= $tel ?>"><?= PHONE_SUPPORT ?></a></td></tr>
          <tr><td><strong>General enquiries</strong></td><td><a href="mailto:info@healtho.pro">info@healtho.pro</a></td></tr>
          <tr><td><strong>Billing</strong></td><td><a href="mailto:accounts@healtho.pro">accounts@healtho.pro</a></td></tr>
          <tr><td><strong>Legal notices</strong></td><td>Healthocare Private Limited, Plot No. 23, Sr. No. 136, Opp. AIG Hospital, Gachibowli, Serilingampally, K.V. Rangareddy, Telangana – 500032 · CIN: U62090TS2023PTC180399 · <a href="mailto:legal@healtho.pro">legal@healtho.pro</a></td></tr>
          <tr><td><strong>Legal queries</strong></td><td><a href="mailto:legal@healtho.pro">legal@healtho.pro</a></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/foot.php';
