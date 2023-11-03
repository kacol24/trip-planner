<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    const TIME_OF_DAY = [
        '10-morning'   => 'Morning (06:00 - 11:00)',
        '20-afternoon' => 'Afternoon (11:00 - 15:00)',
        '30-evening'   => 'Evening (15:00 - 18:00)',
        '40-night'     => 'Night (18:00 - 22:00)',
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

    public function getCostCalculatorAttribute()
    {
        if (! $this->price_per_pax || ! $this->pax) {
            return null;
        }

        $pricePerPax = number_format($this->price_per_pax, 0, ',', '.');
        $pax = $this->pax;
        $totalCost = number_format($this->total_cost, 0, ',', '.');

        return 'Rp' . $pricePerPax . ' x ' . $pax . ' = Rp' . $totalCost;
    }
}
