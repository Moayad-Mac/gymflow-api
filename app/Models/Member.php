<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['gym_id', 'user_id'])]
class Member extends Model
{
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function Bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function Subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasActiveSubscription(): bool
    {
    $subscription = $this->subscriptions()
        ->where('status', 'active')
        ->latest('expires_at')
        ->first();

    if (!$subscription) {
        return false;
    }

    if (Carbon::now()->isAfter($subscription->expires_at)) {
        $subscription->update(['status' => 'expired']);
        return false;
    }

    return true;
    }
}
