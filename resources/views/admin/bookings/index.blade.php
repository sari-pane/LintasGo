@extends('layouts.admin')

@section('page-title', 'Monitoring Seluruh Pemesanan')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-wrap items-center gap-2 text-xs">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking / nama / email..." class="px-4 py-2 rounded-2xl bg-white border border-gold/40 w-64">
                <select name="status" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40">
                    <option value="">Semua Status</option>
                    @foreach (['pending', 'waiting_verification', 'confirmed', 'cancelled', 'expired'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-2xl bg-maroon text-cream font-bold cursor-pointer">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Kode Booking</th>
                        <th class="py-3 px-2">Pemesan</th>
                        <th class="py-3 px-2">Rute & Jadwal</th>
                        <th class="py-3 px-2">Total</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2 font-mono font-bold text-maroon">{{ $booking->booking_code }}</td>
                            <td class="py-3 px-2">
                                <p class="font-bold text-ink">{{ $booking->user->name }}</p>
                                <p class="text-ink/60">{{ $booking->user->email }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold">{{ $booking->schedule->route->origin }} → {{ $booking->schedule->route->destination }}</p>
                                <p class="text-ink/65">{{ $booking->schedule->departure_time->format('d M Y H:i') }} WIB</p>
                            </td>
                            <td class="py-3 px-2 font-bold text-maroon">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-mist text-ink">{{ strtoupper(str_replace('_', ' ', $booking->status)) }}</span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="px-3.5 py-1.5 rounded-xl bg-cream hover:bg-mist text-ink font-bold">Detail →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-ink/60">Tidak ada pemesanan ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
@endsection
