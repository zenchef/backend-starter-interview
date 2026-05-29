<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetBookableSlotsByDate;
use App\Actions\GetOccupationsForDate;
use Illuminate\Contracts\View\View;

final class ShowBookingPageController
{
    public function __invoke(
        GetBookableSlotsByDate $getBookableSlotsByDate,
        GetOccupationsForDate $getOccupationsForDate,
    ): View {
        $bookableSlotsByDate = $getBookableSlotsByDate();

        $occupationsByDate = [];
        foreach ($bookableSlotsByDate->keys() as $date) {
            $occupationsByDate[$date] = $getOccupationsForDate($date);
        }

        return view('index', [
            'bookableSlotsByDate' => $bookableSlotsByDate,
            'occupationsByDate' => $occupationsByDate,
        ]);
    }
}
