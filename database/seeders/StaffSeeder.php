<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            ['name' => 'Nadine Aoun', 'email' => 'nadine.staff@gymflow.test', 'gym_id' => 1],
            ['name' => 'Rami Saad', 'email' => 'rami.staff@gymflow.test', 'gym_id' => 2],
        ];

        foreach ($staffMembers as $staffData) {
            $user = User::create([
                'name' => $staffData['name'],
                'email' => $staffData['email'],
                'password' => Hash::make('password'),
            ]);

            Staff::create([
                'user_id' => $user->id,
                'gym_id' => $staffData['gym_id'],
            ]);
        }
    }
}
