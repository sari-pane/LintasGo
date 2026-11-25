<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Note: 'status', 'verified_by', and 'verified_at' are excluded to prevent unauthorized payment validation via mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'attempt_number',
        'payment_method',
        'amount',
        'proof_path',
        'rejection_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Mark payment as submitted by passenger, awaiting verification.
     */
    public function markAsPendingVerification(): void
    {
        $this->status = 'pending_verification';
        $this->save();
    }

    /**
     * Verify payment through authorized admin process.
     */
    public function verify(int $adminId): void
    {
        $this->status = 'verified';
        $this->verified_by = $adminId;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Reject payment through authorized admin process.
     */
    public function reject(int $adminId, ?string $reason = null): void
    {
        $this->status = 'rejected';
        $this->verified_by = $adminId;
        $this->verified_at = now();
        $this->rejection_reason = $reason;
        $this->save();
    }

    /**
     * Get the booking this payment attempt belongs to.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Get the admin user who verified the payment.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
