<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'property_id',
        'room_type_code',
        'date',
        'total',
        'held',
        'booked',
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'integer',
        'held' => 'integer',
        'booked' => 'integer',
    ];
}
