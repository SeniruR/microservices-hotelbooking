<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'integer', 'min:1'],
            'room_type_code' => ['required', 'string', 'max:50'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
        ]);

        $from = CarbonImmutable::parse($validated['from'])->startOfDay();
        $to = CarbonImmutable::parse($validated['to'])->startOfDay();

        $dates = [];
        for ($date = $from; $date->lte($to); $date = $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        $inventories = Inventory::query()
            ->where('property_id', (int) $validated['property_id'])
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
                'exists' => (bool) $inventory,
                'total' => $total,
                'held' => $held,
                'booked' => $booked,
                'available' => max(0, $total - $held - $booked),
            ];
        }, $dates);

        return response()->json([
            'property_id' => (int) $validated['property_id'],
            'room_type_code' => $validated['room_type_code'],
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'nights' => $nights,
        ]);
    }

    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'integer', 'min:1'],
            'room_type_code' => ['required', 'string', 'max:50'],
            'date' => ['sometimes', 'date'],
            'from' => ['required_without:date', 'date'],
            'to' => ['required_without:date', 'date', 'after_or_equal:from'],
            'total' => ['required', 'integer', 'min:0', 'max:100000'],
        ]);

        $from = CarbonImmutable::parse($validated['date'] ?? $validated['from'])->startOfDay();
        $to = CarbonImmutable::parse($validated['date'] ?? $validated['to'] ?? $validated['from'])->startOfDay();

        $dates = [];
        for ($date = $from; $date->lte($to); $date = $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        $propertyId = (int) $validated['property_id'];
        $roomType = (string) $validated['room_type_code'];
        $total = (int) $validated['total'];

        $result = DB::transaction(function () use ($propertyId, $roomType, $total, $dates) {
            $existing = Inventory::query()
                ->where('property_id', $propertyId)
                ->where('room_type_code', $roomType)
                ->whereIn('date', $dates)
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Inventory $inventory) => $inventory->date->toDateString());

            foreach ($dates as $date) {
                /** @var Inventory|null $inventory */
                $inventory = $existing->get($date);
                if (!$inventory) {
                    continue;
                }

                $allocated = $inventory->held + $inventory->booked;
                if ($total < $allocated) {
                    throw ValidationException::withMessages([
                        'total' => ["cannot_set_total_below_held_plus_booked (date={$date}, held={$inventory->held}, booked={$inventory->booked})"],
                    ]);
                }
            }

            $rows = [];
            $created = 0;

            foreach ($dates as $date) {
                if (!$existing->has($date)) {
                    $created++;
                }

                $rows[] = [
                    'property_id' => $propertyId,
                    'room_type_code' => $roomType,
                    'date' => $date,
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

            return [
                'nights' => count($dates),
                'created' => $created,
                'updated' => count($dates) - $created,
            ];
        });

        return response()->json([
            'property_id' => $propertyId,
            'room_type_code' => $roomType,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'total' => $total,
            'result' => $result,
        ]);
    }
}
