@extends('layouts.auth')

@section('title', 'Masuk Penumpang - LintasGo')

@section('content')
    <h2 class="card-title">Masuk Akun Penumpang</h2>

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

    @if ($errors->has('email') && !old('email'))
        <div class="alert alert-danger">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                class="form-control @error('email') is-invalid @enderror" 
                required 
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
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

        <div class="form-check">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Ingat saya</label>
        </div>

        <button type="submit" class="btn-submit">
            Masuk
        </button>
    </form>
@endsection

@section('footer-links')
    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    <p style="margin-top: 8px;"><a href="{{ route('admin.login') }}" style="color: var(--text-muted); font-size: 13px;">Login sebagai Admin &rarr;</a></p>
@endsection
