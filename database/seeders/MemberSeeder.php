<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Tarek Younes', 'email' => 'tarek@gymflow.test', 'gym_id' => 1],
            ['name' => 'Maya Sabbagh', 'email' => 'maya@gymflow.test', 'gym_id' => 1],
            ['name' => 'Jad Abou Chakra', 'email' => 'jad@gymflow.test', 'gym_id' => 2],
            ['name' => 'Dina Rahal', 'email' => 'dina@gymflow.test', 'gym_id' => 3],
        ];

        foreach ($members as $memberData) {
            $user = User::create([
                'name' => $memberData['name'],
                'email' => $memberData['email'],
                'password' => Hash::make('password'),
            ]);

            Member::create([
                'user_id' => $user->id,
                'gym_id' => $memberData['gym_id'],
            ]);
        }
    }
}
