<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'hold_id',
        'property_id',
        'room_type_code',
        'check_in',
        'check_out',
        'status',
        'guest_email',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];
}
