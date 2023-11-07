<?php

namespace App\Filament\Resources\ItineraryResource\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class ItineraryBudgetOverview extends BaseOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $itineraries = optional($this->record);

        $accomodationCost = $itineraries->accomodation_cost;
        $formattedAccomodationCost = number_format($accomodationCost, 0, ',', '.');
        $transportationRate = $itineraries->transportation_rate;
        $formattedTransportationRate = number_format($transportationRate, 0, ',', '.');
        $fuelCost = $itineraries->fuel_cost;
        $formattedFuelCost = number_format($fuelCost, 0, ',', '.');
        $transportationCost = $itineraries->transportation_cost;
        $formattedTransportationCost = number_format($transportationCost, 0, ',', '.');
        $wisataCost = $itineraries->wisata_cost;
        $formattedWisataCost = number_format($wisataCost, 0, ',', '.');
        $kulinerCost = $itineraries->kuliner_cost;
        $formattedKulinerCost = number_format($kulinerCost, 0, ',', '.');
        $formattedWisataKulinerCost = number_format($wisataCost + $kulinerCost, 0, ',', '.');
        $totalForTheDay = $itineraries->total_for_the_day;
        $formattedTotal = number_format($totalForTheDay, 0, ',', '.');

        return [
            Stat::make(
                'Akomodasi',
                'Rp'.$this->thousandsCurrencyFormat($accomodationCost).' x '.$itineraries->room_count
            )->description('Rp'.$formattedAccomodationCost),
            Stat::make(
                'Transport + Fuel',
                'Rp'.$this->thousandsCurrencyFormat($transportationCost)
            )
                ->description('Rp'.$formattedTransportationRate.' + Rp'.$formattedFuelCost.' = Rp'.$formattedTransportationCost),
            Stat::make(
                'Wisata + Kuliner',
                'Rp'.$this->thousandsCurrencyFormat($wisataCost + $kulinerCost)
            )->description('Rp'.$formattedWisataCost.' + Rp'.$formattedKulinerCost.' = Rp'.$formattedWisataKulinerCost),
            Stat::make(
                'All-in',
                'Rp'.$this->thousandsCurrencyFormat($totalForTheDay)
            )->description('Rp'.$formattedTotal),
        ];
    }
}
