<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = [
            [
                'name' => 'GymFlow Downtown',
                'location' => '12 Main Street, Downtown',
                'contact_number' => '+1-555-0101',
            ],
            [
                'name' => 'GymFlow Riverside',
                'location' => '48 River Road, Riverside',
                'contact_number' => '+1-555-0102',
            ],
            [
                'name' => 'GymFlow Uptown',
                'location' => '200 North Ave, Uptown',
                'contact_number' => '+1-555-0103',
            ],
        ];

        foreach ($gyms as $gym) {
            Gym::firstOrCreate($gym);
        }
    }
}
