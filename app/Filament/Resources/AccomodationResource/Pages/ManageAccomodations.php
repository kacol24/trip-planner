<?php

namespace App\Filament\Resources\AccomodationResource\Pages;

use App\Filament\Resources\AccomodationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccomodations extends ManageRecords
{
    protected static string $resource = AccomodationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
