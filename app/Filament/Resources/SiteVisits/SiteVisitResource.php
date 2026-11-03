<?php

namespace App\Filament\Resources\SiteVisits;

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Resources\SiteVisits\Pages\ManageSiteVisits;
use App\Models\SiteEvent;
use App\Models\SiteVisit;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Every anonymous visit behind the Site Analytics cards. Each card row links here with matching filters.
 */
class SiteVisitResource extends Resource
{
    protected static ?string $model = SiteVisit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Visitor Log';

    protected static ?string $modelLabel = 'visit';

    protected static ?int $navigationSort = 5;

    /**
     * Event types a visitor can be filtered by, as shown in the "Did something" filter.
     *
     * @var array<string, string>
     */
    private const EVENT_TYPES = [
        'page_view' => 'Viewed a page',
        'click' => 'Clicked a button or link',
        'section_time' => 'Read a homepage section',
        'scroll' => 'Scrolled down a page',
        'form_start' => 'Started a form',
        'form_field' => 'Clicked into a form field',
        'form_submit' => 'Submitted a form',
    ];

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Visit')
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('started_at')
                            ->label('Started')
                            ->dateTime('M d, Y g:i A', SiteAnalytics::TIMEZONE),
                        TextEntry::make('source')
                            ->label('Came from')
                            ->state(fn (SiteVisit $record): string => $record->source().($record->utm_campaign ? " · {$record->utm_campaign}" : '')),
                        TextEntry::make('duration')
                            ->state(fn (SiteVisit $record): string => self::duration($record)),
                        TextEntry::make('device')
                            ->label('Device')
                            ->state(fn (SiteVisit $record): string => ucfirst($record->device).' · '.$record->browser),
                        TextEntry::make('location')
                            ->state(fn (SiteVisit $record): string => $record->locationName()),
                        TextEntry::make('visitor')
                            ->label('Visitor')
                            ->state(fn (SiteVisit $record): string => $record->is_returning ? 'Returning visitor' : 'First visit'),
                        TextEntry::make('booking.name')
                            ->label('Booked a demo')
                            ->placeholder('No booking during this visit')
                            ->color('success')
                            ->columnSpanFull(),
                    ]),
                Section::make('Timeline')
                    ->description('Everything this visitor did, in order (Philippine time).')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('timeline')
                            ->hiddenLabel()
                            ->state(fn (SiteVisit $record): array => $record->events()
                                ->orderBy('created_at')
                                ->orderBy('id')
                                ->get()
                                ->map(fn (SiteEvent $event): string => $event->created_at->timezone(SiteAnalytics::TIMEZONE)->format('g:i:s A').'  ·  '.$event->description())
                                ->all())
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with('booking')
                ->withCount(['events as page_views_count' => fn (Builder $events) => $events->where('type', 'page_view')]))
            ->defaultSort('started_at', 'desc')
            ->columns([
                TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime('M d, Y g:i A', SiteAnalytics::TIMEZONE)
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Came from')
                    ->state(fn (SiteVisit $record): string => $record->source())
                    ->description(fn (SiteVisit $record): ?string => $record->utm_campaign),
                TextColumn::make('landing_path')
                    ->label('Landed on')
                    ->formatStateUsing(fn (?string $state): string => $state ? SiteEvent::pageName($state) : '—')
                    ->toggleable(),
                TextColumn::make('page_views_count')
                    ->label('Pages')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration')
                    ->state(fn (SiteVisit $record): string => self::duration($record)),
                TextColumn::make('device')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->description(fn (SiteVisit $record): string => $record->browser)
                    ->toggleable(),
                TextColumn::make('location')
                    ->state(fn (SiteVisit $record): string => $record->locationName())
                    ->toggleable(),
                IconColumn::make('is_returning')
                    ->label('Returning')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('booking.name')
                    ->label('Booked')
                    ->badge()
                    ->color('success')
                    ->placeholder('—'),
            ])
            ->filters([
                SiteAnalytics::periodFilter('started_at'),
                SelectFilter::make('source')
                    ->label('Came from')
                    ->options(fn (): array => SiteVisit::sourceOptions())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null) ? $query->fromSource($data['value']) : $query),
                SelectFilter::make('location')
                    ->options(fn (): array => SiteVisit::locationOptions())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null) ? $query->fromLocation($data['value']) : $query),
                Filter::make('event')
                    ->label('Did something')
                    ->schema([
                        Select::make('type')
                            ->label('Did something')
                            ->options(self::EVENT_TYPES),
                        TextInput::make('label')
                            ->label('Button, question, field or form'),
                        TextInput::make('section'),
                        TextInput::make('path')
                            ->label('Page path'),
                        TextInput::make('min_value')
                            ->label('At least (seconds or scroll %)')
                            ->numeric(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['type'] ?? null)
                        ? $query->whereHas('events', fn (Builder $events) => $events
                            ->where('type', $data['type'])
                            ->when(filled($data['label'] ?? null), fn (Builder $events) => $events->where('label', $data['label']))
                            ->when(filled($data['section'] ?? null), fn (Builder $events) => $events->where('section', $data['section']))
                            ->when(filled($data['path'] ?? null), fn (Builder $events) => $events->where('path', $data['path']))
                            ->when(filled($data['min_value'] ?? null), fn (Builder $events) => $events->where('value', '>=', (int) $data['min_value'])))
                        : $query)
                    ->indicateUsing(fn (array $data): ?string => filled($data['type'] ?? null)
                        ? collect([self::EVENT_TYPES[$data['type']] ?? $data['type'], $data['label'] ?? null, filled($data['section'] ?? null) ? "in {$data['section']}" : null, $data['path'] ?? null, filled($data['min_value'] ?? null) ? "≥ {$data['min_value']}" : null])->filter()->implode(' · ')
                        : null),
                Filter::make('stopped_at')
                    ->label('Gave up on the booking form at')
                    ->schema([
                        TextInput::make('field')
                            ->label('Gave up on the booking form at field'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['field'] ?? null)
                        ? $query
                            ->whereHas('events', fn (Builder $events) => $events->where('type', 'form_start')->where('label', 'Book a Demo'))
                            ->whereDoesntHave('booking')
                            ->where(
                                SiteEvent::select('label')
                                    ->whereColumn('site_visit_id', 'site_visits.id')
                                    ->where('type', 'form_field')
                                    ->where('section', 'Book a Demo')
                                    ->orderByDesc('id')
                                    ->limit(1),
                                $data['field'],
                            )
                        : $query)
                    ->indicateUsing(fn (array $data): ?string => filled($data['field'] ?? null) ? "Gave up at: {$data['field']}" : null),
                SelectFilter::make('demo_modules')
                    ->label('Live demo')
                    ->options(['1' => 'Opened the live demo', '2' => 'Explored 2+ modules', '4' => 'Explored 4+ modules', '7' => 'Explored all 7 modules'])
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                        ? $query->whereIn('id', SiteEvent::select('site_visit_id')
                            ->where('type', 'page_view')
                            ->where('path', 'like', '/demo%')
                            ->groupBy('site_visit_id')
                            ->havingRaw('COUNT(DISTINCT path) >= ?', [(int) $data['value']]))
                        : $query),
                TernaryFilter::make('booked')
                    ->label('Booked a demo')
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas('booking'),
                        false: fn (Builder $query) => $query->whereDoesntHave('booking'),
                    ),
                TernaryFilter::make('is_returning')
                    ->label('Returning visitor'),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                ViewAction::make()
                    ->label('Timeline')
                    ->slideOver(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteVisits::route('/'),
        ];
    }

    private static function duration(SiteVisit $visit): string
    {
        $seconds = (int) $visit->started_at->diffInSeconds($visit->last_seen_at);

        return $seconds < 60 ? "{$seconds}s" : intdiv($seconds, 60).'m '.str_pad((string) ($seconds % 60), 2, '0', STR_PAD_LEFT).'s';
    }
}
