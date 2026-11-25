<?php

use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Passenger\PaymentController;
use App\Http\Controllers\Passenger\ScheduleController;
use App\Http\Controllers\Passenger\TicketController;
use Illuminate\Support\Facades\Route;

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
