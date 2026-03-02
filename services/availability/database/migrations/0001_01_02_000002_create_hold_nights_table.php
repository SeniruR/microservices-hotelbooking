<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hold_nights', function (Blueprint $table) {
            $table->id();
            $table->uuid('hold_id');
            $table->date('date');
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps();

            $table->unique(['hold_id', 'date']);
            $table->index(['date']);
            $table->foreign('hold_id')->references('id')->on('holds')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hold_nights');
    }
};
