<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    const TIME_OF_DAY = [
        '10-morning'   => 'Morning',
        '20-afternoon' => 'Afternoon',
        '30-evening'   => 'Evening',
        '40-night'     => 'Night',
    ];

    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'destination_id',
        'time_of_day',
        'pax',
        'sort',
        'notes',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getPricePerPaxAttribute()
    {
        return $this->destination->price_per_pax;
    }

    public function getTotalCostAttribute()
    {
        return $this->price_per_pax * $this->pax;
    }
}
