@extends('layouts.app')

@section('title', $title ?? 'LintasGo - Travel Antar Kota')
@section('body-class', 'font-sans antialiased bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 min-h-screen flex flex-col')

@section('content')
    {{-- Header / Navbar Penumpang --}}
    <header class="sticky top-0 z-40 w-full border-b border-slate-200 bg-white/95 backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-bold text-xl text-blue-600 dark:text-blue-400">
                    <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span>Lintas<span class="text-orange-500">Go</span></span>
                </a>
                <nav class="hidden md:flex items-center gap-5 text-sm font-medium text-slate-600 dark:text-slate-300">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
                    <a href="{{ url('/#jadwal') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Jadwal & Rute</a>
                    <a href="{{ url('/#tentang') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Tentang Kami</a>
                    <a href="{{ url('/#kontak') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Bantuan</a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-3.5 py-1.5 text-xs font-semibold text-white hover:bg-slate-700 transition">
                            <span>Panel Admin</span>
                        </a>
                    @else
                        <div class="flex items-center gap-3 text-sm">
                            <span class="font-medium text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') ?? '#' }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-rose-600 hover:underline">Keluar</button>
                            </form>
                        </div>
                    @endif
                @else
                    <a href="{{ url('/auth/login') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Content Penumpang --}}
    <main class="flex-1">
        {{-- Flash Notification Alerts --}}
        @if (session('success'))
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-rose-50 p-4 border border-rose-200 text-rose-800 text-sm flex items-center justify-between dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-300">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('main-content')
    </main>

    {{-- Footer Penumpang --}}
    <footer class="mt-auto border-t border-slate-200 bg-white py-8 text-center text-xs text-slate-500 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} LintasGo Travel. Seluruh hak cipta dilindungi.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-blue-600">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-blue-600">Kebijakan Privasi</a>
                <a href="#" class="hover:text-blue-600">Hubungi Kami</a>
            </div>
        </div>
    </footer>
@endsection
