<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hold;
use App\Models\HoldNight;
use App\Models\Inventory;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoldController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'integer', 'min:1'],
            'room_type_code' => ['required', 'string', 'max:50'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'qty' => ['sometimes', 'integer', 'min:1', 'max:5'],
        ]);

        $qty = (int) ($validated['qty'] ?? 1);
        $checkIn = CarbonImmutable::parse($validated['check_in'])->startOfDay();
        $checkOut = CarbonImmutable::parse($validated['check_out'])->startOfDay();

        $dates = [];
        for ($date = $checkIn; $date->lt($checkOut); $date = $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        $expiresAt = now()->addMinutes(15);

        try {
            $hold = DB::transaction(function () use ($validated, $qty, $dates, $expiresAt, $checkIn, $checkOut) {
                $inventories = Inventory::query()
                    ->where('property_id', $validated['property_id'])
                    ->where('room_type_code', $validated['room_type_code'])
                    ->whereIn('date', $dates)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy(fn (Inventory $inventory) => $inventory->date->toDateString());

                foreach ($dates as $date) {
                    /** @var Inventory|null $inventory */
                    $inventory = $inventories->get($date);
                    if (!$inventory) {
                        abort(409, 'inventory_missing');
                    }

                    $available = $inventory->total - $inventory->held - $inventory->booked;
                    if ($available < $qty) {
                        abort(409, 'not_available');
                    }
                }

                $hold = Hold::create([
                    'property_id' => (int) $validated['property_id'],
                    'room_type_code' => $validated['room_type_code'],
                    'check_in' => $checkIn->toDateString(),
                    'check_out' => $checkOut->toDateString(),
                    'status' => 'held',
                    'expires_at' => $expiresAt,
                ]);

                foreach ($dates as $date) {
                    HoldNight::create([
                        'hold_id' => $hold->id,
                        'date' => $date,
                        'qty' => $qty,
                    ]);

                    /** @var Inventory $inventory */
                    $inventory = $inventories->get($date);
                    $inventory->held += $qty;
                    $inventory->save();
                }

                return $hold;
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getStatusCode());
        }

        return response()->json([
            'hold_id' => $hold->id,
            'status' => $hold->status,
            'expires_at' => $hold->expires_at?->toIso8601String(),
        ], 201);
    }

    public function confirm(Hold $hold)
    {
        if ($hold->status === 'confirmed') {
            return response()->json(['hold_id' => $hold->id, 'status' => $hold->status]);
        }

        if ($hold->status !== 'held') {
            return response()->json(['error' => 'invalid_status'], 409);
        }

        if ($hold->expires_at && $hold->expires_at->isPast()) {
            return response()->json(['error' => 'hold_expired'], 409);
        }

        DB::transaction(function () use ($hold) {
            $hold->load('nights');

            $inventories = Inventory::query()
                ->where('property_id', $hold->property_id)
                ->where('room_type_code', $hold->room_type_code)
                ->whereIn('date', $hold->nights->pluck('date')->map(fn ($d) => $d->toDateString())->all())
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Inventory $inventory) => $inventory->date->toDateString());

            foreach ($hold->nights as $night) {
                $date = $night->date->toDateString();
                /** @var Inventory|null $inventory */
                $inventory = $inventories->get($date);
                if (!$inventory) {
                    abort(409, 'inventory_missing');
                }

                $inventory->held = max(0, $inventory->held - $night->qty);
                $inventory->booked += $night->qty;
                $inventory->save();
            }

            $hold->status = 'confirmed';
            $hold->save();
        });

        return response()->json(['hold_id' => $hold->id, 'status' => $hold->status]);
    }

    public function cancel(Hold $hold)
    {
        if ($hold->status === 'cancelled') {
            return response()->json(['hold_id' => $hold->id, 'status' => $hold->status]);
        }

        if (!in_array($hold->status, ['held', 'confirmed'], true)) {
            return response()->json(['error' => 'invalid_status'], 409);
        }

        DB::transaction(function () use ($hold) {
            $hold->load('nights');

            $inventories = Inventory::query()
                ->where('property_id', $hold->property_id)
                ->where('room_type_code', $hold->room_type_code)
                ->whereIn('date', $hold->nights->pluck('date')->map(fn ($d) => $d->toDateString())->all())
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (Inventory $inventory) => $inventory->date->toDateString());

            foreach ($hold->nights as $night) {
                $date = $night->date->toDateString();
                /** @var Inventory|null $inventory */
                $inventory = $inventories->get($date);
                if (!$inventory) {
                    continue;
                }

                if ($hold->status === 'held') {
                    $inventory->held = max(0, $inventory->held - $night->qty);
                }

                if ($hold->status === 'confirmed') {
                    $inventory->booked = max(0, $inventory->booked - $night->qty);
                }

                $inventory->save();
            }

            $hold->status = 'cancelled';
            $hold->save();
        });

        return response()->json(['hold_id' => $hold->id, 'status' => $hold->status]);
    }
}
