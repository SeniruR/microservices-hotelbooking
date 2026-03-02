<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'integer', 'min:1'],
            'room_type_code' => ['required', 'string', 'max:50'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $checkIn = CarbonImmutable::parse($validated['check_in'])->startOfDay();
        $checkOut = CarbonImmutable::parse($validated['check_out'])->startOfDay();

        $dates = [];
        for ($date = $checkIn; $date->lt($checkOut); $date = $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        $inventories = Inventory::query()
            ->where('property_id', $validated['property_id'])
            ->where('room_type_code', $validated['room_type_code'])
            ->whereIn('date', $dates)
            ->get()
            ->keyBy(fn (Inventory $inventory) => $inventory->date->toDateString());

        $nights = array_map(function (string $date) use ($inventories) {
            /** @var Inventory|null $inventory */
            $inventory = $inventories->get($date);

            $total = $inventory?->total ?? 0;
            $held = $inventory?->held ?? 0;
            $booked = $inventory?->booked ?? 0;

            return [
                'date' => $date,
                'total' => $total,
                'held' => $held,
                'booked' => $booked,
                'available' => max(0, $total - $held - $booked),
            ];
        }, $dates);

        return response()->json([
            'property_id' => (int) $validated['property_id'],
            'room_type_code' => $validated['room_type_code'],
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'nights' => $nights,
        ]);
    }
}
