<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['booking_id', 'checked_in_at'])]
class CheckIn extends Model
{
    public function Booking()
    {
        return $this->belongsTo(Booking::class);
    }

    protected function casts(): array
    {
        return [
            'checked_in_at' => 'date',
        ];
    }
}
