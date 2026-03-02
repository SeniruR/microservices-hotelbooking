<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('holds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('property_id');
            $table->string('room_type_code', 50);
            $table->date('check_in');
            $table->date('check_out');
            $table->string('status', 20);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'room_type_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holds');
    }
};
