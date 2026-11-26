<?php

use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Passenger\PaymentController;
use App\Http\Controllers\Passenger\ProfileController;
use App\Http\Controllers\Passenger\ScheduleController;
use App\Http\Controllers\Passenger\TicketController;
use Illuminate\Support\Facades\Route;

// Publicly-named Indonesian Passenger Routes (Auth Protected)
Route::middleware(['auth'])->group(function () {
    // Schedules & 3D Seat Selection
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/cari', [ScheduleController::class, 'search'])->name('jadwal.search');
    Route::get('/jadwal/{schedule}', [ScheduleController::class, 'showSeats'])->name('jadwal.show');
    Route::get('/jadwal/{schedule}/pesan', [ScheduleController::class, 'showSeats'])->name('jadwal.pesan');

    // Bookings
    Route::get('/pemesanan', [BookingController::class, 'history'])->name('pemesanan.index');
    Route::post('/pemesanan', [BookingController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{booking}', [BookingController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{booking}/batal', [BookingController::class, 'cancel'])->name('pemesanan.batal');

    // Payments
    Route::get('/pemesanan/{booking}/bayar', [PaymentController::class, 'show'])->name('pemesanan.bayar');
    Route::post('/pemesanan/{booking}/bayar', [PaymentController::class, 'uploadProof'])->name('pemesanan.bayar.store');

    // Tickets
    Route::get('/tiket', [TicketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/{ticket}', [TicketController::class, 'show'])->name('tiket.show');

    // Profile
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil.show');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});

// Existing /passenger/* routes preserved for backward compatibility and API integration
Route::middleware(['auth', 'passenger'])->prefix('passenger')->name('passenger.')->group(function () {
    // Dashboard & Schedules
    Route::get('/dashboard', [ScheduleController::class, 'index'])->name('dashboard');
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/search', [ScheduleController::class, 'search'])->name('schedules.search');
    Route::get('/schedules/{schedule}/seats', [ScheduleController::class, 'showSeats'])->name('schedules.seats');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'history'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payments
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/bookings/{booking}/payment', [PaymentController::class, 'uploadProof'])->name('payments.upload');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
});
