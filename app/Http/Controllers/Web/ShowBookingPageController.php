<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Actions\GetBookableSlotsByDate;
use Illuminate\Contracts\View\View;

final class ShowBookingPageController
{
    public function __invoke(GetBookableSlotsByDate $getBookableSlotsByDate): View
    {
        return view('index', [
            'bookableSlotsByDate' => $getBookableSlotsByDate(),
        ]);
    }
}
