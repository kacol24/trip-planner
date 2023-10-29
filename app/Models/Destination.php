<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'destination_type_id',
        'name',
        'notes',
        'price_per_pax',
        'pax',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function destinationType()
    {
        return $this->belongsTo(DestinationType::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->price_per_pax * $this->pax;
    }
}
