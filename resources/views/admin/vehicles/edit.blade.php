@extends('layouts.admin')

@section('page-title', 'Edit Armada')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-xl">
        <form method="POST" action="{{ route('admin.armada.update', $vehicle) }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-ink mb-1">Merek / Tipe Kendaraan</label>
                <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Nomor Polisi</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Jumlah Kursi</label>
                    <input type="number" name="total_seats" value="{{ old('total_seats', $vehicle->total_seats) }}" required min="2" max="30" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Fasilitas Kabin</label>
                <textarea name="facility" rows="2" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">{{ old('facility', $vehicle->facility) }}</textarea>
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Status Armada</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                    <option value="active" {{ old('status', $vehicle->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="inactive" {{ old('status', $vehicle->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Perbarui Armada</button>
                <a href="{{ route('admin.armada.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
