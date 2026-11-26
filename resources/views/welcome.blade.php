@extends('layouts.passenger', [
    'title' => 'LintasGo 3D — Sistem Pemesanan & Operasional Travel Antar Kota',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1800&q=80',
])

@section('main-content')
    <!-- BINGKAI ORGANIK 3D ARMADA & PANEL PENCARIAN -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <!-- Panggung 3D Armada Bersudut Lengkung (8 Kolom) -->
        <div class="lg:col-span-8 relative h-[430px] sm:h-[470px] organic-frame overflow-hidden bg-gradient-to-b from-mist/75 to-cream">
            <div id="three-stage-wrapper" class="absolute inset-0 w-full h-full"></div>

            <!-- Overlay Pintu Gerbang / Masuk Armada -->
            <div id="gate-overlay" class="absolute inset-0 z-20 flex flex-col items-center justify-end p-6 sm:p-8 bg-gradient-to-t from-ink/75 via-ink/25 to-transparent text-center">
                <span class="px-3.5 py-1 rounded-full bg-cream/90 text-maroon text-[11px] font-bold uppercase tracking-wider mb-2 shadow">
                    Armada Eksekutif Jawa Timur
                </span>
                <h2 class="font-display text-2xl sm:text-3xl text-cream drop-shadow">
                    Toyota HiAce • Isuzu Elf • MPV Zenix
                </h2>
                <p class="text-xs text-cream/90 max-w-md mt-1 mb-4">
                    Klik tombol di bawah untuk membuka pintu geser armada 3D dan memutar musik latar perjalanan LintasGo.
                </p>
                <button type="button" onclick="openSlidingDoorAndMusic()" class="px-6 py-3 rounded-full bg-maroon hover:bg-vred text-cream font-semibold text-xs sm:text-sm shadow-xl border border-gold/50 transition transform hover:scale-105 cursor-pointer flex items-center gap-2">
                    <span>🚪 Buka Pintu Geser & Masuk</span>
                </button>
            </div>

            <!-- HUD Kontrol Armada 3D (Muncul setelah pintu dibuka) -->
            <div id="stage-hud" class="hidden absolute inset-0 z-10 p-4 flex flex-col justify-between pointer-events-none">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span id="active-vehicle-badge" class="soft-card px-4 py-1.5 rounded-full text-xs font-bold text-maroon pointer-events-auto">
                        Toyota HiAce Commuter (Pintu Terbuka)
                    </span>
                    <button type="button" onclick="toggleLandingDoor()" class="soft-card px-4 py-1.5 rounded-full text-xs font-bold text-ink hover:bg-white transition pointer-events-auto cursor-pointer">
                        🚪 Buka / Tutup Pintu Geser
                    </button>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="soft-card p-1.5 rounded-full flex items-center gap-1.5 pointer-events-auto">
                        <button type="button" onclick="switchLandingVehicle('hiace')" id="veh-btn-hiace" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-maroon text-cream shadow cursor-pointer">HiAce</button>
                        <button type="button" onclick="switchLandingVehicle('elf')" id="veh-btn-elf" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/90 text-ink hover:bg-white shadow-sm cursor-pointer">Isuzu Elf</button>
                        <button type="button" onclick="switchLandingVehicle('mpv')" id="veh-btn-mpv" class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/90 text-ink hover:bg-white shadow-sm cursor-pointer">MPV Executive</button>
                    </div>
                    <span class="soft-card px-3.5 py-1.5 rounded-full text-[11px] font-medium text-ink/80 pointer-events-auto">
                        Geser layar untuk memutar 360°
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Pencarian Jadwal Spesifik (4 Kolom) -->
        <div class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 flex flex-col justify-between border-2 border-white/85">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-maroon">Reservasi Tiket</span>
                    @auth
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800">✓ Terbuka</span>
                    @else
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-maroon/10 text-maroon">🔒 Wajib Login</span>
                    @endauth
                </div>
                <h3 class="font-display text-2xl text-ink">Cari Jadwal Travel</h3>
                <p class="text-xs text-ink/70 mt-1">Pilih kota asal, tujuan, dan tanggal keberangkatan spesifik Anda.</p>

                <form id="home-search-form" method="GET" action="{{ route('jadwal.search') }}" class="space-y-3.5 mt-5 text-xs">
                    <div>
                        <label class="block font-semibold text-ink mb-1">Kota Keberangkatan</label>
                        <select name="origin" id="search-origin" class="w-full px-4 py-3 rounded-2xl bg-white border border-gold/40 font-semibold text-ink focus:outline-none focus:border-maroon cursor-pointer">
                            @forelse ($origins as $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                            @empty
                                <option value="Jember">Jember</option>
                                <option value="Surabaya">Surabaya</option>
                                <option value="Malang">Malang</option>
                                <option value="Banyuwangi">Banyuwangi</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-ink mb-1">Kota Tujuan</label>
                        <select name="destination" id="search-dest" class="w-full px-4 py-3 rounded-2xl bg-white border border-gold/40 font-semibold text-ink focus:outline-none focus:border-maroon cursor-pointer">
                            @forelse ($destinations as $city)
                                <option value="{{ $city }}" {{ $loop->index === 1 ? 'selected' : '' }}>{{ $city }}</option>
                            @empty
                                <option value="Surabaya">Surabaya</option>
                                <option value="Malang">Malang</option>
                                <option value="Banyuwangi">Banyuwangi</option>
                                <option value="Jember">Jember</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-ink mb-1">Tanggal Keberangkatan</label>
                        <input
                            type="date"
                            name="date"
                            id="search-date"
                            min="{{ now('Asia/Jakarta')->toDateString() }}"
                            value="{{ now('Asia/Jakarta')->addDay()->toDateString() }}"
                            @guest onclick="event.preventDefault(); openLoginModal();" readonly @endguest
                            class="w-full px-4 py-3 rounded-2xl bg-white border border-gold/40 font-semibold text-ink focus:outline-none focus:border-maroon cursor-pointer"
                        >
                    </div>

                    @auth
                        <button type="submit" class="w-full mt-2 py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
                            Cari Jadwal & Pilih Kursi 3D →
                        </button>
                    @else
                        <button type="button" onclick="openLoginModal()" class="w-full mt-2 py-3.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
                            Masuk untuk Cari Jadwal & Pesan →
                        </button>
                    @endauth
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR RUTE UMUM (DAPAT DIAKSES TANPA LOGIN / GUEST) -->
    <div id="rute-umum" class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Akses Publik • Rute Umum</span>
                <h3 class="font-display text-xl sm:text-2xl text-ink">Jurusan Layanan Utama LintasGo</h3>
            </div>
            <span class="text-xs text-ink/70">
                @auth Klik rute untuk memilih jadwal & kursi 3D @else Klik rute untuk masuk dan mengecek jadwal tanggal @endauth
            </span>
        </div>

        @if ($publicRoutes->isEmpty())
            <div class="p-6 rounded-2xl bg-white/80 text-center text-xs text-ink/70">
                Belum ada rute aktif yang terdaftar.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($publicRoutes as $route)
                    @php
                        $routeUrl = auth()->check()
                            ? route('jadwal.index', ['origin' => $route->origin, 'destination' => $route->destination])
                            : '#';
                    @endphp
                    <a
                        href="{{ $routeUrl }}"
                        @guest onclick="event.preventDefault(); showToast('Silakan masuk terlebih dahulu untuk melihat jadwal tanggal rute {{ $route->origin }} → {{ $route->destination }}.', 'info'); openLoginModal();" @endguest
                        class="p-4 rounded-2xl bg-white/90 hover:bg-white border border-gold/30 hover:border-maroon transition flex flex-col justify-between shadow-sm group"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-mist text-ink text-[10px] font-bold">
                                {{ $route->estimated_duration }}
                            </span>
                            @if ($route->schedules_min_price)
                                <span class="text-maroon font-bold text-xs">
                                    Mulai Rp {{ number_format($route->schedules_min_price / 1000, 0) }}rb
                                </span>
                            @elseif ($route->distance_km)
                                <span class="text-maroon font-bold text-xs">{{ number_format($route->distance_km, 0) }} km</span>
                            @endif
                        </div>
                        <h4 class="font-display text-lg text-ink mt-2.5 group-hover:text-maroon transition">
                            {{ $route->origin }} → {{ $route->destination }}
                        </h4>
                        <p class="text-xs text-ink/70 mt-1 line-clamp-2">
                            {{ $route->description ?? 'Layanan travel antar kota eksekutif dengan armada ber-AC penuh dan pengemudi berpengalaman.' }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @include('partials.three-vehicle-script')
    <script>
        let landingStage = null;

        document.addEventListener('DOMContentLoaded', () => {
            landingStage = initLintasGoThreeStage({
                containerId: 'three-stage-wrapper',
                vehicleType: 'hiace',
                interactiveSeats: false,
                initialDoorOpen: false,
                transparentRoof: false
            });
        });

        function openSlidingDoorAndMusic() {
            if (landingStage) {
                landingStage.setDoorOpen(true);
                landingStage.setOrbit(1.25, 0.38);
            }
            playTone(180, 340, 0.55);
            startAmbientMusic();

            document.getElementById('gate-overlay').classList.add('hidden');
            document.getElementById('stage-hud').classList.remove('hidden');
            showToast('Pintu geser armada terbuka! Musik latar LintasGo aktif.', 'success');
        }

        function toggleLandingDoor() {
            if (!landingStage) return;
            const open = landingStage.toggleDoor();
            playTone(open ? 190 : 320, open ? 330 : 180, 0.45);
            const name = landingStage.getVehicleName();
            document.getElementById('active-vehicle-badge').textContent = `${name} (${open ? 'Pintu Terbuka' : 'Pintu Tertutup'})`;
        }

        function switchLandingVehicle(type) {
            if (!landingStage) return;
            landingStage.switchVehicle(type);
            playTone(420, 620, 0.18);

            ['hiace', 'elf', 'mpv'].forEach(v => {
                const btn = document.getElementById(`veh-btn-${v}`);
                if (btn) {
                    btn.className = v === type
                        ? 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-maroon text-cream shadow cursor-pointer'
                        : 'px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/90 text-ink hover:bg-white shadow-sm cursor-pointer';
                }
            });

            const open = landingStage.isDoorOpen();
            document.getElementById('active-vehicle-badge').textContent = `${landingStage.getVehicleName(type)} (${open ? 'Pintu Terbuka' : 'Pintu Tertutup'})`;
        }
    </script>
@endpush
