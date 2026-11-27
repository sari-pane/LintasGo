@extends('layouts.admin')

@section('page-title', 'Tambah Armada Baru')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-xl">
        <form method="POST" action="{{ route('admin.armada.store') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-ink mb-1">Merek / Tipe Kendaraan</label>
                <input type="text" name="brand" value="{{ old('brand') }}" required placeholder="Contoh: Toyota HiAce Premio" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Nomor Polisi</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}" required placeholder="Contoh: P 7011 LG" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Jumlah Kursi Penumpang</label>
                    <input type="number" name="total_seats" value="{{ old('total_seats', 7) }}" required min="2" max="30" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Fasilitas Kabin</label>
                <textarea name="facility" rows="2" placeholder="AC, Reclining Seat, USB Charger, Audio" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">{{ old('facility') }}</textarea>
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Status Armada</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                    <option value="active">Aktif</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Simpan Armada & Generate Kursi</button>
                <a href="{{ route('admin.armada.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
