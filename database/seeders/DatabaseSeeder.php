<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Sample Passengers
        $admin = User::firstOrNew(['email' => 'admin@lintasgo.id']);
        $admin->fill([
            'name' => 'Sari Oktaviani',
            'phone' => '081100001111',
            'password' => Hash::make('admin12345'),
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        $passenger = User::firstOrNew(['email' => 'febiola@lintasgo.id']);
        $passenger->fill([
            'name' => 'Febiola Safira',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);
        $passenger->forceFill(['role' => 'passenger'])->save();

        // 2. Active Routes
        $routesData = [
            [
                'origin' => 'Jember',
                'destination' => 'Surabaya',
                'distance_km' => 195,
                'estimated_duration_minutes' => 270,
                'description' => 'Via Probolinggo & Pasuruan. Tersedia pengantaran Bandara Juanda & Terminal Bungurasih.',
            ],
            [
                'origin' => 'Jember',
                'destination' => 'Malang',
                'distance_km' => 180,
                'estimated_duration_minutes' => 255,
                'description' => 'Via Lumajang atau Tol Pandaan. Armada HiAce & MPV Executive ber-AC penuh.',
            ],
            [
                'origin' => 'Jember',
                'destination' => 'Banyuwangi',
                'distance_km' => 105,
                'estimated_duration_minutes' => 165,
                'description' => 'Jalur Gumitir dengan jadwal keberangkatan pagi hingga malam menuju Pelabuhan Ketapang.',
            ],
            [
                'origin' => 'Surabaya',
                'destination' => 'Jember',
                'distance_km' => 195,
                'estimated_duration_minutes' => 270,
                'description' => 'Penjemputan Terminal Bungurasih / Bandara Juanda menuju Pool Utama Jember.',
            ],
        ];

        $routes = collect($routesData)->map(function ($data) {
            return Route::firstOrCreate(
                ['origin' => $data['origin'], 'destination' => $data['destination']],
                array_merge($data, ['is_active' => true])
            );
        });

        // 3. Vehicles & Physical Seats
        $vehiclesData = [
            [
                'plate_number' => 'P 7011 LG',
                'brand' => 'Toyota HiAce Premio',
                'total_seats' => 7,
                'facility' => 'AC Executive, Reclining Seat, USB Port, Audio Travel',
                'seat_codes' => ['1A', '2A', '2B', '3A', '3B', '4A', '4B'],
            ],
            [
                'plate_number' => 'P 7022 LG',
                'brand' => 'MPV Innova Zenix',
                'total_seats' => 5,
                'facility' => 'Captain Seat, AC Double Blower, Kabin Senyap',
                'seat_codes' => ['1A', '2A', '2B', '3A', '3B'],
            ],
            [
                'plate_number' => 'P 7033 LG',
                'brand' => 'Isuzu Elf Long Microbus',
                'total_seats' => 10,
                'facility' => 'AC Ducting, Bagasi Luas, Legroom Lega',
                'seat_codes' => ['1A', '1B', '2A', '2B', '3A', '3B', '4A', '4B', '5A', '5B'],
            ],
        ];

        $vehicles = collect($vehiclesData)->map(function ($vData) {
            $vehicle = Vehicle::firstOrCreate(
                ['plate_number' => $vData['plate_number']],
                [
                    'brand' => $vData['brand'],
                    'total_seats' => $vData['total_seats'],
                    'facility' => $vData['facility'],
                    'status' => 'active',
                ]
            );

            foreach ($vData['seat_codes'] as $idx => $code) {
                Seat::firstOrCreate(
                    ['vehicle_id' => $vehicle->id, 'seat_number' => $code],
                    ['seat_position' => 'row-' . (intdiv($idx, 2) + 1)]
                );
            }

            return $vehicle;
        });

        // 4. Drivers
        $driversData = [
            ['name' => 'Pak Joko Santoso', 'phone' => '082233445501', 'license_number' => 'SIM-B1-10001'],
            ['name' => 'Pak Hendra Wijaya', 'phone' => '082233445502', 'license_number' => 'SIM-B1-10002'],
            ['name' => 'Pak Budi Hartono', 'phone' => '082233445503', 'license_number' => 'SIM-B1-10003'],
        ];

        $drivers = collect($driversData)->map(fn ($d) => Driver::firstOrCreate(
            ['license_number' => $d['license_number']],
            array_merge($d, ['status' => 'active'])
        ));

        // 5. Upcoming Schedules for Today + Next 3 Days (Non-Conflicting Slots)
        if (Schedule::count() === 0) {
            $slots = [
                ['hour' => 6, 'minute' => 0, 'vehicle_idx' => 0, 'driver_idx' => 0, 'route_idx' => 0, 'price' => 150000],
                ['hour' => 10, 'minute' => 30, 'vehicle_idx' => 1, 'driver_idx' => 1, 'route_idx' => 1, 'price' => 175000],
                ['hour' => 15, 'minute' => 0, 'vehicle_idx' => 2, 'driver_idx' => 2, 'route_idx' => 2, 'price' => 135000],
                ['hour' => 20, 'minute' => 0, 'vehicle_idx' => 0, 'driver_idx' => 0, 'route_idx' => 0, 'price' => 160000],
            ];

            for ($dayOffset = 1; $dayOffset <= 4; $dayOffset++) {
                foreach ($slots as $slot) {
                    $route = $routes[$slot['route_idx']];
                    $dep = Carbon::today('Asia/Jakarta')->addDays($dayOffset)->setTime($slot['hour'], $slot['minute']);
                    $arr = (clone $dep)->addMinutes($route->estimated_duration_minutes);

                    Schedule::create([
                        'route_id' => $route->id,
                        'vehicle_id' => $vehicles[$slot['vehicle_idx']]->id,
                        'driver_id' => $drivers[$slot['driver_idx']]->id,
                        'departure_time' => $dep,
                        'arrival_time' => $arr,
                        'price' => $slot['price'],
                        'status' => 'scheduled',
                    ]);
                }
            }
        }
    }
}
