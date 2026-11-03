<?php

namespace App\Filament\Resources\DemoBookings\Pages;

use App\Filament\Resources\DemoBookings\DemoBookingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageDemoBookings extends ManageRecords
{
    protected static string $resource = DemoBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
