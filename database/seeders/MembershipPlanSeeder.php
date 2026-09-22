<?php

namespace Database\Seeders;

use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    public function run(): void
    {
        MembershipPlan::firstOrCreate([
            'name' => 'Monthly',
            'price' => 30,
            'duration_days' => 30,
            'description' => 'Full access to all gyms, billed monthly.',
        ]);

        MembershipPlan::firstOrCreate([
            'name' => 'Quarterly',
            'price' => 80,
            'duration_days' => 90,
            'description' => 'Full access to all gyms, billed every 3 months. Save compared to monthly.',
        ]);

        MembershipPlan::firstOrCreate([
            'name' => 'Annual',
            'price' => 280,
            'duration_days' => 365,
            'description' => 'Full access to all gyms, billed yearly. Best value.',
        ]);
    }
}