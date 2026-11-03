<?php

namespace App\Models;

use Database\Factories\SiteVisitFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Locale;
use Throwable;

/**
 * One anonymous browsing session on the marketing site (a new visit starts after 30 idle minutes).
 */
#[Fillable(['uuid', 'visitor_id', 'landing_path', 'referrer_host', 'utm_source', 'utm_medium', 'utm_campaign', 'device', 'browser', 'country', 'region', 'timezone', 'is_returning', 'started_at', 'last_seen_at'])]
class SiteVisit extends Model
{
    /** @use HasFactory<SiteVisitFactory> */
    use HasFactory, MassPrunable;

    /**
     * Analytics older than this are deleted by `model:prune`.
     */
    public const RETENTION_MONTHS = 13;

    /**
     * Referrer hosts that belong to the same network, matched by substring.
     *
     * @var array<string, string>
     */
    public const KNOWN_SOURCES = [
        'google.' => 'Google',
        'facebook.com' => 'Facebook',
        'fb.com' => 'Facebook',
        'messenger.com' => 'Messenger',
        'instagram.com' => 'Instagram',
        'tiktok.com' => 'TikTok',
        'youtube.com' => 'YouTube',
        'linkedin.com' => 'LinkedIn',
        'bing.com' => 'Bing',
        't.co' => 'X (Twitter)',
    ];

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_returning' => 'boolean',
            'started_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<SiteEvent, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(SiteEvent::class);
    }

    /**
     * The demo booking sent during this visit, if any.
     *
     * @return HasOne<DemoBooking, $this>
     */
    public function booking(): HasOne
    {
        return $this->hasOne(DemoBooking::class);
    }

    /**
     * Visits whose source() matches the given name (sources are derived from utm_source and referrer_host).
     *
     * @param  Builder<static>  $query
     */
    public function scopeFromSource(Builder $query, string $source): void
    {
        $this->whereMatchingCombinations($query, ['utm_source', 'referrer_host'], fn (self $visit): bool => $visit->source() === $source);
    }

    /**
     * Visits whose locationName() matches the given place.
     *
     * @param  Builder<static>  $query
     */
    public function scopeFromLocation(Builder $query, string $location): void
    {
        $this->whereMatchingCombinations($query, ['region', 'country', 'timezone'], fn (self $visit): bool => $visit->locationName() === $location);
    }

    /**
     * Every source name seen so far, for filter dropdowns.
     *
     * @return array<string, string>
     */
    public static function sourceOptions(): array
    {
        return static::query()->select('utm_source', 'referrer_host')->distinct()->get()
            ->map(fn (self $visit): string => $visit->source())->unique()->sort()->mapWithKeys(fn (string $source): array => [$source => $source])->all();
    }

    /**
     * Every location name seen so far, for filter dropdowns.
     *
     * @return array<string, string>
     */
    public static function locationOptions(): array
    {
        return static::query()->select('region', 'country', 'timezone')->distinct()->get()
            ->map(fn (self $visit): string => $visit->locationName())->unique()->sort()->mapWithKeys(fn (string $location): array => [$location => $location])->all();
    }

    /**
     * Filter on a value computed in PHP: find the distinct column combinations that produce it, then match those exactly.
     *
     * @param  Builder<static>  $query
     * @param  list<string>  $columns
     * @param  callable(self): bool  $matches
     */
    private function whereMatchingCombinations(Builder $query, array $columns, callable $matches): void
    {
        $combinations = static::query()->select($columns)->distinct()->get()->filter($matches);

        $query->where(function (Builder $query) use ($combinations, $columns): void {
            if ($combinations->isEmpty()) {
                $query->whereRaw('1 = 0');

                return;
            }

            foreach ($combinations as $combination) {
                $query->orWhere(function (Builder $query) use ($combination, $columns): void {
                    foreach ($columns as $column) {
                        $combination->{$column} === null ? $query->whereNull($column) : $query->where($column, $combination->{$column});
                    }
                });
            }
        });
    }

    /**
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::where('last_seen_at', '<', now()->subMonths(self::RETENTION_MONTHS));
    }

    public static function isBot(?string $userAgent): bool
    {
        return $userAgent === null || preg_match('/bot|crawl|spider|slurp|headless|lighthouse|preview|curl|wget/i', $userAgent) === 1;
    }

    public static function deviceFromUserAgent(string $userAgent): string
    {
        return match (true) {
            (bool) preg_match('/ipad|tablet|kindle|playbook|silk|(android(?!.*mobile))/i', $userAgent) => 'tablet',
            (bool) preg_match('/mobi|iphone|ipod|android|blackberry|opera mini|iemobile/i', $userAgent) => 'mobile',
            default => 'desktop',
        };
    }

    public static function browserFromUserAgent(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'FBAN') || str_contains($userAgent, 'FBAV') => 'Facebook app',
            str_contains($userAgent, 'Edg/') => 'Edge',
            str_contains($userAgent, 'OPR/') || str_contains($userAgent, 'Opera') => 'Opera',
            str_contains($userAgent, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($userAgent, 'Firefox') || str_contains($userAgent, 'FxiOS') => 'Firefox',
            str_contains($userAgent, 'Chrome') || str_contains($userAgent, 'CriOS') => 'Chrome',
            str_contains($userAgent, 'Safari') => 'Safari',
            default => 'Other',
        };
    }

    /**
     * Where the visit came from: the campaign tag (utm_source) when present, otherwise the referring website.
     */
    public static function sourceName(?string $utmSource, ?string $referrerHost): string
    {
        if ($utmSource) {
            return str($utmSource)->headline()->toString();
        }

        if (! $referrerHost) {
            return 'Direct / unknown';
        }

        foreach (self::KNOWN_SOURCES as $needle => $name) {
            if (str_contains($referrerHost, $needle)) {
                return $name;
            }
        }

        return str($referrerHost)->after('www.')->toString();
    }

    public function source(): string
    {
        return self::sourceName($this->utm_source, $this->referrer_host);
    }

    /**
     * Best available place name: Cloudflare city/region, else country, else the time zone's city.
     */
    public function locationName(): string
    {
        return $this->region
            ?? $this->country
            ?? ($this->timezone ? str($this->timezone)->afterLast('/')->replace('_', ' ')->toString() : 'Unknown');
    }

    /**
     * Turn an ISO country code (e.g. Cloudflare's CF-IPCountry header) into a name. Ignores placeholders like "XX" or "T1".
     */
    public static function countryName(?string $countryCode): ?string
    {
        if ($countryCode === null || ! preg_match('/^[A-Z]{2}$/', $countryCode) || in_array($countryCode, ['XX', 'T1'], true)) {
            return null;
        }

        $name = Locale::getDisplayRegion('-'.$countryCode, 'en');

        return $name !== '' && $name !== $countryCode ? $name : $countryCode;
    }

    /**
     * Derive the country from an IANA time zone, e.g. "Asia/Manila" is the Philippines and "Asia/Dubai" the UAE.
     */
    public static function countryFromTimezone(?string $timezone): ?string
    {
        if (! $timezone) {
            return null;
        }

        try {
            return self::countryName((new \DateTimeZone($timezone))->getLocation()['country_code'] ?? null);
        } catch (Throwable) {
            return null;
        }
    }
}
