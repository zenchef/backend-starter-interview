<?php

declare(strict_types=1);

namespace App\Actions;

final class GetOccupationsByDate
{
    public function __construct(
        private readonly GetOccupationsForDate $getOccupationsForDate,
    ) {}

    /**
     * @param  array<int, string>  $dates
     * @return array<string, array<int, int>>
     */
    public function __invoke(array $dates): array
    {
        $occupationsByDate = [];
        foreach ($dates as $date) {
            $occupationsByDate[$date] = ($this->getOccupationsForDate)($date);
        }

        return $occupationsByDate;
    }
}
