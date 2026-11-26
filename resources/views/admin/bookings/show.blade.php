@extends('layouts.admin')

@section('page-title', 'Detail Pemesanan ' . $booking->booking_code)

@section('admin-content')
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bookings.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon">← Kembali ke Pemesanan</a>
    </div>

    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-5 text-xs">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Pemesan</span>
                <strong>{{ $booking->user->name }} ({{ $booking->user->phone }})</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Rute</span>
                <strong>{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Status</span>
                <strong class="text-maroon">{{ strtoupper($booking->status) }}</strong>
            </div>
            <div class="p-3.5 rounded-2xl bg-white border border-gold/25">
                <span class="text-ink/60 block text-[10px]">Total Tagihan</span>
                <strong class="text-maroon">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>

        <div>
            <h3 class="font-display text-lg text-ink mb-2">Penumpang & Kursi</h3>
            <div class="space-y-2">
                @foreach ($booking->bookingSeats as $bs)
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/30 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-maroon mr-2">Kursi {{ $bs->seat->seat_number }}</span>
                            <strong>{{ $bs->passenger_name }}</strong> ({{ $bs->passenger_phone ?? '-' }})
                        </div>
                        @if ($bs->ticket)
                            <span class="font-mono font-bold text-ink">{{ $bs->ticket->ticket_code }} ({{ strtoupper($bs->ticket->status) }})</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
