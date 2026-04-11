<?php

namespace App\Filament\Resources\BloodDonationResource\Pages;

use App\Filament\Resources\BloodDonationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodDonation extends EditRecord
{
    protected static string $resource = BloodDonationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
