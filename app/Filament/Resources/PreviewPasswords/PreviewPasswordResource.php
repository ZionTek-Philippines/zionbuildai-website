<?php

namespace App\Filament\Resources\PreviewPasswords;

use App\Filament\Resources\PreviewPasswords\Pages\ManagePreviewPasswords;
use App\Models\PreviewPassword;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

/**
 * Passwords for the private homepage, one per prospective client (see RequireSitePreviewPassword).
 */
class PreviewPasswordResource extends Resource
{
    protected static ?string $model = PreviewPassword::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'Homepage Passwords';

    protected static ?string $modelLabel = 'homepage password';

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Client')
                    ->helperText('Who this password is for, e.g. "Juan Dela Cruz — DC Builders".')
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->required()
                    ->minLength(6)
                    ->maxLength(100)
                    ->default(fn (): string => PreviewPassword::generate())
                    ->helperText('Share this with the client. Each client should get a different password.')
                    ->rule(fn (?PreviewPassword $record): Closure => function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                        if (PreviewPassword::isTaken((string) $value, $record?->getKey())) {
                            $fail('Another client already has this password.');
                        }
                    })
                    ->suffixAction(
                        Action::make('generatePassword')
                            ->icon(Heroicon::OutlinedArrowPath)
                            ->tooltip('Generate a new password')
                            ->action(fn (Set $set) => $set('password', PreviewPassword::generate())),
                    ),
                Toggle::make('is_active')
                    ->label('Active')
                    ->helperText('Turn off to lock this client out immediately.')
                    ->default(true),
                DateTimePicker::make('expires_at')
                    ->label('Expires')
                    ->helperText('Optional. Stops working after this date.')
                    ->seconds(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('label')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('password')
                    ->fontFamily('mono')
                    ->copyable()
                    ->copyMessage('Password copied'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M d, Y g:i A')
                    ->placeholder('Never')
                    ->color(fn (PreviewPassword $record): ?string => $record->expires_at?->isPast() ? 'danger' : null)
                    ->sortable(),
                TextColumn::make('last_used_at')
                    ->label('Last used')
                    ->since()
                    ->placeholder('Not yet')
                    ->sortable(),
                TextColumn::make('uses')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ManagePreviewPasswords::route('/'),
        ];
    }
}
