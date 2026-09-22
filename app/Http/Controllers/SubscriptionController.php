<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPlan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->Staff) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'member_id' => 'required|int|exists:members,id',
            'membership_plan_id' => 'required|int|exists:membership_plans,id',
        ]);

        $member = Member::findOrFail($request->member_id);
        $membership_plan = MembershipPlan::findOrFail($request->membership_plan_id);
        
        if ($member->hasActiveSubscription()) {
            return response()->json(['message' => 'Member already has an active subscription'], 400);
        }

        $subscription = Subscription::create([
            'member_id' => $member->id,
            'membership_plan_id' => $membership_plan->id,
            'status' => 'active',
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addDays($membership_plan->duration_days),
        ]);

        return response()->json(['message' => 'Subscription created successfully', 'subscription' => $subscription], 201);
    }

    public function index()
    {
        $user = request()->user();

        if (!$user || $user->Trainer) 
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        elseif ($user->Staff || $user->Member) 
        {
            $subscriptions = $user->Staff
                ? Subscription::with('Member.User', 'MembershipPlan')->get()
                : ($user->Member ? $user->Member->Subscriptions()->with('Member.User', 'MembershipPlan')->get() : null);
            
            if (is_null($subscriptions)) 
            {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $response = [];
            foreach ($subscriptions as $subscription){
                array_push($response, [
                    'subscription_id' => $subscription->id,
                    'member' => $subscription->Member->User->name,
                    'membership_plan' => $subscription->MembershipPlan->name,
                    'membership_starts_at' => $subscription->starts_at,
                    'membership_expires_at' => $subscription->expires_at,
                    'subscription_status' => $subscription->status,
                    ]);
            }
            return response()->json(['response' => $response]);

        } 
        
        else 
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

    }

    public function destroy(Request $request, int $subscription_id)
    {
        if (!$request->user()->Staff) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $subscription = Subscription::findOrFail($subscription_id);

        if ($subscription->status !== 'active') {
            return response()->json(['message' => 'subscription is not active'], 400);
        }

        $subscription->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Subscription cancelled successfully']);
    }
}
