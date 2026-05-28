<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookableSlot;
use App\Models\Booking;

final class GetOccupationsForDate
{
    /**
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
            ->get(['slot', 'nb_guests']);

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
