<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['gym_id', 'user_id'])]
class Trainer extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function GymClasses()
    {
        return $this->hasMany(GymClass::class);
    }
}
