<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hold extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'property_id',
        'room_type_code',
        'check_in',
        'check_out',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'expires_at' => 'datetime',
    ];

    public function nights(): HasMany
    {
        return $this->hasMany(HoldNight::class);
    }
}
