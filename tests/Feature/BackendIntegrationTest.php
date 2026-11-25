<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\ScheduleService;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackendIntegrationTest extends TestCase
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

        // Passenger A
        $this->passengerA = new User([
            'name' => 'Ahmad Penumpang A',
            'email' => 'penumpang.a@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);
        $this->passengerA->forceFill(['role' => 'passenger'])->save();

        // Passenger B
        $this->passengerB = new User([
            'name' => 'Budi Penumpang B',
            'email' => 'penumpang.b@example.com',
            'phone' => '081298765432',
            'password' => Hash::make('password123'),
        ]);
        $this->passengerB->forceFill(['role' => 'passenger'])->save();

        // Admin
        $this->admin = new User([
            'name' => 'Admin Operasional LintasGo',
            'email' => 'admin@lintasgo.com',
            'phone' => '08111222333',
            'password' => Hash::make('admin12345'),
        ]);
        $this->admin->forceFill(['role' => 'admin'])->save();

        // Route: Surabaya - Malang
        $this->route = Route::create([
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'distance_km' => 95,
            'estimated_duration_minutes' => 120,
        ]);

        // Vehicle: HiAce 10 Seats
        $this->vehicle = Vehicle::create([
            'brand' => 'Toyota HiAce Premio Luxury',
            'plate_number' => 'L 7788 AB',
            'total_seats' => 10,
            'status' => 'active',
        ]);

        // Create 10 seats
        for ($i = 1; $i <= 10; $i++) {
            $this->seats[$i] = Seat::create([
                'vehicle_id' => $this->vehicle->id,
                'seat_number' => 'A' . $i,
                'seat_position' => 'row-' . $i,
            ]);
        }

        // Driver
        $this->driver = Driver::create([
            'name' => 'Pak Joko Santoso',
            'license_number' => 'SIM-B1-998877',
            'phone' => '082233445566',
            'status' => 'active',
        ]);

        // Schedule: Tomorrow 08:00 WIB
        $dep = Carbon::tomorrow('Asia/Jakarta')->setTime(8, 0);
        $arr = (clone $dep)->addMinutes(120);

        $this->schedule = Schedule::create([
            'route_id' => $this->route->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'departure_time' => $dep,
            'arrival_time' => $arr,
            'price' => 125000,
            'status' => 'scheduled',
        ]);
    }

    public function test_passenger_registration_and_login_flow(): void
    {
        $regResponse = $this->post('/register', [
            'name' => 'Citra Lestari',
            'email' => 'citra@example.com',
            'phone' => '081345678901',
            'password' => 'citrasecret123',
            'password_confirmation' => 'citrasecret123',
        ]);

        $regResponse->assertRedirect('/passenger/dashboard');
        $this->assertAuthenticated();

        $user = User::where('email', 'citra@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('passenger', $user->role);

        // Logout
        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect('/login');
        $this->assertGuest();

        // Login again
        $loginResponse = $this->post('/login', [
            'email' => 'citra@example.com',
            'password' => 'citrasecret123',
        ]);
        $loginResponse->assertRedirect('/passenger/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_rejects_passenger_and_accepts_admin(): void
    {
        // Passenger tries logging into admin portal
        $failResponse = $this->post('/admin/login', [
            'email' => $this->passengerA->email,
            'password' => 'password123',
        ]);
        $failResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        // Admin logs into admin portal
        $adminResponse = $this->post('/admin/login', [
            'email' => $this->admin->email,
            'password' => 'admin12345',
        ]);
        $adminResponse->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);

        // Admin accesses dashboard JSON
        $dashResponse = $this->actingAs($this->admin)->getJson('/admin/dashboard');
        $dashResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['metrics', 'recent_pending_payments', 'upcoming_schedules']);
    }

    public function test_schedule_search_validation_and_results(): void
    {
        // 1. Same origin and destination rejected (422)
        $invalidResponse = $this->actingAs($this->passengerA)->getJson('/passenger/schedules/search?' . http_build_query([
            'origin' => 'Surabaya',
            'destination' => 'Surabaya',
            'date' => Carbon::tomorrow()->format('Y-m-d'),
        ]));
        $invalidResponse->assertStatus(422);

        // 2. Past date rejected (422)
        $pastResponse = $this->actingAs($this->passengerA)->getJson('/passenger/schedules/search?' . http_build_query([
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'date' => Carbon::yesterday()->format('Y-m-d'),
        ]));
        $pastResponse->assertStatus(422);

        // 3. Valid search returns schedule
        $validResponse = $this->actingAs($this->passengerA)->getJson('/passenger/schedules/search?' . http_build_query([
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'date' => Carbon::tomorrow('Asia/Jakarta')->format('Y-m-d'),
        ]));
        $validResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertCount(1, $validResponse->json('data'));

        // 4. Seat availability endpoint
        $seatsResponse = $this->actingAs($this->passengerA)->getJson("/passenger/schedules/{$this->schedule->id}/seats");
        $seatsResponse->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertCount(10, $seatsResponse->json('seats'));
        $this->assertTrue($seatsResponse->json('seats.0.is_available'));
    }

    public function test_booking_creation_enforces_max_3_seats_and_server_price_calculation(): void
    {
        // Rule 1: Attempting to book 4 seats must be rejected with 422
        $fourSeatsResponse = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'P1', 'phone' => '0811'],
                ['seat_id' => $this->seats[2]->id, 'name' => 'P2', 'phone' => '0812'],
                ['seat_id' => $this->seats[3]->id, 'name' => 'P3', 'phone' => '0813'],
                ['seat_id' => $this->seats[4]->id, 'name' => 'P4', 'phone' => '0814'],
            ],
            'notes' => 'Pesan 4 kursi',
        ]);
        $fourSeatsResponse->assertStatus(422)
            ->assertJsonValidationErrors('passengers');

        // Rule 2: Booking 2 seats calculates server price: 2 * 125,000 = 250,000
        $bookingResponse = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'Ahmad', 'phone' => '081234567890'],
                ['seat_id' => $this->seats[2]->id, 'name' => 'Rina', 'phone' => '081234567891'],
            ],
            'total_price' => 1000, // Client manipulation attempt! Should be ignored.
            'notes' => 'Dekat jendela',
        ]);

        $bookingResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $bookingId = $bookingResponse->json('booking.id');
        $booking = Booking::with('bookingSeats')->find($bookingId);

        $this->assertNotNull($booking);
        $this->assertEquals(250000.00, (float) $booking->total_amount); // Server calculated!
        $this->assertEquals('pending', $booking->status);
        $this->assertCount(2, $booking->bookingSeats);

        // Check active_seat_key anti-double-booking in database
        $this->assertEquals("{$this->schedule->id}-{$this->seats[1]->id}", $booking->bookingSeats[0]->active_seat_key);
        $this->assertEquals("{$this->schedule->id}-{$this->seats[2]->id}", $booking->bookingSeats[1]->active_seat_key);
    }

    public function test_anti_double_booking_prevention_on_same_seat(): void
    {
        // Passenger A books seat 1
        $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'Ahmad', 'phone' => '081234567890'],
            ],
        ])->assertStatus(201);

        // Passenger B tries to book the SAME seat 1
        $conflictResponse = $this->actingAs($this->passengerB)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'Budi', 'phone' => '081298765432'],
            ],
        ]);
        $conflictResponse->assertStatus(422);

        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseCount('booking_seats', 1);
    }

    public function test_data_ownership_protection_passenger_cannot_access_other_passenger_booking(): void
    {
        // Passenger A books seat 1
        $bookingResponse = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[1]->id, 'name' => 'Ahmad', 'phone' => '081234567890'],
            ],
        ]);
        $bookingId = $bookingResponse->json('booking.id');

        // Passenger B attempts to view Passenger A's booking
        $this->actingAs($this->passengerB)->get("/passenger/bookings/{$bookingId}")
            ->assertStatus(403);

        // Passenger B attempts to cancel Passenger A's booking
        $this->actingAs($this->passengerB)->post("/passenger/bookings/{$bookingId}/cancel")
            ->assertStatus(403);

        // Passenger B attempts to upload payment for Passenger A's booking
        $file = UploadedFile::fake()->create('proof.png', 200, 'image/png');
        $this->actingAs($this->passengerB)->post("/passenger/bookings/{$bookingId}/payment", [
            'payment_method' => 'qris_manual',
            'amount' => 125000,
            'proof_file' => $file,
        ])->assertStatus(403);
    }

    public function test_end_to_end_payment_verification_ticket_issuance_and_checkin(): void
    {
        // 1. Passenger A books seat 5
        $bookResp = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[5]->id, 'name' => 'Ahmad Santoso', 'phone' => '081234567890'],
            ],
        ]);
        $bookingId = $bookResp->json('booking.id');
        $booking = Booking::find($bookingId);

        // 2. Passenger A uploads payment proof
        $file = UploadedFile::fake()->create('struk_qris.jpg', 300, 'image/jpeg');
        $uploadResp = $this->actingAs($this->passengerA)->postJson("/passenger/bookings/{$bookingId}/payment", [
            'payment_method' => 'qris_manual',
            'amount' => 125000,
            'proof_file' => $file,
        ]);
        $uploadResp->assertStatus(200)
            ->assertJsonPath('success', true);

        $paymentId = $uploadResp->json('payment.id');
        $payment = Payment::find($paymentId);
        $this->assertEquals('pending_verification', $payment->status);
        $this->assertEquals('waiting_verification', $booking->fresh()->status);
        $this->assertEquals(1, $payment->attempt_number);

        // 3. Security: Passenger cannot access admin proof stream endpoint
        $this->actingAs($this->passengerA)->get("/admin/payments/{$paymentId}/proof")
            ->assertStatus(403);

        // Admin CAN access and stream the private proof
        $adminProofResp = $this->actingAs($this->admin)->get("/admin/payments/{$paymentId}/proof");
        $adminProofResp->assertStatus(200);

        // 4. Admin approves payment
        $verifyResp = $this->actingAs($this->admin)->postJson("/admin/payments/{$paymentId}/verify", [
            'action' => 'approve',
        ]);
        $verifyResp->assertStatus(200)
            ->assertJsonPath('success', true);

        $payment->refresh();
        $this->assertEquals('verified', $payment->status);
        $this->assertEquals($this->admin->id, $payment->verified_by);
        $this->assertNotNull($payment->verified_at);

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);

        // Exactly 1 ticket issued
        $ticket = Ticket::whereHas('bookingSeat', fn($q) => $q->where('booking_id', $booking->id))->first();
        $this->assertNotNull($ticket);
        $this->assertEquals('issued', $ticket->status);
        $this->assertStringStartsWith('TIX-', $ticket->ticket_code);
        $this->assertEquals(64, strlen($ticket->qr_code_identifier));

        // 5. Check-in by Admin
        $checkinResp = $this->actingAs($this->admin)->postJson('/admin/check-in', [
            'identifier' => $ticket->ticket_code,
        ]);
        $checkinResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('ticket.status', 'checked_in');

        $ticket->refresh();
        $this->assertEquals('checked_in', $ticket->status);
        $this->assertEquals($this->admin->id, $ticket->checked_in_by);
        $this->assertNotNull($ticket->checked_in_at);

        // 6. Anti-duplicate boarding: Attempt second check-in must fail
        $secondCheckin = $this->actingAs($this->admin)->postJson('/admin/check-in', [
            'identifier' => $ticket->ticket_code,
        ]);
        $secondCheckin->assertStatus(422);
    }

    public function test_payment_rejection_and_multi_attempt_history(): void
    {
        // 1. Passenger A books seat 3
        $bookResp = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[3]->id, 'name' => 'Ahmad', 'phone' => '081234567890'],
            ],
        ]);
        $bookingId = $bookResp->json('booking.id');
        $booking = Booking::find($bookingId);

        // 2. Upload Attempt 1
        $file1 = UploadedFile::fake()->create('proof_1.jpg', 200, 'image/jpeg');
        $this->actingAs($this->passengerA)->postJson("/passenger/bookings/{$bookingId}/payment", [
            'payment_method' => 'bank_transfer_manual',
            'amount' => 100000, // Less than required 125,000
            'proof_file' => $file1,
        ])->assertStatus(200);

        $payment1 = Payment::where('booking_id', $bookingId)->where('attempt_number', 1)->first();
        $this->assertNotNull($payment1);

        // 3. Admin rejects attempt 1 with reason
        $this->actingAs($this->admin)->postJson("/admin/payments/{$payment1->id}/verify", [
            'action' => 'reject',
            'rejection_reason' => 'Nominal transfer kurang dari total tagihan (Rp 125.000).',
        ])->assertStatus(200);

        $payment1->refresh();
        $this->assertEquals('rejected', $payment1->status);
        $this->assertEquals('Nominal transfer kurang dari total tagihan (Rp 125.000).', $payment1->rejection_reason);
        $this->assertEquals('pending', $booking->fresh()->status); // Booking still pending within deadline

        // 4. Passenger re-uploads as Attempt 2
        $file2 = UploadedFile::fake()->create('proof_2.jpg', 250, 'image/jpeg');
        $upload2 = $this->actingAs($this->passengerA)->postJson("/passenger/bookings/{$bookingId}/payment", [
            'payment_method' => 'bank_transfer_manual',
            'amount' => 125000,
            'proof_file' => $file2,
        ]);
        $upload2->assertStatus(200)
            ->assertJsonPath('payment.attempt_number', 2);

        // Both attempts preserved in database
        $this->assertDatabaseCount('payments', 2);
        $this->assertDatabaseHas('payments', ['booking_id' => $bookingId, 'attempt_number' => 1, 'status' => 'rejected']);
        $this->assertDatabaseHas('payments', ['booking_id' => $bookingId, 'attempt_number' => 2, 'status' => 'pending_verification']);
    }

    public function test_cancelled_booking_releases_seats_for_others(): void
    {
        // Passenger A books seat 7
        $bookResp = $this->actingAs($this->passengerA)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[7]->id, 'name' => 'Ahmad', 'phone' => '081234567890'],
            ],
        ]);
        $bookingId = $bookResp->json('booking.id');
        $booking = Booking::find($bookingId);

        // Verify active_seat_key is set
        $seatA = BookingSeat::where('booking_id', $bookingId)->first();
        $this->assertEquals("{$this->schedule->id}-{$this->seats[7]->id}", $seatA->active_seat_key);

        // Passenger A cancels the booking
        $this->actingAs($this->passengerA)->postJson("/passenger/bookings/{$bookingId}/cancel")
            ->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertNull($seatA->fresh()->active_seat_key); // Seat released!

        // Now Passenger B CAN book seat 7 without error!
        $bookRespB = $this->actingAs($this->passengerB)->postJson('/passenger/bookings', [
            'schedule_id' => $this->schedule->id,
            'passengers' => [
                ['seat_id' => $this->seats[7]->id, 'name' => 'Budi', 'phone' => '081298765432'],
            ],
        ]);
        $bookRespB->assertStatus(201);
    }

    public function test_admin_schedule_conflict_prevention_with_60_minute_buffer(): void
    {
        // Attempt to create schedule with same vehicle overlapping or within 60m
        $conflictDep = Carbon::tomorrow('Asia/Jakarta')->setTime(9, 0); // overlaps with 08:00 - 10:00 schedule
        $conflictArr = Carbon::tomorrow('Asia/Jakarta')->setTime(11, 0);

        $conflictResp = $this->actingAs($this->admin)->post('/admin/schedules', [
            'route_id' => $this->route->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'departure_time' => $conflictDep->toDateTimeString(),
            'arrival_time' => $conflictArr->toDateTimeString(),
            'price' => 150000,
        ]);

        $conflictResp->assertSessionHasErrors('vehicle_id');
        $this->assertDatabaseCount('schedules', 1);
    }
}
