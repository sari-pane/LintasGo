<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ScheduleService
{
    /**
     * Search available schedules based on origin, destination, and date.
     *
     * @param string $origin
     * @param string $destination
     * @param string $date
     * @return Collection<int, Schedule>
     */
    public function searchSchedules(string $origin, string $destination, string $date): Collection
    {
        $parsedDate = Carbon::parse($date)->toDateString();

        return Schedule::with(['route', 'vehicle', 'driver'])
            ->whereHas('route', function ($query) use ($origin, $destination) {
                $query->where('origin', 'like', "%{$origin}%")
                    ->where('destination', 'like', "%{$destination}%")
                    ->where('is_active', true);
            })
            ->whereHas('vehicle', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('driver', function ($query) {
                $query->where('status', 'active');
            })
            ->where('status', 'scheduled')
            ->whereDate('departure_time', $parsedDate)
            ->where('departure_time', '>', now())
            ->orderBy('departure_time', 'asc')
            ->get();
    }

    /**
     * Get seat layout for a schedule along with availability.
     *
     * @param Schedule $schedule
     * @return array<int, array{id: int, seat_number: string, seat_position: string, is_available: bool}>
     */
    public function getSeatLayoutWithAvailability(Schedule $schedule): array
    {
        $vehicle = $schedule->vehicle;
        $seats = $vehicle->seats()->orderBy('id')->get();

        // Get occupied seat IDs from active booking seats (where active_seat_key is NOT NULL)
        $occupiedSeatIds = $schedule->bookingSeats()
            ->whereNotNull('active_seat_key')
            ->pluck('seat_id')
            ->flip()
            ->all();

        return $seats->map(function ($seat) use ($occupiedSeatIds) {
            return [
                'id' => $seat->id,
                'seat_number' => $seat->seat_number,
                'seat_position' => $seat->seat_position,
                'is_available' => !isset($occupiedSeatIds[$seat->id]),
            ];
        })->toArray();
    }

    /**
     * Check if a vehicle or driver has conflicting active schedules within the time window.
     * Includes operational buffer time (default 60 minutes).
     *
     * @param int $vehicleId
     * @param int $driverId
     * @param Carbon|string $departureTime
     * @param Carbon|string $arrivalTime
     * @param int|null $excludeScheduleId
     * @param int $bufferMinutes
     * @return void
     * @throws ValidationException
     */
    public function assertNoConflict(
        int $vehicleId,
        int $driverId,
        Carbon|string $departureTime,
        Carbon|string $arrivalTime,
        ?int $excludeScheduleId = null,
        int $bufferMinutes = 60
    ): void {
        $dep = Carbon::parse($departureTime)->subMinutes($bufferMinutes);
        $arr = Carbon::parse($arrivalTime)->addMinutes($bufferMinutes);

        // Check vehicle conflict
        $conflictingVehicle = Schedule::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'boarding', 'departed'])
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
            ->where(function ($query) use ($dep, $arr) {
                $query->where('departure_time', '<', $arr)
                    ->where('arrival_time', '>', $dep);
            })
            ->first();

        if ($conflictingVehicle) {
            $vehicle = Vehicle::find($vehicleId);
            $plate = $vehicle ? $vehicle->plate_number : "ID #{$vehicleId}";
            throw ValidationException::withMessages([
                'vehicle_id' => "Armada [{$plate}] memiliki penugasan perjalanan yang bertabrakan pada rentang waktu tersebut (termasuk buffer {$bufferMinutes} menit).",
            ]);
        }

        // Check driver conflict
        $conflictingDriver = Schedule::where('driver_id', $driverId)
            ->whereIn('status', ['scheduled', 'boarding', 'departed'])
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
            ->where(function ($query) use ($dep, $arr) {
                $query->where('departure_time', '<', $arr)
                    ->where('arrival_time', '>', $dep);
            })
            ->first();

        if ($conflictingDriver) {
            $driver = Driver::find($driverId);
            $driverName = $driver ? $driver->name : "ID #{$driverId}";
            throw ValidationException::withMessages([
                'driver_id' => "Sopir [{$driverName}] memiliki penugasan perjalanan yang bertabrakan pada rentang waktu tersebut (termasuk buffer {$bufferMinutes} menit).",
            ]);
        }
    }
}
