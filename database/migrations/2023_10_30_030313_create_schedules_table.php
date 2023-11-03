<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id');
            $table->foreignId('destination_id');
            $table->string('time_of_day')->index();
            $table->tinyInteger('pax')->nullable();
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('sort')->default(999)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
