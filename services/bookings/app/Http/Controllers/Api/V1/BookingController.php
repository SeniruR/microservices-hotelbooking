<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['sometimes', 'integer', 'min:1'],
            'room_type_code' => ['sometimes', 'string', 'max:50'],
            'status' => ['sometimes', 'string', 'in:confirmed,cancelled'],
            'guest_email' => ['sometimes', 'string', 'max:255'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:200'],
        ]);

        $from = CarbonImmutable::parse($validated['from'])->startOfDay();
        $toInclusive = CarbonImmutable::parse($validated['to'])->startOfDay();
        $toExclusive = $toInclusive->addDay();

        $limit = (int) ($validated['limit'] ?? 50);

        $query = Booking::query();

        if (isset($validated['property_id'])) {
            $query->where('property_id', (int) $validated['property_id']);
        }

        if (isset($validated['room_type_code'])) {
            $query->where('room_type_code', (string) $validated['room_type_code']);
        }

        if (isset($validated['status'])) {
            $query->where('status', (string) $validated['status']);
        }

        if (isset($validated['guest_email'])) {
            $guestEmail = trim((string) $validated['guest_email']);
            if ($guestEmail !== '') {
                $query->where('guest_email', 'like', '%'.$guestEmail.'%');
            }
        }

        // Overlap logic: booking [check_in, check_out) overlaps search window [from, toInclusive+1day)
        $query
            ->whereDate('check_in', '<', $toExclusive->toDateString())
            ->whereDate('check_out', '>', $from->toDateString());

        $paginator = $query
            ->orderBy('check_in')
            ->orderBy('id')
            ->simplePaginate($limit)
            ->appends($request->query());

        return response()->json([
            'filters' => [
                'property_id' => $validated['property_id'] ?? null,
                'room_type_code' => $validated['room_type_code'] ?? null,
                'status' => $validated['status'] ?? null,
                'guest_email' => $validated['guest_email'] ?? null,
                'from' => $from->toDateString(),
                'to' => $toInclusive->toDateString(),
                'limit' => $limit,
            ],
            'bookings' => $paginator->items(),
            'next_page_url' => $paginator->nextPageUrl(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'integer', 'min:1'],
            'room_type_code' => ['required', 'string', 'max:50'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guest_email' => ['nullable', 'string', 'max:255'],
        ]);

        $guestEmailRaw = trim((string) ($validated['guest_email'] ?? ''));
        $guestEmail = filter_var($guestEmailRaw, FILTER_VALIDATE_EMAIL) ? $guestEmailRaw : null;

        $availabilityBaseUrl = rtrim((string) config('services.availability.base_url', ''), '/');
        if ($availabilityBaseUrl === '') {
            return response()->json(['error' => 'availability_base_url_missing'], 500);
        }

        $httpTimeoutSeconds = (int) config('services.availability.timeout_seconds', 10);
        $http = Http::connectTimeout(2)->timeout(max(3, $httpTimeoutSeconds));

        try {
            $holdResponse = $http->post($availabilityBaseUrl.'/api/v1/holds', [
                'property_id' => (int) $validated['property_id'],
                'room_type_code' => $validated['room_type_code'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'qty' => 1,
            ]);
        } catch (ConnectionException $e) {
            return response()->json([
                'error' => 'availability_unreachable',
                'message' => $e->getMessage(),
            ], 502);
        }

        if (!$holdResponse->successful()) {
            $status = (int) $holdResponse->status();
            $outStatus = $status >= 500 ? 502 : $status;
            return response()->json([
                'error' => 'hold_failed',
                'availability_status' => $status,
                'details' => $holdResponse->json(),
            ], $outStatus);
        }

        $holdId = (string) ($holdResponse->json('hold_id') ?? '');
        if ($holdId === '') {
            return response()->json(['error' => 'hold_id_missing'], 502);
        }

        try {
            $confirmResponse = $http->post($availabilityBaseUrl.'/api/v1/holds/'.$holdId.'/confirm');
        } catch (ConnectionException $e) {
            try {
                $http->post($availabilityBaseUrl.'/api/v1/holds/'.$holdId.'/cancel');
            } catch (ConnectionException) {
                // Ignore.
            }

            return response()->json([
                'error' => 'availability_unreachable',
                'message' => $e->getMessage(),
            ], 502);
        }
        if (!$confirmResponse->successful()) {
            try {
                $http->post($availabilityBaseUrl.'/api/v1/holds/'.$holdId.'/cancel');
            } catch (ConnectionException) {
                // Ignore.
            }

            $status = (int) $confirmResponse->status();
            $outStatus = $status >= 500 ? 502 : $status;

            return response()->json([
                'error' => 'confirm_failed',
                'availability_status' => $status,
                'details' => $confirmResponse->json(),
            ], $outStatus);
        }

        $bookingId = (string) Str::uuid();

        $booking = Booking::create([
            'id' => $bookingId,
            'hold_id' => $holdId,
            'property_id' => (int) $validated['property_id'],
            'room_type_code' => $validated['room_type_code'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'status' => 'confirmed',
            'guest_email' => $guestEmail,
        ]);

        $this->publishEvent('BookingCreated', [
            'booking_id' => $booking->id,
            'hold_id' => $booking->hold_id,
            'property_id' => (string) $booking->property_id,
            'room_type_code' => $booking->room_type_code,
            'check_in' => $booking->check_in->toDateString(),
            'check_out' => $booking->check_out->toDateString(),
            'guest_email' => $booking->guest_email,
        ]);

        return response()->json([
            'booking' => $booking,
        ], 201);
    }

    public function show(Booking $booking)
    {
        return response()->json(['booking' => $booking]);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return response()->json(['booking' => $booking]);
        }

        $availabilityBaseUrl = rtrim((string) config('services.availability.base_url', ''), '/');
        if ($availabilityBaseUrl === '') {
            return response()->json(['error' => 'availability_base_url_missing'], 500);
        }

        $httpTimeoutSeconds = (int) config('services.availability.timeout_seconds', 10);
        $http = Http::connectTimeout(2)->timeout(max(3, $httpTimeoutSeconds));

        try {
            $cancelResponse = $http->post($availabilityBaseUrl.'/api/v1/holds/'.$booking->hold_id.'/cancel');
        } catch (ConnectionException $e) {
            return response()->json([
                'error' => 'availability_unreachable',
                'message' => $e->getMessage(),
            ], 502);
        }
        if (!$cancelResponse->successful()) {
            $status = (int) $cancelResponse->status();
            $outStatus = $status >= 500 ? 502 : $status;
            return response()->json([
                'error' => 'availability_cancel_failed',
                'availability_status' => $status,
                'details' => $cancelResponse->json(),
            ], $outStatus);
        }

        $booking->status = 'cancelled';
        $booking->save();

        $this->publishEvent('BookingCancelled', [
            'booking_id' => $booking->id,
            'hold_id' => $booking->hold_id,
        ]);

        return response()->json(['booking' => $booking]);
    }

    private function publishEvent(string $type, array $payload): void
    {
        $stream = (string) config('services.streams.booking_events', 'booking-events');

        $fields = [
            'type', (string) $type,
            'event_id', (string) Str::uuid(),
            'occurred_at', now()->toIso8601String(),
        ];

        foreach ($payload as $key => $value) {
            $fields[] = (string) $key;
            $fields[] = is_scalar($value) ? (string) $value : (string) json_encode($value);
        }

        try {
            $client = Redis::connection()->client();
            if (method_exists($client, 'executeRaw')) {
                $client->executeRaw(array_merge(['XADD', $stream, '*'], $fields));
                return;
            }

            Redis::command('xadd', array_merge([$stream, '*'], $fields));
        } catch (\Throwable) {
            // For MVP, event publishing failures should not break booking creation.
        }
    }
}
