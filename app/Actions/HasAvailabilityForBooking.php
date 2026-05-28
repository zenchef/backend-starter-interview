<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Slot;

final class HasAvailabilityForBooking
{
    public function __construct(
        private readonly GetOccupationsByDate $getOccupationsByDate,
    ) {}

    public function __invoke(string $date, Slot $slot, int $nbGuests): bool
    {
        /** return BookableSlot::query()
            ->whereDate('date', $date)
            ->where('slot', $slot)
            ->exists(); */
        $occupationsByDate = ($this->getOccupationsByDate)([$date]);
        $occupation = $occupationsByDate[$date][$slot->value] ?? 0;

        return $occupation + $nbGuests <= (int) config('restaurant.slot_capacity');
    }
}
