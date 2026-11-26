<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $publicRoutes = Route::query()
            ->where('is_active', true)
            ->withMin(['schedules' => fn ($q) => $q->where('status', 'scheduled')->where('departure_time', '>', now())], 'price')
            ->orderBy('origin')
            ->orderBy('destination')
            ->get();

        $origins = $publicRoutes->pluck('origin')->unique()->values();
        $destinations = $publicRoutes->pluck('destination')->unique()->values();

        return view('welcome', compact('publicRoutes', 'origins', 'destinations'));
    }
}
