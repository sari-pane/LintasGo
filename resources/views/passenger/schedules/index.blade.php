
@extends('layouts.passenger')

@section('title', 'Beranda - LintasGo')

@section('body-class', 'min-h-screen bg-slate-50 text-slate-800')

@section('main-content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Sapaan Penumpang --}}
    <section class="mb-8">
        <p class="text-sm font-medium text-blue-600">Selamat datang di LintasGo!</p>

        <h1 class="mt-2 text-3xl font-bold text-slate-800">
            Halo, {{ auth()->user()->name }} 👋
        </h1>

        <p class="mt-2 text-slate-500">
            Mau bepergian ke mana hari ini?
        </p>
    </section>

    {{-- Form Pencarian Travel --}}
    <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">
                Cari Perjalanan
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Temukan jadwal travel sesuai tujuan dan tanggal keberangkatanmu.
            </p>
        </div>

        <form action="{{ route('passenger.schedules.search') }}" method="GET">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                {{-- Kota Asal --}}
                <div>
                    <label for="origin" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kota Asal
                    </label>

                    <select
                        id="origin"
                        name="origin"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Pilih kota asal</option>

                        @foreach ($origins as $origin)
                            <option value="{{ $origin }}" @selected(old('origin') == $origin)>
                                {{ $origin }}
                            </option>
                        @endforeach
                    </select>

                    @error('origin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota Tujuan --}}
                <div>
                    <label for="destination" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kota Tujuan
                    </label>

                    <select
                        id="destination"
                        name="destination"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Pilih kota tujuan</option>

                        @foreach ($destinations as $destination)
                            <option value="{{ $destination }}" @selected(old('destination') == $destination)>
                                {{ $destination }}
                            </option>
                        @endforeach
                    </select>

                    @error('destination')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Keberangkatan --}}
                <div>
                    <label for="date" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal Berangkat
                    </label>

                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date', now()->toDateString()) }}"
                        min="{{ now()->toDateString() }}"
                        required
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    >

                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol Cari --}}
            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-700 px-6 py-3 font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 md:w-auto"
                >
                    Cari Travel
                </button>
            </div>
        </form>
    </section>

    {{-- Daftar Jadwal Perjalanan Tersedia --}}
    <section class="mt-8">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">
                Jadwal Perjalanan Tersedia
            </h2>
            <span class="text-sm text-slate-500">
                {{ $schedules->count() }} perjalanan ditemukan
            </span>
        </div>

        @if ($schedules->isEmpty())
            <div class="rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-slate-200">
                <p class="text-base font-semibold text-slate-700">Belum ada jadwal perjalanan untuk kriteria ini.</p>
                <p class="mt-1 text-sm text-slate-500">Silakan pilih tanggal atau rute lain pada formulir di atas.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($schedules as $schedule)
                    <div class="flex flex-col justify-between gap-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md sm:flex-row sm:items-center">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    {{ $schedule->vehicle->brand }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    {{ $schedule->vehicle->plate_number }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center gap-4">
                                <div>
                                    <p class="text-lg font-bold text-slate-800">
                                        {{ $schedule->departure_time->format('H:i') }}
                                    </p>
                                    <p class="text-sm font-medium text-slate-600">
                                        {{ $schedule->route->origin }}
                                    </p>
                                </div>

                                <div class="flex flex-col items-center">
                                    <span class="text-[11px] text-slate-400">
                                        {{ $schedule->departure_time->locale('id')->isoFormat('D MMM Y') }}
                                    </span>
                                    <span class="text-slate-300">→</span>
                                </div>

                                <div>
                                    <p class="text-lg font-bold text-slate-800">
                                        {{ $schedule->arrival_time ? $schedule->arrival_time->format('H:i') : '-' }}
                                    </p>
                                    <p class="text-sm font-medium text-slate-600">
                                        {{ $schedule->route->destination }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                                <span>Driver: <strong class="text-slate-700">{{ $schedule->driver->name ?? 'TBA' }}</strong></span>
                                <span>•</span>
                                @php
                                    $availableSeats = max(0, $schedule->vehicle->total_seats - ($schedule->occupied_seats_count ?? 0));
                                @endphp
                                <span>Sisa Kursi: <strong class="text-blue-600">{{ $availableSeats }}</strong> dari {{ $schedule->vehicle->total_seats }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col items-start gap-2 border-t pt-4 sm:items-end sm:border-t-0 sm:pt-0">
                            <div>
                                <span class="text-xs text-slate-400">Harga per kursi</span>
                                <p class="text-2xl font-bold text-blue-700">
                                    Rp {{ number_format($schedule->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <a
                                href="{{ route('jadwal.pesan', $schedule) }}"
                                class="mt-1 inline-flex items-center justify-center rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Pilih Kursi
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Informasi --}}
    <section class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold text-slate-800">Pilih Perjalanan</h3>
            <p class="mt-2 text-sm text-slate-500">
                Cari rute dan jadwal sesuai kebutuhanmu.
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold text-slate-800">Pilih Kursi</h3>
            <p class="mt-2 text-sm text-slate-500">
                Pilih kursi yang masih tersedia untuk perjalananmu.
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h3 class="font-semibold text-slate-800">Dapatkan E-Tiket</h3>
            <p class="mt-2 text-sm text-slate-500">
                Lakukan pembayaran dan dapatkan tiket perjalanan.
            </p>
        </div>
    </section>

</div>
@endsection
