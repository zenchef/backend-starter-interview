<?php

namespace App\Models;

use App\Enums\Slot;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['firstname', 'lastname', 'email', 'nb_guests', 'date', 'slot'])]
class Booking extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'slot' => Slot::class,
            'nb_guests' => 'integer',
        ];
    }
}
