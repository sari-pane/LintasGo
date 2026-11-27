@extends('layouts.admin')

@section('page-title', 'Manajemen Rute Travel')
@section('page-subtitle', 'Kelola daftar jurusan antar kota yang tampil di landing page publik dan jadwal perjalanan.')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <h2 class="font-display text-2xl text-ink">Daftar Rute Layanan</h2>
            <a href="{{ route('admin.rute.create') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Tambah Rute Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Kota Asal → Tujuan</th>
                        <th class="py-3 px-2">Jarak</th>
                        <th class="py-3 px-2">Estimasi Durasi</th>
                        <th class="py-3 px-2">Jadwal</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($routes as $route)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2">
                                <p class="font-bold text-ink text-sm">{{ $route->origin }} → {{ $route->destination }}</p>
                                <p class="text-ink/60 truncate max-w-xs">{{ $route->description }}</p>
                            </td>
                            <td class="py-3 px-2">{{ $route->distance_km ? number_format($route->distance_km, 0) . ' km' : '-' }}</td>
                            <td class="py-3 px-2">{{ $route->estimated_duration }} ({{ $route->estimated_duration_minutes }} mnt)</td>
                            <td class="py-3 px-2 font-bold text-maroon">{{ $route->schedules_count }} jadwal</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $route->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-ink/10 text-ink/60' }}">
                                    {{ $route->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right space-x-2">
                                <a href="{{ route('admin.rute.edit', $route) }}" class="px-3 py-1.5 rounded-xl bg-cream hover:bg-mist text-ink font-bold">Edit</a>
                                <form method="POST" action="{{ route('admin.rute.destroy', $route) }}" class="inline" onsubmit="return confirm('Hapus rute ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-vred hover:text-cream text-vred font-bold cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-ink/60">Belum ada rute travel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $routes->links() }}</div>
    </div>
@endsection
