
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
