<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        try {
            $publicRoutes = Route::query()
                ->where('is_active', true)
                ->withMin(['schedules' => fn ($q) => $q->where('status', 'scheduled')->where('departure_time', '>', now())], 'price')
                ->orderBy('origin')
                ->orderBy('destination')
                ->get();

            $origins = $publicRoutes->pluck('origin')->unique()->values();
            $destinations = $publicRoutes->pluck('destination')->unique()->values();

            $schedules = Schedule::query()
                ->with(['route', 'vehicle.seats', 'driver', 'bookingSeats.booking'])
                ->where('status', 'scheduled')
                ->where('departure_time', '>', now())
                ->orderBy('departure_time')
                ->get();

            $tripsData = $schedules->map(function ($s) {
                $vKey = 'hiace';
                if ($s->vehicle) {
                    $brandLower = strtolower($s->vehicle->brand);
                    if (str_contains($brandLower, 'innova') || $s->vehicle->total_seats <= 7) {
                        $vKey = 'innova';
                    } elseif (str_contains($brandLower, 'elf') || $s->vehicle->total_seats > 14) {
                        $vKey = 'elf';
                    } else {
                        $vKey = 'hiace';
                    }
                }

                $takenSeatNumbers = $s->bookingSeats
                    ->filter(fn ($bs) => !in_array($bs->booking?->status, ['cancelled', 'expired']))
                    ->map(function ($bs) {
                        return $bs->seat ? (int) preg_replace('/\D/', '', $bs->seat->seat_number) : (int) $bs->seat_number;
                    })
                    ->filter(fn ($n) => $n > 0)
                    ->values()
                    ->all();

                return [
                    'id' => $s->id,
                    'from' => $s->route?->origin ?? '',
                    'to' => $s->route?->destination ?? '',
                    'dep' => $s->departure_time ? $s->departure_time->format('H.i') : '08.00',
                    'arr' => $s->arrival_time ? $s->arrival_time->format('H.i') : '12.00',
                    'date' => $s->departure_time ? $s->departure_time->format('Y-m-d') : now()->format('Y-m-d'),
                    'v' => $vKey,
                    'price' => (int) $s->price,
                    'taken' => $takenSeatNumbers,
                ];
            })->values();
        } catch (\Throwable) {
            $publicRoutes = collect();
            $origins = collect();
            $destinations = collect();
            $schedules = collect();
            $tripsData = collect();
        }

        $authUser = null;
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $authUser = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'role' => $user->role,
                'isAdmin' => $user->isAdmin(),
            ];
        }

        return view('welcome', compact('publicRoutes', 'origins', 'destinations', 'schedules', 'tripsData', 'authUser'));
    }
}
