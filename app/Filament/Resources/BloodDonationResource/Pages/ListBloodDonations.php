<?php

namespace App\Filament\Resources\BloodDonationResource\Pages;

use App\Filament\Resources\BloodDonationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBloodDonations extends ListRecords
{
    protected static string $resource = BloodDonationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
