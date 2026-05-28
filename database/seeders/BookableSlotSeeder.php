<?php

namespace Database\Seeders;

use App\Enums\Slot;
use App\Models\BookableSlot;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class BookableSlotSeeder extends Seeder
{
    public function run(): void
    {
        $bookableSlots = collect([
            Slot::T12_00, Slot::T12_15, Slot::T12_30, Slot::T12_45,
            Slot::T13_00, Slot::T13_15, Slot::T13_30, Slot::T13_45,
            Slot::T14_00, Slot::T14_15, Slot::T14_30,
            Slot::T19_00, Slot::T19_15, Slot::T19_30, Slot::T19_45,
            Slot::T20_00, Slot::T20_15, Slot::T20_30, Slot::T20_45,
            Slot::T21_00, Slot::T21_15, Slot::T21_30, Slot::T21_45,
            Slot::T22_00, Slot::T22_15, Slot::T22_30,
        ])->shuffle();

        $start = now()->startOfDay()->tomorrow()->toImmutable();
        $end = $start->addDays(2);

        $dates = collect(CarbonPeriod::create($start, $end))->shuffle();

        foreach ($dates as $date) {
            foreach ($bookableSlots as $slot) {
                BookableSlot::firstOrCreate([
                    'date' => $date->toDateString(),
                    'slot' => $slot,
                ]);
            }
        }
    }
}
