<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['member_id', 'membership_plan_id', 'starts_at', 'expires_at', 'status'])]
class Subscription extends Model
{
    public function Member()
    {
        return $this->belongsTo(Member::class);
    }

    public function MembershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'expires_at' => 'date',
        ];
    }
}
