@extends('layouts.passenger', [
    'title' => 'Jadwal Keberangkatan — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Cari Jadwal & Pilih Kursi 3D')
@section('header-subtext', 'Pilih tanggal dan jam keberangkatan untuk masuk ke kabin 3D LintasGo.')

@section('main-content')
    <!-- FILTER RUTE & TANGGAL -->
    <div class="soft-card rounded-[2.2rem] p-5 sm:p-6 border-2 border-white/85 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gold/25 pb-3.5">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-maroon">Langkah 1 • Pilih Tanggal & Rute</span>
                <h2 class="font-display text-2xl text-ink mt-0.5">
                    @if ($selectedOrigin && $selectedDestination)
                        {{ $selectedOrigin }} → {{ $selectedDestination }}
                    @else
                        Semua Rute Keberangkatan
                    @endif
                </h2>
            </div>

            <form method="GET" action="{{ route('jadwal.index') }}" class="flex flex-wrap items-center gap-2.5 text-xs">
                <select name="origin" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40 font-bold text-ink">
                    <option value="">Semua Asal</option>
                    @foreach ($origins as $city)
                        <option value="{{ $city }}" {{ $selectedOrigin === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>

                <select name="destination" class="px-3.5 py-2 rounded-2xl bg-white border border-gold/40 font-bold text-ink">
                    <option value="">Semua Tujuan</option>
                    @foreach ($destinations as $city)
                        <option value="{{ $city }}" {{ $selectedDestination === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-2xl border border-gold/40">
                    <span class="text-maroon font-bold">📅</span>
                    <input type="date" name="date" value="{{ $selectedDate }}" min="{{ now('Asia/Jakarta')->toDateString() }}" class="font-bold text-ink bg-transparent focus:outline-none">
                </div>

                <button type="submit" class="px-4 py-2 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer transition">
                    Terapkan
                </button>
            </form>
        </div>

        <!-- PILIHAN CEPAT TANGGAL -->
        <div>
            <span class="block text-[11px] font-semibold text-ink/70 mb-2">Pilihan Cepat Tanggal Keberangkatan:</span>
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                @foreach ($quickDates as $qd)
                    @php $isActive = $qd['iso'] === $selectedDate; @endphp
                    <a
                        href="{{ route('jadwal.index', ['origin' => $selectedOrigin, 'destination' => $selectedDestination, 'date' => $qd['iso']]) }}"
                        class="{{ $isActive ? 'bg-maroon text-cream shadow-md' : 'bg-white hover:bg-cream text-ink border border-gold/30' }} px-4 py-2 rounded-2xl shrink-0 text-left transition"
                    >
                        <span class="block text-[10px] uppercase font-bold {{ $isActive ? 'text-gold' : 'text-ink/60' }}">{{ $qd['day_label'] }}</span>
                        <span class="block text-xs font-bold">{{ $qd['date_label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- DAFTAR JADWAL TERSEDIA -->
    <div class="soft-card rounded-[2.2rem] p-5 sm:p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="font-display text-xl text-ink">
                Tersedia {{ $schedules->count() }} Jadwal Keberangkatan
            </h3>
            <span class="text-xs text-ink/70">
                Tanggal: <strong class="text-maroon">{{ \Carbon\Carbon::parse($selectedDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
            </span>
        </div>

        @if ($schedules->isEmpty())
            <div class="p-8 rounded-3xl bg-white/80 border border-gold/25 text-center space-y-2">
                <p class="font-display text-xl text-ink">Belum Ada Jadwal pada Tanggal Ini</p>
                <p class="text-xs text-ink/70">Coba pilih tanggal keberangkatan lain melalui pill tanggal di atas atau pilih rute yang berbeda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($schedules as $schedule)
                    @php
                        $totalSeats = $schedule->vehicle->total_seats ?? $schedule->vehicle->seats->count();
                        $occupied = $schedule->occupied_seats_count ?? 0;
                        $remaining = max(0, $totalSeats - $occupied);
                    @endphp
                    <div class="p-5 rounded-3xl bg-white/95 border border-gold/35 hover:border-maroon shadow-sm flex flex-col justify-between gap-4 transition">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-maroon">
                                        {{ $schedule->route->origin }} → {{ $schedule->route->destination }}
                                    </span>
                                    <div class="font-display text-2xl text-ink leading-tight mt-0.5">
                                        {{ $schedule->departure_time->format('H:i') }} WIB
                                    </div>
                                    <span class="text-[11px] text-ink/60">
                                        Estimasi tiba ± {{ $schedule->arrival_time->format('H:i') }} WIB
                                    </span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $remaining > 0 ? 'bg-mist/80 text-ink' : 'bg-red-100 text-vred' }}">
                                    {{ $remaining }} / {{ $totalSeats }} Kursi
                                </span>
                            </div>

                            <div class="mt-3 pt-3 border-t border-gold/20 text-xs space-y-1 text-ink/80">
                                <p>🚐 <strong class="text-ink">{{ $schedule->vehicle->brand }}</strong> ({{ $schedule->vehicle->plate_number }})</p>
                                <p>🧑‍✈️ Sopir: {{ $schedule->driver->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <span class="text-[10px] text-ink/60 block">Harga per Kursi</span>
                                <span class="font-display text-xl text-maroon">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                            </div>

                            @if ($remaining > 0)
                                <a href="{{ route('jadwal.pesan', $schedule) }}" class="px-4 py-2.5 rounded-2xl bg-maroon hover:bg-vred text-cream text-xs font-bold shadow transition">
                                    Pilih Kursi 3D →
                                </a>
                            @else
                                <span class="px-4 py-2.5 rounded-2xl bg-ink/15 text-ink/50 text-xs font-bold">Penuh</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
