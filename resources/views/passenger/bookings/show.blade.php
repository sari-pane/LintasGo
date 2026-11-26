@extends('layouts.passenger', [
    'title' => 'Detail Pemesanan ' . $booking->booking_code . ' — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Detail Pemesanan ' . $booking->booking_code)
@section('header-subtext', $booking->schedule->route->origin . ' → ' . $booking->schedule->route->destination . ' • ' . $booking->schedule->departure_time->locale('id')->isoFormat('dddd, D MMMM Y • HH:mm') . ' WIB')

@section('main-content')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <a href="{{ route('pemesanan.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon transition">
            ← Kembali ke Riwayat Pemesanan
        </a>
        @if ($booking->status === 'pending')
            <span class="soft-card px-4 py-2 rounded-full text-xs font-bold text-vred">
                ⏳ Batas Bayar: {{ $booking->payment_deadline->locale('id')->isoFormat('D MMM Y • HH:mm') }} WIB
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <!-- INFO PERJALANAN & KURSI (8 KOLOM) -->
        <div class="lg:col-span-8 space-y-5">
            <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gold/25 pb-3">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Informasi Perjalanan</span>
                        <h2 class="font-display text-2xl text-ink">
                            {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                        </h2>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : ($booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-mist text-ink') }}">
                        Status: {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3 rounded-2xl bg-white/85 border border-gold/25">
                        <span class="text-ink/60 block text-[10px]">Kode Booking</span>
                        <strong class="font-mono text-maroon">{{ $booking->booking_code }}</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/85 border border-gold/25">
                        <span class="text-ink/60 block text-[10px]">Armada</span>
                        <strong class="text-ink">{{ $booking->schedule->vehicle->brand }}</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/85 border border-gold/25">
                        <span class="text-ink/60 block text-[10px]">Plat Nomor</span>
                        <strong class="text-ink">{{ $booking->schedule->vehicle->plate_number }}</strong>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/85 border border-gold/25">
                        <span class="text-ink/60 block text-[10px]">Total Tagihan</span>
                        <strong class="text-maroon">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>

                @if ($booking->notes)
                    <div class="p-3.5 rounded-2xl bg-cream/80 border border-gold/25 text-xs">
                        <span class="font-bold text-ink">Catatan Penjemputan:</span>
                        <p class="text-ink/80 mt-0.5">{{ $booking->notes }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="font-display text-lg text-ink mb-2.5">Daftar Kursi & Penumpang</h3>
                    <div class="space-y-2.5">
                        @foreach ($booking->bookingSeats as $bs)
                            <div class="p-4 rounded-2xl bg-white border border-gold/30 flex flex-wrap items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <span class="w-10 h-10 rounded-2xl bg-maroon text-cream font-display text-base flex items-center justify-center shadow">
                                        {{ $bs->seat->seat_number }}
                                    </span>
                                    <div>
                                        <p class="font-bold text-ink">{{ $bs->passenger_name }}</p>
                                        <p class="text-ink/65">{{ $bs->passenger_phone ?? '-' }}</p>
                                    </div>
                                </div>

                                @if ($bs->ticket)
                                    <a href="{{ route('tiket.show', $bs->ticket) }}" class="px-4 py-2 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow transition">
                                        🎫 Lihat E-Tiket 3D ({{ $bs->ticket->ticket_code }})
                                    </a>
                                @else
                                    <span class="text-ink/55 font-semibold">E-Tiket terbit setelah verifikasi pembayaran</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIWAYAT UPAYA PEMBAYARAN -->
            <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
                <h3 class="font-display text-lg text-ink mb-3">Riwayat Pembayaran</h3>
                @if ($booking->payments->isEmpty())
                    <p class="text-xs text-ink/65">Belum ada bukti pembayaran yang diunggah.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach ($booking->payments as $pay)
                            <div class="p-3.5 rounded-2xl bg-white border border-gold/30 flex flex-wrap items-center justify-between gap-2 text-xs">
                                <div>
                                    <span class="font-bold text-ink">Percobaan #{{ $pay->attempt_number }}</span>
                                    <span class="text-ink/65 ml-2">{{ $pay->payment_method === 'qris_manual' ? 'QRIS Manual' : 'Transfer Bank Manual' }}</span>
                                    <p class="text-maroon font-bold mt-0.5">Rp {{ number_format($pay->amount, 0, ',', '.') }}</p>
                                    @if ($pay->rejection_reason)
                                        <p class="text-vred mt-1 font-semibold">Alasan Penolakan: {{ $pay->rejection_reason }}</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $pay->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($pay->status === 'rejected' ? 'bg-red-100 text-vred' : 'bg-amber-100 text-amber-800') }}">
                                    {{ strtoupper(str_replace('_', ' ', $pay->status)) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- PANEL AKSI PEMESANAN (4 KOLOM) -->
        <div class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4">
            <h3 class="font-display text-xl text-ink">Aksi Pemesanan</h3>

            @if ($booking->status === 'pending')
                <p class="text-xs text-ink/75 leading-relaxed">
                    Segera selesaikan pembayaran dan unggah bukti transfer sebelum batas waktu agar kursi tidak dilepaskan otomatis.
                </p>
                <a href="{{ route('pemesanan.bayar', $booking) }}" class="block w-full text-center py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold text-xs shadow-lg shadow-maroon/25 transition">
                    💳 Lanjut ke Pembayaran & Upload Bukti
                </a>
            @elseif ($booking->status === 'waiting_verification')
                <div class="p-4 rounded-2xl bg-mist/60 border border-dusk/40 text-xs text-ink space-y-1">
                    <p class="font-bold">Bukti Sedang Diverifikasi</p>
                    <p class="text-ink/75">Admin sedang memeriksa bukti pembayaran Anda. E-Tiket 3D akan terbit otomatis setelah disetujui.</p>
                </div>
            @elseif ($booking->status === 'confirmed')
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                    <p class="font-bold">✓ Pemesanan Terkonfirmasi</p>
                    <p>Klik tombol <strong>Lihat E-Tiket 3D</strong> pada daftar penumpang untuk menampilkan Boarding Pass & QR Check-In.</p>
                </div>
            @endif

            @if (in_array($booking->status, ['pending', 'waiting_verification']))
                <form method="POST" action="{{ route('pemesanan.batal', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini? Kursi akan langsung dilepaskan.');">
                    @csrf
                    <button type="submit" class="w-full py-3 rounded-2xl bg-white hover:bg-red-50 text-vred border border-vred/30 font-bold text-xs transition cursor-pointer">
                        Batalkan Pemesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
