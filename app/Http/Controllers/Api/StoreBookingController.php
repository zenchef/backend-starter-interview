<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class StoreBookingController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Booking creation not implemented yet'], 501);
    }
}
