@extends('layouts.passenger', [
    'title' => 'Pembayaran Pemesanan ' . $booking->booking_code . ' — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Pembayaran Tiket Travel')
@section('header-subtext', 'Kode Booking ' . $booking->booking_code . ' • Total Tagihan Rp ' . number_format($booking->total_amount, 0, ',', '.'))

@section('main-content')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <a href="{{ route('pemesanan.show', $booking) }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon transition">
            ← Kembali ke Detail Pemesanan
        </a>
        <span class="soft-card px-4 py-2 rounded-full text-xs font-bold text-maroon">
            Batas Waktu: {{ $booking->payment_deadline->locale('id')->isoFormat('D MMM Y • HH:mm') }} WIB
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <!-- INSTRUKSI QRIS & TRANSFER BANK (6 KOLOM) -->
        <div class="lg:col-span-6 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Instruksi Pembayaran Manual</span>
            <h2 class="font-display text-2xl text-ink">Pilih Metode Transfer</h2>

            <div class="p-4 rounded-2xl bg-white border border-gold/35 flex flex-col sm:flex-row items-center gap-4">
                <div class="p-3 bg-cream rounded-2xl border border-gold/40 shrink-0">
                    <!-- QRIS Code Visual -->
                    <svg class="w-28 h-28 text-ink" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M10,10 h25 v25 h-25 z M15,15 v15 h15 v-15 z M19,19 h7 v7 h-7 z"/>
                        <path d="M65,10 h25 v25 h-25 z M70,15 v15 h15 v-15 z M74,19 h7 v7 h-7 z"/>
                        <path d="M10,65 h25 v25 h-25 z M15,70 v15 h15 v-15 z M19,74 h7 v7 h-7 z"/>
                        <rect x="42" y="10" width="6" height="6"/><rect x="52" y="10" width="6" height="6"/>
                        <rect x="42" y="22" width="12" height="6"/><rect x="40" y="34" width="6" height="12"/>
                        <rect x="10" y="42" width="12" height="6"/><rect x="28" y="42" width="6" height="14"/>
                        <rect x="48" y="48" width="10" height="10"/><rect x="64" y="42" width="12" height="6"/>
                        <rect x="82" y="42" width="8" height="12"/><rect x="42" y="64" width="6" height="12"/>
                        <rect x="54" y="64" width="12" height="6"/><rect x="72" y="60" width="6" height="14"/>
                        <rect x="82" y="68" width="8" height="8"/><rect x="42" y="82" width="16" height="8"/>
                    </svg>
                </div>
                <div class="text-xs space-y-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-maroon text-cream text-[10px] font-bold">QRIS LintasGo Official</span>
                    <p class="font-bold text-ink text-sm pt-1">PT LintasGo Transportasi Nusantara</p>
                    <p class="text-ink/70">NMID: ID1020268899001 • Scan menggunakan aplikasi e-wallet atau mobile banking Anda.</p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white border border-gold/35 text-xs space-y-2">
                <span class="font-bold text-maroon block">Rekening Transfer Bank Manual</span>
                <div class="flex justify-between py-1 border-b border-cream">
                    <span class="text-ink/70">Bank BCA</span>
                    <span class="font-mono font-bold text-ink">8840-1299-4401 (a.n. PT LintasGo)</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-ink/70">Bank Mandiri</span>
                    <span class="font-mono font-bold text-ink">143-00-8899123-4 (a.n. PT LintasGo)</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-cream border border-gold/35 flex items-center justify-between">
                <span class="text-xs font-semibold text-ink/75">Nominal yang Harus Dibayar</span>
                <span class="font-display text-2xl text-maroon">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- FORM UNGGAH BUKTI BAYAR (6 KOLOM) -->
        <div class="lg:col-span-6 soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
            <span class="text-[11px] font-bold uppercase tracking-wider text-maroon">Konfirmasi Pembayaran</span>
            <h3 class="font-display text-2xl text-ink mb-4">Unggah Bukti Pembayaran</h3>

            @if ($booking->status !== 'pending')
                <div class="p-4 rounded-2xl bg-mist/60 border border-dusk/40 text-xs text-ink space-y-2">
                    <p class="font-bold">Status Pemesanan: {{ strtoupper(str_replace('_', ' ', $booking->status)) }}</p>
                    <p>Pengunggahan bukti hanya tersedia saat pemesanan berstatus <strong>pending</strong>.</p>
                    <a href="{{ route('pemesanan.show', $booking) }}" class="inline-block px-4 py-2 rounded-xl bg-maroon text-cream font-bold">Lihat Detail Pemesanan</a>
                </div>
            @else
                <form method="POST" action="{{ route('pemesanan.bayar.store', $booking) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-semibold text-ink mb-1.5">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <label class="p-3 rounded-2xl bg-white border border-gold/40 flex items-center gap-2 cursor-pointer has-[:checked]:border-maroon has-[:checked]:bg-maroon/5">
                                <input type="radio" name="payment_method" value="qris_manual" checked class="text-maroon focus:ring-maroon">
                                <span class="font-bold text-ink">QRIS Manual</span>
                            </label>
                            <label class="p-3 rounded-2xl bg-white border border-gold/40 flex items-center gap-2 cursor-pointer has-[:checked]:border-maroon has-[:checked]:bg-maroon/5">
                                <input type="radio" name="payment_method" value="bank_transfer_manual" class="text-maroon focus:ring-maroon">
                                <span class="font-bold text-ink">Transfer Bank</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="amount" class="block font-semibold text-ink mb-1">Nominal Transfer (Rp)</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount', (int) $booking->total_amount) }}" required min="1" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 font-bold text-ink focus:outline-none focus:border-maroon">
                    </div>

                    <div>
                        <label for="proof_file" class="block font-semibold text-ink mb-1">Berkas Bukti Pembayaran (JPG, PNG, PDF • Maks 2 MB)</label>
                        <input type="file" id="proof_file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-ink file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:bg-maroon file:text-cream file:font-semibold cursor-pointer">
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
                        Kirim Bukti Pembayaran →
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
