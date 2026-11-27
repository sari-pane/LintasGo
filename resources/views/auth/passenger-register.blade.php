@extends('layouts.auth')

@section('title', 'Pendaftaran Penumpang — LintasGo')
@section('container-class', 'max-w-lg')

@section('auth-content')
    <span class="text-[11px] font-bold uppercase tracking-wider text-gold">Registrasi Penumpang</span>
    <h2 class="font-display text-2xl text-ink mt-0.5 mb-4">Daftar Akun Penumpang Baru</h2>

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

    <form method="POST" action="{{ route('register.attempt') }}" class="space-y-3.5">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold text-ink mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('name') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required autofocus>
            @error('name')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-ink mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('email') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required>
            @error('email')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-xs font-semibold text-ink mb-1">Nomor Telepon / WhatsApp</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('phone') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required>
            @error('phone')
                <p class="text-xs text-vred mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-semibold text-ink mb-1">Password (Min. 8 Karakter)</label>
                <input id="password" type="password" name="password" class="w-full px-4 py-2.5 rounded-2xl bg-white border @error('password') border-vred @else border-gold/40 @enderror text-sm text-ink focus:outline-none focus:border-maroon" required>
                @error('password')
                    <p class="text-xs text-vred mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-ink mb-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-sm text-ink focus:outline-none focus:border-maroon" required>
            </div>
        </div>

        <button type="submit" class="w-full py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer mt-2">
            Daftar Sekarang
        </button>
    </form>
@endsection

@section('footer-links')
    <p>Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-maroon hover:underline">Masuk di sini</a></p>
@endsection
