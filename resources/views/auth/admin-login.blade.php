@extends('layouts.auth')

@section('title', 'Login Administrator - LintasGo')

@section('content')
    <div style="text-align: center; margin-bottom: 12px;">
        <span class="admin-badge">Portal Khusus Internal</span>
    </div>
    <h2 class="card-title" style="text-align: center;">Login Administrator</h2>

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

    <form method="POST" action="{{ route('admin.login.attempt') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Administrator</label>
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
            <label for="remember">Ingat sesi ini</label>
        </div>

        <button type="submit" class="btn-submit" style="background-color: #0f172a;">
            Masuk ke Portal Operasional
        </button>
    </form>
@endsection

@section('footer-links')
    <p><a href="{{ route('login') }}" style="color: var(--text-muted);">&larr; Kembali ke Portal Penumpang</a></p>
@endsection
