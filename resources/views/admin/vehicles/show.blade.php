@extends('layouts.admin')

@section('page-title', 'Konfigurasi Kursi — ' . $vehicle->brand)
@section('page-subtitle', 'Plat Nomor ' . $vehicle->plate_number . ' • Total ' . $vehicle->seats->count() . ' kursi terdaftar')

@section('admin-content')
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.armada.index') }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon">← Kembali ke Armada</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <div class="lg:col-span-8 soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
            <h2 class="font-display text-xl text-ink mb-4">Daftar Kursi Fisik Armada</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach ($vehicle->seats as $seat)
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/35 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-display text-lg text-maroon block">{{ $seat->seat_number }}</span>
                            <span class="text-[10px] text-ink/60">{{ $seat->seat_position }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.armada.kursi.destroy', [$vehicle, $seat]) }}" onsubmit="return confirm('Hapus kursi {{ $seat->seat_number }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 rounded-xl bg-red-50 text-vred hover:bg-vred hover:text-cream font-bold cursor-pointer">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <form method="POST" action="{{ route('admin.armada.kursi.store', $vehicle) }}" class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4 text-xs">
            @csrf
            <h3 class="font-display text-xl text-ink">Tambah Kursi Baru</h3>
            <div>
                <label class="block font-semibold text-ink mb-1">Nomor Kursi</label>
                <input type="text" name="seat_number" required placeholder="Contoh: 5A" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Posisi Kursi</label>
                <input type="text" name="seat_position" required placeholder="Contoh: row-5-left" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <button type="submit" class="w-full py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">
                + Tambah Kursi
            </button>
        </form>
    </div>
@endsection
