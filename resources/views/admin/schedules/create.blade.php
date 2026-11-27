@extends('layouts.admin')

@section('page-title', 'Tambah Jadwal Keberangkatan')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-2xl">
        <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-4 text-xs">
            @csrf

            <div>
                <label class="block font-semibold text-ink mb-1">Rute Perjalanan</label>
                <select name="route_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                    @foreach ($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>
                            {{ $route->origin }} → {{ $route->destination }} ({{ $route->estimated_duration }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Armada Kendaraan</label>
                    <select name="vehicle_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} — {{ $vehicle->plate_number }} ({{ $vehicle->total_seats }} kursi)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Sopir Bertugas</label>
                    <select name="driver_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }} ({{ $driver->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Waktu Berangkat (WIB)</label>
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Estimasi Tiba (WIB)</label>
                    <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-ink mb-1">Tarif per Kursi (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 150000) }}" required min="0" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Simpan Jadwal</button>
                <a href="{{ route('admin.schedules.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
