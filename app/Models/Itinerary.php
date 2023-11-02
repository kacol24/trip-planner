<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    const FUEL_PRICE = 10000;

    use HasFactory;

    protected $fillable = [
        'accomodation_id',
        'transportation_id',
        'date',
        'theme',
        'notes',
        'room_rate',
        'room_count',
        'distance',
        'transportation_rate',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function accomodation()
    {
        return $this->belongsTo(Accomodation::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('sort');
    }

    public function getDropdownNameAttribute()
    {
        return strtoupper($this->date->format('d D'));
    }

    public function getAccomodationCostAttribute()
    {
        return $this->room_rate * $this->room_count;
    }

    public function getFuelCostAttribute()
    {
        return $this->distance / 10 * self::FUEL_PRICE;
    }

    public function getTransportationCostAttribute()
    {
        return $this->transportation_rate + $this->fuel_cost;
    }

    public function getWisataCostAttribute()
    {
        $wisata = $this->schedules()->whereHas('destination', function ($query) {
            $query->where('destination_type_id', DestinationType::TYPE_WISATA);
        })->get();

        return $wisata->sum('total_cost');
    }

    public function getKulinerCostAttribute()
    {
        $wisata = $this->schedules()->whereHas('destination', function ($query) {
            $query->where('destination_type_id', DestinationType::TYPE_KULINER);
        })->get();

        return $wisata->sum('total_cost');
    }

    public function getTotalForTheDayAttribute()
    {
        return $this->accomodation_cost + $this->transportation_cost + $this->wisata_cost + $this->kuliner_cost;
    }

    public function getAccomodationColumnAttribute()
    {
        $data = [
            'accomodation' => $this->accomodation,
            'itinerary'    => $this,
        ];

        return view('columns.accomodation', $data)->render();
    }

    public function getTransportationColumnAttribute()
    {
        $data = [
            'transporation' => $this->transportation,
            'itinerary'     => $this,
        ];

        return view('columns.transportation', $data)->render();
    }
}
