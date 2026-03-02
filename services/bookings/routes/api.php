<?php

use App\Http\Controllers\Api\V1\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json([
        'service' => 'bookings',
        'status' => 'ok',
        'availability' => [
            'config_base_url' => config('services.availability.base_url'),
            'env_base_url' => env('AVAILABILITY_BASE_URL'),
            'getenv_base_url' => getenv('AVAILABILITY_BASE_URL') ?: null,
        ],
    ]));

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});
