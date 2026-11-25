<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BookingSeat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Note: 'active_seat_key' is excluded to protect the database-level double booking prevention mechanism.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'schedule_id',
        'seat_id',
        'passenger_name',
        'passenger_phone',
    ];

    /**
     * Generate and set the unique active seat key.
     */
    public function allocateSeatKey(): void
    {
        $this->active_seat_key = "{$this->schedule_id}-{$this->seat_id}";
    }

    /**
     * Release the active seat key to free the seat in MySQL unique index.
     */
    public function releaseSeatKey(): void
    {
        $this->active_seat_key = null;
        $this->save();
    }

    /**
     * Get the parent booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Get the schedule for this booking seat.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Get the physical seat.
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    /**
     * Get the ticket issued for this reserved seat.
     */
    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'booking_seat_id');
    }
}
