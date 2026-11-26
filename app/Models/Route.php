<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'origin',
        'destination',
        'distance_km',
        'estimated_duration_minutes',
        'description',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'distance_km' => 'decimal:2',
            'estimated_duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get schedules assigned to this route.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'route_id');
    }

    /**
     * Alias accessor for origin_city.
     */
    public function getOriginCityAttribute(): string
    {
        return $this->origin;
    }

    /**
     * Alias accessor for destination_city.
     */
    public function getDestinationCityAttribute(): string
    {
        return $this->destination;
    }

    /**
     * Formatted estimated travel duration label.
     */
    public function getEstimatedDurationAttribute(): string
    {
        $minutes = (int) $this->estimated_duration_minutes;
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours > 0 && $mins > 0) {
            return "± {$hours} Jam {$mins} Menit";
        }
        if ($hours > 0) {
            return "± {$hours} Jam";
        }

        return "± {$mins} Menit";
    }
}
