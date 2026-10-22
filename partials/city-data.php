<?php
/**
 * City guides — one page per product per city, served at /blog/{slug} by
 * blog-post.php (template: partials/city-post.php).
 *
 * Every page mixes three kinds of content so no two pages are the same:
 *   - the city: its healthcare scene, areas, languages and state health scheme
 *   - the product in that city: one line per product in CITIES[...]['angle']
 *   - the product itself: CITY_PRODUCTS (features, pricing, FAQs)
 *
 * Adding a city is adding an entry to CITIES: the blog, sitemap, product pages
 * and llms.txt all read from here. Keep facts general and true — no rankings,
 * no hospital counts, no claims about clients we do not have there.
 */
require_once __DIR__ . '/blog-data.php';

/** Publication date shown on city guides (ISO 8601). */
const CITY_PUBLISHED = '2026-10-22';

/** City where our own offices are (support visits possible). */
const HOME_CITY = 'hyderabad';

/**
 * Per product: URL prefix (the search term), names and shared content.
 * {city} is replaced with the city name.
 */
const CITY_PRODUCTS = [
    'hims' => [
        'prefix'   => 'hospital-management-software',
        'keyword'  => 'Hospital Management Software',
        'audience' => 'hospitals and nursing homes',
        'title'    => 'Best Hospital Management Software in {city} (HIMS)',
        'h1'       => 'Hospital Management Software in {city}',
        'desc'     => 'HealthO Pro HIMS for hospitals and nursing homes in {city}: OPD, IPD, wards, pharmacy, lab, TPA billing and discharge in one cloud system. Free demo.',
        'short'    => 'One system for reception, wards, pharmacy, lab and billing — built for hospitals and nursing homes in {city}.',
        'intro'    => 'Running a hospital in {city} means busy OPDs, full wards, insurance desks and a pharmacy that never stops. HealthO Pro HIMS puts all of it on one patient record, so every medicine, test, procedure and bed-day reaches the bill the moment it happens — and nothing is lost between departments.',
        'points'   => [
            ['OPD to discharge on one record', 'Registration, consultation, admission, wards, OT and discharge all work on the same patient file — no re-typing between desks.'],
            ['No missed charges', 'Every medicine issued, test ordered and bed-day used is added to the running bill automatically, so the bill is complete at discharge.'],
            ['Insurance, TPA and package billing', 'Cash, credit, insurance, package and TPA billing with GST-ready invoices. Claim papers come straight from the patient record.'],
            ['Pharmacy, lab and radiology built in', 'Pharmacy stock, lab orders and radiology reports connect to the same patient and the same bill.'],
            ['Wards, beds and nursing', 'Live bed status, nursing station notes and room transfers, so the front desk always knows what is free.'],
            ['Dashboards for the owner', 'Daily collections, department revenue and pending dues on one screen — from anywhere.'],
        ],
        'pricing'  => 'HIMS comes in Startup, Business and Enterprise plans for anything from a small nursing home to a multi-site hospital. Yearly billing saves up to 20%.',
        'guides'   => ['billing', 'nursing-station', 'rooms', 'surgeries', 'medical-records', 'mis-reports'],
        'faqs'     => [
            'Is it suitable for a small hospital?' => 'Yes. It works for small nursing homes under 50 beds, 500+ bed hospitals and multi-site chains. You choose a plan that fits your size.',
            'Will my staff find it hard to learn?' => 'No. We set it up for you, train your team for free, and stay a call or WhatsApp away 24×7. Most staff are comfortable within a few days.',
            'Is patient data safe?' => 'Yes. Every user only sees what their role allows, every change is recorded, and data is encrypted on a secure cloud you can reach from anywhere.',
        ],
    ],
    'lims' => [
        'prefix'   => 'lab-management-software',
        'keyword'  => 'Pathology Lab Software',
        'audience' => 'pathology labs and diagnostic centres',
        'title'    => 'Best Pathology Lab Software in {city} (LIMS)',
        'h1'       => 'Pathology Lab Management Software in {city}',
        'desc'     => 'HealthO Pro LIMS for labs and diagnostic centres in {city}: analyzer interfacing, barcodes, QC, home collection, B2B and WhatsApp reports. Free demo.',
        'short'    => 'Analyzer results flow into branded reports on WhatsApp — built for pathology labs and diagnostic centres in {city}.',
        'intro'    => 'Labs in {city} compete on two things: accurate reports and fast turnaround. HealthO Pro LIMS connects to your analyzers so results arrive in the report by themselves, checks them against your rules, and sends a branded report to the patient on WhatsApp the moment it is signed.',
        'points'   => [
            ['Analyzer interfacing', 'Biochemistry, hematology and immunoassay analyzers send results straight into the report, with two-way connection — no typing.'],
            ['Barcodes from collection', 'Barcode labels are printed at collection, so every sample is identified from the first step.'],
            ['QC and auto-validation', 'Normal ranges, validation rules and Levey-Jennings QC charts flag unusual values before a report goes out.'],
            ['Reports on WhatsApp', 'Branded reports go out on WhatsApp, email and a patient portal as soon as they are signed.'],
            ['Home collection and centres', 'Book and track home collections, collection centres and outsourced tests in the same system.'],
            ['B2B and referral doctors', 'Referral doctors, B2B clients and their accounts are tracked without a separate notebook or Excel sheet.'],
        ],
        'pricing'  => 'LIMS comes in Startup, Business and Enterprise plans for single labs, multi-branch labs and diagnostic chains. Yearly billing saves up to 20%.',
        'guides'   => ['lis-integration', 'home-sample-management', 'auto-ai-driven-qc', 'referral-lab', 'phlebotomist', 'tests-master'],
        'faqs'     => [
            'Will it work with my analyzers?' => 'Most modern analyzers can send results to a computer, and we support two-way (bi-directional) connection. Tell us your machine models and we will confirm in the demo.',
            'Does it help with quality control?' => 'Yes. Normal ranges, auto-validation rules and Levey-Jennings QC charts are built in, so unusual values are flagged before a report goes out.',
            'How long does it take to start?' => 'Usually a few days. We set up your tests, rates and report format, connect your analyzers and train your team for free.',
        ],
    ],
    'cims' => [
        'prefix'   => 'clinic-management-software',
        'keyword'  => 'Clinic Management Software',
        'audience' => 'clinics and polyclinics',
        'title'    => 'Best Clinic Management Software in {city} (CIMS)',
        'h1'       => 'Clinic Management Software in {city}',
        'desc'     => 'HealthO Pro CIMS for clinics in {city}: online booking, queue tokens, e-prescriptions, billing and WhatsApp follow-up reminders. Book a free demo.',
        'short'    => 'Online booking, tokens, e-prescriptions and WhatsApp reminders — built for clinics and polyclinics in {city}.',
        'intro'    => 'A clinic in {city} grows when patients come back. HealthO Pro CIMS runs the whole visit from one app — booking, token, consultation, digital prescription and bill — and reminds every patient on WhatsApp before their next visit, so fewer of them go elsewhere.',
        'points'   => [
            ['Online and walk-in booking', 'Doctor availability slots and a calendar view, so patients can book online and walk-ins fit around them.'],
            ['Queue tokens', 'Digital tokens keep the waiting room calm — patients know exactly when it is their turn.'],
            ['E-prescriptions in a few taps', 'Quick-pick medicines and dosage templates, with old visits, vitals and reports on the same screen.'],
            ['WhatsApp follow-up reminders', 'When the doctor sets a follow-up date, the patient is reminded automatically before it.'],
            ['Billing in one click', 'Consultation, procedure and package bills in a click, with daily collection reports.'],
            ['Polyclinics and chains', 'Works for single-doctor clinics, multi-specialty polyclinics and multi-location chains on the cloud.'],
        ],
        'pricing'  => 'CIMS comes in Startup, Business and Enterprise plans for single-doctor clinics, polyclinics and clinic chains. Yearly billing saves up to 20%.',
        'guides'   => ['appointment-booking', 'token-system', 'prescription', 'follow-ups-for-appointments', 'patient-app', 'official-whatsapp'],
        'faqs'     => [
            'Can patients book appointments online?' => 'Yes. Online and walk-in booking with doctor availability slots, a calendar view and token/queue management — so the waiting room stays calm.',
            'Is it hard for the doctor to use?' => 'No. Prescriptions use quick-pick medicines and dosage templates, so a full Rx takes a few taps. Old visits, vitals and reports are on the same screen.',
            'How quickly can we start?' => 'Most clinics go live in a few days. Setup and training are free, and help is a call or WhatsApp away 24×7.',
        ],
    ],
    'ris' => [
        'prefix'   => 'ultrasound-reporting-software',
        'keyword'  => 'Ultrasound Reporting Software',
        'audience' => 'scan centres and radiology clinics',
        'title'    => 'Best Ultrasound Reporting Software in {city} (RIS)',
        'h1'       => 'Ultrasound Reporting & Radiology Software in {city}',
        'desc'     => 'HealthO Pro RIS for scan centres in {city}: reads measurements from your ultrasound machine, builds the report, pre-fills PNDT Form F. ₹5 per patient.',
        'short'    => 'Measurements come straight from your ultrasound machine into the report — built for scan centres in {city}. ₹5 per patient.',
        'intro'    => 'Scan centres in {city} lose time typing measurements the machine already knows. HealthO Pro RIS reads the numbers straight from your ultrasound machine — baby measurements, fluid, Doppler values and more — and puts them into your report format on your letterhead, ready for the doctor to check and sign.',
        'points'   => [
            ['No typing measurements', 'Measurements are read from the ultrasound machine over DICOM and placed into the report automatically.'],
            ['Your format, your letterhead', 'The report opens like a Word page on your letterhead. The doctor can edit anything, use ready sentences or voice typing.'],
            ['PNDT Form F pre-filled', 'For pregnancy scans, Form F is pre-filled from the scan and patient details. Fetal sex is never captured from the scan.'],
            ['Worklist from billing', 'On machines with a worklist option, billed patients appear on the machine automatically — nobody types names twice.'],
            ['Reports on WhatsApp', 'Signed reports with charts and images go to the patient on WhatsApp.'],
            ['Nothing extra to buy', 'One Windows PC at your centre is enough. Our team installs the app, connects your machine and sets up your format for free.'],
        ],
        'pricing'  => 'RIS is pay-as-you-go at ₹5 per patient — no monthly licence fee, no setup charge, and 24×7 support included. Scan fewer patients, pay less.',
        'guides'   => ['transcriptor', 'pndt-form', 'dr-authorization', 'tats', 'official-whatsapp', 'billing'],
        'faqs'     => [
            'Will it work with my ultrasound machine?' => 'If your machine can send images to a computer (DICOM — most modern machines can), it works. Machines with a worklist option also show your billed patients automatically.',
            'Can the doctor still change the report?' => 'Always. The report opens like a Word page on your letterhead. The doctor can edit anything, use ready sentences or voice typing, and then signs it.',
            'Does it help with PNDT Form F?' => 'Yes. For pregnancy scans, Form F is pre-filled from the scan and the patient details. Your staff checks and saves it. Fetal sex is never captured from the scan.',
        ],
    ],
];

/**
 * slug => [
 *   name, state, region (for grouping), langs, areas (localities),
 *   scheme (government health scheme hospitals bill against, or ''),
 *   about (the city's healthcare scene),
 *   angle (one line per product: what that kind of centre faces here),
 *   near (nearby city slugs, for links)
 * ]
 */
const CITIES = [
    'hyderabad' => [
        'name'   => 'Hyderabad', 'state' => 'Telangana', 'region' => 'South India',
        'langs'  => ['Telugu', 'Urdu', 'Hindi', 'English'],
        'areas'  => ['Banjara Hills', 'Jubilee Hills', 'Gachibowli', 'Madhapur', 'Kukatpally', 'Ameerpet', 'Secunderabad', 'Dilsukhnagar', 'LB Nagar', 'Abids', 'Somajiguda', 'Kondapur'],
        'scheme' => 'Aarogyasri',
        'about'  => 'Hyderabad is one of India’s largest healthcare hubs. Big corporate hospitals sit alongside hundreds of nursing homes, diagnostic chains and neighbourhood clinics, and patients travel in from across Telangana, Andhra Pradesh and beyond for treatment.',
        'angle'  => [
            'hims' => 'Hospitals here run busy OPDs, Aarogyasri cases and TPA patients side by side — a single missed ward charge or stuck claim adds up fast.',
            'lims' => 'With diagnostic chains on every main road, independent labs win on turnaround time and on reports that reach the patient’s phone the same day.',
            'cims' => 'Clinics in residential areas like Kukatpally, Dilsukhnagar and LB Nagar live on repeat visits and word of mouth, so follow-ups matter.',
            'ris'  => 'Scan centres handle long ultrasound queues every day, and pregnancy scans need a complete PNDT Form F each time.',
        ],
        'near'   => ['warangal', 'vijayawada', 'visakhapatnam', 'bengaluru'],
    ],
    'warangal' => [
        'name'   => 'Warangal', 'state' => 'Telangana', 'region' => 'South India',
        'langs'  => ['Telugu', 'Urdu', 'English'],
        'areas'  => ['Hanamkonda', 'Kazipet', 'Hunter Road', 'Subedari', 'Nakkalagutta', 'Warangal Fort Road', 'Naimnagar'],
        'scheme' => 'Aarogyasri',
        'about'  => 'Warangal is Telangana’s second-largest city and the referral centre for the north of the state. MGM Hospital and Kakatiya Medical College anchor a growing ring of private hospitals, labs and scan centres around Hanamkonda.',
        'angle'  => [
            'hims' => 'Many hospitals here grew from smaller nursing homes and still run billing, pharmacy and wards on paper or separate tools.',
            'lims' => 'Labs serve patients from surrounding districts who cannot come back for a printed report — WhatsApp delivery saves them a second trip.',
            'cims' => 'Specialist clinics draw patients from nearby towns, so a booked slot and a reminder keep them from making a wasted journey.',
            'ris'  => 'Scan centres see patients referred from across north Telangana, and doctors want reports out before the patient leaves.',
        ],
        'near'   => ['hyderabad', 'vijayawada'],
    ],
    'visakhapatnam' => [
        'name'   => 'Visakhapatnam', 'state' => 'Andhra Pradesh', 'region' => 'South India',
        'langs'  => ['Telugu', 'Hindi', 'English'],
        'areas'  => ['MVP Colony', 'Dwaraka Nagar', 'Seethammadhara', 'Maharanipeta', 'Gajuwaka', 'Madhurawada', 'Akkayyapalem', 'Siripuram'],
        'scheme' => 'Dr. NTR Vaidya Seva',
        'about'  => 'Visakhapatnam is Andhra Pradesh’s largest city and the medical centre for the north coastal districts. King George Hospital and Andhra Medical College sit at its heart, with private hospitals and diagnostic centres spread from Maharanipeta to Madhurawada.',
        'angle'  => [
            'hims' => 'Hospitals manage Dr. NTR Vaidya Seva patients, corporate insurance and cash patients together, and each needs a clean, complete bill.',
            'lims' => 'Labs here collect from industrial townships and suburbs far from the centre, so home collection and fast digital reports win patients.',
            'cims' => 'As the city spreads towards Madhurawada and Gajuwaka, new clinics compete for the same families — reminders bring them back.',
            'ris'  => 'Imaging centres serve patients from Srikakulam and Vizianagaram too, and need reports ready quickly.',
        ],
        'near'   => ['vijayawada', 'hyderabad', 'bhubaneswar'],
    ],
    'vijayawada' => [
        'name'   => 'Vijayawada', 'state' => 'Andhra Pradesh', 'region' => 'South India',
        'langs'  => ['Telugu', 'English'],
        'areas'  => ['Benz Circle', 'Labbipet', 'Governorpet', 'Suryaraopet', 'Patamata', 'Moghalrajpuram', 'Gunadala', 'Guntur Road'],
        'scheme' => 'Dr. NTR Vaidya Seva',
        'about'  => 'Vijayawada is central Andhra Pradesh’s commercial and medical hub. Suryaraopet and the streets around Benz Circle are lined with specialty hospitals, labs and scan centres serving the Krishna and Guntur districts and the capital region.',
        'angle'  => [
            'hims' => 'Specialty hospitals here balance Dr. NTR Vaidya Seva, TPA and cash billing, and discharge delays directly cost beds.',
            'lims' => 'Diagnostic centres compete street by street, so the lab that sends the report first usually keeps the referring doctor.',
            'cims' => 'Clinics depend on referrals and returning families, and patients expect to book a slot rather than wait for hours.',
            'ris'  => 'Scan centres in Suryaraopet and Labbipet run high daily volumes where every minute saved on typing helps.',
        ],
        'near'   => ['visakhapatnam', 'hyderabad', 'tirupati', 'warangal'],
    ],
    'tirupati' => [
        'name'   => 'Tirupati', 'state' => 'Andhra Pradesh', 'region' => 'South India',
        'langs'  => ['Telugu', 'Tamil', 'English'],
        'areas'  => ['Tiruchanoor Road', 'Balaji Colony', 'Air Bypass Road', 'Renigunta Road', 'Korlagunta', 'Bairagipatteda', 'Alipiri Road'],
        'scheme' => 'Dr. NTR Vaidya Seva',
        'about'  => 'Tirupati is the medical centre for the Rayalaseema south, with SVIMS and Ruia Hospital alongside private hospitals, labs and clinics. It also sees a constant flow of visitors from across India who need care quickly while they are in town.',
        'angle'  => [
            'hims' => 'Hospitals treat local patients and visitors from other states side by side, and need quick registration and clear bills for both.',
            'lims' => 'Patients from nearby towns and visitors want reports on their phone, not a second visit to collect paper.',
            'cims' => 'Clinics see a mix of regular families and one-time visitors, and a simple booking and token system keeps both moving.',
            'ris'  => 'Scan centres serve patients from surrounding districts who want their report the same day.',
        ],
        'near'   => ['chennai', 'vijayawada', 'bengaluru'],
    ],
    'bengaluru' => [
        'name'   => 'Bengaluru', 'state' => 'Karnataka', 'region' => 'South India',
        'langs'  => ['Kannada', 'English', 'Hindi', 'Tamil', 'Telugu'],
        'areas'  => ['Whitefield', 'Koramangala', 'Jayanagar', 'Indiranagar', 'HSR Layout', 'Electronic City', 'Hebbal', 'Malleswaram', 'Marathahalli', 'Yelahanka', 'Banashankari'],
        'scheme' => 'Ayushman Bharat–Arogya Karnataka',
        'about'  => 'Bengaluru has one of India’s most competitive private healthcare markets — large hospital groups, medical tourism, and a young, tech-savvy population that expects to book, pay and get reports on their phone.',
        'angle'  => [
            'hims' => 'Patients here compare hospitals online, and a slow discharge or a confusing bill shows up in reviews the same day.',
            'lims' => 'Corporate health check-ups and home collections are a big share of lab business, and patients expect app-style tracking.',
            'cims' => 'Clinics compete with app-based consultations, so online booking and WhatsApp reminders are the minimum patients expect.',
            'ris'  => 'Imaging centres face long queues in fast-growing areas like Whitefield and Electronic City and need reports out quickly.',
        ],
        'near'   => ['chennai', 'hyderabad', 'coimbatore', 'tirupati'],
    ],
    'chennai' => [
        'name'   => 'Chennai', 'state' => 'Tamil Nadu', 'region' => 'South India',
        'langs'  => ['Tamil', 'English'],
        'areas'  => ['Greams Road', 'Anna Nagar', 'T. Nagar', 'Adyar', 'Velachery', 'Porur', 'OMR', 'Vadapalani', 'Tambaram', 'Mylapore', 'Nungambakkam'],
        'scheme' => 'CMCHIS',
        'about'  => 'Chennai is often called India’s health capital. It draws patients from across the country and abroad, and its hospitals, labs and clinics run to high standards of documentation and service.',
        'angle'  => [
            'hims' => 'Hospitals here handle CMCHIS, insurance and international patients, and each claim needs complete records from admission to discharge.',
            'lims' => 'Labs in a city with many accredited peers need clean QC records and reports that look professional.',
            'cims' => 'From Anna Nagar to OMR, clinics compete for the same families — the ones that remind patients keep them.',
            'ris'  => 'Scan centres here are busy and well-equipped, and typing measurements by hand is the slowest step left.',
        ],
        'near'   => ['bengaluru', 'coimbatore', 'tirupati'],
    ],
    'coimbatore' => [
        'name'   => 'Coimbatore', 'state' => 'Tamil Nadu', 'region' => 'South India',
        'langs'  => ['Tamil', 'Malayalam', 'English'],
        'areas'  => ['RS Puram', 'Gandhipuram', 'Peelamedu', 'Saibaba Colony', 'Avinashi Road', 'Singanallur', 'Ramanathapuram', 'Saravanampatti'],
        'scheme' => 'CMCHIS',
        'about'  => 'Coimbatore is western Tamil Nadu’s medical centre, serving the Kongu region and many patients from neighbouring Kerala. Its hospitals along Avinashi Road and its labs and clinics across the city have grown with its industry.',
        'angle'  => [
            'hims' => 'Hospitals see patients from Tamil Nadu and Kerala, with CMCHIS, insurance and cash billing to keep straight.',
            'lims' => 'Labs serve both city patients and those travelling in from smaller towns, who want reports on WhatsApp instead of a return trip.',
            'cims' => 'Clinics in growing areas like Saravanampatti and Peelamedu need booking and reminders to keep families coming back.',
            'ris'  => 'Scan centres handle referrals from across the region and need reports done without delay.',
        ],
        'near'   => ['kochi', 'chennai', 'bengaluru'],
    ],
    'kochi' => [
        'name'   => 'Kochi', 'state' => 'Kerala', 'region' => 'South India',
        'langs'  => ['Malayalam', 'English'],
        'areas'  => ['Edappally', 'Kaloor', 'Palarivattom', 'Kakkanad', 'Vyttila', 'Aluva', 'Ernakulam South', 'Kadavanthra', 'Fort Kochi'],
        'scheme' => 'KASP',
        'about'  => 'Kochi is Kerala’s commercial capital, in a state known for high health awareness. Many families have members working in the Gulf, so reports and bills often need to reach someone abroad as well as the patient.',
        'angle'  => [
            'hims' => 'Hospitals manage KASP, insurance and self-paying patients, and families abroad often want bills and updates digitally.',
            'lims' => 'Health-aware patients take regular tests, and a report on WhatsApp is easy to forward to a doctor or family member anywhere.',
            'cims' => 'Clinics here see patients with long-term conditions who need regular follow-ups — reminders keep them on schedule.',
            'ris'  => 'Scan centres need fast, clear reports that patients can share with family and doctors right away.',
        ],
        'near'   => ['coimbatore', 'bengaluru', 'chennai'],
    ],
    'mumbai' => [
        'name'   => 'Mumbai', 'state' => 'Maharashtra', 'region' => 'West India',
        'langs'  => ['Marathi', 'Hindi', 'English', 'Gujarati'],
        'areas'  => ['Andheri', 'Bandra', 'Parel', 'Dadar', 'Borivali', 'Chembur', 'Ghatkopar', 'Mulund', 'Thane', 'Navi Mumbai', 'Malad', 'Kandivali'],
        'scheme' => 'MJPJAY',
        'about'  => 'Mumbai has one of India’s densest healthcare networks — large hospitals, thousands of nursing homes, diagnostic chains and clinics in every building. Space is tight and time is tighter, so efficiency decides who grows.',
        'angle'  => [
            'hims' => 'Nursing homes and hospitals handle MJPJAY, TPA and corporate patients, and with high costs per bed, faster discharges matter.',
            'lims' => 'Labs collect from homes and offices across a huge area, so barcodes, home-collection tracking and digital reports are essential.',
            'cims' => 'Patients here have no time to wait — clinics that offer booked slots and tokens keep them.',
            'ris'  => 'Imaging centres run long hours with heavy volumes, and every typed measurement slows the queue.',
        ],
        'near'   => ['pune', 'ahmedabad', 'nagpur'],
    ],
    'pune' => [
        'name'   => 'Pune', 'state' => 'Maharashtra', 'region' => 'West India',
        'langs'  => ['Marathi', 'Hindi', 'English'],
        'areas'  => ['Kothrud', 'Baner', 'Aundh', 'Wakad', 'Hinjewadi', 'Hadapsar', 'Viman Nagar', 'Deccan', 'Shivajinagar', 'Pimpri-Chinchwad', 'Kharadi'],
        'scheme' => 'MJPJAY',
        'about'  => 'Pune’s healthcare has grown with its IT and industrial belts. New hospitals, labs and clinics have opened across Hinjewadi, Wakad, Kharadi and Pimpri-Chinchwad to serve a young, working population.',
        'angle'  => [
            'hims' => 'Hospitals see many corporate-insured patients alongside MJPJAY cases, and need TPA billing that does not hold up discharge.',
            'lims' => 'Corporate health check-ups and home collections are a large share of lab work in the IT corridors.',
            'cims' => 'Working patients want to book online and be seen on time — clinics that run on tokens and reminders win them.',
            'ris'  => 'Scan centres in fast-growing suburbs need to keep up with rising volumes without adding typists.',
        ],
        'near'   => ['mumbai', 'nagpur', 'hyderabad'],
    ],
    'nagpur' => [
        'name'   => 'Nagpur', 'state' => 'Maharashtra', 'region' => 'Central India',
        'langs'  => ['Marathi', 'Hindi', 'English'],
        'areas'  => ['Dhantoli', 'Ramdaspeth', 'Dharampeth', 'Sitabuldi', 'Manish Nagar', 'Sadar', 'Wardha Road', 'Medical Square'],
        'scheme' => 'MJPJAY',
        'about'  => 'Nagpur sits at the centre of India and is the referral city for Vidarbha and parts of Madhya Pradesh and Chhattisgarh. AIIMS Nagpur and the hospital cluster around Dhantoli and Ramdaspeth draw patients from hundreds of kilometres away.',
        'angle'  => [
            'hims' => 'Hospitals admit patients from far-off districts and states, and need complete records and bills for MJPJAY and insurance claims.',
            'lims' => 'Labs serve patients who travel long distances — sending reports on WhatsApp saves them a second trip.',
            'cims' => 'Specialist clinics see patients from across the region, so booked slots and reminders avoid wasted journeys.',
            'ris'  => 'Scan centres near Medical Square handle referrals from a large area and need reports ready the same day.',
        ],
        'near'   => ['pune', 'indore', 'hyderabad', 'mumbai'],
    ],
    'delhi' => [
        'name'   => 'Delhi NCR', 'state' => 'Delhi', 'region' => 'North India',
        'langs'  => ['Hindi', 'English', 'Punjabi', 'Urdu'],
        'areas'  => ['South Delhi', 'Saket', 'Dwarka', 'Rohini', 'Janakpuri', 'Lajpat Nagar', 'Laxmi Nagar', 'Pitampura', 'Gurugram', 'Noida', 'Ghaziabad', 'Faridabad'],
        'scheme' => 'CGHS and Ayushman Bharat PM-JAY',
        'about'  => 'Delhi NCR is home to AIIMS, large private hospital groups, and thousands of nursing homes, labs and clinics across Delhi, Gurugram, Noida, Ghaziabad and Faridabad. Patients come from across North India.',
        'angle'  => [
            'hims' => 'Hospitals bill CGHS, PM-JAY, TPA and cash patients, each with its own rates and papers — mistakes mean rejected claims.',
            'lims' => 'With national diagnostic chains everywhere, local labs win with faster turnaround, home collection and reports on WhatsApp.',
            'cims' => 'Clinics across the NCR compete for busy families, and the ones that remind patients of follow-ups keep them.',
            'ris'  => 'Imaging centres run very high volumes, and pregnancy scans need a complete PNDT Form F every time.',
        ],
        'near'   => ['jaipur', 'lucknow', 'indore'],
    ],
    'kolkata' => [
        'name'   => 'Kolkata', 'state' => 'West Bengal', 'region' => 'East India',
        'langs'  => ['Bengali', 'Hindi', 'English'],
        'areas'  => ['Salt Lake', 'EM Bypass', 'New Town', 'Park Street', 'Ballygunge', 'Howrah', 'Dum Dum', 'Behala', 'Garia', 'Tollygunge'],
        'scheme' => 'Swasthya Sathi',
        'about'  => 'Kolkata is eastern India’s medical hub. The hospital belt along EM Bypass and in Salt Lake serves patients from West Bengal, the North-East and neighbouring countries.',
        'angle'  => [
            'hims' => 'Hospitals handle Swasthya Sathi, insurance and cash patients together, and need every charge on the bill before discharge.',
            'lims' => 'Labs collect from a dense city and its suburbs, and families want reports sent straight to their phones.',
            'cims' => 'Neighbourhood clinics and polyclinics run on loyal families — reminders and simple booking keep them close.',
            'ris'  => 'Scan centres serve patients from across eastern India who want a clear report before they head home.',
        ],
        'near'   => ['bhubaneswar', 'patna'],
    ],
    'ahmedabad' => [
        'name'   => 'Ahmedabad', 'state' => 'Gujarat', 'region' => 'West India',
        'langs'  => ['Gujarati', 'Hindi', 'English'],
        'areas'  => ['SG Highway', 'Navrangpura', 'Satellite', 'Vastrapur', 'Bopal', 'Maninagar', 'Paldi', 'Chandkheda', 'Gota', 'Naroda'],
        'scheme' => 'PMJAY-MA',
        'about'  => 'Ahmedabad is Gujarat’s largest city and a major medical centre for the state. Private hospitals along SG Highway, busy labs in Navrangpura and clinics in every society serve a fast-growing city.',
        'angle'  => [
            'hims' => 'Hospitals bill PMJAY-MA, insurance and cash patients, and owners want a clear daily view of collections and dues.',
            'lims' => 'Labs compete hard on price and speed, so automation that removes typing and errors protects margins.',
            'cims' => 'Clinics in residential societies rely on families who return for years — follow-up reminders keep that habit.',
            'ris'  => 'Scan centres here are price-conscious, so paying only per patient scanned makes sense.',
        ],
        'near'   => ['mumbai', 'jaipur', 'indore'],
    ],
    'jaipur' => [
        'name'   => 'Jaipur', 'state' => 'Rajasthan', 'region' => 'North India',
        'langs'  => ['Hindi', 'Rajasthani', 'English'],
        'areas'  => ['Malviya Nagar', 'Mansarovar', 'Vaishali Nagar', 'C-Scheme', 'Tonk Road', 'Jagatpura', 'Raja Park', 'Sodala', 'Jhotwara'],
        'scheme' => 'MAA Yojana',
        'about'  => 'Jaipur is Rajasthan’s capital and its main referral city. SMS Hospital and a growing number of private hospitals, labs and imaging centres serve patients from across the state.',
        'angle'  => [
            'hims' => 'Hospitals handle a large share of state-scheme patients, where incomplete records lead to delayed or rejected claims.',
            'lims' => 'Labs serve patients from smaller towns across Rajasthan, who want reports on WhatsApp instead of a second trip.',
            'cims' => 'Clinics in growing areas like Mansarovar and Jagatpura need simple booking and reminders to build a steady practice.',
            'ris'  => 'Scan centres see referrals from across the state and need fast, accurate reports with PNDT paperwork done right.',
        ],
        'near'   => ['delhi', 'ahmedabad', 'indore'],
    ],
    'lucknow' => [
        'name'   => 'Lucknow', 'state' => 'Uttar Pradesh', 'region' => 'North India',
        'langs'  => ['Hindi', 'Urdu', 'English'],
        'areas'  => ['Gomti Nagar', 'Aliganj', 'Indira Nagar', 'Hazratganj', 'Alambagh', 'Vikas Nagar', 'Chinhat', 'Rajajipuram', 'Jankipuram'],
        'scheme' => 'Ayushman Bharat PM-JAY',
        'about'  => 'Lucknow is the medical centre for central Uttar Pradesh, home to SGPGI and KGMU, with private hospitals, labs and clinics growing fast in Gomti Nagar and beyond.',
        'angle'  => [
            'hims' => 'Hospitals see many Ayushman Bharat patients, and every claim needs clean, complete documentation.',
            'lims' => 'Labs serve patients from surrounding districts who cannot easily return for paper reports.',
            'cims' => 'Specialist clinics draw patients from across the region, so booked slots and reminders save them wasted trips.',
            'ris'  => 'Scan centres handle growing volumes, and doctors want reports done without typing every measurement.',
        ],
        'near'   => ['delhi', 'patna', 'indore'],
    ],
    'indore' => [
        'name'   => 'Indore', 'state' => 'Madhya Pradesh', 'region' => 'Central India',
        'langs'  => ['Hindi', 'English'],
        'areas'  => ['Vijay Nagar', 'Palasia', 'AB Road', 'Bhawarkuan', 'Rajendra Nagar', 'Sudama Nagar', 'Scheme 54', 'Rau'],
        'scheme' => 'Ayushman Bharat Niramayam',
        'about'  => 'Indore is Madhya Pradesh’s largest city and commercial capital, and the medical centre for the Malwa region. Hospitals and diagnostic centres along AB Road and in Vijay Nagar draw patients from across the state.',
        'angle'  => [
            'hims' => 'Hospitals manage Niramayam, insurance and cash patients, and owners want to see every department’s revenue in one place.',
            'lims' => 'Labs serve both the city and surrounding towns, and fast digital reports keep referring doctors loyal.',
            'cims' => 'Clinics in growing colonies rely on returning families — reminders and online booking help them grow.',
            'ris'  => 'Scan centres receive referrals from across Malwa and need reports finished while the patient waits.',
        ],
        'near'   => ['nagpur', 'ahmedabad', 'jaipur'],
    ],
    'patna' => [
        'name'   => 'Patna', 'state' => 'Bihar', 'region' => 'East India',
        'langs'  => ['Hindi', 'Magahi', 'English'],
        'areas'  => ['Boring Road', 'Kankarbagh', 'Rajendra Nagar', 'Bailey Road', 'Patliputra', 'Ashok Rajpath', 'Danapur', 'Saguna More'],
        'scheme' => 'Ayushman Bharat PM-JAY',
        'about'  => 'Patna is Bihar’s medical centre, with AIIMS Patna, PMCH and IGIMS alongside many private hospitals, labs and clinics. Patients travel in from districts across the state.',
        'angle'  => [
            'hims' => 'Hospitals see many Ayushman Bharat patients from across Bihar, and complete records keep claims moving.',
            'lims' => 'Labs on Boring Road and in Kankarbagh serve patients from far away, who want reports on WhatsApp rather than a return trip.',
            'cims' => 'Specialist clinics are crowded — booked slots and tokens make long waits shorter and calmer.',
            'ris'  => 'Scan centres handle heavy referral volumes and need PNDT paperwork correct every time.',
        ],
        'near'   => ['kolkata', 'lucknow'],
    ],
    'bhubaneswar' => [
        'name'   => 'Bhubaneswar', 'state' => 'Odisha', 'region' => 'East India',
        'langs'  => ['Odia', 'Hindi', 'English'],
        'areas'  => ['Patia', 'Nayapalli', 'Saheed Nagar', 'Chandrasekharpur', 'Jaydev Vihar', 'Khandagiri', 'Old Town', 'Cuttack Road'],
        'scheme' => '',
        'about'  => 'Bhubaneswar, with neighbouring Cuttack, is Odisha’s healthcare centre. AIIMS Bhubaneswar and a fast-growing private sector of hospitals, labs and clinics serve patients from across the state.',
        'angle'  => [
            'hims' => 'New and expanding hospitals here want to start with one system for billing, wards and pharmacy rather than patch tools together later.',
            'lims' => 'Labs serve both the twin cities and districts beyond, and digital reports save patients long journeys.',
            'cims' => 'Clinics in growing areas like Patia and Chandrasekharpur need booking and reminders to build a loyal base.',
            'ris'  => 'Scan centres see rising volumes as the city grows, and need reports ready without extra staff.',
        ],
        'near'   => ['kolkata', 'visakhapatnam'],
    ],
];

/** URL slug of one city guide. */
function city_slug(string $product, string $city): string
{
    return CITY_PRODUCTS[$product]['prefix'] . '-' . $city;
}

/** Fill {city} in a template string. */
function city_fill(string $text, string $city): string
{
    return str_replace('{city}', CITIES[$city]['name'], $text);
}

/** Every city guide, slug => ['product' => key, 'city' => key], products first then cities. */
function city_pages(): array
{
    static $pages = null;
    if ($pages !== null) {
        return $pages;
    }
    $pages = [];
    foreach (array_keys(CITY_PRODUCTS) as $product) {
        foreach (array_keys(CITIES) as $city) {
            $pages[city_slug($product, $city)] = ['product' => $product, 'city' => $city];
        }
    }
    return $pages;
}

/** Page title for one guide (also used as link text). */
function city_title(string $product, string $city): string
{
    return city_fill(CITY_PRODUCTS[$product]['title'], $city);
}

/**
 * Compact "in your city" links for a product page or the blog index.
 * Pass a product key to list that product's cities.
 */
function city_links_section(string $product): void
{
    $p = CITY_PRODUCTS[$product];
    ?>
<section class="section">
  <div class="container">
    <h2 class="h-sec center"><?= h($p['keyword']) ?> <span class="text-grad">in your city</span></h2>
    <p class="lead center" style="margin:12px auto 24px;max-width:640px;">HealthO Pro <?= h(PRODUCTS[$product]['short']) ?> serves <?= h($p['audience']) ?> across India. Find the guide for your city.</p>
    <div class="bl-for city-links">
<?php foreach (CITIES as $key => $c): ?>
      <a href="<?= h(blog_url(city_slug($product, $key))) ?>"><?= h($c['name']) ?></a>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php
}
