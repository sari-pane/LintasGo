@extends('layouts.passenger', [
    'title' => 'Profil Penumpang — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', 'Profil Akun Anda')
@section('header-subtext', 'Kelola informasi kontak dan keamanan akun LintasGo Anda.')

@section('main-content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <div class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-maroon text-cream font-display text-2xl flex items-center justify-center shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-display text-xl text-ink">{{ $user->name }}</h2>
                    <p class="text-xs text-ink/70">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center pt-2">
                <div class="p-3 rounded-2xl bg-white border border-gold/25">
                    <span class="font-display text-2xl text-maroon block">{{ $stats['total_bookings'] }}</span>
                    <span class="text-[10px] font-semibold text-ink/65">Pesanan</span>
                </div>
                <div class="p-3 rounded-2xl bg-white border border-gold/25">
                    <span class="font-display text-2xl text-ink block">{{ $stats['confirmed_bookings'] }}</span>
                    <span class="text-[10px] font-semibold text-ink/65">Sukses</span>
                </div>
                <div class="p-3 rounded-2xl bg-white border border-gold/25">
                    <span class="font-display text-2xl text-maroon block">{{ $stats['active_tickets'] }}</span>
                    <span class="text-[10px] font-semibold text-ink/65">E-Tiket</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profil.update') }}" class="lg:col-span-8 soft-card rounded-[2.2rem] p-6 border-2 border-white/85 space-y-4 text-xs">
            @csrf
            @method('PUT')

            <h3 class="font-display text-2xl text-ink">Perbarui Data Profil</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-ink mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-sm text-ink focus:outline-none focus:border-maroon">
                </div>
                <div>
                    <label class="block font-semibold text-ink mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-sm text-ink focus:outline-none focus:border-maroon">
                </div>
            </div>

            <div class="pt-3 border-t border-gold/25">
                <span class="font-bold text-maroon block mb-2">Ubah Kata Sandi (Opsional)</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-ink mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" class="w-full px-3.5 py-2.5 rounded-2xl bg-white border border-gold/40 text-ink focus:outline-none focus:border-maroon">
                    </div>
                    <div>
                        <label class="block font-semibold text-ink mb-1">Password Baru</label>
                        <input type="password" name="new_password" class="w-full px-3.5 py-2.5 rounded-2xl bg-white border border-gold/40 text-ink focus:outline-none focus:border-maroon">
                    </div>
                    <div>
                        <label class="block font-semibold text-ink mb-1">Konfirmasi Baru</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-3.5 py-2.5 rounded-2xl bg-white border border-gold/40 text-ink focus:outline-none focus:border-maroon">
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold text-xs shadow-lg shadow-maroon/25 transition cursor-pointer">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
@endsection
