<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Note: 'status' is excluded to prevent arbitrary status modification via mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'schedule_id',
        'total_passengers',
        'total_amount',
        'payment_deadline',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_passengers' => 'integer',
            'total_amount' => 'decimal:2',
            'payment_deadline' => 'datetime',
        ];
    }

    /**
     * Mark booking as waiting for admin verification.
     */
    public function markAsWaitingVerification(): void
    {
        $this->status = 'waiting_verification';
        $this->save();
    }

    /**
     * Mark booking as confirmed.
     */
    public function markAsConfirmed(): void
    {
        $this->status = 'confirmed';
        $this->save();
    }

    /**
     * Mark booking as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Mark booking as expired.
     */
    public function markAsExpired(): void
    {
        $this->status = 'expired';
        $this->save();
    }

    /**
     * Get the user who made the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the scheduled trip for this booking.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Get seats reserved under this booking.
     */
    public function bookingSeats(): HasMany
    {
        return $this->hasMany(BookingSeat::class, 'booking_id');
    }

    /**
     * Get payment attempts for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }
}
