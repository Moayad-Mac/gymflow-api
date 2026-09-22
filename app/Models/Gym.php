<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    public function Members()
    {
        return $this->hasMany(Member::class);
    }

    public function Trainers()
    {
        return $this->hasMany(Trainer::class);
    }

    public function Staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function GymClasses()
    {
        return $this->hasMany(GymClass::class);
    }
}
