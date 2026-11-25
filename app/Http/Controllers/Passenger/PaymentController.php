<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\UploadPaymentRequest;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected BookingService $bookingService
    ) {}

    public function show(Booking $booking, Request $request): View|JsonResponse
    {
        // Enforce data ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Check deadline status & expire if necessary
        $this->bookingService->expireBooking($booking);
        $booking->refresh()->load(['schedule.route', 'payments' => fn($q) => $q->latest()]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'booking' => $booking,
                    'latest_payment' => $booking->latestPayment,
                    'is_expired' => $booking->status === 'expired',
                ],
            ]);
        }

        return view('passenger.payments.show', compact('booking'));
    }

    public function uploadProof(UploadPaymentRequest $request, Booking $booking): RedirectResponse|JsonResponse
    {
        // Enforce data ownership
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $payment = $this->paymentService->submitPaymentProof(
            $booking,
            $request->validated('payment_method'),
            $request->file('proof_file'),
            (float) $request->validated('amount')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.',
                'payment' => $payment,
            ]);
        }

        return redirect()->route('passenger.bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang dalam verifikasi.');
    }
}
