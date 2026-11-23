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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('restrict');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('restrict');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('price', 12, 2);
            $table->enum('status', ['scheduled', 'boarding', 'departed', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index(['route_id', 'departure_time'], 'idx_route_departure');
            $table->index(['vehicle_id', 'departure_time', 'arrival_time'], 'idx_vehicle_schedule');
            $table->index(['driver_id', 'departure_time', 'arrival_time'], 'idx_driver_schedule');
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
