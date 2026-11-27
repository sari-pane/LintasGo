<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ManifestController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes Management (/admin/rute & /admin/routes)
    Route::get('/rute', [RouteController::class, 'index'])->name('rute.index');
    Route::get('/rute/create', [RouteController::class, 'create'])->name('rute.create');
    Route::post('/rute', [RouteController::class, 'store'])->name('rute.store');
    Route::get('/rute/{route}/edit', [RouteController::class, 'edit'])->name('rute.edit');
    Route::put('/rute/{route}', [RouteController::class, 'update'])->name('rute.update');
    Route::delete('/rute/{route}', [RouteController::class, 'destroy'])->name('rute.destroy');
    Route::get('/routes', [RouteController::class, 'index'])->name('routes.index');

    // Vehicles & Seats Management (/admin/armada & /admin/vehicles)
    Route::get('/armada', [VehicleController::class, 'index'])->name('armada.index');
    Route::get('/armada/create', [VehicleController::class, 'create'])->name('armada.create');
    Route::post('/armada', [VehicleController::class, 'store'])->name('armada.store');
    Route::get('/armada/{vehicle}', [VehicleController::class, 'show'])->name('armada.show');
    Route::get('/armada/{vehicle}/edit', [VehicleController::class, 'edit'])->name('armada.edit');
    Route::put('/armada/{vehicle}', [VehicleController::class, 'update'])->name('armada.update');
    Route::delete('/armada/{vehicle}', [VehicleController::class, 'destroy'])->name('armada.destroy');
    Route::post('/armada/{vehicle}/kursi', [VehicleController::class, 'storeSeat'])->name('armada.kursi.store');
    Route::delete('/armada/{vehicle}/kursi/{seat}', [VehicleController::class, 'destroySeat'])->name('armada.kursi.destroy');
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');

    // Drivers Management (/admin/sopir & /admin/drivers)
    Route::get('/sopir', [DriverController::class, 'index'])->name('sopir.index');
    Route::get('/sopir/create', [DriverController::class, 'create'])->name('sopir.create');
    Route::post('/sopir', [DriverController::class, 'store'])->name('sopir.store');
    Route::get('/sopir/{driver}/edit', [DriverController::class, 'edit'])->name('sopir.edit');
    Route::put('/sopir/{driver}', [DriverController::class, 'update'])->name('sopir.update');
    Route::delete('/sopir/{driver}', [DriverController::class, 'destroy'])->name('sopir.destroy');
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');

    // Schedules Management (/admin/schedules & /admin/jadwal)
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [ScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/create', [ScheduleController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{schedule}', [ScheduleController::class, 'show'])->name('jadwal.show');
    Route::get('/jadwal/{schedule}/edit', [ScheduleController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{schedule}', [ScheduleController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{schedule}', [ScheduleController::class, 'destroy'])->name('jadwal.destroy');

    // Bookings Monitoring (/admin/bookings & /admin/pemesanan)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/pemesanan', [BookingController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/{booking}', [BookingController::class, 'show'])->name('pemesanan.show');

    // Payments Verification & Proof Inspection (/admin/payments & /admin/pembayaran)
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentVerificationController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    Route::get('/payments/{payment}/proof', [PaymentVerificationController::class, 'streamProof'])->name('payments.proof');

    Route::get('/pembayaran', [PaymentVerificationController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{payment}', [PaymentVerificationController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('pembayaran.verify');
    Route::get('/pembayaran/{payment}/proof', [PaymentVerificationController::class, 'streamProof'])->name('pembayaran.proof');

    // Check-in (/admin/checkin & /admin/check-in)
    Route::get('/checkin', [CheckInController::class, 'index'])->name('checkin.index');
    Route::post('/checkin', [CheckInController::class, 'process'])->name('checkin.process');
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('/check-in', [CheckInController::class, 'process'])->name('check-in.process');

    // Manifest & Reports
    Route::get('/manifest/{schedule}', [ManifestController::class, 'show'])->name('manifest.show');
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
});
