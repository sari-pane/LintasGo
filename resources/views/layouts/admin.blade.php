@extends('layouts.app')

@section('title', $title ?? 'Panel Operasional - LintasGo Admin')
@section('body-class', 'font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex')

@section('content')
    {{-- Sidebar Admin --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 border-r border-slate-800 bg-slate-900 text-slate-100 flex flex-col">
        {{-- Brand --}}
        <div class="flex h-16 items-center px-6 border-b border-slate-800">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2 font-bold text-lg text-white">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white font-extrabold text-sm">LG</span>
                <span>Lintas<span class="text-orange-400">Go</span> <span class="text-xs font-normal text-slate-400">Admin</span></span>
            </a>
        </div>

        {{-- Navigasi Menu Admin --}}
        <nav class="flex-1 space-y-1 px-3 py-4 text-sm font-medium overflow-y-auto">
            <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Master Data</div>

            <a href="{{ url('/admin/routes') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Rute Travel</span>
            </a>

            <a href="{{ url('/admin/vehicles') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Armada & Kursi</span>
            </a>

            <a href="{{ url('/admin/drivers') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Supir Travel</span>
            </a>

            <a href="{{ url('/admin/schedules') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Jadwal Keberangkatan</span>
            </a>

            <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Operasional</div>

            <a href="{{ url('/admin/payments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Verifikasi Pembayaran</span>
            </a>

            <a href="{{ url('/admin/check-in') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span>Check-in Tiket</span>
            </a>
        </nav>

        {{-- Profil Admin & Tombol Keluar --}}
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center justify-between text-xs">
                <div class="truncate mr-2">
                    <p class="font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@lintasgo.com' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') ?? '#' }}" class="inline shrink-0">
                    @csrf
                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Container Admin (Offset by sidebar width 64) --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        {{-- Topbar Admin --}}
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6 dark:border-slate-800 dark:bg-slate-900">
            <h1 class="text-base font-semibold text-slate-800 dark:text-slate-100">@yield('page-title', 'Panel Operasional')</h1>
            <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                <a href="{{ url('/') }}" target="_blank" class="text-blue-600 hover:underline">Lihat Web Publik &rarr;</a>
            </div>
        </header>

        {{-- Area Konten Admin --}}
        <main class="flex-1 p-6 lg:p-8">
            {{-- Flash Alert Messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-rose-50 p-4 border border-rose-200 text-rose-800 text-sm flex items-center justify-between dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('admin-content')
        </main>
    </div>
@endsection
