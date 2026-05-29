<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Slot;
use Database\Factories\BookableSlotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'slot'])]
class BookableSlot extends Model
{
    /** @use HasFactory<BookableSlotFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'slot' => Slot::class,
        ];
    }
}
