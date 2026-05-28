<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\Slot;
use App\Models\BookableSlot;

final class HasAvailabilityForBooking
{
    public function __invoke(string $date, Slot $slot): bool
    {
        return BookableSlot::query()
            ->whereDate('date', $date)
            ->where('slot', $slot)
            ->exists();
    }
}
