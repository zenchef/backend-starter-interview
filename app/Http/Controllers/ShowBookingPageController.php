<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetBookableSlotsByDate;
use App\Actions\GetOccupationsByDate;
use Illuminate\Contracts\View\View;

final class ShowBookingPageController
{
    public function __invoke(
        GetBookableSlotsByDate $getBookableSlotsByDate,
        GetOccupationsByDate $getOccupationsByDate,
    ): View {
        $bookableSlotsByDate = $getBookableSlotsByDate();

        return view('index', [
            'bookableSlotsByDate' => $bookableSlotsByDate,
            'occupationsByDate' => $getOccupationsByDate($bookableSlotsByDate->keys()->all()),
        ]);
    }
}
