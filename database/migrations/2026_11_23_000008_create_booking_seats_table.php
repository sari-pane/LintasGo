<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('restrict');
            $table->foreignId('seat_id')->constrained('seats')->onDelete('restrict');
            $table->string('passenger_name', 150);
            $table->string('passenger_phone', 20)->nullable();
            $table->string('active_seat_key', 64)->nullable()->unique('uk_active_seat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
