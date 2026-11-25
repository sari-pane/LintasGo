<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\SearchScheduleRequest;
use App\Models\Route;
use App\Models\Schedule;
use App\Services\ScheduleService;
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
        $origins = Route::query()->select('origin')->distinct()->pluck('origin');
        $destinations = Route::query()->select('destination')->distinct()->pluck('destination');

        return view('passenger.schedules.index', compact('origins', 'destinations'));
    }

    public function search(SearchScheduleRequest $request): View|JsonResponse
    {
        $schedules = $this->scheduleService->searchSchedules(
            $request->validated('origin'),
            $request->validated('destination'),
            $request->validated('date')
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $schedules,
            ]);
        }

        return view('passenger.schedules.results', [
            'schedules' => $schedules,
            'origin' => $request->validated('origin'),
            'destination' => $request->validated('destination'),
            'date' => $request->validated('date'),
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
            'schedule' => $schedule->load(['route', 'vehicle']),
            'seats' => $seatsData,
        ]);
    }
}
