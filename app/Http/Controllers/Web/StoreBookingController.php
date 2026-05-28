<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;

final class StoreBookingController
{
    public function __invoke(StoreBookingRequest $request): RedirectResponse
    {
        Booking::create($request->validated());

        return back()->with('success', 'Reservation confirmed!');
    }
}
