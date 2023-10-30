<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinationType extends Model
{
    const TYPE_WISATA = 1;

    const TYPE_KULINER = 2;

    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
