<?php

namespace App\Filament\Resources\PreviewPasswords\Pages;

use App\Filament\Resources\PreviewPasswords\PreviewPasswordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePreviewPasswords extends ManageRecords
{
    protected static string $resource = PreviewPasswordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
