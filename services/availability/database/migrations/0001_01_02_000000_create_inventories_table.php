<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('room_type_code', 50);
            $table->date('date');
            $table->unsignedInteger('total');
            $table->unsignedInteger('held')->default(0);
            $table->unsignedInteger('booked')->default(0);
            $table->timestamps();

            $table->unique(['property_id', 'room_type_code', 'date']);
            $table->index(['room_type_code', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
