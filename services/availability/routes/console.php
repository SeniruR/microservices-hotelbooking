<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Inventory;
use Carbon\CarbonImmutable;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('inventory:seed {property_id=1} {room_type_code=STD} {--days=30} {--total=5}', function () {
    $propertyId = (int) $this->argument('property_id');
    $roomTypeCode = (string) $this->argument('room_type_code');
    $days = max(1, (int) $this->option('days'));
    $total = max(1, (int) $this->option('total'));

    $start = CarbonImmutable::now()->startOfDay();
    $rows = [];

    for ($i = 0; $i < $days; $i++) {
        $rows[] = [
            'property_id' => $propertyId,
            'room_type_code' => $roomTypeCode,
            'date' => $start->addDays($i)->toDateString(),
            'total' => $total,
            'held' => 0,
            'booked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Inventory::query()->upsert(
        $rows,
        ['property_id', 'room_type_code', 'date'],
        ['total', 'updated_at']
    );

    $this->info("Seeded {$days} nights for property={$propertyId}, room_type={$roomTypeCode}, total={$total}");
})->purpose('Seed demo inventory for Availability service');
