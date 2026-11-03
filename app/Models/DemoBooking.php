<?php

namespace App\Models;

use Database\Factories\DemoBookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A visitor who sent the "Book a Free Demo" form on the homepage.
 */
#[Fillable(['name', 'contact_number', 'email', 'company_info', 'visitor_id', 'site_visit_id', 'status', 'notes'])]
class DemoBooking extends Model
{
    /** @use HasFactory<DemoBookingFactory> */
    use HasFactory;

    /**
     * Follow-up stages tracked in the admin panel, keyed by stored value.
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'scheduled' => 'Demo scheduled',
        'closed' => 'Closed',
    ];

    /**
     * Badge colors for each status in the admin panel.
     *
     * @var array<string, string>
     */
    public const STATUS_COLORS = [
        'new' => 'warning',
        'contacted' => 'info',
        'scheduled' => 'success',
        'closed' => 'gray',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'new',
    ];

    /**
     * The analytics visit the booking was sent from (null when tracking was blocked or opted out).
     *
     * @return BelongsTo<SiteVisit, $this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(SiteVisit::class, 'site_visit_id');
    }

    /**
     * Every visit this visitor's browser made up to the moment they booked.
     *
     * @return Builder<SiteVisit>
     */
    public function visitsBeforeBooking(): Builder
    {
        return SiteVisit::query()
            ->where('visitor_id', $this->visitor_id)
            ->where('started_at', '<=', $this->created_at);
    }

    /**
     * The visitor's events up to the booking, optionally of one type.
     *
     * @return Builder<SiteEvent>
     */
    public function eventsBeforeBooking(?string $type = null): Builder
    {
        return SiteEvent::query()
            ->whereIn('site_visit_id', $this->visitsBeforeBooking()->select('id'))
            ->where('created_at', '<=', $this->created_at)
            ->when($type, fn (Builder $query) => $query->where('type', $type));
    }
}
