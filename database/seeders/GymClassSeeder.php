<?php

namespace Database\Seeders;

use App\Models\GymClass;
use Illuminate\Database\Seeder;

class GymClassSeeder extends Seeder
{
    public function run(): void
    {
        GymClass::create([
            'name' => 'Morning Yoga',
            'trainer_id' => 1,
            'gym_id' => 1,
            'duration' => 60,
            'day_of_week' => 1, // Monday
            'starts_at' => '08:00:00',
            'capacity' => 15,
        ]);

        GymClass::create([
            'name' => 'HIIT Blast',
            'trainer_id' => 2,
            'gym_id' => 1,
            'duration' => 45,
            'day_of_week' => 3, // Wednesday
            'starts_at' => '18:00:00',
            'capacity' => 20,
        ]);

        GymClass::create([
            'name' => 'Strength Training',
            'trainer_id' => 3,
            'gym_id' => 2,
            'duration' => 60,
            'day_of_week' => 2, // Tuesday
            'starts_at' => '17:00:00',
            'capacity' => 12,
        ]);

        GymClass::create([
            'name' => 'Evening Pilates',
            'trainer_id' => 4,
            'gym_id' => 3,
            'duration' => 50,
            'day_of_week' => 4, // Thursday
            'starts_at' => '19:00:00',
            'capacity' => 10,
        ]);
    }
}
