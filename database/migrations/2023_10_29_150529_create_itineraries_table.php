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
        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accomodation_id')->nullable();
            $table->foreignId('transportation_id')->nullable();

            $table->date('date')->index();

            $table->string('theme')->nullable();
            $table->longText('notes')->nullable();

            $table->bigInteger('room_rate')->nullable();
            $table->tinyInteger('room_count')->nullable();

            $table->tinyInteger('distance')->nullable();
            $table->bigInteger('transporation_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
