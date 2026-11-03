@php
    $sections = [
        ['id' => 'who-we-are', 'title' => 'Who we are', 'blocks' => [
            'ZionBuild AI is a construction management platform built and operated by <strong>ZionTek</strong> ("ZionTek", "we", "us"), with office at Phase 8, Cainta Greenland Executive Village, Taytay, Rizal, Philippines.',
            'This policy explains what personal information we collect through this website and the ZionBuild AI platform, why we collect it, and the choices you have. We handle personal information in line with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> and the rules of the National Privacy Commission (NPC).',
        ]],
        ['id' => 'what-we-collect', 'title' => 'Information we collect', 'blocks' => [
            '<strong>Information you give us.</strong> When you book a demo, unlock the full live demo, or contact us, we collect what you enter:',
            ['list' => [
                'Your name',
                'Your contact number and/or email address',
                'Your company name, role, or a short description of your company or projects',
            ]],
            '<strong>Information collected automatically on this website.</strong> We run our own privacy-friendly analytics to understand which parts of the site are useful. It records:',
            ['list' => [
                'Pages viewed, buttons clicked, how far you scroll, and how long homepage sections are on screen',
                'Which form fields you click into (the field name only, <strong>never what you type</strong>)',
                'Where the visit came from (for example Google, Facebook, or a campaign link)',
                'Device type (mobile, tablet or desktop), browser name, and an approximate location (country from your browser\'s time zone, or city and region from our network provider)',
            ]],
            'We <strong>do not store IP addresses</strong> or full browser details, and we do not use advertising or third-party tracking cookies. Analytics uses a random ID kept in your browser so repeat visits can be counted. If your browser sends a "Do Not Track" or "Global Privacy Control" signal, analytics is switched off for you.',
            '<strong>Information in the ZionBuild AI platform.</strong> When your company uses ZionBuild, you and your team add project information such as worker names and attendance, payroll, expenses, progress photos, documents and client details. We process this data only to provide the service to your company, under our agreement with you.',
        ]],
        ['id' => 'how-we-use', 'title' => 'How we use your information', 'blocks' => [
            ['list' => [
                'To contact you, arrange and run your demo, and answer your questions',
                'To prepare a project assessment or quotation you asked for',
                'To provide, support and improve the ZionBuild AI platform',
                'To understand how visitors use this website so we can make it clearer and more useful',
                'To keep our website and platform secure and prevent spam and abuse',
                'To meet our legal obligations',
            ]],
            'We process your information based on your consent (for example when you submit a form), to take steps you asked for before entering into an agreement, to perform our agreement with your company, and for our legitimate interest in improving and securing our services.',
        ]],
        ['id' => 'sharing', 'title' => 'Sharing your information', 'blocks' => [
            'We <strong>do not sell or rent</strong> your personal information. We share it only:',
            ['list' => [
                'With service providers that help us run our services, such as hosting, email delivery and network providers, who may use it only for that purpose and must keep it confidential',
                'When required by law, a court order, or a lawful request from government authorities',
                'To protect the rights, safety and property of ZionTek, our customers, or others',
                'As part of a merger, acquisition or sale of assets, with notice to you where required',
            ]],
        ]],
        ['id' => 'cookies', 'title' => 'Cookies and browser storage', 'blocks' => [
            'This website uses a small number of essential cookies to keep forms secure (protection against cross-site request forgery) and to remember your session, for example when you unlock the full live demo. These are needed for the site to work.',
            'Our analytics does not use cookies. It keeps a random visitor ID and current visit ID in your browser\'s local storage. You can clear these at any time through your browser settings.',
        ]],
        ['id' => 'retention', 'title' => 'How long we keep information', 'blocks' => [
            ['list' => [
                '<strong>Demo requests and inquiries:</strong> for as long as needed to follow up with you and manage our relationship, and deleted on request',
                '<strong>Website analytics:</strong> automatically deleted 13 months after the visit',
                '<strong>Platform data:</strong> for as long as your company\'s account is active and as set out in our agreement with your company, unless the law requires us to keep it longer',
            ]],
        ]],
        ['id' => 'your-rights', 'title' => 'Your rights', 'blocks' => [
            'Under the Data Privacy Act, you have the right to:',
            ['list' => [
                'Be informed about how your personal information is processed',
                'Access the personal information we hold about you',
                'Object to processing, including for marketing',
                'Have inaccurate information corrected',
                'Have your information erased or blocked when it is no longer needed or was unlawfully processed',
                'Receive a copy of your information in a commonly used electronic format (data portability)',
                'Claim damages, and file a complaint with the <a href="https://privacy.gov.ph" target="_blank" rel="noopener">National Privacy Commission</a>',
            ]],
            'To use any of these rights, email <a href="mailto:hello@ziontek.co">hello@ziontek.co</a> with the subject "Privacy request". We may need to confirm your identity before acting on a request.',
        ]],
        ['id' => 'security', 'title' => 'How we protect information', 'blocks' => [
            'We use organizational, physical and technical measures to protect personal information against loss, misuse and unauthorized access. See our <a href="'.route('data-security').'">Data Security</a> page for details. If a personal data breach occurs that is likely to put you at risk, we will notify you and the National Privacy Commission as the law requires.',
        ]],
        ['id' => 'children', 'title' => 'Children', 'blocks' => [
            'Our website and platform are meant for businesses and adults. We do not knowingly collect personal information from children. If you believe a child has given us their information, please contact us and we will delete it.',
        ]],
        ['id' => 'changes', 'title' => 'Changes to this policy', 'blocks' => [
            'We may update this policy from time to time. We will post the new version on this page and change the "Last updated" date. If the changes are significant, we will let customers know by email or in the platform.',
        ]],
        ['id' => 'contact', 'title' => 'Contact us', 'blocks' => [
            'For privacy questions or requests, contact our Data Protection Officer:',
            ['list' => [
                'Email: <a href="mailto:hello@ziontek.co">hello@ziontek.co</a> (subject: "Privacy request")',
                'Phone: <a href="tel:+639617858547">+63 961 785 8547</a>',
                'Address: Phase 8, Cainta Greenland Executive Village, Taytay, Rizal, Philippines',
            ]],
        ]],
    ];
@endphp

<x-landing.legal-page
    title="Privacy Policy"
    description="How ZionTek collects, uses and protects personal information on the ZionBuild AI website and platform, in line with the Philippine Data Privacy Act."
    icon="shield_person"
    intro="Your privacy is our #1 priority. Here's exactly what we collect, why, and how you stay in control."
    :sections="$sections"
/>
