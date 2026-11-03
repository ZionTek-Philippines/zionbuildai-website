@php
    $sections = [
        ['id' => 'agreement', 'title' => 'Agreement to these terms', 'blocks' => [
            'These Terms of Service ("Terms") apply to this website and the ZionBuild AI platform, provided by <strong>ZionTek</strong> ("ZionTek", "we", "us"). By using the website or the platform, you agree to these Terms. If you use ZionBuild on behalf of a company, you confirm that you are allowed to accept these Terms for that company.',
            'If your company has a signed agreement or accepted quotation with us, that agreement controls where it differs from these Terms.',
        ]],
        ['id' => 'service', 'title' => 'The service', 'blocks' => [
            'ZionBuild AI helps contractors, construction teams and homeowners manage projects in one place, including progress photos, attendance, payroll, budgets and expenses, materials, schedules and a client portal. Features may be added, changed or removed over time. Features marked "Coming soon" or "Beta" may be incomplete or change before release.',
        ]],
        ['id' => 'live-demo', 'title' => 'Live demo and sample data', 'blocks' => [
            'The "Try it!" live demo on this website uses <strong>fictional sample data</strong>. Names, figures, photos and projects in the demo are for illustration only and are not real people, companies or results.',
        ]],
        ['id' => 'accounts', 'title' => 'Accounts and access', 'blocks' => [
            ['list' => [
                'Keep your login details confidential and use a strong password',
                'You are responsible for activity under your account and your company\'s user accounts',
                'Give team members and homeowners only the access they need, and remove access for people who leave',
                'Tell us right away at <a href="mailto:hello@ziontek.co">hello@ziontek.co</a> if you suspect unauthorized access',
            ]],
        ]],
        ['id' => 'pricing', 'title' => 'Pricing and payment', 'blocks' => [
            'ZionBuild pricing is tailored to each company after a project assessment and demo. Fees, billing periods, setup and support are set out in your quotation or agreement. Unless stated otherwise there, fees are in Philippine pesos and exclusive of applicable taxes.',
        ]],
        ['id' => 'your-data', 'title' => 'Your data', 'blocks' => [
            '<strong>Your company owns the project data you put into ZionBuild.</strong> You give us permission to store and process it only to provide, secure and support the service for you.',
            'You are responsible for making sure you may lawfully upload the data you add, including personal information about workers, clients and other people, and for giving them any notices the Data Privacy Act requires. How we handle personal information is explained in our <a href="'.route('privacy-policy').'">Privacy Policy</a>.',
            'When your subscription ends, you may ask for an export of your data within the period set in your agreement, after which we may delete it.',
        ]],
        ['id' => 'ai-features', 'title' => 'AI features', 'blocks' => [
            'ZionBuild\'s AI features, such as AI Photo Analysis, give <strong>suggestions only</strong>. A person must review and accept a suggestion before it changes official project progress; AI never changes progress on its own.',
            'AI output can be wrong or incomplete. It is not engineering, legal, financial or professional advice, and you remain responsible for decisions you make based on it.',
        ]],
        ['id' => 'acceptable-use', 'title' => 'Acceptable use', 'blocks' => [
            'You agree not to:',
            ['list' => [
                'Use the website or platform for anything unlawful, fraudulent or harmful',
                'Upload malware, or content that infringes other people\'s rights',
                'Try to access accounts, data or systems you are not authorized to access',
                'Interfere with, overload, scan or reverse engineer the service',
                'Resell or provide the platform to third parties without our written consent',
            ]],
            'We may suspend access that breaks these rules or puts the service or other users at risk.',
        ]],
        ['id' => 'intellectual-property', 'title' => 'Intellectual property', 'blocks' => [
            'The ZionBuild AI platform, website, software, design, logos and content are owned by ZionTek or its licensors and protected by law. These Terms do not transfer any of these rights to you, other than the right to use the service as agreed.',
            'If you send us feedback or suggestions, we may use them to improve our services without any obligation to you.',
        ]],
        ['id' => 'disclaimers', 'title' => 'Disclaimers', 'blocks' => [
            'We work hard to keep ZionBuild available, accurate and secure, but the service is provided "as is" and "as available". To the extent the law allows, we do not guarantee that it will be uninterrupted or error-free. Website content is general information and may change without notice.',
        ]],
        ['id' => 'liability', 'title' => 'Limitation of liability', 'blocks' => [
            'To the extent the law allows, ZionTek is not liable for indirect, incidental or consequential losses such as lost profits, lost data or business interruption. Our total liability for any claim relating to the service is limited to the fees your company paid us for the service in the three (3) months before the claim. Nothing in these Terms limits liability that cannot be limited under Philippine law.',
        ]],
        ['id' => 'termination', 'title' => 'Ending the service', 'blocks' => [
            'You may stop using the service at any time, subject to the terms of your agreement. We may suspend or end access if you seriously breach these Terms or your agreement, or if required by law.',
        ]],
        ['id' => 'governing-law', 'title' => 'Governing law', 'blocks' => [
            'These Terms are governed by the laws of the Republic of the Philippines. Any dispute will be brought before the proper courts of the Philippines, after both sides first try in good faith to settle it informally.',
        ]],
        ['id' => 'changes', 'title' => 'Changes to these terms', 'blocks' => [
            'We may update these Terms from time to time and will post the new version on this page with a new "Last updated" date. Continuing to use the service after changes take effect means you accept the updated Terms.',
        ]],
    ];
@endphp

<x-landing.legal-page
    title="Terms of Service"
    description="The terms for using the ZionBuild AI website, live demo and construction management platform by ZionTek."
    icon="gavel"
    intro="The ground rules for using our website, live demo and the ZionBuild AI platform, written as plainly as we can."
    :sections="$sections"
/>
