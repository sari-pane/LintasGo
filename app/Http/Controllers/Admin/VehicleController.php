<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::query()
            ->withCount(['seats', 'schedules'])
            ->orderBy('brand')
            ->paginate(15);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number'],
            'brand' => ['required', 'string', 'max:100'],
            'total_seats' => ['required', 'integer', 'min:2', 'max:30'],
            'facility' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,maintenance,inactive'],
        ], [
            'plate_number.required' => 'Nomor polisi wajib diisi.',
            'plate_number.unique' => 'Nomor polisi sudah terdaftar.',
            'brand.required' => 'Merek/model armada wajib diisi.',
            'total_seats.required' => 'Kapasitas kursi wajib diisi.',
        ]);

        $vehicle = DB::transaction(function () use ($validated) {
            $vehicle = Vehicle::create($validated);
            $this->generateDefaultSeats($vehicle, (int) $validated['total_seats']);

            return $vehicle;
        });

        return redirect()->route('admin.armada.show', $vehicle)
            ->with('success', 'Armada baru berhasil ditambahkan beserta konfigurasi kursinya.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['seats' => fn ($q) => $q->orderBy('id')]);

        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', 'unique:vehicles,plate_number,' . $vehicle->id],
            'brand' => ['required', 'string', 'max:100'],
            'total_seats' => ['required', 'integer', 'min:2', 'max:30'],
            'facility' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:active,maintenance,inactive'],
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.armada.index')
            ->with('success', 'Data armada berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->schedules()->exists()) {
            return back()->withErrors([
                'vehicle' => 'Armada tidak dapat dihapus karena masih terhubung dengan jadwal perjalanan.',
            ]);
        }

        DB::transaction(function () use ($vehicle) {
            $vehicle->seats()->delete();
            $vehicle->delete();
        });

        return redirect()->route('admin.armada.index')
            ->with('success', 'Armada berhasil dihapus.');
    }

    public function storeSeat(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'seat_number' => ['required', 'string', 'max:10'],
            'seat_position' => ['required', 'string', 'max:50'],
        ]);

        if ($vehicle->seats()->where('seat_number', $validated['seat_number'])->exists()) {
            return back()->withErrors(['seat_number' => 'Nomor kursi tersebut sudah ada pada armada ini.']);
        }

        $vehicle->seats()->create($validated);
        $vehicle->update(['total_seats' => $vehicle->seats()->count()]);

        return back()->with('success', "Kursi {$validated['seat_number']} berhasil ditambahkan.");
    }

    public function destroySeat(Vehicle $vehicle, Seat $seat): RedirectResponse
    {
        if ($seat->vehicle_id !== $vehicle->id) {
            abort(404);
        }

        if ($seat->bookingSeats()->exists()) {
            return back()->withErrors(['seat' => 'Kursi tidak dapat dihapus karena memiliki riwayat pemesanan.']);
        }

        $seat->delete();
        $vehicle->update(['total_seats' => $vehicle->seats()->count()]);

        return back()->with('success', 'Kursi berhasil dihapus.');
    }

    private function generateDefaultSeats(Vehicle $vehicle, int $totalSeats): void
    {
        $codes = ['1A'];
        $row = 2;
        while (count($codes) < $totalSeats) {
            $codes[] = "{$row}A";
            if (count($codes) < $totalSeats) {
                $codes[] = "{$row}B";
            }
            $row++;
        }

        foreach ($codes as $idx => $code) {
            $rowNum = $idx === 0 ? 1 : intdiv($idx + 1, 2) + 1;
            $side = str_ends_with($code, 'A') ? 'left' : 'right';
            Seat::create([
                'vehicle_id' => $vehicle->id,
                'seat_number' => $code,
                'seat_position' => "row-{$rowNum}-{$side}",
            ]);
        }
    }
}
