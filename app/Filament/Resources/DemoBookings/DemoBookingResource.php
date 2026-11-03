<?php

namespace App\Filament\Resources\DemoBookings;

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Resources\DemoBookings\Pages\ManageDemoBookings;
use App\Models\DemoBooking;
use App\Models\SiteVisit;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Demo requests sent from the homepage's "Book a Free Demo" form, with a simple follow-up status.
 */
class DemoBookingResource extends Resource
{
    protected static ?string $model = DemoBooking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $newBookings = DemoBooking::where('status', 'new')->count();

        return $newBookings > 0 ? (string) $newBookings : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options(DemoBooking::STATUSES)
                    ->required()
                    ->native(false),
                Textarea::make('notes')
                    ->label('Internal notes')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('created_at')
                            ->label('Booked')
                            ->dateTime('M d, Y g:i A'),
                        TextEntry::make('contact_number')
                            ->placeholder('—')
                            ->copyable(),
                        TextEntry::make('email')
                            ->placeholder('—')
                            ->copyable(),
                    ]),
                Section::make('About their company')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('company_info')
                            ->hiddenLabel(),
                    ]),
                Section::make('Visitor journey before booking')
                    ->description('What this lead looked at on the website before booking, from anonymous site analytics.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->visible(fn (DemoBooking $record): bool => $record->visitor_id !== null && $record->visitsBeforeBooking()->exists())
                    ->schema([
                        TextEntry::make('journey_source')
                            ->label('First found us via')
                            ->state(fn (DemoBooking $record): string => $record->visitsBeforeBooking()->oldest('started_at')->first()?->source() ?? '—'),
                        TextEntry::make('journey_device')
                            ->label('Device and location')
                            ->state(function (DemoBooking $record): string {
                                $visit = $record->visit ?? $record->visitsBeforeBooking()->latest('started_at')->first();

                                return $visit ? ucfirst($visit->device).' · '.$visit->browser.' · '.$visit->locationName() : '—';
                            }),
                        TextEntry::make('journey_visits')
                            ->label('Visits before booking')
                            ->state(function (DemoBooking $record): string {
                                $visits = $record->visitsBeforeBooking()->count();
                                $firstVisit = $record->visitsBeforeBooking()->min('started_at');

                                return $visits.' '.str('visit')->plural($visits).', first on '.Carbon::parse($firstVisit)->timezone('Asia/Manila')->format('M d, Y g:i A');
                            }),
                        TextEntry::make('journey_demo_modules')
                            ->label('Live demo modules viewed')
                            ->placeholder('Did not open the live demo')
                            ->state(fn (DemoBooking $record): array => $record->eventsBeforeBooking('page_view')
                                ->where('path', 'like', '/demo%')
                                ->distinct()
                                ->pluck('path')
                                ->map(fn (string $path): string => $path === '/demo' ? 'Command Center' : str($path)->after('/demo/')->headline()->replace('Ai ', 'AI ')->toString())
                                ->all())
                            ->listWithLineBreaks()
                            ->bulleted(),
                        TextEntry::make('journey_sections')
                            ->label('Homepage sections read (longest first)')
                            ->placeholder('—')
                            ->state(fn (DemoBooking $record): array => $record->eventsBeforeBooking('section_time')
                                ->select('section', DB::raw('SUM(value) as seconds'))
                                ->groupBy('section')
                                ->orderByDesc('seconds')
                                ->limit(8)
                                ->get()
                                ->map(fn ($row): string => $row->section.': '.((int) $row->seconds < 60 ? $row->seconds.'s' : intdiv((int) $row->seconds, 60).'m '.((int) $row->seconds % 60).'s'))
                                ->all())
                            ->listWithLineBreaks()
                            ->bulleted(),
                        TextEntry::make('journey_clicks')
                            ->label('Buttons and FAQ questions clicked')
                            ->placeholder('—')
                            ->state(fn (DemoBooking $record): array => $record->eventsBeforeBooking('click')
                                ->select('label', DB::raw('COUNT(*) as clicks'))
                                ->groupBy('label')
                                ->orderByDesc('clicks')
                                ->limit(10)
                                ->get()
                                ->map(fn ($row): string => $row->label.($row->clicks > 1 ? " (×{$row->clicks})" : ''))
                                ->all())
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ]),
                Section::make('Follow-up')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => DemoBooking::STATUSES[$state] ?? $state)
                            ->color(fn (string $state): string => DemoBooking::STATUS_COLORS[$state] ?? 'gray'),
                        TextEntry::make('notes')
                            ->label('Internal notes')
                            ->placeholder('—'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('visit'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Booked')
                    ->dateTime('M d, Y g:i A')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->placeholder('—')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('email')
                    ->placeholder('—')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('company_info')
                    ->label('Company')
                    ->limit(60)
                    ->tooltip(fn (DemoBooking $record): string => $record->company_info)
                    ->searchable()
                    ->wrap(),
                TextColumn::make('source')
                    ->label('Came from')
                    ->tooltip('Source of the visit in which they booked')
                    ->state(fn (DemoBooking $record): string => $record->visit?->source() ?? 'Not tracked')
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => DemoBooking::STATUSES[$state] ?? $state)
                    ->color(fn (string $state): string => DemoBooking::STATUS_COLORS[$state] ?? 'gray')
                    ->sortable(),
            ])
            ->filters([
                SiteAnalytics::periodFilter('created_at'),
                SelectFilter::make('status')
                    ->options(DemoBooking::STATUSES),
                SelectFilter::make('source')
                    ->label('Came from')
                    ->options(fn (): array => [...SiteVisit::sourceOptions(), 'Not tracked' => 'Not tracked'])
                    ->query(fn (Builder $query, array $data): Builder => match ($data['value'] ?? null) {
                        null, '' => $query,
                        'Not tracked' => $query->whereNull('site_visit_id'),
                        default => $query->whereHas('visit', fn (Builder $visit) => $visit->fromSource($data['value'])),
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->label('Follow up'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDemoBookings::route('/'),
        ];
    }
}
