@extends('layouts.admin')

@section('page-title', 'Check-In Penumpang & Validasi QR')
@section('page-subtitle', 'Masukkan kode tiket (TIX-...) atau hash QR Identifier untuk memvalidasi keberangkatan dan mencegah check-in ganda.')

@section('admin-content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <form method="POST" action="{{ route('admin.checkin.process') }}" class="lg:col-span-5 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4 text-xs">
            @csrf
            <span class="text-[11px] font-bold uppercase tracking-wider text-maroon">Boarding Gate</span>
            <h2 class="font-display text-2xl text-ink">Proses Check-In Tiket</h2>

            <div>
                <label for="identifier" class="block font-semibold text-ink mb-1">Kode Tiket / QR Identifier</label>
                <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus placeholder="Contoh: TIX-261126-ABCDE atau SHA256 QR" class="w-full px-4 py-3 rounded-2xl bg-white border border-gold/40 font-mono text-sm text-ink focus:outline-none focus:border-maroon">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold text-sm shadow-lg transition cursor-pointer">
                ✓ Validasi & Check-In Sekarang
            </button>
        </form>

        <div class="lg:col-span-7 soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
            <h3 class="font-display text-xl text-ink mb-4">Riwayat Check-In Hari Ini</h3>
            <div class="space-y-2.5 text-xs">
                @forelse ($recentCheckIns as $ticket)
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/30 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="font-mono font-bold text-maroon">{{ $ticket->ticket_code }}</span>
                            <span class="font-bold text-ink ml-2">{{ $ticket->bookingSeat->passenger_name }}</span>
                            <span class="text-ink/70 ml-1">(Kursi {{ $ticket->bookingSeat->seat->seat_number }})</span>
                            <p class="text-ink/60 mt-0.5">{{ $ticket->bookingSeat->booking->schedule->route->origin }} → {{ $ticket->bookingSeat->booking->schedule->route->destination }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-0.5 rounded-full bg-dusk/30 text-ink font-bold text-[10px]">CHECKED IN</span>
                            <p class="text-[10px] text-ink/60 mt-1">{{ $ticket->checked_in_at?->format('H:i') }} WIB</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-ink/60">Belum ada penumpang yang melakukan check-in hari ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
