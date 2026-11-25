<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $today = Carbon::today('Asia/Jakarta');

        $metrics = [
            'today_bookings_count' => Booking::whereDate('created_at', $today)->count(),
            'pending_verifications_count' => Payment::where('status', 'pending_verification')->count(),
            'confirmed_bookings_count' => Booking::where('status', 'confirmed')->count(),
            'total_revenue' => (float) Booking::where('status', 'confirmed')->sum('total_amount'),
            'today_schedules_count' => Schedule::whereDate('departure_time', $today)->count(),
            'today_checked_in_tickets' => Ticket::where('status', 'checked_in')->whereDate('checked_in_at', $today)->count(),
        ];

        $recentPendingPayments = Payment::query()
            ->where('status', 'pending_verification')
            ->with(['booking.user', 'booking.schedule.route'])
            ->latest()
            ->limit(5)
            ->get();

        $upcomingSchedules = Schedule::query()
            ->where('departure_time', '>=', now('Asia/Jakarta'))
            ->with(['route', 'vehicle', 'driver'])
            ->orderBy('departure_time', 'asc')
            ->limit(5)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'metrics' => $metrics,
                'recent_pending_payments' => $recentPendingPayments,
                'upcoming_schedules' => $upcomingSchedules,
            ]);
        }

        return view('admin.dashboard', compact('metrics', 'recentPendingPayments', 'upcomingSchedules'));
    }
}
