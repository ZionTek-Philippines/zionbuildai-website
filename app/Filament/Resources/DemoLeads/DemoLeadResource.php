<?php

namespace App\Filament\Resources\DemoLeads;

use App\Filament\Pages\SiteAnalytics;
use App\Filament\Resources\DemoLeads\Pages\ManageDemoLeads;
use App\Models\DemoLead;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Read-only list of visitors who unlocked the full demo on the marketing site.
 */
class DemoLeadResource extends Resource
{
    protected static ?string $model = DemoLead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Signed up')
                    ->dateTime('M d, Y g:i A')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('mobile')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => DemoLead::ROLES[$state] ?? $state),
                TextColumn::make('unlocked_from')
                    ->label('Unlocked from')
                    ->formatStateUsing(fn (?string $state): string => str($state)->after('demo.')->headline()->toString())
                    ->toggleable(),
            ])
            ->filters([
                SiteAnalytics::periodFilter('created_at'),
                SelectFilter::make('role')
                    ->options(DemoLead::ROLES),
            ])
            ->recordActions([
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
            'index' => ManageDemoLeads::route('/'),
        ];
    }
}
