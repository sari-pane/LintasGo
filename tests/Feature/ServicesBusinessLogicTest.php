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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ServicesBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    protected User $passenger;
    protected User $admin;
    protected Route $route;
    protected Vehicle $vehicle;
    protected Driver $driver;
    protected Schedule $schedule;
    /** @var array<int, Seat> */
    protected array $seats = [];

    protected ScheduleService $scheduleService;
    protected BookingService $bookingService;
    protected PaymentService $paymentService;
    protected TicketService $ticketService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ticketService = new TicketService();
        $this->paymentService = new PaymentService($this->ticketService);
        $this->bookingService = new BookingService();
        $this->scheduleService = new ScheduleService();

        // Seed basic fixtures
        $this->passenger = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
            'role' => 'passenger',
            'phone' => '081234567890',
        ]);

        $this->admin = new User([
            'name' => 'Admin LintasGo',
            'email' => 'admin@lintasgo.com',
            'password' => bcrypt('admin123'),
            'phone' => '081987654321',
        ]);
        $this->admin->forceFill(['role' => 'admin'])->save();

        $this->route = Route::create([
            'origin' => 'Surabaya',
            'destination' => 'Malang',
            'distance_km' => 95.5,
            'estimated_duration_minutes' => 120,
            'is_active' => true,
        ]);

        $this->vehicle = Vehicle::create([
            'plate_number' => 'L 1234 AB',
            'brand' => 'Toyota HiAce Premio',
            'total_seats' => 10,
            'status' => 'active',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $this->seats[$i] = Seat::create([
                'vehicle_id' => $this->vehicle->id,
                'seat_number' => (string) $i,
                'seat_position' => "row-{$i}",
            ]);
        }

        $this->driver = Driver::create([
            'name' => 'Pak Joko',
            'phone' => '081122334455',
            'license_number' => 'SIM-B1-998877',
            'status' => 'active',
        ]);

        $this->schedule = Schedule::create([
            'route_id' => $this->route->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'departure_time' => now()->addDays(2)->setTime(8, 0),
            'arrival_time' => now()->addDays(2)->setTime(10, 0),
            'price' => 75000.00,
            'status' => 'scheduled',
        ]);
    }

    public function test_booking_accepts_1_2_and_3_seats_and_calculates_server_price(): void
    {
        // Test 1 seat
        $passengers1 = [
            ['seat_id' => $this->seats[1]->id, 'name' => 'Penumpang 1', 'phone' => '0812001'],
        ];
        $booking1 = $this->bookingService->createBooking($this->passenger, $this->schedule, $passengers1);
        $this->assertEquals(1, $booking1->total_passengers);
        $this->assertEquals(75000.00, (float) $booking1->total_amount);
        $this->assertEquals('pending', $booking1->status);
        $this->assertCount(1, $booking1->bookingSeats);

        // Test 2 seats
        $passengers2 = [
            ['seat_id' => $this->seats[2]->id, 'name' => 'Penumpang 2', 'phone' => '0812002'],
            ['seat_id' => $this->seats[3]->id, 'name' => 'Penumpang 3', 'phone' => '0812003'],
        ];
        $booking2 = $this->bookingService->createBooking($this->passenger, $this->schedule, $passengers2);
        $this->assertEquals(2, $booking2->total_passengers);
        $this->assertEquals(150000.00, (float) $booking2->total_amount);

        // Test 3 seats
        $passengers3 = [
            ['seat_id' => $this->seats[4]->id, 'name' => 'Penumpang 4', 'phone' => '0812004'],
            ['seat_id' => $this->seats[5]->id, 'name' => 'Penumpang 5', 'phone' => '0812005'],
            ['seat_id' => $this->seats[6]->id, 'name' => 'Penumpang 6', 'phone' => '0812006'],
        ];
        $booking3 = $this->bookingService->createBooking($this->passenger, $this->schedule, $passengers3);
        $this->assertEquals(3, $booking3->total_passengers);
        $this->assertEquals(225000.00, (float) $booking3->total_amount);
    }

    public function test_booking_rejects_more_than_3_seats(): void
    {
        $this->expectException(ValidationException::class);

        $passengers = [
            ['seat_id' => $this->seats[1]->id, 'name' => 'P1'],
            ['seat_id' => $this->seats[2]->id, 'name' => 'P2'],
            ['seat_id' => $this->seats[3]->id, 'name' => 'P3'],
            ['seat_id' => $this->seats[4]->id, 'name' => 'P4'],
        ];

        $this->bookingService->createBooking($this->passenger, $this->schedule, $passengers);
    }

    public function test_booking_prevents_double_booking_same_seat_on_same_schedule(): void
    {
        // Booking 1 books seat 1
        $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'P1'],
        ]);

        // Booking 2 tries to book seat 1 again
        $this->expectException(ValidationException::class);
        $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'Another Person'],
        ]);
    }

    public function test_booking_allows_same_seat_on_different_schedule(): void
    {
        // Booking on schedule 1
        $booking1 = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'P1'],
        ]);
        $this->assertNotNull($booking1);

        // Another schedule on next day
        $schedule2 = Schedule::create([
            'route_id' => $this->route->id,
            'vehicle_id' => $this->vehicle->id,
            'driver_id' => $this->driver->id,
            'departure_time' => now()->addDays(3)->setTime(8, 0),
            'arrival_time' => now()->addDays(3)->setTime(10, 0),
            'price' => 75000.00,
            'status' => 'scheduled',
        ]);

        // Booking the same seat on schedule 2 should succeed
        $booking2 = $this->bookingService->createBooking($this->passenger, $schedule2, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'P1 on S2'],
        ]);
        $this->assertNotNull($booking2);
    }

    public function test_booking_cancellation_releases_seat(): void
    {
        $booking = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'P1'],
        ]);

        // Seat 1 is occupied
        $this->assertEquals("{$this->schedule->id}-{$this->seats[1]->id}", $booking->bookingSeats->first()->active_seat_key);

        // Cancel booking
        $this->bookingService->cancelBooking($booking, $this->passenger, 'Rencana berubah');
        $this->assertEquals('cancelled', $booking->fresh()->status);
        $this->assertNull($booking->bookingSeats->first()->fresh()->active_seat_key);

        // Seat 1 can now be re-booked
        $newBooking = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'New Person'],
        ]);
        $this->assertNotNull($newBooking);
    }

    public function test_schedule_conflict_detection_with_buffer(): void
    {
        // Existing schedule: 2 days later at 08:00 - 10:00
        // New schedule attempting to use same vehicle at 10:30 (within 60m buffer)
        $this->expectException(ValidationException::class);

        $dep = now()->addDays(2)->setTime(10, 30);
        $arr = now()->addDays(2)->setTime(12, 30);

        $this->scheduleService->assertNoConflict(
            $this->vehicle->id,
            $this->driver->id,
            $dep,
            $arr,
            null,
            60
        );
    }

    public function test_payment_submission_verification_and_ticket_issuance(): void
    {
        Storage::fake('local');

        $booking = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'Ali'],
            ['seat_id' => $this->seats[2]->id, 'name' => 'Bara'],
        ]);

        $file = UploadedFile::fake()->create('bukti_transfer.jpg', 200, 'image/jpeg');

        // Submit proof
        $payment = $this->paymentService->submitPaymentProof($booking, 'bank_transfer_manual', $file, 150000.00);

        $this->assertEquals('pending_verification', $payment->status);
        $this->assertEquals(1, $payment->attempt_number);
        $this->assertEquals('waiting_verification', $booking->fresh()->status);

        // Verify payment by admin
        $this->paymentService->verifyPayment($payment, $this->admin);

        $this->assertEquals('verified', $payment->fresh()->status);
        $this->assertEquals('confirmed', $booking->fresh()->status);

        // Exactly 2 tickets issued (1 per seat)
        $tickets = Ticket::whereIn('booking_seat_id', $booking->bookingSeats->pluck('id'))->get();
        $this->assertCount(2, $tickets);
        foreach ($tickets as $t) {
            $this->assertEquals('issued', $t->status);
            $this->assertNotNull($t->ticket_code);
            $this->assertNotNull($t->qr_code_identifier);
        }
    }

    public function test_ticket_checkin_and_prevents_double_checkin(): void
    {
        Storage::fake('local');

        $booking = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'Ali'],
        ]);

        $file = UploadedFile::fake()->create('bukti.jpg', 100);
        $payment = $this->paymentService->submitPaymentProof($booking, 'qris_manual', $file, 75000.00);
        $this->paymentService->verifyPayment($payment, $this->admin);

        $ticket = Ticket::where('booking_seat_id', $booking->bookingSeats->first()->id)->firstOrFail();

        // 1st Check-in: Success
        $checkedTicket = $this->ticketService->processCheckIn($ticket->qr_code_identifier, $this->admin);
        $this->assertEquals('checked_in', $checkedTicket->status);
        $this->assertNotNull($checkedTicket->checked_in_at);
        $this->assertEquals($this->admin->id, $checkedTicket->checked_in_by);

        // 2nd Check-in: Rejected
        $this->expectException(ValidationException::class);
        $this->ticketService->processCheckIn($ticket->ticket_code, $this->admin);
    }

    public function test_payment_rejection_after_deadline_expires_booking_and_releases_seats(): void
    {
        Storage::fake('local');

        $booking = $this->bookingService->createBooking($this->passenger, $this->schedule, [
            ['seat_id' => $this->seats[1]->id, 'name' => 'Ali'],
        ]);

        $file = UploadedFile::fake()->create('bukti_buram.jpg', 100);
        $payment = $this->paymentService->submitPaymentProof($booking, 'bank_transfer_manual', $file, 75000.00);

        // Simulate admin checking after deadline has expired
        $booking->payment_deadline = now()->subMinutes(10);
        $booking->save();

        $this->paymentService->rejectPayment($payment, $this->admin, 'Struk transfer tidak terbaca/buram');

        $this->assertEquals('rejected', $payment->fresh()->status);
        $this->assertEquals('expired', $booking->fresh()->status);

        // Seat should be freed
        $this->assertNull($booking->bookingSeats->first()->fresh()->active_seat_key);
    }
}
