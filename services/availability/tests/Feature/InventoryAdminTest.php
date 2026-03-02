<?php

namespace Tests\Feature;

use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upsert_inventory_for_date_range(): void
    {
        $resp = $this->putJson('/api/v1/inventory', [
            'property_id' => 1,
            'room_type_code' => 'STD',
            'from' => '2026-03-01',
            'to' => '2026-03-03',
            'total' => 5,
        ]);

        $resp->assertOk();
        $resp->assertJsonPath('result.nights', 3);

        $this->assertDatabaseHas('inventories', [
            'property_id' => 1,
            'room_type_code' => 'STD',
            'date' => '2026-03-01',
            'total' => 5,
        ]);
        $this->assertDatabaseHas('inventories', [
            'property_id' => 1,
            'room_type_code' => 'STD',
            'date' => '2026-03-02',
            'total' => 5,
        ]);
        $this->assertDatabaseHas('inventories', [
            'property_id' => 1,
            'room_type_code' => 'STD',
            'date' => '2026-03-03',
            'total' => 5,
        ]);
    }

    public function test_cannot_set_total_below_held_plus_booked(): void
    {
        Inventory::create([
            'property_id' => 1,
            'room_type_code' => 'STD',
            'date' => '2026-03-01',
            'total' => 5,
            'held' => 2,
            'booked' => 1,
        ]);

        $resp = $this->putJson('/api/v1/inventory', [
            'property_id' => 1,
            'room_type_code' => 'STD',
            'date' => '2026-03-01',
            'total' => 2,
        ]);

        $resp->assertStatus(422);
        $resp->assertJsonValidationErrors(['total']);
    }
}
