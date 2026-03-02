<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for column modifications.
        DB::statement('ALTER TABLE bookings MODIFY COLUMN guest_email VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement("UPDATE bookings SET guest_email = '' WHERE guest_email IS NULL");
        DB::statement('ALTER TABLE bookings MODIFY COLUMN guest_email VARCHAR(255) NOT NULL');
    }
};
