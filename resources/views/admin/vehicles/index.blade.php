@extends('layouts.admin')

@section('page-title', 'Manajemen Armada & Kursi')
@section('page-subtitle', 'Kelola kendaraan LintasGo beserta konfigurasi fisik kursi untuk kabin 3D.')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <h2 class="font-display text-2xl text-ink">Daftar Armada</h2>
            <a href="{{ route('admin.armada.create') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Tambah Armada
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Armada & Plat Nomor</th>
                        <th class="py-3 px-2">Kapasitas</th>
                        <th class="py-3 px-2">Fasilitas</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($vehicles as $vehicle)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2">
                                <p class="font-bold text-ink text-sm">{{ $vehicle->brand }}</p>
                                <span class="font-mono text-maroon font-bold">{{ $vehicle->plate_number }}</span>
                            </td>
                            <td class="py-3 px-2 font-bold">{{ $vehicle->seats_count }} kursi terdaftar</td>
                            <td class="py-3 px-2 text-ink/70 max-w-xs truncate">{{ $vehicle->facility ?? '-' }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $vehicle->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($vehicle->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right space-x-1.5">
                                <a href="{{ route('admin.armada.show', $vehicle) }}" class="px-3 py-1.5 rounded-xl bg-maroon text-cream font-bold">Kursi ({{ $vehicle->seats_count }})</a>
                                <a href="{{ route('admin.armada.edit', $vehicle) }}" class="px-3 py-1.5 rounded-xl bg-cream hover:bg-mist text-ink font-bold">Edit</a>
                                <form method="POST" action="{{ route('admin.armada.destroy', $vehicle) }}" class="inline" onsubmit="return confirm('Hapus armada ini beserta kursinya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-vred hover:text-cream text-vred font-bold cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-ink/60">Belum ada armada terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $vehicles->links() }}</div>
    </div>
@endsection
