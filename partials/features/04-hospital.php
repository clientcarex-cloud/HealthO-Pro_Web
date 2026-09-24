<?php
/** Hospital & Inpatient — see partials/blog-data.php for the format. */
return [
    'nursing-station' => [
        'name'  => 'Nursing Station',
        'cat'   => 'hospital',
        'for'   => ['hims'],
        'short' => 'Ward patients, medication rounds, vitals and nursing notes on one screen.',
        'title' => 'Nursing Station: every ward patient, every task, one screen',
        'desc'  => 'HealthO Pro Nursing Station gives nurses a live view of ward patients, medication schedules, vitals, orders and nursing notes.',
        'intro' => 'Nurses juggle medication rounds, vitals, doctor orders and handovers for many patients at once. HealthO Pro Nursing Station puts all of it on one screen per ward, so nothing depends on memory or scraps of paper.',
        'points' => [
            ['Ward overview', 'All admitted patients in the ward with bed, doctor and status.'],
            ['Medication schedule', 'What is due, what has been given and what was missed.'],
            ['Vitals charting', 'Record vitals and see trends over the stay.'],
            ['Doctor orders', 'New orders for tests, medicines and procedures appear immediately.'],
            ['Nursing notes and handover', 'Notes carry over to the next shift.'],
        ],
        'benefits' => [
            'Safer medication administration.',
            'Complete, legible nursing records.',
            'Smoother shift handovers.',
            'Less running between desk and ward.',
        ],
        'faqs' => [
            'What does the Nursing Station show?' => 'Each ward’s admitted patients with their medication schedule, vitals, doctor orders and nursing notes.',
            'Are doctor orders visible to nurses immediately?' => 'Yes. Orders entered by doctors appear at the nursing station as soon as they are placed.',
        ],
    ],

    'surgeries' => [
        'name'  => 'Surgeries',
        'cat'   => 'hospital',
        'for'   => ['hims'],
        'short' => 'Schedule OTs, surgical teams and procedure records with package billing.',
        'title' => 'Surgeries: organised OT schedules and complete surgical records',
        'desc'  => 'Schedule operation theatres and surgical teams, record procedures and bill surgery packages with HealthO Pro Surgeries.',
        'intro' => 'Operation theatres are among a hospital’s most valuable resources. HealthO Pro Surgeries schedules theatres and teams without clashes and records every procedure from booking to billing.',
        'points' => [
            ['OT scheduling', 'Book theatres by date and time and avoid double-booking.'],
            ['Surgical team', 'Assign surgeon, anaesthetist and assisting staff.'],
            ['Procedure records', 'Record the procedure, notes and consumables used.'],
            ['Surgery packages', 'Bill fixed-price packages or itemised charges.'],
            ['Surgeon payouts', 'Calculate professional fees for the team.'],
        ],
        'benefits' => [
            'Better OT utilisation.',
            'Fewer last-minute clashes.',
            'Complete surgical documentation.',
            'Accurate surgery billing.',
        ],
        'faqs' => [
            'Can HealthO Pro schedule operation theatres?' => 'Yes. Theatres are booked by date and time with the surgical team, and clashes are prevented.',
            'Does it support surgery packages?' => 'Yes. Surgeries can be billed as packages or itemised.',
        ],
    ],

    'rooms' => [
        'name'  => 'Rooms',
        'cat'   => 'hospital',
        'for'   => ['hims'],
        'short' => 'Real-time bed and room status with transfers and room-wise tariffs.',
        'title' => 'Rooms: real-time bed status and smooth admissions',
        'desc'  => 'HealthO Pro Rooms shows real-time bed and room availability, handles admissions and transfers, and applies room-wise tariffs automatically.',
        'intro' => '“Is there a bed available?” should have an instant answer. HealthO Pro Rooms shows live availability of every bed and room, so admissions, transfers and room charges are handled without phone calls to the wards.',
        'points' => [
            ['Live bed board', 'Occupied, vacant, reserved and under-cleaning beds at a glance.'],
            ['Room categories', 'General ward, semi-private, private, ICU and more.'],
            ['Admission and transfer', 'Allot a bed at admission and transfer patients with history kept.'],
            ['Room tariffs', 'Room charges are applied to the bill automatically.'],
            ['Occupancy reports', 'Occupancy and average length of stay by category.'],
        ],
        'benefits' => [
            'Faster admissions.',
            'Higher bed occupancy.',
            'No missed room charges.',
            'Better capacity planning.',
        ],
        'faqs' => [
            'Does HealthO Pro show live bed availability?' => 'Yes. The bed board shows each bed’s status in real time.',
            'Are room charges added automatically?' => 'Yes. Room tariffs are applied to the patient’s bill based on the room and duration of stay.',
        ],
    ],

    'blood-management' => [
        'name'  => 'Blood Management',
        'cat'   => 'hospital',
        'for'   => ['hims', 'lims'],
        'short' => 'Track donors, blood units, stock, cross-matching and issues.',
        'title' => 'Blood Management: donors, units and issues fully traceable',
        'desc'  => 'HealthO Pro Blood Management tracks donors, blood units and components, stock and expiry, cross-matching and issue to patients.',
        'intro' => 'In blood management, traceability is everything — from donor to unit to patient. HealthO Pro Blood Management keeps each step recorded and each unit’s status clear.',
        'points' => [
            ['Donor records', 'Register donors with their details and donation history.'],
            ['Unit and component tracking', 'Each unit and component with its group and expiry.'],
            ['Stock view', 'Available stock by blood group and component.'],
            ['Cross-match and issue', 'Record cross-matching and issue units to patients.'],
            ['Expiry alerts', 'Units nearing expiry are highlighted.'],
        ],
        'benefits' => [
            'Full donor-to-patient traceability.',
            'Less wastage from expired units.',
            'Faster response in emergencies.',
            'Records ready for audits.',
        ],
        'faqs' => [
            'Can we see blood stock by group?' => 'Yes. Available stock is shown by blood group and component, with expiry dates.',
            'Is the issue of blood units recorded?' => 'Yes. Cross-matching and issue to each patient are recorded against the unit.',
        ],
    ],

    'canteen-management' => [
        'name'  => 'Canteen Management',
        'cat'   => 'hospital',
        'for'   => ['hims'],
        'short' => 'Run the hospital canteen — menus, orders, patient diets and billing.',
        'title' => 'Canteen Management: patient diets, staff meals and canteen billing',
        'desc'  => 'Manage your hospital canteen in HealthO Pro — menus, patient diet orders, staff meals and canteen billing linked to the patient bill.',
        'intro' => 'The hospital canteen serves patients on special diets, staff and visitors — often tracked on paper. HealthO Pro Canteen Management brings menus, orders and billing into the same system as the rest of the hospital.',
        'points' => [
            ['Menu management', 'Maintain items and prices.'],
            ['Patient diet orders', 'Record diet orders for admitted patients.'],
            ['Staff and visitor sales', 'Bill staff meals and visitor purchases.'],
            ['Charge to the patient bill', 'Add patient meal charges to the IPD bill.'],
            ['Canteen reports', 'Daily sales and consumption.'],
        ],
        'benefits' => [
            'Accurate canteen revenue.',
            'Correct diets for patients.',
            'No separate canteen software.',
            'Better control of stock and wastage.',
        ],
        'faqs' => [
            'Can canteen charges go on the patient’s bill?' => 'Yes. Meals ordered for admitted patients can be added to their IPD bill.',
            'Can staff meals be tracked?' => 'Yes. Staff and visitor sales are recorded and reported separately.',
        ],
    ],

    'parking-management' => [
        'name'  => 'Parking Management',
        'cat'   => 'hospital',
        'for'   => ['hims'],
        'short' => 'Record vehicle entry and exit, parking fees and passes.',
        'title' => 'Parking Management: organised parking and accurate collections',
        'desc'  => 'Record vehicle entry and exit, parking fees and passes in HealthO Pro Parking Management, with collection reports for the facility.',
        'intro' => 'Busy hospitals handle hundreds of vehicles a day, and unrecorded parking collections are a common leak. HealthO Pro Parking Management records every vehicle and every fee.',
        'points' => [
            ['Entry and exit', 'Record vehicle number and time in and out.'],
            ['Fee calculation', 'Charges are calculated by duration and vehicle type.'],
            ['Passes', 'Issue passes for staff, doctors or long-stay patients.'],
            ['Receipts', 'Give a receipt for every payment.'],
            ['Collection reports', 'Daily vehicles and collections.'],
        ],
        'benefits' => [
            'No unrecorded parking cash.',
            'Faster exits.',
            'Easy staff pass control.',
            'Clear daily figures.',
        ],
        'faqs' => [
            'How are parking charges calculated?' => 'Charges are based on the vehicle type and the time between entry and exit, using the rates you set.',
            'Can staff get parking passes?' => 'Yes. Passes can be issued for staff, doctors and long-stay patients.',
        ],
    ],
];
