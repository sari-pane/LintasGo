<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PassengerAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Passenger Auth
    Route::get('/login', [PassengerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PassengerAuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [PassengerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [PassengerAuthController::class, 'register'])->name('register.attempt');

    // Admin Auth
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [PassengerAuthController::class, 'logout'])->name('logout');
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
