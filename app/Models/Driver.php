<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'license_number',
        'status',
    ];

    /**
     * Get schedules assigned to this driver.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'driver_id');
    }
}
