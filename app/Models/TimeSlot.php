<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeSlot extends Model
{
    protected $fillable = ['date', 'time', 'period', 'max_covers', 'booked_covers'];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function availableCovers(): int
    {
        return $this->max_covers - $this->booked_covers;
    }
}
