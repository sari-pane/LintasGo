@extends('layouts.admin')

@section('page-title', 'Manifest Keberangkatan Penumpang')
@section('page-subtitle', $schedule->route->origin . ' → ' . $schedule->route->destination . ' • ' . $schedule->departure_time->locale('id')->isoFormat('dddd, D MMMM Y • HH:mm') . ' WIB')

@section('admin-content')
    <div class="flex items-center justify-between flex-wrap gap-2 no-print">
        <a href="{{ route('admin.schedules.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon">← Kembali ke Jadwal</a>
        <button type="button" onclick="window.print()" class="px-5 py-2 rounded-full bg-maroon text-cream text-xs font-bold shadow cursor-pointer">🖨 Cetak Manifest</button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Kapasitas Armada</span>
            <span class="font-display text-2xl text-ink">{{ $manifestSummary['total_capacity'] }} Kursi</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-maroon block">Kursi Terisi</span>
            <span class="font-display text-2xl text-maroon">{{ $manifestSummary['booked_seats'] }} Kursi</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Terkonfirmasi Lunas</span>
            <span class="font-display text-2xl text-ink">{{ $manifestSummary['confirmed_passengers'] }} Org</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-gold block">Sudah Check-In</span>
            <span class="font-display text-2xl text-maroon">{{ $manifestSummary['checked_in_count'] }} Org</span>
        </div>
    </div>

    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gold/25 pb-3 mb-4 text-xs">
            <div>
                <h2 class="font-display text-2xl text-ink">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</h2>
                <p class="text-ink/70">Armada: <strong>{{ $schedule->vehicle->brand }} ({{ $schedule->vehicle->plate_number }})</strong> • Sopir: <strong>{{ $schedule->driver->name }}</strong> ({{ $schedule->driver->phone }})</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Kursi</th>
                        <th class="py-3 px-2">Nama Penumpang</th>
                        <th class="py-3 px-2">Kontak</th>
                        <th class="py-3 px-2">Kode Booking & Tiket</th>
                        <th class="py-3 px-2">Status Boarding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @foreach ($schedule->vehicle->seats as $seat)
                        @php $bs = $activeSeatsBySeatId->get($seat->id); @endphp
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2 font-display text-base text-maroon">{{ $seat->seat_number }}</td>
                            @if ($bs)
                                <td class="py-3 px-2 font-bold text-ink">{{ $bs->passenger_name }}</td>
                                <td class="py-3 px-2">{{ $bs->passenger_phone ?? $bs->booking?->user?->phone ?? '-' }}</td>
                                <td class="py-3 px-2 font-mono">
                                    {{ $bs->booking?->booking_code }}
                                    @if ($bs->ticket)
                                        <span class="block text-maroon font-bold">{{ $bs->ticket->ticket_code }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2">
                                    @if ($bs->ticket?->status === 'checked_in')
                                        <span class="px-2.5 py-1 rounded-full bg-dusk/30 text-ink font-bold text-[10px]">CHECKED IN</span>
                                    @elseif ($bs->booking?->status === 'confirmed')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">SIAP BOARDING</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">{{ strtoupper($bs->booking?->status ?? 'PENDING') }}</span>
                                    @endif
                                </td>
                            @else
                                <td colspan="4" class="py-3 px-2 text-ink/45 italic">Kursi kosong / tersedia</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
