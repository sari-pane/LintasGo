@extends('layouts.app')

@section('title', $title ?? 'LintasGo 3D — Sistem Pemesanan Travel Antar Kota')
@section('backdrop-photo', $backdropPhoto ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1800&q=80')

@section('content')
<div class="flex-1 flex flex-col md:flex-row max-w-[1440px] w-full mx-auto p-3 sm:p-5 gap-4 sm:gap-6 relative z-10">

    <!-- LEFT FLOATING SIDEBAR RAIL -->
    <aside class="soft-rail rounded-[2rem] md:w-28 lg:w-32 shrink-0 p-3 sm:p-4 flex md:flex-col items-center justify-between md:justify-start gap-3 sm:gap-4 z-30 no-print">
        <a href="{{ route('home') }}" title="Beranda Armada 3D" class="flex flex-col items-center gap-1.5 group md:mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-maroon to-vred text-cream font-display text-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition">
                L
            </div>
            <span class="font-display text-base text-maroon tracking-tight leading-none">LintasGo</span>
        </a>

        <nav class="flex md:flex-col items-center gap-2.5 sm:gap-3 overflow-x-auto w-full justify-center">
            <a href="{{ route('home') }}" class="sidebar-btn {{ request()->routeIs('home') ? 'active' : '' }} w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 text-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[11px] font-semibold">Beranda</span>
            </a>

            <a href="{{ auth()->check() ? route('jadwal.index') : '#' }}" @guest onclick="event.preventDefault(); openLoginModal();" @endguest class="sidebar-btn {{ request()->routeIs('jadwal.*', 'passenger.schedules.*', 'passenger.dashboard') ? 'active' : '' }} w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 relative text-center">
                @guest <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-gold" title="Wajib Login"></span> @endguest
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span class="text-[11px] font-semibold leading-tight">Jadwal & Kursi</span>
            </a>

            <a href="{{ auth()->check() ? route('pemesanan.index') : '#' }}" @guest onclick="event.preventDefault(); openLoginModal();" @endguest class="sidebar-btn {{ request()->routeIs('pemesanan.*', 'passenger.bookings.*', 'passenger.payments.*') ? 'active' : '' }} w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 relative text-center">
                @guest <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-gold" title="Wajib Login"></span> @endguest
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-[11px] font-semibold">Pesanan</span>
            </a>

            <a href="{{ auth()->check() ? route('tiket.index') : '#' }}" @guest onclick="event.preventDefault(); openLoginModal();" @endguest class="sidebar-btn {{ request()->routeIs('tiket.*', 'passenger.tickets.*') ? 'active' : '' }} w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 relative text-center">
                @guest <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-gold" title="Wajib Login"></span> @endguest
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                <span class="text-[11px] font-semibold">E-Tiket</span>
            </a>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-btn w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span class="text-[11px] font-semibold">Admin</span>
                    </a>
                @else
                    <a href="{{ route('profil.show') }}" class="sidebar-btn {{ request()->routeIs('profil.*') ? 'active' : '' }} w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 shrink-0 text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-[11px] font-semibold">Profil</span>
                    </a>
                @endif
            @endauth
        </nav>

        <!-- Audio Toggle -->
        <div class="hidden md:flex flex-col items-center mt-auto pt-3">
            <button type="button" id="btn-audio-sidebar" onclick="toggleAudio()" class="sidebar-btn w-16 h-16 rounded-2xl flex flex-col items-center justify-center gap-1 p-2 cursor-pointer" title="Musik Latar">
                <span class="flex items-end gap-0.5 h-4">
                    <span class="w-1 h-2 bg-maroon rounded-full"></span>
                    <span class="w-1 h-3.5 bg-vred rounded-full"></span>
                    <span class="w-1 h-2.5 bg-gold rounded-full"></span>
                </span>
                <span id="audio-status-text" class="text-[10px] font-semibold text-ink/80">Musik OFF</span>
            </button>
        </div>
    </aside>

    <!-- RIGHT WORKSPACE -->
    <div class="flex-1 flex flex-col gap-5 min-w-0">
        <!-- TOP BAR -->
        <header class="flex flex-wrap items-center justify-between gap-4 px-2 pt-1 no-print">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl md:text-4xl text-cream drop-shadow-sm tracking-tight">
                    @yield('header-greeting', auth()->check() ? 'Halo, ' . auth()->user()->name . ' 👋' : 'Selamat Datang di LintasGo 👋')
                </h1>
                <p class="text-xs sm:text-sm text-cream/90 mt-0.5 drop-shadow-sm">
                    @yield('header-subtext', 'Jelajahi rute antar kota Jawa Timur dengan armada nyaman & reservasi 3D interaktif.')
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @auth
                    <a href="{{ route('jadwal.index') }}" class="soft-card px-4 py-2.5 rounded-full flex items-center gap-2 hover:border-maroon/40 transition text-xs font-medium text-ink">
                        <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Cari Jadwal</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-maroon/10 text-maroon">✓ Aktif</span>
                    </a>
                @else
                    <button type="button" onclick="openLoginModal()" class="soft-card px-4 py-2.5 rounded-full flex items-center gap-2 cursor-pointer hover:border-maroon/40 transition">
                        <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="text-xs font-medium text-ink/75 pr-1">Cari jadwal berdasarkan tanggal...</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-maroon/10 text-maroon">🔒 Login</span>
                    </button>
                @endauth

                <button type="button" onclick="toggleAudio()" class="md:hidden soft-card p-2.5 rounded-full text-maroon cursor-pointer" title="Musik Latar">🎵</button>

                @auth
                    <div class="flex items-center gap-2 soft-card px-3.5 py-2 rounded-full">
                        <span class="w-2.5 h-2.5 rounded-full bg-maroon"></span>
                        <span class="text-xs font-bold text-ink">{{ auth()->user()->isAdmin() ? 'Admin' : 'Penumpang' }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                            @csrf
                            <button type="submit" class="text-[11px] font-bold text-vred hover:underline cursor-pointer">Keluar</button>
                        </form>
                    </div>
                @else
                    <button type="button" onclick="openLoginModal()" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream font-semibold text-xs sm:text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
                        Masuk / Daftar
                    </button>
                @endauth
            </div>
        </header>

        <!-- FLASH BANNERS (ACCESSIBLE FALLBACK) -->
        @if (session('success'))
            <div class="soft-card rounded-2xl px-4 py-3 border border-emerald-300 bg-emerald-50/90 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="soft-card rounded-2xl px-4 py-3 border border-vred/40 bg-red-50/90 text-vred text-xs font-semibold flex items-center gap-2">
                <span>⚠</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="soft-card rounded-2xl px-4 py-3 border border-vred/40 bg-red-50/90 text-vred text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <main class="flex-1 flex flex-col gap-5">
            @yield('main-content')
        </main>

        <footer class="mt-auto py-4 px-2 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink/60 no-print">
            <p>&copy; {{ date('Y') }} LintasGo 3D Travel Antar Kota Jawa Timur.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-maroon">Beranda</a>
                @auth
                    <a href="{{ route('jadwal.index') }}" class="hover:text-maroon">Cari Jadwal</a>
                    <a href="{{ route('pemesanan.index') }}" class="hover:text-maroon">Riwayat Pemesanan</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-maroon">Login Penumpang</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-maroon">Portal Admin</a>
                @endauth
            </div>
        </footer>
    </div>
</div>
@endsection
