<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Slot;
use App\Models\BookableSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookableSlot>
 */
class BookableSlotFactory extends Factory
{
    protected $model = BookableSlot::class;

    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'slot' => fake()->randomElement(Slot::cases()),
        ];
    }
}
