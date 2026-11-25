<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Booking::query()->with(['user', 'schedule.route', 'schedule.vehicle']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->latest()->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $bookings]);
        }

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking, Request $request): View|JsonResponse
    {
        $booking->load([
            'user',
            'schedule.route',
            'schedule.vehicle',
            'schedule.driver',
            'bookingSeats.seat',
            'bookingSeats.ticket',
            'payments' => fn($q) => $q->latest(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $booking]);
        }

        return view('admin.bookings.show', compact('booking'));
    }
}
