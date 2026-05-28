<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\HasAvailabilityForBooking;
use App\Enums\Slot;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

final class StoreBookingController
{
    public function __invoke(
        StoreBookingRequest $request,
        HasAvailabilityForBooking $hasAvailabilityForBooking,
    ): RedirectResponse {
        $validated = $request->validated();

        if (! $hasAvailabilityForBooking($validated['date'], $request->enum('slot', Slot::class), (int) $validated['nb_guests'])) {
            throw ValidationException::withMessages([
                'slot' => 'The selected time slot is not available for this date.',
            ]);
        }

        Booking::create($validated);

        return back()->with('success', 'Reservation confirmed!');
    }
}
