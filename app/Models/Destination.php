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
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function destinationType()
    {
        return $this->belongsTo(DestinationType::class);
    }

    public function getDropdownNameAttribute()
    {
        $area = strtoupper($this->area->name);
        $type = strtoupper($this->destinationType->name);

        return "[$area] $type > $this->name";
    }

    public function getRepeaterTitleAttribute()
    {
        $area = strtoupper($this->area->name);
        $type = strtoupper($this->destinationType->name);

        $title = "$type > $this->name";

        if ($this->destination_type_id != DestinationType::TYPE_OTW) {
            $title = "[$area] " . $title;
        }

        return $title;
    }
}
