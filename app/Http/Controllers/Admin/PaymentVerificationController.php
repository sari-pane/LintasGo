<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VerifyPaymentRequest;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentVerificationController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected TicketService $ticketService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $query = Payment::query()
            ->with(['booking.user', 'booking.schedule.route', 'booking.schedule.vehicle'])
            ->latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } elseif (! $request->filled('status')) {
            // Default show pending verification first
            $query->where('status', 'pending_verification');
        }

        $payments = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $payments]);
        }

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment, Request $request): View|JsonResponse
    {
        $payment->load([
            'booking.user',
            'booking.schedule.route',
            'booking.bookingSeats.seat',
            'verifier',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $payment]);
        }

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(VerifyPaymentRequest $request, Payment $payment): RedirectResponse|JsonResponse
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $isApproved = $request->validated('action') === 'approve';
        $rejectionReason = $request->validated('rejection_reason');

        if ($isApproved) {
            $this->paymentService->verifyPayment($payment, $admin);
        } else {
            $this->paymentService->rejectPayment($payment, $admin, $rejectionReason ?? 'Pembayaran tidak memenuhi syarat.');
        }

        $payment->refresh();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $isApproved ? 'Pembayaran berhasil disetujui dan e-tiket telah diterbitkan.' : 'Pembayaran telah ditolak.',
                'payment' => $payment->load(['booking.tickets']),
            ]);
        }

        $message = $isApproved
            ? 'Pembayaran berhasil diverifikasi! E-tiket untuk seluruh kursi penumpang telah diterbitkan.'
            : 'Pembayaran telah ditolak. Alasan: ' . $rejectionReason;

        return redirect()->route('admin.payments.index')->with('success', $message);
    }

    /**
     * Securely stream private payment proof to authorized admin.
     * Prevents direct URL access from public internet.
     */
    public function streamProof(Payment $payment): StreamedResponse
    {
        if (empty($payment->proof_path) || !Storage::disk('local')->exists($payment->proof_path)) {
            abort(404, 'Berkas bukti pembayaran tidak ditemukan pada penyimpanan privat.');
        }

        return Storage::disk('local')->response(
            $payment->proof_path,
            "bukti-pembayaran-{$payment->booking->booking_code}-attempt-{$payment->attempt_number}"
        );
    }
}
