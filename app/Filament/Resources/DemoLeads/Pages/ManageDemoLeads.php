<?php

namespace App\Filament\Resources\DemoLeads\Pages;

use App\Filament\Resources\DemoLeads\DemoLeadResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDemoLeads extends ManageRecords
{
    protected static string $resource = DemoLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
