@extends('layouts.admin')

@section('page-title', 'Edit Rute Travel')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-2xl">
        <form method="POST" action="{{ route('admin.rute.update', $route) }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Kota Asal</label>
                    <input type="text" name="origin" value="{{ old('origin', $route->origin) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Kota Tujuan</label>
                    <input type="text" name="destination" value="{{ old('destination', $route->destination) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Jarak Tempuh (km)</label>
                    <input type="number" step="0.1" name="distance_km" value="{{ old('distance_km', $route->distance_km) }}" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Estimasi Durasi (Menit)</label>
                    <input type="number" name="estimated_duration_minutes" value="{{ old('estimated_duration_minutes', $route->estimated_duration_minutes) }}" required min="15" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-ink mb-1">Deskripsi Rute Umum</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">{{ old('description', $route->description) }}</textarea>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $route->is_active) ? 'checked' : '' }} class="rounded text-maroon">
                <span class="font-semibold text-ink">Rute Aktif</span>
            </label>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Perbarui Rute</button>
                <a href="{{ route('admin.rute.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
