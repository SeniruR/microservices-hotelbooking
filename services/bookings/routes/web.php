<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/health', function () {
    return response()->json([
        'service' => 'bookings',
        'status' => 'ok',
        'time' => now()->toIso8601String(),
    ]);
});
