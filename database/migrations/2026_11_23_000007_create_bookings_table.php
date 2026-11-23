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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 32)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('restrict');
            $table->unsignedTinyInteger('total_passengers');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'waiting_verification', 'confirmed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('payment_deadline');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
