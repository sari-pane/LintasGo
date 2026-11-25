<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    /**
     * Submit manual payment proof for a booking.
     *
     * @param Booking $booking
     * @param string $paymentMethod
     * @param UploadedFile $proofFile
     * @param float $amount
     * @return Payment
     * @throws ValidationException
     */
    public function submitPaymentProof(
        Booking $booking,
        string $paymentMethod,
        UploadedFile $proofFile,
        float $amount
    ): Payment {
        // Rule 1: Booking must be in pending status
        if ($booking->status !== 'pending') {
            throw ValidationException::withMessages([
                'payment' => "Pemesanan dengan status [{$booking->status}] tidak menerima pengunggahan bukti pembayaran.",
            ]);
        }

        // Rule 2: Cannot upload if payment deadline has already passed
        if (now()->isAfter($booking->payment_deadline)) {
            throw ValidationException::withMessages([
                'payment' => 'Batas waktu pembayaran telah berakhir. Pemesanan ini tidak dapat diproses lagi.',
            ]);
        }

        // Rule 3: Only one attempt waiting for verification allowed at a time
        $hasPendingVerification = $booking->payments()
            ->where('status', 'pending_verification')
            ->exists();

        if ($hasPendingVerification) {
            throw ValidationException::withMessages([
                'payment' => 'Bukti pembayaran sebelumnya masih dalam antrean verifikasi Admin. Harap tunggu konfirmasi.',
            ]);
        }

        // Rule 4: Compute next attempt number
        $lastAttempt = $booking->payments()->max('attempt_number') ?? 0;
        $attemptNumber = $lastAttempt + 1;

        // Rule 5: Store proof file in private storage
        $extension = $proofFile->getClientOriginalExtension();
        $safeFilename = Str::random(40) . '.' . $extension;
        $storedPath = $proofFile->storeAs('payments/' . $booking->booking_code, $safeFilename, 'local');

        return DB::transaction(function () use ($booking, $attemptNumber, $paymentMethod, $amount, $storedPath) {
            $payment = new Payment();
            $payment->booking_id = $booking->id;
            $payment->attempt_number = $attemptNumber;
            $payment->payment_method = $paymentMethod;
            $payment->amount = $amount;
            $payment->proof_path = $storedPath;
            $payment->status = 'pending_verification';
            $payment->save();

            // Transition booking to waiting_verification
            $booking->markAsWaitingVerification();

            return $payment;
        });
    }

    /**
     * Verify and approve a payment by an Administrator.
     * Transitions booking to confirmed and issues e-tickets.
     *
     * @param Payment $payment
     * @param User $admin
     * @return void
     * @throws ValidationException
     */
    public function verifyPayment(Payment $payment, User $admin): void
    {
        if (!$admin->isAdmin()) {
            throw ValidationException::withMessages([
                'payment' => 'Hanya Administrator yang memiliki wewenang memverifikasi pembayaran.',
            ]);
        }

        if ($payment->status !== 'pending_verification') {
            throw ValidationException::withMessages([
                'payment' => "Pembayaran dengan status [{$payment->status}] tidak dapat diverifikasi.",
            ]);
        }

        DB::transaction(function () use ($payment, $admin) {
            // Mark payment as verified
            $payment->verify($admin->id);

            // Transition booking to confirmed
            $booking = $payment->booking;
            $booking->markAsConfirmed();

            // Issue e-tickets for all booked seats
            $this->ticketService->issueTicketsForBooking($booking);
        });
    }

    /**
     * Reject a payment attempt with a reason.
     * If the payment deadline has passed, the booking expires and seats are released.
     * If the payment deadline is still valid, booking returns to pending allowing re-upload.
     *
     * @param Payment $payment
     * @param User $admin
     * @param string $reason
     * @return void
     * @throws ValidationException
     */
    public function rejectPayment(Payment $payment, User $admin, string $reason): void
    {
        if (!$admin->isAdmin()) {
            throw ValidationException::withMessages([
                'payment' => 'Hanya Administrator yang berwenang menolak pembayaran.',
            ]);
        }

        if ($payment->status !== 'pending_verification') {
            throw ValidationException::withMessages([
                'payment' => "Pembayaran dengan status [{$payment->status}] tidak dapat diproses penolakan.",
            ]);
        }

        DB::transaction(function () use ($payment, $admin, $reason) {
            $payment->reject($admin->id, $reason);

            $booking = $payment->booking;

            // If rejected after deadline, booking expires and seats are freed
            if (now()->isAfter($booking->payment_deadline)) {
                $booking->markAsExpired();

                foreach ($booking->bookingSeats as $seat) {
                    $seat->releaseSeatKey();
                }
            } else {
                // If deadline is still valid, return booking to pending so passenger can re-upload
                $booking->status = 'pending';
                $booking->save();
            }
        });
    }
}
