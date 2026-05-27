<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StoreReservationController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Reservation creation not implemented yet'], 501);
        // TODO: implement reservation creation
        //
        // Requirements:
        //    - all fields are required
        //    - covers must be a positive integer
        //    - the slot must exist
        //    - the slot must have enough remaining capacity for the requested covers
        //
        //
        // Returns a JSON response
        //    - 201 with the created reservation on success
        //    - 422 with validation errors if the request is invalid
        //    - 400 if the slot doesn't have enough capacity
    }
}
