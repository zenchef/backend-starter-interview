<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today()->toDateString();

        $slots = [
            // Lunch — some availability, one full
            ['date' => $today, 'time' => '12:00', 'period' => 'lunch', 'max_covers' => 8,  'booked_covers' => 4],
            ['date' => $today, 'time' => '12:30', 'period' => 'lunch', 'max_covers' => 6,  'booked_covers' => 6], // full
            ['date' => $today, 'time' => '13:00', 'period' => 'lunch', 'max_covers' => 8,  'booked_covers' => 2],
            ['date' => $today, 'time' => '13:30', 'period' => 'lunch', 'max_covers' => 6,  'booked_covers' => 0],

            // Dinner — mostly open
            ['date' => $today, 'time' => '19:00', 'period' => 'dinner', 'max_covers' => 10, 'booked_covers' => 0],
            ['date' => $today, 'time' => '19:30', 'period' => 'dinner', 'max_covers' => 10, 'booked_covers' => 3],
            ['date' => $today, 'time' => '20:00', 'period' => 'dinner', 'max_covers' => 8,  'booked_covers' => 8], // full
            ['date' => $today, 'time' => '20:30', 'period' => 'dinner', 'max_covers' => 10, 'booked_covers' => 1],
            ['date' => $today, 'time' => '21:00', 'period' => 'dinner', 'max_covers' => 6,  'booked_covers' => 0],
        ];

        foreach ($slots as $slot) {
            DB::table('time_slots')->insert(array_merge($slot, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
