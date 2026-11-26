@extends('layouts.auth')

@section('title', 'Login Administrator — LintasGo')

@section('auth-content')
    <div class="text-center mb-3">
        <span class="inline-block px-3 py-1 rounded-full bg-ink/10 text-ink text-[10px] font-bold uppercase tracking-wider">Portal Khusus Internal</span>
        <h2 class="font-display text-2xl text-ink mt-2">Login Administrator</h2>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded-2xl bg-red-50 border border-vred/30 text-vred text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-ink mb-1">Email Administrator</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('email') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required autofocus>
            @error('email')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-ink mb-1">Password</label>
            <input id="password" type="password" name="password" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('password') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required>
            @error('password')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-xs text-ink/75 cursor-pointer">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="rounded border-gold/40 text-maroon focus:ring-maroon">
            <span>Ingat sesi ini</span>
        </label>

        <button type="submit" class="w-full py-3 rounded-2xl bg-ink hover:bg-maroon text-cream font-semibold text-sm shadow-lg transition cursor-pointer">
            Masuk ke Portal Operasional
        </button>
    </form>
@endsection

@section('footer-links')
    <p><a href="{{ route('login') }}" class="font-semibold text-ink/75 hover:text-maroon">← Kembali ke Portal Penumpang</a></p>
@endsection
