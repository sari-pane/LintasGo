@extends('layouts.admin')

@section('page-title', 'Laporan Keuangan & Okupansi Armada')
@section('page-subtitle', 'Rekapitulasi pendapatan terkonfirmasi dan tingkat keterisian kursi per rute.')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-5 border-2 border-white/85 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-center gap-2 text-xs">
            <label class="font-bold text-ink">Periode:</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40">
            <span>s/d</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40">
            <button type="submit" class="px-4 py-2 rounded-2xl bg-maroon text-cream font-bold cursor-pointer">Tampilkan Laporan</button>
        </form>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-full bg-white border border-gold/40 text-xs font-bold text-ink cursor-pointer no-print">🖨 Cetak Laporan</button>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="soft-card rounded-3xl p-5 border border-white">
            <span class="text-[11px] font-bold uppercase text-gold block">Pendapatan Terkonfirmasi</span>
            <span class="font-display text-2xl text-maroon">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</span>
        </div>
        <div class="soft-card rounded-3xl p-5 border border-white">
            <span class="text-[11px] font-bold uppercase text-ink/60 block">Transaksi Sukses</span>
            <span class="font-display text-2xl text-ink">{{ $summary['confirmed_bookings_count'] }} Booking ({{ $summary['total_passengers'] }} Kursi)</span>
        </div>
        <div class="soft-card rounded-3xl p-5 border border-white">
            <span class="text-[11px] font-bold uppercase text-ink/60 block">Tingkat Okupansi</span>
            <span class="font-display text-2xl text-maroon">{{ $summary['occupancy_rate'] }}%</span>
        </div>
        <div class="soft-card rounded-3xl p-5 border border-white">
            <span class="text-[11px] font-bold uppercase text-ink/60 block">Pembayaran Terverifikasi</span>
            <span class="font-display text-2xl text-ink">{{ $summary['verified_payments_count'] }} Transaksi</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <div class="lg:col-span-6 soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
            <h2 class="font-display text-xl text-ink mb-4">Pendapatan per Rute</h2>
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-2">Rute</th>
                        <th class="py-2">Penumpang</th>
                        <th class="py-2 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($revenueByRoute as $row)
                        <tr>
                            <td class="py-2.5 font-bold text-ink">{{ $row['route'] }}</td>
                            <td class="py-2.5">{{ $row['passengers_count'] }} kursi ({{ $row['bookings_count'] }} trx)</td>
                            <td class="py-2.5 text-right font-bold text-maroon">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-6 text-center text-ink/60">Belum ada pendapatan terkonfirmasi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="lg:col-span-6 soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
            <h2 class="font-display text-xl text-ink mb-4">Okupansi per Jadwal</h2>
            <div class="space-y-2.5 text-xs">
                @forelse ($schedulesInPeriod as $sched)
                    @php
                        $cap = max(1, $sched->vehicle->total_seats ?? 1);
                        $occ = $sched->occupied_seats_count ?? 0;
                        $pct = round(($occ / $cap) * 100);
                    @endphp
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/30 flex items-center justify-between gap-2">
                        <div>
                            <p class="font-bold text-ink">{{ $sched->route->origin }} → {{ $sched->route->destination }}</p>
                            <p class="text-ink/65">{{ $sched->departure_time->format('d M Y H:i') }} • {{ $sched->vehicle->brand }}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-maroon">{{ $occ }} / {{ $cap }} ({{ $pct }}%)</span>
                            <a href="{{ route('admin.manifest.show', $sched) }}" class="block text-[10px] text-ink/70 hover:text-maroon underline">Manifest →</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-ink/60">Tidak ada jadwal pada rentang tanggal ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
