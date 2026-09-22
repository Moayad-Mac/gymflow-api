<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'price', 'duration_days'])]
class MembershipPlan extends Model
{
    public function Subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
