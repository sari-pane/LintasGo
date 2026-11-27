<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(): View
    {
        $drivers = Driver::query()
            ->withCount('schedules')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create(): View
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:50', 'unique:drivers,license_number'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama sopir wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'license_number.required' => 'Nomor SIM wajib diisi.',
            'license_number.unique' => 'Nomor SIM sudah terdaftar.',
        ]);

        Driver::create($validated);

        return redirect()->route('admin.sopir.index')
            ->with('success', 'Data sopir baru berhasil ditambahkan.');
    }

    public function edit(Driver $driver): View
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:20'],
            'license_number' => ['required', 'string', 'max:50', 'unique:drivers,license_number,' . $driver->id],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $driver->update($validated);

        return redirect()->route('admin.sopir.index')
            ->with('success', 'Data sopir berhasil diperbarui.');
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->schedules()->exists()) {
            return back()->withErrors([
                'driver' => 'Sopir tidak dapat dihapus karena masih memiliki riwayat jadwal penugasan.',
            ]);
        }

        $driver->delete();

        return redirect()->route('admin.sopir.index')
            ->with('success', 'Data sopir berhasil dihapus.');
    }
}
