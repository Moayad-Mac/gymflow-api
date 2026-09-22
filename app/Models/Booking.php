<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['member_id', 'gym_class_id', 'class_date', 'booked_at', 'status'])]
class Booking extends Model
{
    public function Member()
    {
        return $this->belongsTo(Member::class);
    }

    public function GymClass()
    {
        return $this->belongsTo(GymClass::class);
    }

    public function CheckIn()
    {
        return $this->hasOne(CheckIn::class);
    }

    protected function casts(): array
    {
        return [
            'class_date' => 'date',
            'booked_at' => 'datetime',
        ];
    }
}
