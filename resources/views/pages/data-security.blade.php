@php
    $sections = [
        ['id' => 'approach', 'title' => 'Our approach', 'blocks' => [
            'Contractors trust ZionBuild with payroll, budgets, client details and site photos. We protect that information with a simple rule: <strong>collect only what is needed, give people only the access they need, and keep a person in control of important decisions.</strong>',
        ]],
        ['id' => 'access-control', 'title' => 'Role-based access', 'blocks' => [
            ['list' => [
                'Every user signs in with their own account. Shared logins are not needed',
                'Access is controlled by roles and permissions, so team members see and do only what their role allows',
                'Homeowners using the Client Portal see only the project information their contractor has authorized, such as progress updates, photos, accomplishment reports and milestones',
                'Account owners can change roles or remove access at any time, for example when someone leaves the company',
            ]],
        ]],
        ['id' => 'accounts', 'title' => 'Account protection', 'blocks' => [
            ['list' => [
                'Passwords are stored only as secure one-way hashes, never in plain text, so not even our team can read them',
                'Login and form endpoints are rate-limited to slow down password guessing and automated abuse',
                'Forms are protected against cross-site request forgery, and website forms also filter out spam bots',
            ]],
        ]],
        ['id' => 'in-transit', 'title' => 'Data in transit', 'blocks' => [
            'Connections to the ZionBuild platform and this website are encrypted with HTTPS (TLS), so information sent between your device and our servers cannot be read or changed along the way.',
        ]],
        ['id' => 'ai', 'title' => 'Responsible AI', 'blocks' => [
            'AI features such as AI Photo Analysis only make <strong>suggestions</strong>. A person must accept a suggestion before official progress changes; AI never updates your project on its own. AI is used to provide features to you, not to sell or share your project data.',
        ]],
        ['id' => 'website-privacy', 'title' => 'Privacy-first website analytics', 'blocks' => [
            'This website uses our own analytics instead of third-party ad trackers:',
            ['list' => [
                'No IP addresses or full browser details are stored',
                'No advertising or cross-site tracking cookies',
                'Form analytics records which field was clicked, never what was typed',
                'Visitors with "Do Not Track" or "Global Privacy Control" turned on are not tracked',
                'Analytics data is deleted automatically after 13 months',
            ]],
        ]],
        ['id' => 'team', 'title' => 'Our team', 'blocks' => [
            ['list' => [
                'Only team members who need it to provide support or keep the service running can access customer systems',
                'Our team is bound by confidentiality obligations',
                'We access a customer\'s data only to provide the service, fix a problem, or when the customer asks us to',
            ]],
        ]],
        ['id' => 'incidents', 'title' => 'If something goes wrong', 'blocks' => [
            'If we discover a security incident affecting personal data, we act quickly to contain it, investigate, and fix the cause. Where a breach is likely to put people at risk, we notify affected customers and the National Privacy Commission as required by the Data Privacy Act and NPC rules.',
        ]],
        ['id' => 'your-part', 'title' => 'How you can help', 'blocks' => [
            ['list' => [
                'Use a strong, unique password and don\'t share your login',
                'Give each person their own account with the right role',
                'Remove access promptly for workers, staff or clients who no longer need it',
                'Keep your phone and computer updated and locked when not in use',
                'Be careful with links or messages asking for your ZionBuild password. We will never ask for it',
            ]],
        ]],
        ['id' => 'report', 'title' => 'Report a security concern', 'blocks' => [
            'If you believe you have found a security issue in ZionBuild or this website, please email <a href="mailto:hello@ziontek.co">hello@ziontek.co</a> with the subject "Security report" and as much detail as you can. Please give us reasonable time to fix it before sharing it publicly, and do not access or change other people\'s data while testing. We appreciate responsible reports.',
        ]],
    ];
@endphp

<x-landing.legal-page
    title="Data Security"
    description="How ZionBuild AI protects your construction project data: role-based access, secure passwords, encrypted connections, responsible AI and privacy-first analytics."
    icon="encrypted"
    intro="Your project data, payroll and client details are protected by design. Here's how we keep them safe."
    :sections="$sections"
/>
