<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoldNight extends Model
{
    protected $fillable = [
        'hold_id',
        'date',
        'qty',
    ];

    protected $casts = [
        'date' => 'date',
        'qty' => 'integer',
    ];

    public function hold(): BelongsTo
    {
        return $this->belongsTo(Hold::class);
    }
}
