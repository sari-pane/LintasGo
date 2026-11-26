<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\View\View;

class ManifestController extends Controller
{
    public function show(Schedule $schedule): View
    {
        $schedule->load([
            'route',
            'vehicle.seats' => fn ($q) => $q->orderBy('id'),
            'driver',
            'bookingSeats' => fn ($q) => $q->whereNotNull('active_seat_key')
                ->with(['booking.user', 'seat', 'ticket.checkedInBy']),
        ]);

        $activeSeatsBySeatId = $schedule->bookingSeats->keyBy('seat_id');

        $manifestSummary = [
            'total_capacity' => $schedule->vehicle->seats->count(),
            'booked_seats' => $activeSeatsBySeatId->count(),
            'confirmed_passengers' => $schedule->bookingSeats->filter(fn ($bs) => $bs->booking?->status === 'confirmed')->count(),
            'checked_in_count' => $schedule->bookingSeats->filter(fn ($bs) => $bs->ticket?->status === 'checked_in')->count(),
        ];

        return view('admin.manifest.show', compact('schedule', 'activeSeatsBySeatId', 'manifestSummary'));
    }
}
