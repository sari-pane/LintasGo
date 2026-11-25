<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TicketService
{
    /**
     * Issue e-tickets for all reserved seats in a confirmed booking.
     * Generates a unique ticket code and unique QR code identifier per seat.
     *
     * @param Booking $booking
     * @return Collection<int, Ticket>
     * @throws ValidationException
     */
    public function issueTicketsForBooking(Booking $booking): Collection
    {
        if ($booking->status !== 'confirmed') {
            throw ValidationException::withMessages([
                'booking' => 'E-tiket hanya dapat diterbitkan untuk pemesanan yang telah terkonfirmasi sah.',
            ]);
        }

        return DB::transaction(function () use ($booking) {
            $issuedTickets = new Collection();

            foreach ($booking->bookingSeats as $bookingSeat) {
                // Check if ticket already exists
                $existingTicket = Ticket::where('booking_seat_id', $bookingSeat->id)->first();
                if ($existingTicket) {
                    $issuedTickets->push($existingTicket);
                    continue;
                }

                $ticketCode = $this->generateUniqueTicketCode();
                $qrIdentifier = $this->generateUniqueQrIdentifier($bookingSeat->id);

                $ticket = new Ticket();
                $ticket->booking_seat_id = $bookingSeat->id;
                $ticket->ticket_code = $ticketCode;
                $ticket->qr_code_identifier = $qrIdentifier;
                $ticket->status = 'issued';
                $ticket->save();

                $issuedTickets->push($ticket);
            }

            return $issuedTickets;
        });
    }

    /**
     * Process check-in boarding for a passenger ticket.
     * Accepts either QR code identifier or ticket code.
     * Prevents double check-in and verifies booking confirmation.
     *
     * @param string $identifierOrCode
     * @param User $admin
     * @return Ticket
     * @throws ValidationException
     */
    public function processCheckIn(string $identifierOrCode, User $admin): Ticket
    {
        if (!$admin->isAdmin()) {
            throw ValidationException::withMessages([
                'checkin' => 'Hanya Administrator yang memiliki otorisasi melakukan proses check-in.',
            ]);
        }

        $ticket = Ticket::with(['bookingSeat.booking', 'bookingSeat.schedule.route', 'bookingSeat.seat'])
            ->where('qr_code_identifier', $identifierOrCode)
            ->orWhere('ticket_code', $identifierOrCode)
            ->first();

        if (!$ticket) {
            throw ValidationException::withMessages([
                'ticket' => 'Tiket tidak ditemukan dalam sistem LintasGo.',
            ]);
        }

        // Rule: Prevent double check-in
        if ($ticket->status === 'checked_in') {
            $checkedAt = $ticket->checked_in_at ? $ticket->checked_in_at->format('d/m/Y H:i') : '-';
            throw ValidationException::withMessages([
                'ticket' => "Tiket ini sudah pernah digunakan untuk check-in pada {$checkedAt} WIB.",
            ]);
        }

        if ($ticket->status === 'cancelled') {
            throw ValidationException::withMessages([
                'ticket' => 'Tiket ini telah dibatalkan dan tidak dapat digunakan untuk boarding.',
            ]);
        }

        // Rule: Verify parent booking is confirmed
        if ($ticket->bookingSeat->booking->status !== 'confirmed') {
            throw ValidationException::withMessages([
                'ticket' => 'Pemesanan untuk tiket ini belum berstatus terkonfirmasi.',
            ]);
        }

        DB::transaction(function () use ($ticket, $admin) {
            $ticket->checkIn($admin->id);
        });

        return $ticket->fresh();
    }

    /**
     * Generate an unpredictable unique ticket code.
     *
     * @return string
     */
    private function generateUniqueTicketCode(): string
    {
        do {
            $code = 'TIX-' . date('ymd') . '-' . strtoupper(Str::random(5));
        } while (Ticket::where('ticket_code', $code)->exists());

        return $code;
    }

    /**
     * Generate a cryptographic hash for QR Code identifier.
     * Only stores the secure identifier, never sensitive personal data.
     *
     * @param int $bookingSeatId
     * @return string
     */
    private function generateUniqueQrIdentifier(int $bookingSeatId): string
    {
        do {
            $hash = hash('sha256', $bookingSeatId . Str::random(32) . microtime(true));
        } while (Ticket::where('qr_code_identifier', $hash)->exists());

        return $hash;
    }
}
