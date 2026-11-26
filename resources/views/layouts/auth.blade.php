@extends('layouts.app')

@section('content')
<div class="flex-1 flex flex-col items-center justify-center p-4 sm:p-6 relative z-10 min-h-screen">
    <div class="w-full @yield('container-class', 'max-w-md')">
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                <span class="w-12 h-12 rounded-2xl bg-gradient-to-br from-maroon to-vred text-cream font-display text-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition">L</span>
                <div class="text-left">
                    <span class="font-display text-3xl text-cream drop-shadow-sm block leading-none">LintasGo</span>
                    <span class="text-xs text-cream/90 drop-shadow-sm">Travel Antar Kota Jawa Timur</span>
                </div>
            </a>
        </div>

        <div class="soft-card rounded-[2.2rem] p-7 sm:p-8 border-2 border-white/85 shadow-2xl">
            @yield('auth-content')
        </div>

        <div class="mt-5 text-center text-xs text-ink/75 space-y-2">
            @yield('footer-links')
            <p class="pt-1">
                <a href="{{ route('home') }}" class="font-semibold text-maroon hover:underline">← Kembali ke Beranda 3D</a>
            </p>
        </div>
    </div>
</div>
@endsection
