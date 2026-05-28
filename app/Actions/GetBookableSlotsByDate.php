<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookableSlot;
use Illuminate\Support\Collection;

final class GetBookableSlotsByDate
{
    /**
     * Expected shape:
     *   [
     *       '2026-05-28' => [BookableSlot, BookableSlot, ...],
     *       '2026-05-29' => [BookableSlot, ...],
     *       ...
     *   ]
     *
     * Sort by date, and bookable slots within each date must be sorted by slot value.
     *
     * @return Collection<string, Collection<int, BookableSlot>>
     */
    public function __invoke(): Collection
    {
        return BookableSlot::query()
            ->orderBy('date')
            ->orderBy('slot')
            ->get()
            ->groupBy(fn (BookableSlot $bookableSlot) => $bookableSlot->date->toDateString());
    }
}
