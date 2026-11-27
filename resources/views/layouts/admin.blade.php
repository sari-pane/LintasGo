@extends('layouts.app')

@section('title', $title ?? 'Panel Operasional — LintasGo Admin')
@section('backdrop-photo', 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1800&q=80')

@section('content')
<div class="flex-1 flex flex-col lg:flex-row max-w-[1440px] w-full mx-auto p-3 sm:p-5 gap-4 sm:gap-6 relative z-10">

    <!-- LEFT FLOATING ADMIN SIDEBAR -->
    <aside class="soft-rail rounded-[2rem] lg:w-64 shrink-0 p-4 flex flex-col justify-between gap-4 z-30 no-print">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-2 py-2 mb-4 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-maroon to-vred text-cream font-display text-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition">L</div>
                <div>
                    <span class="font-display text-xl text-maroon leading-none block">LintasGo</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gold">Operasional Admin</span>
                </div>
            </a>

            <nav class="flex lg:flex-col gap-1.5 overflow-x-auto pb-1 lg:pb-0 text-xs font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>📖</span> <span>Buku Ledger 3D</span>
                </a>

                <div class="hidden lg:block pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-ink/50">Master Data</div>

                <a href="{{ route('admin.rute.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.rute.*', 'admin.routes.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>🗺️</span> <span>Rute Travel</span>
                </a>

                <a href="{{ route('admin.armada.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.armada.*', 'admin.vehicles.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>🚐</span> <span>Armada & Kursi</span>
                </a>

                <a href="{{ route('admin.sopir.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.sopir.*', 'admin.drivers.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>🧑‍✈️</span> <span>Sopir Travel</span>
                </a>

                <a href="{{ route('admin.jadwal.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.jadwal.*', 'admin.schedules.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>📅</span> <span>Jadwal</span>
                </a>

                <div class="hidden lg:block pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-ink/50">Operasional</div>

                <a href="{{ route('admin.pembayaran.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.pembayaran.*', 'admin.payments.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>💳</span> <span>Verifikasi Bayar</span>
                </a>

                <a href="{{ route('admin.pemesanan.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.pemesanan.*', 'admin.bookings.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>📋</span> <span>Pemesanan</span>
                </a>

                <a href="{{ route('admin.checkin.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.checkin.*', 'admin.check-in.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>🎫</span> <span>Check-In QR</span>
                </a>

                <a href="{{ route('admin.laporan.index') }}" class="px-3.5 py-2.5 rounded-2xl flex items-center gap-2.5 shrink-0 transition {{ request()->routeIs('admin.laporan.*') ? 'bg-gradient-to-r from-maroon to-vred text-cream shadow-md' : 'text-ink hover:bg-white' }}">
                    <span>📊</span> <span>Laporan</span>
                </a>
            </nav>
        </div>

        <!-- Admin User Info & Logout -->
        <div class="hidden lg:block pt-3 border-t border-gold/25">
            <div class="p-3 rounded-2xl bg-white/80 border border-gold/25 flex items-center justify-between gap-2 text-xs">
                <div class="min-w-0">
                    <p class="font-bold text-ink truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-ink/60 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-vred/10 text-vred font-bold hover:bg-vred hover:text-cream transition cursor-pointer">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN ADMIN WORKSPACE -->
    <div class="flex-1 flex flex-col gap-5 min-w-0">
        <header class="flex flex-wrap items-center justify-between gap-4 px-2 pt-1 no-print">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl text-cream drop-shadow-sm tracking-tight">
                    @yield('page-title', 'Buku Pencatatan Hardcover Operasional')
                </h1>
                <p class="text-xs sm:text-sm text-cream/90 mt-0.5 drop-shadow-sm">
                    @yield('page-subtitle', 'Pantau manifest penumpang, verifikasi struk pembayaran, dan kelola jadwal armada LintasGo.')
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('home') }}" class="soft-card px-4 py-2 rounded-full text-xs font-semibold text-ink hover:text-maroon transition">
                    🌐 Web Publik
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="lg:hidden inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-full bg-vred text-cream text-xs font-semibold cursor-pointer">Keluar</button>
                </form>
            </div>
        </header>

        @if (session('success'))
            <div class="soft-card rounded-2xl px-4 py-3 border border-emerald-300 bg-emerald-50/95 text-emerald-900 text-xs font-semibold flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="soft-card rounded-2xl px-4 py-3 border border-vred/40 bg-red-50/95 text-vred text-xs font-semibold flex items-center gap-2">
                <span>⚠</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="soft-card rounded-2xl px-4 py-3 border border-vred/40 bg-red-50/95 text-vred text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <main class="flex-1 flex flex-col gap-5">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection
