@extends('layouts.admin')

@section('page-title', 'Manajemen Jadwal Keberangkatan')
@section('page-subtitle', 'Atur jadwal travel dengan deteksi bentrok armada & sopir otomatis (buffer 60 menit).')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <form method="GET" action="{{ route('admin.schedules.index') }}" class="flex flex-wrap items-center gap-2 text-xs">
                <input type="date" name="date" value="{{ request('date') }}" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40">
                <select name="status" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40">
                    <option value="">Semua Status</option>
                    @foreach (['scheduled', 'boarding', 'departed', 'completed', 'cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 rounded-2xl bg-cream border border-gold/40 font-bold text-ink cursor-pointer">Filter</button>
            </form>

            <a href="{{ route('admin.schedules.create') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Tambah Jadwal
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Rute & Waktu</th>
                        <th class="py-3 px-2">Armada & Sopir</th>
                        <th class="py-3 px-2">Tarif</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($schedules as $schedule)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2">
                                <p class="font-bold text-ink text-sm">{{ $schedule->route->origin }} → {{ $schedule->route->destination }}</p>
                                <p class="text-maroon font-semibold">{{ $schedule->departure_time->locale('id')->isoFormat('D MMM Y • HH:mm') }} - {{ $schedule->arrival_time->format('HH:mm') }} WIB</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-bold text-ink">{{ $schedule->vehicle->brand }} ({{ $schedule->vehicle->plate_number }})</p>
                                <p class="text-ink/70">Sopir: {{ $schedule->driver->name }}</p>
                            </td>
                            <td class="py-3 px-2 font-bold text-maroon">Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-mist text-ink">{{ strtoupper($schedule->status) }}</span>
                            </td>
                            <td class="py-3 px-2 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.manifest.show', $schedule) }}" class="px-3 py-1.5 rounded-xl bg-maroon text-cream font-bold">Manifest</a>
                                <a href="{{ route('admin.schedules.show', $schedule) }}" class="px-3 py-1.5 rounded-xl bg-white border border-gold/30 text-ink font-bold">Detail</a>
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="px-3 py-1.5 rounded-xl bg-cream text-ink font-bold">Edit</a>
                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 text-vred font-bold cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-ink/60">Tidak ada jadwal ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $schedules->links() }}</div>
    </div>
@endsection
