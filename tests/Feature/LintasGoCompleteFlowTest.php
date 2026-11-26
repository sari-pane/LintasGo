<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LintasGoCompleteFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $passengerA;
    protected User $passengerB;
    protected User $admin;
    protected Route $route;
    protected Vehicle $vehicle;
    protected Driver $driver;
    protected Schedule $schedule;
    /** @var array<int, Seat> */
    protected array $seats = [];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');

        $this->passengerA = new User([
            'name' => 'Febiola Safira',
            'email' => 'febiola@lintasgo.id',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);
        $this->passengerA->forceFill(['role' => 'passenger'])->save();

        $this->passengerB = new User([
            'name' => 'Penumpang Lain',
            'email' => 'lain@lintasgo.id',
            'phone' => '081299998888',
            'password' => Hash::make('password123'),
        ]);
        $this->passengerB->forceFill(['role' => 'passenger'])->save();

        $this->admin = new User([
            'name' => 'Sari Admin',
            'email' => 'admin@lintasgo.id',
            'phone' => '08111222333',
            'password' => Hash::make('admin12345'),
        ]);
        $this->admin->forceFill(['role' => 'admin'])->save();

        $this->route = Route::create([
            'origin' => 'Jember',
            'destination' => 'Surabaya',
            'distance_km' => 195,
            'estimated_duration_minutes' => 270,
            'description' => 'Via Probolinggo & Pasuruan.',
            'is_active' => true,
        ]);

        $this->vehicle = Vehicle::create([
            'plate_number' => 'P 7011 LG',
            'brand' => 'Toyota HiAce Premio',
            'total_seats' => 7,
            'facility' => 'AC, Reclining Seat',
            'status' => 'active',
        ]);

        foreach (['1A', '2A', '2B', '3A', '3B', '4A', '4B'] as $idx => $code) {
            $this->seats[$idx + 1] = Seat::create([
                'vehicle_id' => $this->vehicle->id,
                'seat_number' => $code,
                'seat_position' => 'row-' . (intdiv($idx, 2) + 1),
            ]);
        }

        $this->driver = Driver::create([
            'name' => 'Pak Joko Santoso',
            'phone' => '082233445566',
            'license_number' => 'SIM-B1-778899',
            'status' => 'active',
        ]);

        $dep = Carbon::tomorrow('Asia/Jakarta')->setTime(8, 0);
        $this->schedule = Schedule::create([
            'route_id' => $this->route->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'departure_time' => $dep,
            'arrival_time' => (clone $dep)->addMinutes(270),
            'price' => 150000,
            'status' => 'scheduled',
        ]);
    }

    public function test_guest_can_view_landing_and_public_routes_but_is_redirected_from_protected_routes(): void
    {
        $home = $this->get('/');
        $home->assertStatus(200)
            ->assertSee('LintasGo')
            ->assertSee('Jember')
            ->assertSee('Surabaya')
            ->assertSee('Via Probolinggo');

        $this->get('/jadwal')->assertRedirect('/login');
        $this->get("/jadwal/{$this->schedule->id}")->assertRedirect('/login');
        $this->get('/pemesanan')->assertRedirect('/login');
        $this->get('/profil')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->get('/admin/rute')->assertRedirect('/admin/login');
    }

    public function test_role_based_login_redirects_and_logout_to_home(): void
    {
        // Passenger logs in via /login -> redirected to /jadwal
        $this->post('/login', [
            'email' => $this->passengerA->email,
            'password' => 'password123',
        ])->assertRedirect('/jadwal');

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        // Admin logs in via /login -> redirected to /admin/dashboard
        $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'admin12345',
        ])->assertRedirect('/admin/dashboard');

        $this->post('/admin/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_passenger_cannot_access_admin_routes_and_gets_403(): void
    {
        $adminUrls = [
            '/admin/dashboard',
            '/admin/rute',
            '/admin/armada',
            '/admin/sopir',
            '/admin/jadwal',
            '/admin/pembayaran',
            '/admin/pemesanan',
            '/admin/checkin',
            "/admin/manifest/{$this->schedule->id}",
            '/admin/laporan',
        ];

        foreach ($adminUrls as $url) {
            $this->actingAs($this->passengerA)->get($url)->assertStatus(403);
        }
    }

    public function test_full_indonesian_passenger_and_admin_web_flow_with_ownership_checks(): void
    {
        // 1. Passenger views schedule list and 3D seat selection page
        $this->actingAs($this->passengerA)->get('/jadwal')->assertStatus(200)->assertSee('Toyota HiAce Premio');
        $this->actingAs($this->passengerA)->get("/jadwal/{$this->schedule->id}/pesan")
            ->assertStatus(200)
            ->assertSee('Kursi 1A');

        // 2. Passenger books 2 seats via POST /pemesanan
        $bookResp = $this->actingAs($this->passengerA)->post('/pemesanan', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'Febiola Safira', 'phone' => '081234567890'],
                ['seat_id' => $this->seats[2]->id, 'name' => 'Nafisa Tsania', 'phone' => '081234567891'],
            ],
            'notes' => 'Jemput depan kampus',
        ]);
        $booking = Booking::firstOrFail();
        $bookResp->assertRedirect(route('passenger.bookings.show', $booking));

        // 3. Ownership check on Indonesian routes for Passenger B
        $this->actingAs($this->passengerB)->get("/pemesanan/{$booking->id}")->assertStatus(403);
        $this->actingAs($this->passengerB)->get("/pemesanan/{$booking->id}/bayar")->assertStatus(403);
        $this->actingAs($this->passengerB)->post("/pemesanan/{$booking->id}/batal")->assertStatus(403);

        // 4. Passenger A views payment page & uploads proof via /pemesanan/{booking}/bayar
        $this->actingAs($this->passengerA)->get("/pemesanan/{$booking->id}/bayar")->assertStatus(200);
        $proof = UploadedFile::fake()->create('qris.png', 200, 'image/png');
        $this->actingAs($this->passengerA)->post("/pemesanan/{$booking->id}/bayar", [
            'payment_method' => 'qris_manual',
            'amount' => 300000,
            'proof_file' => $proof,
        ])->assertRedirect(route('passenger.bookings.show', $booking));

        $payment = Payment::firstOrFail();

        // 5. Admin verifies payment via /admin/pembayaran/{payment}/verify
        $this->actingAs($this->admin)->get('/admin/pembayaran')->assertStatus(200);
        $this->actingAs($this->admin)->get("/admin/pembayaran/{$payment->id}")->assertStatus(200);
        $this->actingAs($this->admin)->post("/admin/pembayaran/{$payment->id}/verify", [
            'action' => 'approve',
        ])->assertRedirect(route('admin.payments.index'));

        $ticket = Ticket::firstOrFail();

        // 6. Ticket ownership: Passenger B gets 403, Passenger A gets 200
        $this->actingAs($this->passengerB)->get("/tiket/{$ticket->id}")->assertStatus(403);
        $this->actingAs($this->passengerA)->get("/tiket/{$ticket->id}")
            ->assertStatus(200)
            ->assertSee($ticket->ticket_code);

        // 7. Admin check-in, manifest, and reports
        $this->actingAs($this->admin)->post('/admin/checkin', [
            'identifier' => $ticket->ticket_code,
        ])->assertRedirect(route('admin.checkin.index'));

        $this->assertEquals('checked_in', $ticket->fresh()->status);

        $this->actingAs($this->admin)->get("/admin/manifest/{$this->schedule->id}")
            ->assertStatus(200)
            ->assertSee('Febiola Safira')
            ->assertSee('CHECKED IN');

        $this->actingAs($this->admin)->get('/admin/laporan')
            ->assertStatus(200)
            ->assertSee('300.000');

        // 8. Passenger profile view & update
        $this->actingAs($this->passengerA)->get('/profil')->assertStatus(200);
        $this->actingAs($this->passengerA)->put('/profil', [
            'name' => 'Febiola Updated',
            'phone' => '081200009999',
        ])->assertRedirect('/profil');
        $this->assertEquals('Febiola Updated', $this->passengerA->fresh()->name);
    }

    public function test_admin_can_crud_routes_vehicles_and_drivers(): void
    {
        // Route CRUD
        $this->actingAs($this->admin)->post('/admin/rute', [
            'origin' => 'Malang',
            'destination' => 'Banyuwangi',
            'distance_km' => 260,
            'estimated_duration_minutes' => 360,
            'description' => 'Jalur selatan',
            'is_active' => 1,
        ])->assertRedirect('/admin/rute');
        $this->assertDatabaseHas('routes', ['origin' => 'Malang', 'destination' => 'Banyuwangi']);

        // Vehicle CRUD + auto-generated seats
        $this->actingAs($this->admin)->post('/admin/armada', [
            'plate_number' => 'P 8899 XY',
            'brand' => 'Isuzu Elf Long',
            'total_seats' => 8,
            'facility' => 'AC',
            'status' => 'active',
        ]);
        $newVehicle = Vehicle::where('plate_number', 'P 8899 XY')->firstOrFail();
        $this->assertEquals(8, $newVehicle->seats()->count());

        // Driver CRUD
        $this->actingAs($this->admin)->post('/admin/sopir', [
            'name' => 'Pak Slamet',
            'phone' => '081277776666',
            'license_number' => 'SIM-B1-55555',
            'status' => 'active',
        ])->assertRedirect('/admin/sopir');
        $this->assertDatabaseHas('drivers', ['name' => 'Pak Slamet']);
    }

    public function test_seeder_populates_initial_data_cleanly(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->assertDatabaseHas('users', ['email' => 'admin@lintasgo.id', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'febiola@lintasgo.id', 'role' => 'passenger']);
        $this->assertGreaterThanOrEqual(4, Route::count());
        $this->assertGreaterThanOrEqual(3, Vehicle::count());
        $this->assertGreaterThanOrEqual(10, Schedule::count());
    }
}
