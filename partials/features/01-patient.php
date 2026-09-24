<?php
/** Patient Experience & Front Desk — see partials/blog-data.php for the format. */
return [
    'appointment-booking' => [
        'name'  => 'Appointment Booking',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'ris', 'lims'],
        'short' => 'Online and walk-in bookings with doctor slots, calendars and instant confirmations.',
        'title' => 'Appointment Booking: fill every slot without the phone-tag',
        'desc'  => 'HealthO Pro Appointment Booking handles online and walk-in bookings, doctor slots, calendars and instant confirmations for hospitals, clinics and labs.',
        'intro' => 'Missed calls, double-booked doctors and long queues at the front desk all start with how appointments are taken. HealthO Pro Appointment Booking puts every doctor’s availability, every booking channel and every confirmation in one calendar, so the front desk spends less time on the phone and patients get a time they can rely on.',
        'points' => [
            ['Online and walk-in booking', 'Take bookings from your website, the patient app, WhatsApp or the front desk — they all land in the same calendar.'],
            ['Doctor availability slots', 'Set consulting hours, slot length, breaks and leave per doctor, so only genuinely free slots are offered.'],
            ['Calendar and day views', 'See the whole day by doctor, department or room and reschedule with a click.'],
            ['Instant confirmations', 'Patients receive a confirmation with the date, time and doctor as soon as the booking is made.'],
            ['Linked to registration and billing', 'A booked patient is registered, tokenised and billed without re-entering their details.'],
        ],
        'benefits' => [
            'Fewer no-shows and double bookings.',
            'Shorter front-desk queues at peak hours.',
            'Doctors see a predictable, well-spread day.',
            'Management sees booking trends by doctor and channel.',
        ],
        'faqs' => [
            'Can patients book appointments online?' => 'Yes. Bookings can come from your website, the patient app or messaging channels as well as the front desk, and all of them use the same live doctor availability.',
            'Can one calendar handle several doctors and departments?' => 'Yes. Each doctor has their own slots and schedule, and the calendar can be viewed by doctor, department or the whole facility.',
        ],
    ],

    'follow-ups-for-appointments' => [
        'name'  => 'Follow-ups for Appointments',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims'],
        'short' => 'Schedule follow-up visits at consultation and remind patients automatically.',
        'title' => 'Follow-ups for Appointments: bring every patient back on time',
        'desc'  => 'Schedule follow-up visits during the consultation and let HealthO Pro remind patients automatically, so treatment plans are completed and revenue is not lost.',
        'intro' => 'A treatment plan only works if the patient comes back. Follow-ups are easy to agree in the consulting room and easy to forget afterwards. HealthO Pro lets the doctor set the follow-up during the visit and then takes care of reminding the patient and tracking whether they returned.',
        'points' => [
            ['Set at consultation', 'The doctor picks the follow-up date or interval while writing the prescription.'],
            ['Automatic reminders', 'Patients are reminded before the due date by SMS, WhatsApp or email.'],
            ['Due and overdue lists', 'The front desk sees who is due today and who has missed their follow-up.'],
            ['One-click rebooking', 'Turn a due follow-up into a confirmed appointment in a single step.'],
            ['Follow-up history', 'Every follow-up and its outcome stays on the patient’s record.'],
        ],
        'benefits' => [
            'Better continuity of care and treatment completion.',
            'More repeat visits without extra marketing spend.',
            'No manual follow-up registers to maintain.',
            'Clear visibility of patients who have dropped off.',
        ],
        'faqs' => [
            'How are patients reminded about follow-ups?' => 'HealthO Pro sends reminders automatically before the follow-up date through the channels you use, such as SMS, WhatsApp and email.',
            'Can staff see which patients missed a follow-up?' => 'Yes. Due and overdue follow-ups are listed separately so the team can call patients back and rebook them.',
        ],
    ],

    'token-system' => [
        'name'  => 'Token System',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims', 'ris'],
        'short' => 'Digital tokens and queue display so patients know exactly when it is their turn.',
        'title' => 'Token System: calm, orderly queues at every counter',
        'desc'  => 'HealthO Pro Token System issues digital tokens, manages queues per doctor or counter and shows live status, so waiting areas stay calm and organised.',
        'intro' => 'A crowded waiting room with patients asking “how long?” is stressful for everyone. The HealthO Pro Token System issues a token at registration and runs a fair, visible queue for each doctor, counter or collection point.',
        'points' => [
            ['Token at registration', 'Every patient gets a token number as soon as they are registered or checked in.'],
            ['Queues per doctor or counter', 'Run separate queues for consultation rooms, billing counters and sample collection.'],
            ['Call next', 'Doctors and staff call the next patient from their screen.'],
            ['Priority handling', 'Move emergencies, senior citizens or booked patients ahead when needed.'],
            ['Queue status', 'Patients and staff can see the current token and how many are waiting.'],
        ],
        'benefits' => [
            'Shorter perceived waiting time.',
            'Fewer arguments and interruptions at the desk.',
            'A fair first-come, first-served order.',
            'Data on waiting times by doctor and hour.',
        ],
        'faqs' => [
            'Can the token system run separate queues?' => 'Yes. Each doctor, counter or collection point can have its own queue, and staff call the next token from their own screen.',
            'Can urgent patients be moved ahead?' => 'Yes. Staff can prioritise emergencies or other priority patients while the rest of the queue stays in order.',
        ],
    ],

    'self-qr' => [
        'name'  => 'Self QR',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims', 'ris'],
        'short' => 'Patients scan a QR code to register or check in themselves from their own phone.',
        'title' => 'Self QR: let patients register themselves in seconds',
        'desc'  => 'With HealthO Pro Self QR, patients scan a code at reception to register or check in on their own phone — fewer forms and a faster front desk.',
        'intro' => 'Typing out names, ages, phone numbers and addresses is the slowest part of any registration. With Self QR, patients scan a code at the entrance or reception and fill in their own details on their phone, while the front desk simply verifies and proceeds.',
        'points' => [
            ['Scan and register', 'A QR code at reception opens a short, mobile-friendly registration form.'],
            ['Accurate details', 'Patients type their own name, phone and address, which reduces spelling mistakes.'],
            ['Instant hand-off', 'The completed form appears at the front desk, ready for billing or a token.'],
            ['Returning patients', 'Existing patients can be matched to their previous record.'],
            ['Contact-free', 'No shared clipboards or paper forms at a busy counter.'],
        ],
        'benefits' => [
            'Faster registration during peak hours.',
            'Cleaner patient data with fewer typing errors.',
            'Less workload on front-desk staff.',
            'A modern first impression for patients.',
        ],
        'faqs' => [
            'Do patients need to install an app to use Self QR?' => 'No. Scanning the QR code opens a web form on the patient’s phone, so nothing needs to be installed.',
            'What happens after the patient submits the form?' => 'The details appear at the front desk, where staff verify them and continue with billing, a token or an appointment.',
        ],
    ],

    'patient-app' => [
        'name'  => 'Patient App',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims', 'ris'],
        'short' => 'A branded app for bookings, reports, bills and reminders in the patient’s pocket.',
        'title' => 'Patient App: your hospital, lab or clinic in every patient’s pocket',
        'desc'  => 'The HealthO Pro Patient App lets patients book appointments, download reports, view bills and get reminders — keeping them connected to your facility.',
        'intro' => 'Patients expect to book, pay and see their reports from their phone. The HealthO Pro Patient App gives them one place to do all of it, connected directly to your HealthO Pro system, so every booking and report stays in sync.',
        'points' => [
            ['Book appointments', 'Patients see available slots and book or reschedule themselves.'],
            ['Reports on the phone', 'Lab and radiology reports are available to view and download as soon as they are released.'],
            ['Bills and payments', 'Patients see their invoices and payment history.'],
            ['Reminders and alerts', 'Appointment, follow-up and report-ready notifications reach the patient directly.'],
            ['Family profiles', 'One login can manage bookings and reports for family members.'],
        ],
        'benefits' => [
            'Fewer calls asking “is my report ready?”.',
            'More self-service bookings.',
            'Stronger patient loyalty to your brand.',
            'A direct channel for offers and health packages.',
        ],
        'faqs' => [
            'What can patients do in the HealthO Pro Patient App?' => 'Patients can book appointments, view and download reports, see bills and payment history, and receive reminders and alerts.',
            'Is the Patient App connected to our HealthO Pro system?' => 'Yes. Bookings, reports and bills in the app come from the same HealthO Pro system your staff use, so they are always up to date.',
        ],
    ],

    'patient-subscriptions' => [
        'name'  => 'Patient Subscriptions',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims'],
        'short' => 'Sell health plans and memberships with included visits, tests and renewals.',
        'title' => 'Patient Subscriptions: recurring health plans that build loyalty',
        'desc'  => 'Create health plans and memberships in HealthO Pro Patient Subscriptions — included visits, tests and discounts with automatic tracking and renewals.',
        'intro' => 'Health plans and memberships turn one-time visitors into long-term patients and give your facility predictable revenue. HealthO Pro Patient Subscriptions lets you design plans, sell them at the counter and track exactly what each member has used.',
        'points' => [
            ['Design your plans', 'Define what a plan includes — consultations, tests, discounts — and its validity.'],
            ['Enrol at the counter', 'Sell a subscription during billing and link it to the patient’s record.'],
            ['Automatic entitlement', 'Included services and discounts apply automatically when the member is billed.'],
            ['Usage tracking', 'See what each member has used and what remains.'],
            ['Renewal reminders', 'Members are reminded before their plan expires.'],
        ],
        'benefits' => [
            'Predictable, recurring revenue.',
            'Higher patient retention.',
            'No manual tracking of plan usage.',
            'Family and corporate plans are easy to offer.',
        ],
        'faqs' => [
            'How are subscription benefits applied?' => 'When a member is billed, the services and discounts included in their plan are applied automatically and recorded against the plan.',
            'Are members reminded to renew?' => 'Yes. Members can be reminded before their subscription expires so they can renew in time.',
        ],
    ],

    'privilege-card' => [
        'name'  => 'Privilege Card',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims', 'ris'],
        'short' => 'Issue discount and loyalty cards that apply benefits automatically at billing.',
        'title' => 'Privilege Card: loyalty discounts without the paperwork',
        'desc'  => 'Issue privilege and loyalty cards with HealthO Pro. Card benefits apply automatically at billing, with validity and usage tracked for every holder.',
        'intro' => 'Privilege cards reward loyal patients, families and partner organisations, but paper cards and manual discounts are easy to misuse. HealthO Pro Privilege Card ties every card to the patient record and applies the right benefit at billing automatically.',
        'points' => [
            ['Card types', 'Create card types with their own discounts, services and validity.'],
            ['Issue and link', 'Issue a card number and link it to a patient or family.'],
            ['Automatic discount', 'The benefit is applied at billing — no manual discount approval.'],
            ['Validity control', 'Expired or blocked cards stop giving benefits.'],
            ['Card reports', 'See how many cards are active and how much they are used.'],
        ],
        'benefits' => [
            'Loyal patients come back more often.',
            'Discounts are consistent and controlled.',
            'No misuse of lost or expired cards.',
            'A simple tie-up offer for companies and groups.',
        ],
        'faqs' => [
            'How does a privilege card discount get applied?' => 'The card is linked to the patient’s record, and its benefit is applied automatically when they are billed.',
            'Can privilege cards expire?' => 'Yes. Each card type has a validity period, and expired or blocked cards no longer apply benefits.',
        ],
    ],

    'patients-review' => [
        'name'  => 'Patients Review',
        'cat'   => 'patient',
        'for'   => ['hims', 'cims', 'lims', 'ris'],
        'short' => 'Collect patient feedback after every visit and grow your online reviews.',
        'title' => 'Patients Review: hear from every patient after every visit',
        'desc'  => 'HealthO Pro Patients Review requests feedback after each visit, flags unhappy patients early and encourages happy ones to leave public reviews.',
        'intro' => 'Most patients never tell you how their visit went — they just don’t return, or they post a review somewhere. HealthO Pro Patients Review asks for feedback after each visit, so you hear the good and the bad while you can still act on it.',
        'points' => [
            ['Automatic feedback requests', 'A short rating request is sent after a visit, report or discharge.'],
            ['Ratings by doctor and department', 'See satisfaction scores for each doctor, department and branch.'],
            ['Early alerts', 'Low ratings are flagged so a manager can call the patient back.'],
            ['Public review prompts', 'Happy patients can be invited to review you online.'],
            ['Comments in one place', 'Every comment is stored with the visit it relates to.'],
        ],
        'benefits' => [
            'Service problems are caught early.',
            'More positive online reviews.',
            'Objective data for staff appraisals.',
            'Patients feel heard.',
        ],
        'faqs' => [
            'When is a patient asked for a review?' => 'Feedback requests are sent automatically after a visit, report delivery or discharge, depending on how you set it up.',
            'What happens with negative feedback?' => 'Low ratings are flagged so the team can contact the patient quickly and resolve the issue.',
        ],
    ],
];
