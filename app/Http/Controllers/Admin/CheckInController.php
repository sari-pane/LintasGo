<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CheckInRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $today = Carbon::today('Asia/Jakarta');

        $recentCheckIns = Ticket::query()
            ->where('status', 'checked_in')
            ->whereDate('checked_in_at', $today)
            ->with([
                'bookingSeat.seat',
                'bookingSeat.booking.schedule.route',
                'bookingSeat.booking.schedule.vehicle',
                'checkedInBy',
            ])
            ->latest('checked_in_at')
            ->limit(20)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $recentCheckIns,
            ]);
        }

        return view('admin.check-in.index', compact('recentCheckIns'));
    }

    public function process(CheckInRequest $request): RedirectResponse|JsonResponse
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $identifier = $request->validated('identifier');

        try {
            $ticket = $this->ticketService->processCheckIn($identifier, $admin);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Check-in berhasil! Penumpang siap naik ke armada.',
                    'ticket' => $ticket->load([
                        'bookingSeat.seat',
                        'bookingSeat.booking.schedule.route',
                        'bookingSeat.booking.schedule.vehicle',
                        'checkedInBy',
                    ]),
                ]);
            }

            return redirect()->route('admin.checkin.index')->with('success', "Check-in berhasil untuk tiket {$ticket->ticket_code} (Penumpang: {$ticket->bookingSeat->passenger_name}, Kursi: {$ticket->bookingSeat->seat->seat_number}).");
        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                throw $e;
            }
            return back()->withInput()->withErrors($e->errors());
        } catch (\InvalidArgumentException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withInput()->withErrors(['checkin' => $e->getMessage()]);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memproses check-in: ' . $e->getMessage()], 500);
            }
            return back()->withInput()->withErrors(['checkin' => 'Gagal memproses check-in: ' . $e->getMessage()]);
        }
    }
}
