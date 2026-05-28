<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Slot;
use App\Models\BookableSlot;

final class HasAvailabilityForBooking
{
    public function __construct(
        private readonly GetOccupationsForDate $getOccupationsForDate,
    ) {}

    public function __invoke(string $date, Slot $slot, int $nbGuests): bool
    {
        return BookableSlot::query()
            ->whereDate('date', $date)
            ->where('slot', $slot)
            ->exists();

        /*$occupationsByDate = ($this->getOccupationsForDate)($date);
        $occupation = $occupationsByDate[$slot->value] ?? 0;

        return $occupation + $nbGuests <= (int) config('restaurant.slot_capacity');*/
    }
}
