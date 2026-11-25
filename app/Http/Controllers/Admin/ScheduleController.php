<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreScheduleRequest;
use App\Http\Requests\Admin\UpdateScheduleRequest;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Vehicle;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        $query = Schedule::query()->with(['route', 'vehicle', 'driver']);

        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->orderBy('departure_time', 'desc')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $schedules]);
        }

        return view('admin.schedules.index', compact('schedules'));
    }

    public function create(): View|JsonResponse
    {
        $routes = Route::all();
        $vehicles = Vehicle::where('status', 'active')->get();
        $drivers = Driver::where('status', 'active')->get();

        return view('admin.schedules.create', compact('routes', 'vehicles', 'drivers'));
    }

    public function store(StoreScheduleRequest $request): RedirectResponse|JsonResponse
    {
        $depTime = Carbon::parse($request->validated('departure_time'), 'Asia/Jakarta');
        $arrTime = Carbon::parse($request->validated('arrival_time'), 'Asia/Jakarta');

        try {
            $this->scheduleService->assertNoConflict(
                (int) $request->validated('vehicle_id'),
                (int) $request->validated('driver_id'),
                $depTime,
                $arrTime
            );

            $schedule = Schedule::create([
                'route_id' => $request->validated('route_id'),
                'vehicle_id' => $request->validated('vehicle_id'),
                'driver_id' => $request->validated('driver_id'),
                'departure_time' => $depTime,
                'arrival_time' => $arrTime,
                'price' => $request->validated('price'),
                'status' => 'scheduled',
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal perjalanan berhasil dibuat.',
                    'data' => $schedule->load(['route', 'vehicle', 'driver']),
                ], 201);
            }

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Jadwal perjalanan berhasil ditambahkan.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['conflict' => $e->getMessage()]);
        }
    }

    public function show(Schedule $schedule, Request $request): View|JsonResponse
    {
        $schedule->load([
            'route',
            'vehicle.seats',
            'driver',
            'bookings.user',
            'bookings.bookingSeats.seat',
            'bookings.bookingSeats.ticket',
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'data' => $schedule]);
        }

        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule): View
    {
        $routes = Route::all();
        $vehicles = Vehicle::where('status', 'active')->get();
        $drivers = Driver::where('status', 'active')->get();

        return view('admin.schedules.edit', compact('schedule', 'routes', 'vehicles', 'drivers'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse|JsonResponse
    {
        $vehicleId = $request->validated('vehicle_id', $schedule->vehicle_id);
        $driverId = $request->validated('driver_id', $schedule->driver_id);
        $depTime = Carbon::parse($request->validated('departure_time', $schedule->departure_time), 'Asia/Jakarta');
        $arrTime = Carbon::parse($request->validated('arrival_time', $schedule->arrival_time), 'Asia/Jakarta');

        try {
            $this->scheduleService->assertNoConflict(
                (int) $vehicleId,
                (int) $driverId,
                $depTime,
                $arrTime,
                $schedule->id
            );

            $schedule->update([
                'route_id' => $request->validated('route_id', $schedule->route_id),
                'vehicle_id' => $vehicleId,
                'driver_id' => $driverId,
                'departure_time' => $depTime,
                'arrival_time' => $arrTime,
                'price' => $request->validated('price', $schedule->price),
                'status' => $request->validated('status', $schedule->status),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal berhasil diperbarui.',
                    'data' => $schedule->fresh(['route', 'vehicle', 'driver']),
                ]);
            }

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Jadwal perjalanan berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['conflict' => $e->getMessage()]);
        }
    }

    public function destroy(Schedule $schedule): RedirectResponse|JsonResponse
    {
        $activeBookingsCount = $schedule->bookings()
            ->whereIn('status', ['confirmed', 'waiting_verification'])
            ->count();

        if ($activeBookingsCount > 0) {
            return back()->withErrors([
                'schedule' => 'Jadwal tidak dapat dihapus karena sudah memiliki pemesanan aktif.',
            ]);
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal perjalanan berhasil dihapus.');
    }
}
