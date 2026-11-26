@extends('layouts.auth')

@section('title', 'Masuk Akun Penumpang — LintasGo')

@section('auth-content')
    <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Portal Penumpang</span>
    <h2 class="font-display text-2xl text-ink mt-0.5 mb-4">Masuk Akun Penumpang</h2>

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

    @if ($errors->has('email') && !old('email'))
        <div class="mb-4 p-3 rounded-2xl bg-red-50 border border-vred/30 text-vred text-xs font-semibold">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-ink mb-1">Alamat Email</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('email') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon"
                required
                autofocus
            >
            @error('email')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-ink mb-1">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('password') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon"
                required
            >
            @error('password')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 text-xs text-ink/75 cursor-pointer">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="rounded border-gold/40 text-maroon focus:ring-maroon">
            <span>Ingat saya</span>
        </label>

        <button type="submit" class="w-full py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
            Masuk
        </button>
    </form>
@endsection

@section('footer-links')
    <p>Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-maroon hover:underline">Daftar di sini</a></p>
    <p><a href="{{ route('admin.login') }}" class="text-ink/70 hover:text-maroon font-medium">Login sebagai Admin →</a></p>
@endsection
