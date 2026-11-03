<?php

namespace App\Mail;

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Widgets\Analytics\AnalyticsOverview;
use App\Filament\Widgets\Analytics\BookingsBySource;
use App\Filament\Widgets\Analytics\ConversionFunnel;
use App\Filament\Widgets\Analytics\FaqQuestions;
use App\Filament\Widgets\Analytics\RankedListWidget;
use App\Filament\Widgets\Analytics\SectionEngagement;
use App\Filament\Widgets\Analytics\TopClicks;
use App\Filament\Widgets\Analytics\TrafficSources;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * The last 7 days of site analytics, built from the same widgets as the admin panel's Site Analytics page.
 */
class WeeklyAnalyticsReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Report cards in the email, keyed by heading.
     *
     * @var array<string, class-string<RankedListWidget>>
     */
    private const CARDS = [
        'Booking funnel' => ConversionFunnel::class,
        'Bookings by source' => BookingsBySource::class,
        'Where visitors come from' => TrafficSources::class,
        'Most read homepage sections' => SectionEngagement::class,
        'Most clicked buttons' => TopClicks::class,
        'FAQ questions opened' => FaqQuestions::class,
    ];

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ZionBuild AI website: your weekly analytics',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $overview = $this->widget(AnalyticsOverview::class);
        $metrics = $overview->metrics();

        return new Content(
            markdown: 'mail.weekly-analytics-report',
            with: [
                'metrics' => $metrics,
                'visitorsChange' => $overview->changeDescription($metrics['visitors'], $metrics['previous_visitors']),
                'conversionRate' => $metrics['visits'] > 0 ? round($metrics['bookings'] / $metrics['visits'] * 100, 1).'%' : '0%',
                'cards' => collect(self::CARDS)
                    ->map(fn (string $widget): array => array_slice($this->widget($widget)->getRows(), 0, 5))
                    ->filter()
                    ->all(),
                'dashboardUrl' => SiteAnalytics::getUrl(),
            ],
        );
    }

    /**
     * @template TWidget of object
     *
     * @param  class-string<TWidget>  $widget
     * @return TWidget
     */
    private function widget(string $widget): object
    {
        $instance = app($widget);
        $instance->pageFilters = ['period' => '7'];

        return $instance;
    }
}
