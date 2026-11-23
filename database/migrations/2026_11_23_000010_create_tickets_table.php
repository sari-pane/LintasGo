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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_seat_id')->unique('uk_ticket_booking_seat')->constrained('booking_seats')->onDelete('restrict');
            $table->string('ticket_code', 32)->unique();
            $table->string('qr_code_identifier', 64)->unique();
            $table->enum('status', ['issued', 'checked_in', 'cancelled'])->default('issued');
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
