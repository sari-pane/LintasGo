<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $tickets = Ticket::query()
            ->whereHas('bookingSeat.booking', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with([
                'bookingSeat.seat',
                'bookingSeat.booking.schedule.route',
                'bookingSeat.booking.schedule.vehicle',
            ])
            ->latest()
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $tickets,
            ]);
        }

        return view('passenger.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket, Request $request): View|JsonResponse
    {
        // Enforce passenger ownership via booking
        if ($ticket->bookingSeat?->booking?->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat tiket ini.');
        }

        $ticket->load([
            'bookingSeat.seat',
            'bookingSeat.booking.schedule.route',
            'bookingSeat.booking.schedule.vehicle',
            'bookingSeat.booking.schedule.driver',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ticket' => $ticket,
            ]);
        }

        return view('passenger.tickets.show', compact('ticket'));
    }
}
