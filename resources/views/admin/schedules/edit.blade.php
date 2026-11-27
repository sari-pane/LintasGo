@extends('layouts.admin')

@section('page-title', 'Edit Jadwal Keberangkatan')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-2xl">
        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-ink mb-1">Rute Perjalanan</label>
                <select name="route_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                    @foreach ($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id', $schedule->route_id) == $route->id ? 'selected' : '' }}>
                            {{ $route->origin }} → {{ $route->destination }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Armada Kendaraan</label>
                    <select name="vehicle_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $schedule->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} — {{ $vehicle->plate_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Sopir Bertugas</label>
                    <select name="driver_id" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $schedule->driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Waktu Berangkat (WIB)</label>
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time', $schedule->departure_time->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Estimasi Tiba (WIB)</label>
                    <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time', $schedule->arrival_time->format('Y-m-d\TH:i')) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Tarif per Kursi (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', (int) $schedule->price) }}" required min="0" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Status Jadwal</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                        @foreach (['scheduled', 'boarding', 'departed', 'completed', 'cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $schedule->status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Perbarui Jadwal</button>
                <a href="{{ route('admin.schedules.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
