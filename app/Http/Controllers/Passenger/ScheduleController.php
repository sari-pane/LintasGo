<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\SearchScheduleRequest;
use App\Models\Route;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index(Request $request): View
    {
        $routes = Route::query()->where('is_active', true)->orderBy('origin')->orderBy('destination')->get();
        $origins = $routes->pluck('origin')->unique()->values();
        $destinations = $routes->pluck('destination')->unique()->values();

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->input('date'), 'Asia/Jakarta')->toDateString()
            : Carbon::today('Asia/Jakarta')->toDateString();

        $selectedOrigin = $request->input('origin');
        $selectedDestination = $request->input('destination');

        $schedulesQuery = Schedule::query()
            ->with(['route', 'vehicle.seats', 'driver'])
            ->withCount(['bookingSeats as occupied_seats_count' => fn ($q) => $q->whereNotNull('active_seat_key')])
            ->whereHas('route', function ($q) use ($selectedOrigin, $selectedDestination) {
                $q->where('is_active', true);
                if ($selectedOrigin) {
                    $q->where('origin', 'like', "%{$selectedOrigin}%");
                }
                if ($selectedDestination) {
                    $q->where('destination', 'like', "%{$selectedDestination}%");
                }
            })
            ->whereHas('vehicle', fn ($q) => $q->where('status', 'active'))
            ->whereHas('driver', fn ($q) => $q->where('status', 'active'))
            ->where('status', 'scheduled')
            ->whereDate('departure_time', $selectedDate)
            ->where('departure_time', '>', now('Asia/Jakarta'))
            ->orderBy('departure_time', 'asc');

        $schedules = $schedulesQuery->get();

        // Build quick date pills for today + 5 days
        $quickDates = collect(range(0, 5))->map(function (int $offset) {
            $date = Carbon::today('Asia/Jakarta')->addDays($offset);
            return [
                'iso' => $date->toDateString(),
                'day_label' => $offset === 0 ? 'Hari Ini' : $date->locale('id')->isoFormat('ddd'),
                'date_label' => $date->locale('id')->isoFormat('D MMM'),
            ];
        });

        return view('passenger.schedules.index', compact(
            'routes',
            'origins',
            'destinations',
            'schedules',
            'selectedDate',
            'selectedOrigin',
            'selectedDestination',
            'quickDates'
        ));
    }

    public function search(SearchScheduleRequest $request): View|JsonResponse
    {
        $schedules = $this->scheduleService->searchSchedules(
            $request->validated('origin'),
            $request->validated('destination'),
            $request->validated('date')
        );

        $schedules->loadMissing(['vehicle.seats', 'driver', 'route']);
        $schedules->each(function ($schedule) {
            $occupied = $schedule->bookingSeats()->whereNotNull('active_seat_key')->count();
            $schedule->setAttribute('occupied_seats_count', $occupied);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $schedules,
            ]);
        }

        $routes = Route::query()->where('is_active', true)->orderBy('origin')->orderBy('destination')->get();
        $origins = $routes->pluck('origin')->unique()->values();
        $destinations = $routes->pluck('destination')->unique()->values();
        $selectedDate = $request->validated('date');
        $selectedOrigin = $request->validated('origin');
        $selectedDestination = $request->validated('destination');
        $quickDates = collect(range(0, 5))->map(function (int $offset) {
            $date = Carbon::today('Asia/Jakarta')->addDays($offset);
            return [
                'iso' => $date->toDateString(),
                'day_label' => $offset === 0 ? 'Hari Ini' : $date->locale('id')->isoFormat('ddd'),
                'date_label' => $date->locale('id')->isoFormat('D MMM'),
            ];
        });

        return view('passenger.schedules.index', [
            'routes' => $routes,
            'origins' => $origins,
            'destinations' => $destinations,
            'schedules' => $schedules,
            'selectedDate' => $selectedDate,
            'selectedOrigin' => $selectedOrigin,
            'selectedDestination' => $selectedDestination,
            'quickDates' => $quickDates,
        ]);
    }

    public function showSeats(Schedule $schedule, Request $request): View|JsonResponse
    {
        $seatsData = $this->scheduleService->getSeatLayoutWithAvailability($schedule);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'schedule' => $schedule->load(['route', 'vehicle']),
                'seats' => $seatsData,
            ]);
        }

        return view('passenger.schedules.seats', [
            'schedule' => $schedule->load(['route', 'vehicle', 'driver']),
            'seats' => $seatsData,
        ]);
    }
}
