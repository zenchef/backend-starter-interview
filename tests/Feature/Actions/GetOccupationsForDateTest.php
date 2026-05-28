<?php

declare(strict_types=1);

namespace Tests\Feature\Actions;

use App\Actions\GetOccupationsForDate;
use App\Enums\Slot;
use App\Models\BookableSlot;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class GetOccupationsForDateTest extends TestCase
{
    use RefreshDatabase;

    private const DATE = '2026-06-01';

    private const OTHER_DATE = '2026-06-02';

    public function test_returns_empty_array_when_no_bookable_slots_exist(): void
    {
        $this->assertSame([], (new GetOccupationsForDate)(self::DATE));
    }

    public function test_returns_zero_occupation_for_bookable_slots_without_bookings(): void
    {
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_00]);
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_15]);

        $this->assertSame([
            Slot::T19_00->value => 0,
            Slot::T19_15->value => 0,
        ], (new GetOccupationsForDate)(self::DATE));
    }

    public function test_a_booking_occupies_every_slot_whose_window_overlaps_it(): void
    {
        // booking_duration = 4 → a booking at S occupies slots S, S+1, S+2, S+3
        foreach ([Slot::T19_00, Slot::T19_15, Slot::T19_30, Slot::T19_45, Slot::T20_00] as $slot) {
            BookableSlot::factory()->create(['date' => self::DATE, 'slot' => $slot]);
        }

        $this->createBooking(self::DATE, Slot::T19_30, 4);

        // The booking fills T19_30..T20_15 with 4 guests each. Every bookable slot
        // listed above has its 4-slot window touching at least one filled slot.
        $this->assertSame([
            Slot::T19_00->value => 4,
            Slot::T19_15->value => 4,
            Slot::T19_30->value => 4,
            Slot::T19_45->value => 4,
            Slot::T20_00->value => 4,
        ], (new GetOccupationsForDate)(self::DATE));
    }

    public function test_sums_guests_from_overlapping_bookings_then_takes_the_max(): void
    {
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_00]);

        $this->createBooking(self::DATE, Slot::T19_00, 2);
        $this->createBooking(self::DATE, Slot::T19_15, 3);

        // T19_00 window = T19_00..T19_45.
        //   T19_00: 2 (booking 1 only)
        //   T19_15..T19_45: 2 + 3 = 5 (both bookings overlap)
        $this->assertSame(
            [Slot::T19_00->value => 5],
            (new GetOccupationsForDate)(self::DATE),
        );
    }

    public function test_ignores_bookings_from_other_dates(): void
    {
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_00]);
        $this->createBooking(self::OTHER_DATE, Slot::T19_00, 4);

        $this->assertSame(
            [Slot::T19_00->value => 0],
            (new GetOccupationsForDate)(self::DATE),
        );
    }

    public function test_only_returns_entries_for_slots_present_in_bookable_slot(): void
    {
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_00]);

        // T20_00 booking sits entirely outside the T19_00 window (T19_00..T19_45),
        // and there is no BookableSlot at T20_00, so it must not appear in the result.
        $this->createBooking(self::DATE, Slot::T20_00, 4);

        $occupations = (new GetOccupationsForDate)(self::DATE);

        $this->assertSame([Slot::T19_00->value => 0], $occupations);
        $this->assertArrayNotHasKey(Slot::T20_00->value, $occupations);
    }

    public function test_result_is_keyed_by_slot_value_in_ascending_order(): void
    {
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_30]);
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_00]);
        BookableSlot::factory()->create(['date' => self::DATE, 'slot' => Slot::T19_15]);

        $this->assertSame(
            [Slot::T19_00->value, Slot::T19_15->value, Slot::T19_30->value],
            array_keys((new GetOccupationsForDate)(self::DATE)),
        );
    }

    private function createBooking(string $date, Slot $slot, int $nbGuests): Booking
    {
        return Booking::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'date' => $date,
            'slot' => $slot,
            'nb_guests' => $nbGuests,
        ]);
    }
}
