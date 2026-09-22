<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['gym_id', 'trainer_id', 'name', 'day_of_week', 'starts_at', 'duration', 'capacity'])]
class GymClass extends Model
{
    public function Gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function Trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function Bookings()
    {
        return $this->hasMany(Booking::class);
    }

     protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'starts_at' => 'string',
        ];
    }
}
