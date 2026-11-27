<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RouteController extends Controller
{
    public function index(): View
    {
        $routes = Route::query()
            ->withCount('schedules')
            ->orderBy('origin')
            ->orderBy('destination')
            ->paginate(15);

        return view('admin.routes.index', compact('routes'));
    }

    public function create(): View
    {
        return view('admin.routes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:100', 'different:origin'],
            'distance_km' => ['nullable', 'numeric', 'min:1'],
            'estimated_duration_minutes' => ['required', 'integer', 'min:15', 'max:2880'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'origin.required' => 'Kota asal wajib diisi.',
            'destination.required' => 'Kota tujuan wajib diisi.',
            'destination.different' => 'Kota tujuan tidak boleh sama dengan kota asal.',
            'estimated_duration_minutes.required' => 'Estimasi durasi perjalanan wajib diisi.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Route::create($validated);

        return redirect()->route('admin.rute.index')
            ->with('success', 'Rute travel baru berhasil ditambahkan.');
    }

    public function edit(Route $route): View
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route): RedirectResponse
    {
        $validated = $request->validate([
            'origin' => ['required', 'string', 'max:100'],
            'destination' => ['required', 'string', 'max:100', 'different:origin'],
            'distance_km' => ['nullable', 'numeric', 'min:1'],
            'estimated_duration_minutes' => ['required', 'integer', 'min:15', 'max:2880'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $route->update($validated);

        return redirect()->route('admin.rute.index')
            ->with('success', 'Data rute travel berhasil diperbarui.');
    }

    public function destroy(Route $route): RedirectResponse
    {
        if ($route->schedules()->exists()) {
            return back()->withErrors([
                'route' => 'Rute tidak dapat dihapus karena masih memiliki jadwal terkait. Nonaktifkan saja statusnya.',
            ]);
        }

        $route->delete();

        return redirect()->route('admin.rute.index')
            ->with('success', 'Rute travel berhasil dihapus.');
    }
}
