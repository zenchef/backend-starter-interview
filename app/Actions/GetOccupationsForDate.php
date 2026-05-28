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
        return [];
    }
}
