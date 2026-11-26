@extends('layouts.passenger', [
    'title' => 'Riwayat Pemesanan — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Riwayat Pemesanan Anda')
@section('header-subtext', 'Pantau status pembayaran, unggah bukti transfer, atau akses E-Tiket 3D yang telah terbit.')

@section('main-content')
    <div class="soft-card rounded-[2.2rem] p-5 sm:p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Pemesanan Penumpang</span>
                <h2 class="font-display text-2xl text-ink">Daftar Transaksi Tiket</h2>
            </div>
            <a href="{{ route('jadwal.index') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Pesan Tiket Baru
            </a>
        </div>

        @if ($bookings->isEmpty())
            <div class="p-8 rounded-3xl bg-white/80 border border-gold/25 text-center space-y-3">
                <p class="font-display text-xl text-ink">Belum Ada Riwayat Pemesanan</p>
                <p class="text-xs text-ink/70">Silakan cari jadwal perjalanan dan pilih kursi 3D favorit Anda.</p>
                <a href="{{ route('jadwal.index') }}" class="inline-block px-5 py-2.5 rounded-full bg-maroon text-cream text-xs font-bold">Cari Jadwal Sekarang</a>
            </div>
        @else
            <div class="space-y-3.5">
                @foreach ($bookings as $booking)
                    @php
                        $badgeMap = [
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'waiting_verification' => 'bg-dusk/25 text-ink border-dusk/40',
                            'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'cancelled' => 'bg-ink/10 text-ink/60 border-ink/20',
                            'expired' => 'bg-red-100 text-vred border-vred/30',
                        ];
                        $statusLabelMap = [
                            'pending' => 'Menunggu Pembayaran',
                            'waiting_verification' => 'Menunggu Verifikasi Admin',
                            'confirmed' => 'Terkonfirmasi (E-Tiket Terbit)',
                            'cancelled' => 'Dibatalkan',
                            'expired' => 'Kedaluwarsa',
                        ];
                    @endphp
                    <div class="p-5 rounded-3xl bg-white/95 border border-gold/30 hover:border-maroon/50 transition flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-bold text-maroon bg-maroon/10 px-3 py-1 rounded-full">
                                    {{ $booking->booking_code }}
                                </span>
                                <span class="px-3 py-1 rounded-full border text-[11px] font-bold {{ $badgeMap[$booking->status] ?? 'bg-cream text-ink' }}">
                                    {{ $statusLabelMap[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </div>

                            <h3 class="font-display text-xl text-ink">
                                {{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}
                            </h3>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink/75">
                                <span>📅 {{ $booking->schedule->departure_time->locale('id')->isoFormat('D MMM Y • HH:mm') }} WIB</span>
                                <span>🚐 {{ $booking->schedule->vehicle->brand }}</span>
                                <span>💺 Kursi: <strong class="text-maroon">{{ $booking->bookingSeats->map(fn($bs) => $bs->seat->seat_number)->join(', ') }}</strong></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 md:text-right">
                            <div class="mr-2">
                                <span class="text-[10px] text-ink/60 block">Total Tagihan</span>
                                <span class="font-display text-xl text-maroon">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>

                            @if ($booking->status === 'pending')
                                <a href="{{ route('pemesanan.bayar', $booking) }}" class="px-4 py-2.5 rounded-2xl bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                                    Bayar Sekarang
                                </a>
                            @endif

                            <a href="{{ route('pemesanan.show', $booking) }}" class="px-4 py-2.5 rounded-2xl bg-cream hover:bg-mist text-ink border border-gold/35 text-xs font-bold transition">
                                Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection
