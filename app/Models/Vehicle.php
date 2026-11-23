<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'plate_number',
        'brand',
        'total_seats',
        'facility',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_seats' => 'integer',
        ];
    }

    /**
     * Get physical seats of the vehicle.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class, 'vehicle_id');
    }

    /**
     * Get schedules assigned to this vehicle.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'vehicle_id');
    }
}
