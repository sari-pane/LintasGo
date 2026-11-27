<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date, 'Asia/Jakarta')->startOfDay()
            : Carbon::today('Asia/Jakarta')->subDays(30)->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date, 'Asia/Jakarta')->endOfDay()
            : Carbon::today('Asia/Jakarta')->addDays(30)->endOfDay();

        $confirmedBookings = Booking::query()
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['schedule.route', 'schedule.vehicle', 'user'])
            ->get();

        $summary = [
            'total_revenue' => (float) $confirmedBookings->sum('total_amount'),
            'confirmed_bookings_count' => $confirmedBookings->count(),
            'total_passengers' => (int) $confirmedBookings->sum('total_passengers'),
            'pending_bookings_count' => Booking::whereIn('status', ['pending', 'waiting_verification'])->count(),
            'cancelled_or_expired_count' => Booking::whereIn('status', ['cancelled', 'expired'])->count(),
            'verified_payments_count' => Payment::where('status', 'verified')
                ->whereBetween('verified_at', [$startDate, $endDate])
                ->count(),
        ];

        // Occupancy across active/scheduled trips in date window
        $schedulesInPeriod = Schedule::query()
            ->whereBetween('departure_time', [$startDate, $endDate])
            ->with(['route', 'vehicle'])
            ->withCount(['bookingSeats as occupied_seats_count' => fn ($q) => $q->whereNotNull('active_seat_key')])
            ->orderBy('departure_time', 'asc')
            ->get();

        $totalCapacity = $schedulesInPeriod->sum(fn ($s) => $s->vehicle->total_seats ?? 0);
        $totalOccupied = $schedulesInPeriod->sum('occupied_seats_count');
        $summary['occupancy_rate'] = $totalCapacity > 0
            ? round(($totalOccupied / $totalCapacity) * 100, 1)
            : 0;

        $revenueByRoute = $confirmedBookings
            ->groupBy(fn ($b) => $b->schedule?->route ? ($b->schedule->route->origin . ' → ' . $b->schedule->route->destination) : 'Lainnya')
            ->map(fn ($group, $routeName) => [
                'route' => $routeName,
                'bookings_count' => $group->count(),
                'passengers_count' => $group->sum('total_passengers'),
                'revenue' => (float) $group->sum('total_amount'),
            ])
            ->sortByDesc('revenue')
            ->values();

        return view('admin.reports.index', [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'summary' => $summary,
            'revenueByRoute' => $revenueByRoute,
            'schedulesInPeriod' => $schedulesInPeriod,
            'recentConfirmedBookings' => $confirmedBookings->sortByDesc('created_at')->take(10),
        ]);
    }
}
