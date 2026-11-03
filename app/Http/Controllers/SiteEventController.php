<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiteEventsRequest;
use App\Models\SiteVisit;
use Illuminate\Http\Response;

class SiteEventController extends Controller
{
    /**
     * Record a batch of anonymous events, opening the visit on its first batch. Bots are silently ignored.
     *
     * City/region come from Cloudflare's visitor location headers when the site runs behind Cloudflare; the IP is never stored.
     */
    public function store(StoreSiteEventsRequest $request): Response
    {
        $userAgent = $request->userAgent();

        if (SiteVisit::isBot($userAgent)) {
            return response()->noContent();
        }

        $data = $request->validated();
        $visit = SiteVisit::firstWhere('uuid', $data['visit_id']);

        if ($visit) {
            $visit->update(['last_seen_at' => now()]);
        } else {
            $visit = SiteVisit::createOrFirst(['uuid' => $data['visit_id']], [
                'visitor_id' => $data['visitor_id'],
                'landing_path' => $data['events'][0]['path'],
                'referrer_host' => $data['referrer_host'] ?? null,
                'utm_source' => $data['utm_source'] ?? null,
                'utm_medium' => $data['utm_medium'] ?? null,
                'utm_campaign' => $data['utm_campaign'] ?? null,
                'device' => SiteVisit::deviceFromUserAgent($userAgent),
                'browser' => SiteVisit::browserFromUserAgent($userAgent),
                'country' => SiteVisit::countryName($request->header('CF-IPCountry')) ?? SiteVisit::countryFromTimezone($data['timezone'] ?? null),
                'region' => collect([$request->header('CF-IPCity'), $request->header('CF-Region')])->filter()->unique()->implode(', ') ?: null,
                'timezone' => $data['timezone'] ?? null,
                'is_returning' => SiteVisit::where('visitor_id', $data['visitor_id'])->exists(),
                'started_at' => now(),
                'last_seen_at' => now(),
            ]);
        }

        $visit->events()->createMany(array_map(fn (array $event): array => [
            'type' => $event['type'],
            'path' => $event['path'],
            'section' => $event['section'] ?? null,
            'label' => $event['label'] ?? null,
            'value' => $event['value'] ?? null,
        ], $data['events']));

        return response()->noContent();
    }
}
