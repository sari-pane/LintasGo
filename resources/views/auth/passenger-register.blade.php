@extends('layouts.auth')

@section('title', 'Pendaftaran Penumpang - LintasGo')
@section('container-class', 'wide')

@section('content')
    <h2 class="card-title">Daftar Akun Penumpang Baru</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.attempt') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                class="form-control @error('name') is-invalid @enderror" 
                required 
                autofocus
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                class="form-control @error('email') is-invalid @enderror" 
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon / WhatsApp</label>
            <input 
                id="phone" 
                type="tel" 
                name="phone" 
                value="{{ old('phone') }}" 
                placeholder="Contoh: 081234567890" 
                class="form-control @error('phone') is-invalid @enderror" 
                required
            >
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password (Minimal 8 Karakter)</label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                class="form-control @error('password') is-invalid @enderror" 
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                class="form-control" 
                required
            >
        </div>

        <button type="submit" class="btn-submit">
            Daftar Sekarang
        </button>
    </form>
@endsection

@section('footer-links')
    <p>Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
@endsection
