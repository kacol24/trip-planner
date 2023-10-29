<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accomodation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'area_id',
        'name',
        'notes',
        'rate',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
