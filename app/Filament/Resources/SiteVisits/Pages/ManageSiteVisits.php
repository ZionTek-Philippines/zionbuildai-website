<?php

namespace App\Filament\Resources\SiteVisits\Pages;

use App\Filament\Resources\SiteVisits\SiteVisitResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteVisits extends ManageRecords
{
    protected static string $resource = SiteVisitResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
