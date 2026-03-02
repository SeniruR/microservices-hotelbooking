<?php

use App\Http\Controllers\Api\V1\AvailabilityController;
use App\Http\Controllers\Api\V1\HoldController;
use App\Http\Controllers\Api\V1\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json(['service' => 'availability', 'status' => 'ok']));

    Route::get('/availability', [AvailabilityController::class, 'index']);

    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::put('/inventory', [InventoryController::class, 'upsert']);

    Route::post('/holds', [HoldController::class, 'store']);
    Route::post('/holds/{hold}/confirm', [HoldController::class, 'confirm']);
    Route::post('/holds/{hold}/cancel', [HoldController::class, 'cancel']);
});
