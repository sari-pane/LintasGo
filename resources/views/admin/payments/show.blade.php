@extends('layouts.admin')

@section('page-title', 'Detail & Stempel Verifikasi Pembayaran')

@section('admin-content')
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.payments.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon">← Kembali ke Antrean Pembayaran</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- BUKTI TRANSFER PRIVAT (7 KOLOM) -->
        <div class="lg:col-span-7 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-xl text-ink">Berkas Bukti Pembayaran (Privat)</h2>
                <span class="font-mono text-xs font-bold text-maroon">{{ $payment->booking->booking_code }} • Attempt #{{ $payment->attempt_number }}</span>
            </div>

            <div class="relative rounded-3xl overflow-hidden bg-white border-2 border-dashed border-gold/60 min-h-[280px] flex items-center justify-center p-4">
                @if ($payment->proof_path && \Illuminate\Support\Str::endsWith(strtolower($payment->proof_path), '.pdf'))
                    <div class="text-center space-y-2 text-xs">
                        <p class="font-bold text-ink">Dokumen Bukti Berformat PDF</p>
                        <a href="{{ route('admin.payments.proof', $payment) }}" target="_blank" class="inline-block px-4 py-2 rounded-xl bg-maroon text-cream font-bold">Buka PDF Bukti Bayar ↗</a>
                    </div>
                @elseif ($payment->proof_path)
                    <img src="{{ route('admin.payments.proof', $payment) }}" alt="Bukti Pembayaran" class="max-h-[380px] rounded-2xl object-contain">
                @else
                    <span class="text-xs text-ink/60">Tidak ada berkas bukti terlampir.</span>
                @endif

                @if ($payment->status !== 'pending_verification')
                    <div class="absolute inset-0 flex items-center justify-center bg-cream/55 backdrop-blur-[1px] pointer-events-none">
                        <div class="stamp-badge px-5 py-2 rounded-xl font-display text-3xl uppercase {{ $payment->status === 'verified' ? 'text-maroon border-maroon bg-cream/90' : 'text-vred border-vred bg-cream/90' }}">
                            {{ $payment->status === 'verified' ? 'VERIFIED' : 'REJECTED' }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- RINCIAN & AKSI STEMPEL ADMIN (5 KOLOM) -->
        <div class="lg:col-span-5 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4 text-xs">
            <h3 class="font-display text-2xl text-ink">Keputusan Verifikasi</h3>

            <div class="p-4 rounded-2xl bg-white border border-gold/30 space-y-2">
                <div class="flex justify-between"><span class="text-ink/65">Penumpang:</span><strong>{{ $payment->booking->user->name }}</strong></div>
                <div class="flex justify-between"><span class="text-ink/65">Jurusan:</span><strong>{{ $payment->booking->schedule->route->origin }} → {{ $payment->booking->schedule->route->destination }}</strong></div>
                <div class="flex justify-between"><span class="text-ink/65">Kursi:</span><strong class="text-maroon">{{ $payment->booking->bookingSeats->map(fn($bs) => $bs->seat->seat_number)->join(', ') }}</strong></div>
                <div class="flex justify-between"><span class="text-ink/65">Tagihan Sistem:</span><strong>Rp {{ number_format($payment->booking->total_amount, 0, ',', '.') }}</strong></div>
                <div class="flex justify-between border-t border-cream pt-2"><span class="text-ink/65">Nominal Diklaim:</span><strong class="text-maroon text-sm">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
            </div>

            @if ($payment->status === 'pending_verification')
                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow-lg transition cursor-pointer">
                        ✓ Setujui & Terbitkan E-Tiket (VERIFIED)
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="pt-3 border-t border-gold/25 space-y-2.5">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <label class="block font-semibold text-ink">Alasan Penolakan (Jika Ditolak)</label>
                    <input type="text" name="rejection_reason" required placeholder="Contoh: Nominal kurang / struk buram" class="w-full px-3.5 py-2.5 rounded-2xl bg-white border border-gold/40">
                    <button type="submit" class="w-full py-3 rounded-2xl bg-ink hover:bg-vred text-cream font-bold transition cursor-pointer">
                        ✕ Tolak Bukti Pembayaran (REJECTED)
                    </button>
                </form>
            @else
                <div class="p-4 rounded-2xl bg-cream border border-gold/30 space-y-1">
                    <p class="font-bold text-ink">Status: {{ strtoupper($payment->status) }}</p>
                    <p class="text-ink/70">Diverifikasi oleh: {{ $payment->verifiedBy?->name ?? '-' }} ({{ $payment->verified_at?->format('d M Y H:i') }})</p>
                    @if ($payment->rejection_reason)
                        <p class="text-vred font-semibold">Alasan: {{ $payment->rejection_reason }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
