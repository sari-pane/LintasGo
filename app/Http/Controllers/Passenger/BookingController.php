<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Schedule;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function history(Request $request): View|JsonResponse
    {
        $bookings = Booking::query()
            ->where('user_id', Auth::id())
            ->with(['schedule.route', 'schedule.vehicle', 'bookingSeats.seat', 'payments'])
            ->latest()
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $bookings,
            ]);
        }

        return view('passenger.bookings.history', compact('bookings'));
    }

    public function store(StoreBookingRequest $request): RedirectResponse|JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $schedule = Schedule::findOrFail($request->validated('schedule_id'));

        $booking = $this->bookingService->createBooking(
            $user,
            $schedule,
            $request->validated('passengers'),
            $request->validated('notes')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pemesanan tiket berhasil dibuat.',
                'booking' => $booking->load(['schedule.route', 'bookingSeats.seat']),
            ], 201);
        }

        return redirect()->route('passenger.bookings.show', $booking)
            ->with('success', 'Pemesanan berhasil dibuat. Silakan lakukan pembayaran sebelum batas waktu.');
    }

    public function show(Booking $booking, Request $request): View|JsonResponse
    {
        // Enforce data ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pemesanan ini.');
        }

        $booking->load([
            'schedule.route',
            'schedule.vehicle',
            'bookingSeats.seat',
            'bookingSeats.ticket',
            'payments' => fn($q) => $q->latest(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $booking,
            ]);
        }

        return view('passenger.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking, Request $request): RedirectResponse|JsonResponse
    {
        // Enforce data ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan pemesanan ini.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->bookingService->cancelBooking($booking, $user);
        $booking->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibatalkan.',
                'data' => $booking,
            ]);
        }

        return redirect()->route('passenger.bookings.show', $booking)
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }
}
