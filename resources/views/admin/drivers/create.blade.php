@extends('layouts.admin')

@section('page-title', 'Tambah Sopir Baru')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85 max-w-xl">
        <form method="POST" action="{{ route('admin.sopir.store') }}" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-semibold text-ink mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Nomor Telepon / WA</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Nomor SIM</label>
                <input type="text" name="license_number" value="{{ old('license_number') }}" required class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-maroon hover:bg-vred text-cream font-bold shadow cursor-pointer">Simpan Sopir</button>
                <a href="{{ route('admin.sopir.index') }}" class="px-5 py-3 rounded-2xl bg-white border border-gold/30 font-bold text-ink">Batal</a>
            </div>
        </form>
    </div>
@endsection
