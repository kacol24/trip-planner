<?php

namespace App\Filament\Resources\ItineraryResource\Pages;

use App\Filament\Resources\ItineraryResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListItineraries extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('calendar')
                          ->url('/calendar')
                          ->openUrlInNewTab(),
            Actions\Action::make('Print')
                          ->url('/')
                          ->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ItineraryResource\Widgets\BudgetTotalStats::class,
        ];
    }
}
