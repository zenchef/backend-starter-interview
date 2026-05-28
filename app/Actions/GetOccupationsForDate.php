<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookableSlot;
use App\Models\Booking;

final class GetOccupationsForDate
{
    /**
     * Expected shape (slot value => guests occupying that slot):
     *   [
     *       20 => 4,
     *       21 => 6,
     *       22 => 6,
     *       ...
     *   ]
     *
     * A booking starting at slot S occupies slots S, S+1, ..., S+(booking_duration-1).
     * For each bookable slot, return the max number of guests across its
     * booking_duration window. Only return entries for slots present in BookableSlot.
     *
     * @return array<int, int>
     */
    public function __invoke(string $date): array
    {
        $bookingDuration = (int) config('restaurant.booking_duration');

        $bookableSlots = BookableSlot::query()
            ->whereDate('date', $date)
            ->orderBy('slot')
            ->get();

        $bookings = Booking::query()
            ->whereDate('date', $date)
            ->get();

        $bookedGuestsBySlot = [];
        foreach ($bookings as $booking) {
            $firstSlot = $booking->slot->value;

            for ($slotOffset = 0; $slotOffset < $bookingDuration; $slotOffset++) {
                $occupiedSlot = $firstSlot + $slotOffset;
                $bookedGuestsBySlot[$occupiedSlot] = ($bookedGuestsBySlot[$occupiedSlot] ?? 0) + $booking->nb_guests;
            }
        }

        $occupationsBySlot = [];
        foreach ($bookableSlots as $bookableSlot) {
            $firstSlot = $bookableSlot->slot->value;
            $maxOccupation = 0;

            for ($slotOffset = 0; $slotOffset < $bookingDuration; $slotOffset++) {
                $occupiedSlot = $firstSlot + $slotOffset;
                $maxOccupation = max($maxOccupation, $bookedGuestsBySlot[$occupiedSlot] ?? 0);
            }

            $occupationsBySlot[$firstSlot] = $maxOccupation;
        }

        return $occupationsBySlot;
    }
}
