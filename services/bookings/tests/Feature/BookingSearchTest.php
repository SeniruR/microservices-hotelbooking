<?php

namespace Tests\Feature;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_bookings_by_overlap_and_property(): void
    {
        $b1 = Booking::create([
            'hold_id' => 'h1',
            'property_id' => 1,
            'room_type_code' => 'STD',
            'check_in' => '2026-03-01',
            'check_out' => '2026-03-03',
            'status' => 'confirmed',
            'guest_email' => 'guest1@example.com',
        ]);

        $b2 = Booking::create([
            'hold_id' => 'h2',
            'property_id' => 1,
            'room_type_code' => 'DLX',
            'check_in' => '2026-03-10',
            'check_out' => '2026-03-12',
            'status' => 'confirmed',
            'guest_email' => 'guest2@example.com',
        ]);

        // Different property
        Booking::create([
            'hold_id' => 'h3',
            'property_id' => 2,
            'room_type_code' => 'STD',
            'check_in' => '2026-03-02',
            'check_out' => '2026-03-04',
            'status' => 'confirmed',
            'guest_email' => 'guest3@example.com',
        ]);

        // Outside window
        Booking::create([
            'hold_id' => 'h4',
            'property_id' => 1,
            'room_type_code' => 'STD',
            'check_in' => '2026-04-01',
            'check_out' => '2026-04-02',
            'status' => 'confirmed',
            'guest_email' => 'guest4@example.com',
        ]);

        $resp = $this->getJson('/api/v1/bookings?property_id=1&from=2026-03-01&to=2026-03-31');

        $resp->assertOk();

        $ids = collect($resp->json('bookings'))->pluck('id')->all();
        $this->assertContains($b1->id, $ids);
        $this->assertContains($b2->id, $ids);
        $this->assertCount(2, $ids);
    }

    public function test_can_filter_by_guest_email_fragment(): void
    {
        $b1 = Booking::create([
            'hold_id' => 'h1',
            'property_id' => 1,
            'room_type_code' => 'STD',
            'check_in' => '2026-03-01',
            'check_out' => '2026-03-03',
            'status' => 'confirmed',
            'guest_email' => 'alice@example.com',
        ]);

        Booking::create([
            'hold_id' => 'h2',
            'property_id' => 1,
            'room_type_code' => 'STD',
            'check_in' => '2026-03-01',
            'check_out' => '2026-03-03',
            'status' => 'confirmed',
            'guest_email' => 'bob@example.com',
        ]);

        $resp = $this->getJson('/api/v1/bookings?property_id=1&from=2026-03-01&to=2026-03-31&guest_email=ali');
        $resp->assertOk();

        $ids = collect($resp->json('bookings'))->pluck('id')->all();
        $this->assertSame([$b1->id], $ids);
    }
}
