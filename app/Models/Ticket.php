<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Note: 'status', 'checked_in_by', and 'checked_in_at' are excluded to prevent unauthorized ticket validation via mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_seat_id',
        'ticket_code',
        'qr_code_identifier',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checked_in_at' => 'datetime',
        ];
    }

    /**
     * Perform ticket check-in through authorized admin process.
     */
    public function checkIn(int $adminId): void
    {
        $this->status = 'checked_in';
        $this->checked_in_by = $adminId;
        $this->checked_in_at = now();
        $this->save();
    }

    /**
     * Cancel the ticket.
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();
    }

    /**
     * Get the booking seat this ticket was issued for.
     */
    public function bookingSeat(): BelongsTo
    {
        return $this->belongsTo(BookingSeat::class, 'booking_seat_id');
    }

    /**
     * Get the admin user who performed the check-in.
     */
    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Alias for checkedInBy.
     */
    public function checker(): BelongsTo
    {
        return $this->checkedInBy();
    }
}
