@extends('layouts.admin')

@section('page-title', 'Manajemen Sopir Travel')
@section('page-subtitle', 'Kelola data pengemudi aktif yang bertugas pada jadwal keberangkatan.')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <h2 class="font-display text-2xl text-ink">Daftar Sopir</h2>
            <a href="{{ route('admin.sopir.create') }}" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                + Tambah Sopir
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Nama Sopir</th>
                        <th class="py-3 px-2">No. Telepon</th>
                        <th class="py-3 px-2">Nomor SIM</th>
                        <th class="py-3 px-2">Penugasan</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($drivers as $driver)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2 font-bold text-ink text-sm">{{ $driver->name }}</td>
                            <td class="py-3 px-2">{{ $driver->phone }}</td>
                            <td class="py-3 px-2 font-mono">{{ $driver->license_number }}</td>
                            <td class="py-3 px-2 font-bold text-maroon">{{ $driver->schedules_count }} jadwal</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $driver->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-ink/10 text-ink/60' }}">
                                    {{ $driver->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right space-x-2">
                                <a href="{{ route('admin.sopir.edit', $driver) }}" class="px-3 py-1.5 rounded-xl bg-cream hover:bg-mist text-ink font-bold">Edit</a>
                                <form method="POST" action="{{ route('admin.sopir.destroy', $driver) }}" class="inline" onsubmit="return confirm('Hapus sopir ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-vred hover:text-cream text-vred font-bold cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-ink/60">Belum ada data sopir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $drivers->links() }}</div>
    </div>
@endsection
