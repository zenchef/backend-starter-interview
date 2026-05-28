<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BookableSlot;
use Illuminate\Support\Collection;

final class GetBookableSlotsByDate
{
    /**
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
