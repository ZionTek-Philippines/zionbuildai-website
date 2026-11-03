<?php

namespace Database\Seeders;

use App\Models\DemoBooking;
use App\Models\DemoLead;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Simulates ~90 days of believable visitor behaviour so every Site Analytics card, the booking journey
 * and the Demo Bookings / Demo Leads lists have data. Uses a fixed seed, so every run produces the same story.
 */
class SiteAnalyticsSeeder extends Seeder
{
    private const DAYS = 90;

    private const TIMEZONE = 'Asia/Manila';

    /**
     * Homepage sections top to bottom: [name, chance a reader who gets this far stays to read it, min seconds, max seconds].
     *
     * @var list<array{0: string, 1: float, 2: int, 3: int}>
     */
    private const SECTIONS = [
        ['Hero', 1.0, 6, 25],
        ['Pain Points', 0.85, 8, 30],
        ['Features', 0.9, 30, 140],
        ['All-in-One Suite', 0.8, 15, 60],
        ['How It Works', 0.75, 12, 45],
        ['Practical AI', 0.75, 15, 70],
        ['Real Results', 0.7, 10, 40],
        ['FAQ', 0.8, 15, 90],
        ['Book a Demo', 0.9, 10, 50],
    ];

    /**
     * @var array<string, int>
     */
    private const FAQ_WEIGHTS = [
        'How much does ZionBuild cost?' => 30,
        'Does ZionBuild handle payroll, vale and cash advances?' => 18,
        'Is ZionBuild for small to medium contractors?' => 14,
        'Can homeowners monitor their projects?' => 12,
        'Can I manage multiple construction projects?' => 9,
        'Can I see daily progress photos?' => 7,
        'Can I track budgets and expenses?' => 5,
        'What is ZionBuild AI?' => 3,
        'Can I request a live demo?' => 2,
    ];

    private const DEMO_MODULES = ['/demo/how-it-works', '/demo', '/demo/field-reports', '/demo/ai-project-monitoring', '/demo/finances', '/demo/payroll', '/demo/client-billing'];

    /**
     * Relative traffic by hour of day (Philippine time): early site check-ins, lunch, and evenings at home.
     *
     * @var list<int>
     */
    private const HOUR_WEIGHTS = [1, 1, 0, 0, 0, 1, 3, 6, 7, 5, 4, 4, 7, 6, 4, 4, 4, 5, 7, 9, 10, 8, 5, 2];

    /**
     * @var list<string>
     */
    private const PH_REGIONS = [
        'Quezon City, Metro Manila', 'Makati, Metro Manila', 'Pasig, Metro Manila', 'Taguig, Metro Manila',
        'Cebu City, Central Visayas', 'Davao City, Davao Region', 'Dasmariñas, Calabarzon', 'Calamba, Calabarzon',
        'San Fernando, Central Luzon', 'Malolos, Central Luzon', 'Iloilo City, Western Visayas', 'Cagayan de Oro, Northern Mindanao',
    ];

    /**
     * OFWs often build or renovate a family home back home.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const ABROAD = [
        'Asia/Dubai' => ['United Arab Emirates', 'Dubai, Dubai'],
        'Asia/Riyadh' => ['Saudi Arabia', 'Riyadh, Riyadh Region'],
        'Asia/Qatar' => ['Qatar', 'Doha, Baladiyat ad Dawhah'],
        'Asia/Singapore' => ['Singapore', 'Singapore'],
        'America/Los_Angeles' => ['United States', 'Los Angeles, California'],
    ];

    /**
     * @var list<string>
     */
    private const COMPANIES = [
        'Santos Builders', 'RJ Dela Cruz Construction', 'Bautista Engineering Services', 'Golden Arch Builders', 'Mendoza & Sons Construction',
        'Northpoint Contractors', 'Villanueva Design + Build', 'Cruz Structural Works', 'Lakeside Homes Builder', 'Reyes General Contractor',
        'TriStar Construction Corp.', 'Aquino Renovations', 'Bayanihan Builders', 'Del Rosario Construction', 'Pacific Coast Builders',
        'Gonzales Engineering', 'Silverline Construction', 'Mabuhay Homes', 'Ramos Builders & Supply', 'Iron Oak Contractors',
    ];

    /**
     * @var list<string>
     */
    private const COMPANY_DESCRIPTIONS = [
        'General contractor, %d ongoing residential projects in %s',
        'We build 2-storey houses and small commercial spaces around %2$s. Around %1$d active sites right now',
        'Engineering firm handling %d government and private projects in %s, payroll is our biggest headache',
        'Family construction business, %d projects in %s. Still using Excel and Viber for reports',
        'Renovation and fit-out contractor, about %d jobs at a time around %s',
        'Homeowner building our family house in %2$s while working abroad, %1$d contractor(s) to monitor',
    ];

    /**
     * @var list<array<string, mixed>>
     */
    private array $events = [];

    /**
     * @var list<string>
     */
    private array $knownVisitors = [];

    public function run(): void
    {
        fake()->seed(20261102);

        $now = CarbonImmutable::now(self::TIMEZONE);

        for ($daysAgo = self::DAYS - 1; $daysAgo >= 0; $daysAgo--) {
            $day = $now->subDays($daysAgo)->startOfDay();

            // Traffic grows over the quarter, dips on Sundays and gets a boost while the ad campaign runs.
            $visits = 4 + (int) round((self::DAYS - $daysAgo) / self::DAYS * 9)
                + ($daysAgo < 21 ? 3 : 0)
                - ($day->isSunday() ? 3 : 0)
                + fake()->numberBetween(-2, 3);

            for ($i = 0; $i < max(1, $visits); $i++) {
                $startedAt = $day->setTime($this->weightedPick(self::HOUR_WEIGHTS), fake()->numberBetween(0, 59), fake()->numberBetween(0, 59));

                if ($startedAt->isAfter($now)) {
                    continue;
                }

                $this->simulateVisit($startedAt, $daysAgo);
            }
        }

        foreach (array_chunk($this->events, 1000) as $chunk) {
            SiteEvent::insert($chunk);
        }

        $this->seedUntrackedBookings($now);
    }

    private function simulateVisit(CarbonImmutable $startedAt, int $daysAgo): void
    {
        $isReturning = $this->knownVisitors !== [] && fake()->boolean(22);
        $visitorId = $isReturning ? fake()->randomElement($this->knownVisitors) : (string) Str::uuid();
        $this->knownVisitors[] = $visitorId;

        [$device, $browser] = $this->deviceAndBrowser();
        [$country, $region, $timezone] = $this->location();
        [$referrerHost, $utmSource, $utmMedium, $utmCampaign] = $this->trafficSource($daysAgo);
        $landsOnDemo = fake()->boolean(8);

        $visit = SiteVisit::create([
            'uuid' => (string) Str::uuid(),
            'visitor_id' => $visitorId,
            'landing_path' => $landsOnDemo ? '/demo/how-it-works' : '/',
            'referrer_host' => $referrerHost,
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'device' => $device,
            'browser' => $browser,
            'country' => $country,
            'region' => $region,
            'timezone' => $timezone,
            'is_returning' => $isReturning,
            'started_at' => $startedAt->utc(),
            'last_seen_at' => $startedAt->utc(),
        ]);

        $clock = $startedAt;
        $opensDemo = $landsOnDemo;

        if (! $landsOnDemo) {
            $clock = $this->browseHomepage($visit, $clock, $isReturning, $opensDemo);
        }

        if ($opensDemo) {
            $clock = $this->exploreDemo($visit, $clock);
        }

        $visit->update(['last_seen_at' => $clock->utc()]);
    }

    /**
     * Scroll down the homepage section by section, clicking, opening FAQs and maybe booking along the way.
     */
    private function browseHomepage(SiteVisit $visit, CarbonImmutable $clock, bool $isReturning, bool &$opensDemo): CarbonImmutable
    {
        $this->addEvent($visit, $clock, 'page_view', '/', label: 'ZionBuild AI — Construction Management for Philippine Builders');

        // How far down the page this visitor gets: a third bounce after the hero, a fifth read almost everything.
        $engagement = $this->weightedPick(['bounce' => 34, 'skim' => 44, 'deep' => 22]);
        $lastSection = match ($engagement) {
            'bounce' => fake()->numberBetween(0, 1),
            'skim' => fake()->numberBetween(2, 6),
            'deep' => fake()->numberBetween(7, 8),
        };

        if (fake()->boolean(7)) {
            $this->addEvent($visit, $clock->addSeconds(4), 'click', '/', 'Header', fake()->randomElement(['Features', 'How It Works', 'FAQ']));
        }

        $reachedMilestones = [];

        foreach (array_slice(self::SECTIONS, 0, $lastSection + 1) as $index => [$section, $readChance, $minSeconds, $maxSeconds]) {
            if (! fake()->boolean((int) ($readChance * 100))) {
                $clock = $clock->addSeconds(2);

                continue;
            }

            $seconds = (int) round(fake()->numberBetween($minSeconds, $maxSeconds) * ($engagement === 'deep' ? 1.4 : 1));
            $clock = $clock->addSeconds($seconds);
            $this->addEvent($visit, $clock, 'section_time', '/', $section, $section, $seconds);

            $scrolled = ($index + 1) / count(self::SECTIONS) * 100;
            foreach (array_diff([25, 50, 75, 100], $reachedMilestones) as $milestone) {
                if ($scrolled >= $milestone) {
                    $reachedMilestones[] = $milestone;
                    $this->addEvent($visit, $clock, 'scroll', '/', value: $milestone);
                }
            }

            match ($section) {
                'Hero' => $this->heroClicks($visit, $clock, $opensDemo),
                'Features' => $this->featureTabClicks($visit, $clock),
                'FAQ' => $this->openFaqs($visit, $clock),
                'Book a Demo' => $this->bookingForm($visit, $clock, $isReturning),
                default => null,
            };
        }

        return $clock;
    }

    private function heroClicks(SiteVisit $visit, CarbonImmutable $clock, bool &$opensDemo): void
    {
        if (fake()->boolean(26)) {
            $this->addEvent($visit, $clock, 'click', '/', 'Hero', 'Try it! Live Demo');
            $opensDemo = true;
        }

        if (fake()->boolean(9)) {
            $this->addEvent($visit, $clock->addSecond(), 'click', '/', fake()->randomElement(['Hero', 'Header']), 'Book a Free Demo');
        }

        if (fake()->boolean(8)) {
            $this->addEvent($visit, $clock->addSeconds(2), 'click', '/', 'Hero', 'Explore Features');
        }
    }

    private function featureTabClicks(SiteVisit $visit, CarbonImmutable $clock): void
    {
        $tabs = ['Budget, Expenses & Payroll' => 30, 'Attendance & Site Progress Update' => 22, 'Project Monitoring & Photo Analysis' => 18, 'Homeowner App' => 12, 'Command Center' => 8, 'Milestones & S-Curve' => 5, 'Inventory & Equipment' => 3, 'BOQ & Procurement' => 2];
        $clicks = $this->weightedPick([0 => 45, 1 => 30, 2 => 15, 3 => 10]);

        for ($tab = 1; $tab <= $clicks; $tab++) {
            $this->addEvent($visit, $clock->subSeconds($tab * 9), 'click', '/', 'Features', $this->weightedPick($tabs));
        }
    }

    private function openFaqs(SiteVisit $visit, CarbonImmutable $clock): void
    {
        $questions = array_keys(self::FAQ_WEIGHTS);
        $openedCount = $this->weightedPick([0 => 25, 1 => 35, 2 => 25, 3 => 15]);

        for ($opened = 1; $opened <= $openedCount; $opened++) {
            $this->addEvent($visit, $clock->addSeconds($opened * 6), 'click', '/', 'FAQ', $questions[$this->weightedPick(array_values(self::FAQ_WEIGHTS))]);
        }
    }

    /**
     * Some readers start the form; each field is a chance to give up. Finishing creates a linked booking.
     */
    private function bookingForm(SiteVisit $visit, CarbonImmutable $clock, bool $isReturning): void
    {
        if (! fake()->boolean($isReturning ? 50 : 32)) {
            return;
        }

        $this->addEvent($visit, $clock, 'form_start', '/', label: 'Book a Demo');
        $fields = ['Name' => 92, 'Contact number' => 80, 'Email' => 85, 'Company info' => 72];

        foreach ($fields as $field => $continueChance) {
            $clock = $clock->addSeconds(fake()->numberBetween(4, 20));
            $this->addEvent($visit, $clock, 'form_field', '/', 'Book a Demo', $field);

            if (! fake()->boolean($continueChance + ($isReturning ? 8 : 0))) {
                return;
            }
        }

        $clock = $clock->addSeconds(5);
        $this->addEvent($visit, $clock, 'form_submit', '/', label: 'Book a Demo');
        $this->createBooking($clock, $visit);
    }

    /**
     * Walk through the live demo modules in tour order; deeper modules may get unlocked with a lead form.
     */
    private function exploreDemo(SiteVisit $visit, CarbonImmutable $clock): CarbonImmutable
    {
        foreach (self::DEMO_MODULES as $index => $path) {
            if ($index > 0 && ! fake()->boolean(66)) {
                break;
            }

            $clock = $clock->addSeconds(fake()->numberBetween(20, 120));
            $this->addEvent($visit, $clock, 'page_view', $path, label: 'ZionBuild AI live demo');

            if (fake()->boolean(45)) {
                $this->addEvent($visit, $clock->addSeconds(8), 'click', $path, label: fake()->randomElement(['Next', 'Next', 'Skip tour', 'Book a Free Demo']));
            }

            if ($path === '/demo/finances') {
                if (! fake()->boolean(35)) {
                    break;
                }

                DemoLead::factory()->create([
                    'unlocked_from' => 'demo.finances',
                    'created_at' => $clock->utc(),
                    'updated_at' => $clock->utc(),
                ]);
            }
        }

        return $clock;
    }

    private function createBooking(CarbonImmutable $bookedAt, ?SiteVisit $visit): void
    {
        $company = fake()->randomElement(self::COMPANIES);
        $place = Str::before($visit?->region ?? fake()->randomElement(self::PH_REGIONS), ',');
        $ageInDays = (int) $bookedAt->diffInDays(CarbonImmutable::now(self::TIMEZONE));
        $status = match (true) {
            $ageInDays < 3 => 'new',
            $ageInDays < 10 => $this->weightedPick(['new' => 30, 'contacted' => 50, 'scheduled' => 20]),
            default => $this->weightedPick(['contacted' => 25, 'scheduled' => 35, 'closed' => 40]),
        };
        $hasEmail = fake()->boolean(60);

        DemoBooking::factory()->create([
            'name' => fake()->randomElement(['Juan', 'Maria', 'Jose', 'Ana', 'Mark', 'Kristine', 'Paolo', 'Liza', 'Rommel', 'Joy', 'Arnel', 'Grace']).' '
                .fake()->randomElement(['Santos', 'Reyes', 'Cruz', 'Bautista', 'Dela Cruz', 'Garcia', 'Mendoza', 'Villanueva', 'Ramos', 'Aquino', 'Gonzales', 'Torres']),
            'contact_number' => ! $hasEmail || fake()->boolean(70) ? '09'.fake()->numerify('#########') : null,
            'email' => $hasEmail ? Str::slug($company, '').'@'.fake()->randomElement(['gmail.com', 'yahoo.com', 'outlook.com']) : null,
            'company_info' => $company.': '.sprintf(fake()->randomElement(self::COMPANY_DESCRIPTIONS), fake()->numberBetween(1, 8), $place),
            'visitor_id' => $visit?->visitor_id,
            'site_visit_id' => $visit?->id,
            'status' => $status,
            'notes' => match ($status) {
                'contacted' => 'Called, sending pricing and sample reports.',
                'scheduled' => 'Demo set via Google Meet. Wants to see payroll and the homeowner app.',
                'closed' => fake()->randomElement(['Signed up for onboarding.', 'Not a fit yet, follow up next quarter.']),
                default => null,
            },
            'created_at' => $bookedAt->utc(),
            'updated_at' => $bookedAt->utc(),
        ]);
    }

    /**
     * A few bookings from visitors who block tracking, so "Not tracked" shows up like it will in real life.
     */
    private function seedUntrackedBookings(CarbonImmutable $now): void
    {
        foreach ([2, 9, 23, 41, 66] as $daysAgo) {
            $this->createBooking($now->subDays($daysAgo)->setTime(20, fake()->numberBetween(0, 59)), null);
        }
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function deviceAndBrowser(): array
    {
        $device = $this->weightedPick(['mobile' => 68, 'desktop' => 27, 'tablet' => 5]);

        $browser = $device === 'desktop'
            ? $this->weightedPick(['Chrome' => 65, 'Edge' => 15, 'Safari' => 14, 'Firefox' => 6])
            : $this->weightedPick(['Chrome' => 48, 'Facebook app' => 26, 'Safari' => 20, 'Samsung Internet' => 6]);

        return [$device, $browser];
    }

    /**
     * @return array{0: string, 1: string|null, 2: string}
     */
    private function location(): array
    {
        if (fake()->boolean(11)) {
            $timezone = fake()->randomElement(array_keys(self::ABROAD));

            return [self::ABROAD[$timezone][0], self::ABROAD[$timezone][1], $timezone];
        }

        return ['Philippines', fake()->boolean(70) ? fake()->randomElement(self::PH_REGIONS) : null, 'Asia/Manila'];
    }

    /**
     * @return array{0: string|null, 1: string|null, 2: string|null, 3: string|null}
     */
    private function trafficSource(int $daysAgo): array
    {
        $source = $this->weightedPick([
            'facebook' => 34, 'google' => 24, 'direct' => 18, 'messenger' => 6, 'tiktok' => 4, 'youtube' => 3,
            'campaign' => $daysAgo < 21 ? 22 : 0,
        ]);

        return match ($source) {
            'facebook' => [fake()->randomElement(['m.facebook.com', 'l.facebook.com', 'lm.facebook.com']), null, null, null],
            'google' => ['www.google.com', null, null, null],
            'messenger' => ['www.messenger.com', null, null, null],
            'tiktok' => ['www.tiktok.com', null, null, null],
            'youtube' => ['www.youtube.com', null, null, null],
            'campaign' => ['m.facebook.com', 'facebook_ads', 'paid_social', 'contractors-q4'],
            default => [null, null, null, null],
        };
    }

    private function addEvent(SiteVisit $visit, CarbonImmutable $at, string $type, string $path, ?string $section = null, ?string $label = null, ?int $value = null): null
    {
        $this->events[] = [
            'site_visit_id' => $visit->id,
            'type' => $type,
            'path' => $path,
            'section' => $section,
            'label' => $label,
            'value' => $value,
            'created_at' => $at->utc(),
        ];

        return null;
    }

    /**
     * Pick a key from [key => weight].
     *
     * @template TKey of array-key
     *
     * @param  array<TKey, int>  $weights
     * @return TKey
     */
    private function weightedPick(array $weights): int|string
    {
        $roll = fake()->numberBetween(1, max(1, array_sum($weights)));

        foreach ($weights as $key => $weight) {
            $roll -= $weight;

            if ($roll <= 0) {
                return $key;
            }
        }

        return array_key_first($weights);
    }
}
