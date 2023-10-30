<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
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
        'transporation_rate',
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
}
