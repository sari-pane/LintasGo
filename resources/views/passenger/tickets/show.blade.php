@extends('layouts.passenger', [
    'title' => 'E-Tiket 3D ' . $ticket->ticket_code . ' — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Boarding Pass 3D LintasGo')
@section('header-subtext', 'Arahkan kursor untuk efek hologram atau klik kartu untuk membalik dan menampilkan QR Check-In.')

@section('main-content')
    @php
        $bs = $ticket->bookingSeat;
        $booking = $bs->booking;
        $schedule = $booking->schedule;
        $route = $schedule->route;
        // Generate deterministic 9x9 QR grid from cryptographic qr_code_identifier (zero personal data)
        $hashBytes = hash('sha256', $ticket->qr_code_identifier, true);
    @endphp

    <div class="flex items-center justify-between flex-wrap gap-2 no-print">
        <a href="{{ route('tiket.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon transition">
            ← Kembali ke Daftar E-Tiket
        </a>
        <button type="button" onclick="window.print()" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-maroon hover:bg-white transition cursor-pointer">
            🖨 Cetak Boarding Pass
        </button>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center py-2">
        <div class="text-center mb-4">
            <span class="px-3.5 py-1 rounded-full bg-maroon/10 text-maroon text-xs font-bold">
                @if ($ticket->status === 'checked_in')
                    ✓ Sudah Check-In ({{ $ticket->checked_in_at?->format('d M Y H:i') }} WIB)
                @elseif ($ticket->status === 'issued')
                    ✓ E-Tiket Digital Terverifikasi
                @else
                    ✕ Tiket Dibatalkan
                @endif
            </span>
        </div>

        <!-- 3D HOLOGRAPHIC FLIP E-TICKET -->
        <div class="ticket-perspective w-full max-w-md h-[460px] cursor-pointer" id="ticket-tilt-box" onclick="toggleTicketFlip()">
            <div class="ticket-card-inner" id="ticket-inner-card">

                <!-- SISI DEPAN TIKET -->
                <div class="ticket-face soft-card border-2 border-gold/50 p-6 flex flex-col justify-between bg-gradient-to-br from-[#FDFBF4] via-cream to-mist/40 shadow-2xl">
                    <div class="holo-sheen absolute inset-0 z-10"></div>

                    <div class="flex items-center justify-between border-b border-dashed border-gold/50 pb-4 relative z-20">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-2xl bg-maroon text-cream font-display text-xl flex items-center justify-center shadow">L</div>
                            <div>
                                <span class="font-display text-lg text-ink">LintasGo</span>
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-gold">Executive Shuttle Pass</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-ink/60 block">Kode Booking</span>
                            <span class="font-mono font-bold text-xs text-maroon bg-maroon/10 px-2.5 py-1 rounded-xl">{{ $booking->booking_code }}</span>
                        </div>
                    </div>

                    <div class="my-3 flex items-center justify-between relative z-20 gap-2">
                        <div>
                            <span class="text-[10px] font-bold uppercase text-ink/60">Asal</span>
                            <p class="font-display text-2xl text-ink uppercase">{{ $route->origin }}</p>
                            <span class="text-[11px] text-ink/70">Keberangkatan {{ $schedule->departure_time->format('H:i') }}</span>
                        </div>
                        <div class="flex flex-col items-center shrink-0">
                            <span class="text-[10px] font-bold text-maroon mb-1">{{ $route->estimated_duration }}</span>
                            <div class="flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-maroon"></span>
                                <span class="w-10 h-0.5 bg-gold"></span>
                                <span class="text-maroon text-sm">🚐</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase text-ink/60">Tujuan</span>
                            <p class="font-display text-2xl text-maroon uppercase">{{ $route->destination }}</p>
                            <span class="text-[11px] text-ink/70">Tiba ± {{ $schedule->arrival_time->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 bg-white/85 p-4 rounded-2xl border border-gold/30 relative z-20 text-xs">
                        <div>
                            <span class="text-[10px] text-ink/60 block">Penumpang</span>
                            <span class="font-bold text-ink">{{ $bs->passenger_name }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink/60 block">Kursi</span>
                            <span class="font-bold text-maroon">{{ $bs->seat->seat_number }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink/60 block">Tanggal & Jam</span>
                            <span class="font-semibold text-ink">{{ $schedule->departure_time->locale('id')->isoFormat('D MMM Y • HH:mm') }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-ink/60 block">Armada</span>
                            <span class="font-semibold text-ink">{{ $schedule->vehicle->brand }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 text-xs relative z-20">
                        <span class="font-bold text-maroon">● {{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</span>
                        <span class="text-ink/75 font-semibold">Klik untuk lihat QR ↻</span>
                    </div>
                </div>

                <!-- SISI BELAKANG TIKET (QR CODE) -->
                <div class="ticket-face ticket-back soft-card border-2 border-maroon/40 p-6 flex flex-col items-center justify-between bg-cream shadow-2xl">
                    <div class="w-full flex items-center justify-between border-b border-gold/30 pb-3">
                        <span class="font-display text-base text-ink">QR Check-In Penumpang</span>
                        <span class="font-mono text-xs font-bold text-maroon">{{ $ticket->ticket_code }}</span>
                    </div>

                    <div class="my-auto flex flex-col items-center">
                        <div class="p-4 bg-white rounded-3xl shadow-md border border-gold/30">
                            <svg class="w-44 h-44 text-ink" viewBox="0 0 110 110" fill="currentColor" aria-label="QR Check-In">
                                <!-- Finder Patterns -->
                                <path d="M5,5 h25 v25 h-25 z M10,10 v15 h15 v-15 z M14,14 h7 v7 h-7 z"/>
                                <path d="M80,5 h25 v25 h-25 z M85,10 v15 h15 v-15 z M89,14 h7 v7 h-7 z"/>
                                <path d="M5,80 h25 v25 h-25 z M10,85 v15 h15 v-15 z M14,89 h7 v7 h-7 z"/>
                                <!-- Deterministic Data Modules from SHA256 QR Identifier -->
                                @for ($row = 0; $row < 9; $row++)
                                    @for ($col = 0; $col < 9; $col++)
                                        @php
                                            $inCorner = ($row < 3 && $col < 3) || ($row < 3 && $col > 5) || ($row > 5 && $col < 3);
                                            $byteVal = ord($hashBytes[($row * 9 + $col) % 32]);
                                        @endphp
                                        @if (!$inCorner && ($byteVal % 2 === 0))
                                            <rect x="{{ 10 + $col * 10 }}" y="{{ 10 + $row * 10 }}" width="8" height="8" rx="1.5"/>
                                        @endif
                                    @endfor
                                @endfor
                            </svg>
                        </div>
                        <p class="font-mono text-[10px] text-ink/60 mt-2.5 break-all max-w-[260px] text-center">
                            ID: {{ substr($ticket->qr_code_identifier, 0, 24) }}...
                        </p>
                        <p class="text-xs text-ink/75 text-center mt-1 max-w-xs">
                            Tunjukkan QR atau kode <strong>{{ $ticket->ticket_code }}</strong> kepada petugas saat boarding.
                        </p>
                    </div>

                    <span class="text-xs font-semibold text-maroon">Klik untuk kembali ke sisi depan ↺</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let ticketFlipped = false;
        function toggleTicketFlip() {
            ticketFlipped = !ticketFlipped;
            playTone(380, 520, 0.14);
            const card = document.getElementById('ticket-inner-card');
            card.classList.toggle('is-flipped', ticketFlipped);
        }

        const tiltBox = document.getElementById('ticket-tilt-box');
        if (tiltBox) {
            tiltBox.addEventListener('mousemove', (e) => {
                if (ticketFlipped) return;
                const r = tiltBox.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                document.getElementById('ticket-inner-card').style.transform = `rotateY(${x * 16}deg) rotateX(${-y * 16}deg)`;
            });
            tiltBox.addEventListener('mouseleave', () => {
                document.getElementById('ticket-inner-card').style.transform = ticketFlipped ? 'rotateY(180deg)' : 'rotateY(0deg)';
            });
        }
    </script>
@endpush
