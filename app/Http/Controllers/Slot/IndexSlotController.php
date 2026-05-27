<?php

declare(strict_types=1);

namespace App\Http\Controllers\Slot;

use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

final class IndexSlotController
{
    public function __invoke(): JsonResponse
    {
        $slots = TimeSlot::whereDate('date', Carbon::today())
            ->orderBy('time')
            ->get()
            ->map(fn(TimeSlot $slot) => [
                'id'               => $slot->id,
                'time'             => $slot->time,
                'period'           => $slot->period,
                'available_covers' => $slot->availableCovers(),
                'available'        => $slot->availableCovers() > 0,
            ]);

        return response()->json([
            'date'  => Carbon::today()->toDateString(),
            'slots' => $slots,
        ]);
    }
}
