@extends('layouts.admin')

@section('page-title', 'Detail Jadwal — ' . $schedule->route->origin . ' → ' . $schedule->route->destination)

@section('admin-content')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <a href="{{ route('admin.schedules.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon">← Kembali ke Jadwal</a>
        <a href="{{ route('admin.manifest.show', $schedule) }}" class="px-5 py-2 rounded-full bg-maroon text-cream text-xs font-bold shadow">📋 Buka Manifest Penumpang</a>
    </div>

    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Berangkat</span>
                <strong class="text-maroon">{{ $schedule->departure_time->locale('id')->isoFormat('D MMM Y • HH:mm') }} WIB</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Armada</span>
                <strong>{{ $schedule->vehicle->brand }} ({{ $schedule->vehicle->plate_number }})</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Sopir</span>
                <strong>{{ $schedule->driver->name }}</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Tarif</span>
                <strong class="text-maroon">Rp {{ number_format($schedule->price, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div>
            <h3 class="font-display text-lg text-ink mb-2">Daftar Pemesanan di Jadwal Ini</h3>
            <div class="space-y-2 text-xs">
                @forelse ($schedule->bookings as $booking)
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/30 flex items-center justify-between">
                        <div>
                            <span class="font-mono font-bold text-maroon">{{ $booking->booking_code }}</span>
                            <span class="ml-2 font-semibold text-ink">{{ $booking->user->name }}</span>
                            <span class="ml-2 text-ink/65">({{ $booking->bookingSeats->map(fn($bs) => $bs->seat->seat_number)->join(', ') }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-mist font-bold text-[10px]">{{ strtoupper($booking->status) }}</span>
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-maroon font-bold hover:underline">Detail →</a>
                        </div>
                    </div>
                @empty
                    <p class="text-ink/60">Belum ada pemesanan pada jadwal ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
