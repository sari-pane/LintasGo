<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a new booking for a passenger.
     * Enforces: max 3 seats, seat ownership by schedule vehicle, server-side price calculation,
     * database pessimistic row locking, and unique active_seat_key.
     *
     * @param User $user
     * @param Schedule $schedule
     * @param array<int, array{seat_id: int, name: string, phone?: string|null}> $passengers
     * @param string|null $notes
     * @return Booking
     * @throws ValidationException
     */
    public function createBooking(User $user, Schedule $schedule, array $passengers, ?string $notes = null): Booking
    {
        // Rule 1: Maximum 3 seats per booking transaction
        $count = count($passengers);
        if ($count < 1 || $count > 3) {
            throw ValidationException::withMessages([
                'passengers' => 'Pemesanan harus berisi minimal 1 dan maksimal 3 kursi dalam satu transaksi.',
            ]);
        }

        // Rule 2: Ensure schedule is available
        if ($schedule->status !== 'scheduled' || $schedule->departure_time->isPast()) {
            throw ValidationException::withMessages([
                'schedule_id' => 'Jadwal perjalanan tidak tersedia untuk pemesanan tiket.',
            ]);
        }

        // Rule 3: Check for duplicate seat selections within the request itself
        $requestedSeatIds = array_column($passengers, 'seat_id');
        if (count($requestedSeatIds) !== count(array_unique($requestedSeatIds))) {
            throw ValidationException::withMessages([
                'seats' => 'Tidak boleh memilih kursi yang sama lebih dari satu kali dalam satu pemesanan.',
            ]);
        }

        // Rule 4: Verify that all selected seats genuinely belong to the vehicle assigned to this schedule
        $validSeatCount = Seat::where('vehicle_id', $schedule->vehicle_id)
            ->whereIn('id', $requestedSeatIds)
            ->count();

        if ($validSeatCount !== $count) {
            throw ValidationException::withMessages([
                'seats' => 'Satu atau lebih kursi yang dipilih bukan merupakan bagian dari armada jadwal perjalanan ini.',
            ]);
        }

        // Rule 5: Calculate total price strictly on the server
        $totalAmount = (float) $schedule->price * $count;

        // Execute inside database transaction with pessimistic row locking
        try {
            return DB::transaction(function () use ($user, $schedule, $passengers, $requestedSeatIds, $count, $totalAmount, $notes) {
                // Pessimistic locking: Check if any requested seat is actively reserved
                $occupiedSeatIds = BookingSeat::where('schedule_id', $schedule->id)
                    ->whereIn('seat_id', $requestedSeatIds)
                    ->whereNotNull('active_seat_key')
                    ->lockForUpdate()
                    ->pluck('seat_id')
                    ->all();

                if (!empty($occupiedSeatIds)) {
                    throw ValidationException::withMessages([
                        'seats' => 'Satu atau lebih kursi yang Anda pilih baru saja dipesan oleh pengguna lain. Silakan pilih kursi lain.',
                    ]);
                }

                // Generate unique booking code
                $bookingCode = $this->generateUniqueBookingCode();

                // Create booking record
                $booking = new Booking();
                $booking->booking_code = $bookingCode;
                $booking->user_id = $user->id;
                $booking->schedule_id = $schedule->id;
                $booking->total_passengers = $count;
                $booking->total_amount = $totalAmount;
                $booking->status = 'pending';
                $booking->payment_deadline = now()->addMinutes(60);
                $booking->notes = $notes;
                $booking->save();

                // Create booking seats with active_seat_key
                foreach ($passengers as $p) {
                    $bookingSeat = new BookingSeat();
                    $bookingSeat->booking_id = $booking->id;
                    $bookingSeat->schedule_id = $schedule->id;
                    $bookingSeat->seat_id = $p['seat_id'];
                    $bookingSeat->passenger_name = $p['name'];
                    $bookingSeat->passenger_phone = $p['phone'] ?? null;
                    $bookingSeat->allocateSeatKey(); // Sets {schedule_id}-{seat_id}
                    $bookingSeat->save();
                }

                return $booking->load(['bookingSeats.seat', 'schedule.route', 'schedule.vehicle']);
            });
        } catch (QueryException $e) {
            // MySQL error 1062 = Duplicate entry for active_seat_key
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062) {
                throw ValidationException::withMessages([
                    'seats' => 'Kursi yang Anda pilih baru saja diambil oleh pemesan lain.',
                ]);
            }
            throw $e;
        }
    }

    /**
     * Cancel an active booking and release its seats.
     *
     * @param Booking $booking
     * @param User $actor
     * @param string|null $reason
     * @return void
     * @throws ValidationException
     */
    public function cancelBooking(Booking $booking, User $actor, ?string $reason = null): void
    {
        // Only owner or admin can cancel
        if ($actor->id !== $booking->user_id && !$actor->isAdmin()) {
            throw ValidationException::withMessages([
                'booking' => 'Anda tidak memiliki hak akses untuk membatalkan pemesanan ini.',
            ]);
        }

        if (!in_array($booking->status, ['pending', 'waiting_verification'])) {
            throw ValidationException::withMessages([
                'booking' => "Pemesanan dengan status [{$booking->status}] tidak dapat dibatalkan.",
            ]);
        }

        DB::transaction(function () use ($booking) {
            $booking->markAsCancelled();

            // Release all active seats so they become available in MySQL unique index
            foreach ($booking->bookingSeats as $seat) {
                $seat->releaseSeatKey();
            }
        });
    }

    /**
     * Expire a pending booking when payment deadline passes and release its seats.
     *
     * @param Booking $booking
     * @return void
     */
    public function expireBooking(Booking $booking): void
    {
        if ($booking->status === 'pending' && now()->isAfter($booking->payment_deadline)) {
            DB::transaction(function () use ($booking) {
                $booking->markAsExpired();

                foreach ($booking->bookingSeats as $seat) {
                    $seat->releaseSeatKey();
                }
            });
        }
    }

    /**
     * Generate an unpredictable, unique booking code.
     *
     * @return string
     */
    private function generateUniqueBookingCode(): string
    {
        do {
            $code = 'LG-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }
}
