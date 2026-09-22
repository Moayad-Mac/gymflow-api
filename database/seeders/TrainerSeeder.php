<?php

namespace Database\Seeders;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            ['name' => 'Karim Haddad', 'email' => 'karim.trainer@gymflow.test', 'gym_id' => 1],
            ['name' => 'Lea Fares', 'email' => 'lea.trainer@gymflow.test', 'gym_id' => 1],
            ['name' => 'Omar Nasser', 'email' => 'omar.trainer@gymflow.test', 'gym_id' => 2],
            ['name' => 'Sara Khalil', 'email' => 'sara.trainer@gymflow.test', 'gym_id' => 3],
        ];

        foreach ($trainers as $trainerData) {
            $user = User::create([
                'name' => $trainerData['name'],
                'email' => $trainerData['email'],
                'password' => Hash::make('password'),
            ]);

            Trainer::create([
                'user_id' => $user->id,
                'gym_id' => $trainerData['gym_id'],
            ]);
        }
    }
}
