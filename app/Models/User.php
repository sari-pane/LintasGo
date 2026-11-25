<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * Note: 'role' is intentionally excluded to prevent privilege escalation via mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a passenger.
     */
    public function isPassenger(): bool
    {
        return $this->role === 'passenger';
    }

    /**
     * Explicitly assign a role through a controlled backend process.
     */
    public function assignRole(string $role): void
    {
        $this->role = $role;
        $this->save();
    }

    /**
     * Get bookings made by the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get payments verified by this admin user.
     */
    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    /**
     * Get tickets checked-in by this admin user.
     */
    public function checkedInTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'checked_in_by');
    }
}
