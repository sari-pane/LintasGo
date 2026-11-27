@extends('layouts.passenger', [
    'title' => 'E-Tiket 3D Saya — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'E-Tiket Boarding Pass Anda')
@section('header-subtext', 'Klik kartu tiket untuk membuka E-Tiket 3D Hologram dan QR Code Check-In.')

@section('main-content')
    <div class="soft-card rounded-[2.2rem] p-5 sm:p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Boarding Pass Digital</span>
                <h2 class="font-display text-2xl text-ink">Daftar E-Tiket Terbit</h2>
            </div>
            <a href="{{ route('jadwal.index') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Pesan Tiket Lain
            </a>
        </div>

        @if ($tickets->isEmpty())
            <div class="p-8 rounded-3xl bg-white/80 border border-gold/25 text-center space-y-2">
                <p class="font-display text-xl text-ink">Belum Ada E-Tiket Aktif</p>
                <p class="text-xs text-ink/70">E-Tiket akan otomatis diterbitkan setelah admin memverifikasi bukti pembayaran Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($tickets as $ticket)
                    @php
                        $bs = $ticket->bookingSeat;
                        $booking = $bs->booking;
                        $schedule = $booking->schedule;
                    @endphp
                    <a href="{{ route('tiket.show', $ticket) }}" class="p-5 rounded-3xl bg-white/95 border-2 border-gold/35 hover:border-maroon shadow-sm transition flex flex-col justify-between gap-3 group">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-bold text-maroon bg-maroon/10 px-2.5 py-1 rounded-xl">{{ $ticket->ticket_code }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $ticket->status === 'checked_in' ? 'bg-dusk/30 text-ink' : ($ticket->status === 'issued' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-vred') }}">
                                {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-display text-xl text-ink group-hover:text-maroon transition">
                                {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                            </h3>
                            <p class="text-xs text-ink/75 mt-1">Penumpang: <strong>{{ $bs->passenger_name }}</strong> • Kursi <strong class="text-maroon">{{ $bs->seat->seat_number }}</strong></p>
                            <p class="text-[11px] text-ink/60 mt-0.5">{{ $schedule->departure_time->locale('id')->isoFormat('D MMM Y • HH:mm') }} WIB • {{ $schedule->vehicle->brand }}</p>
                        </div>

                        <div class="pt-2 border-t border-gold/20 flex items-center justify-between text-xs font-bold text-maroon">
                            <span>Buka E-Tiket 3D Hologram</span>
                            <span>→</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
@endsection
