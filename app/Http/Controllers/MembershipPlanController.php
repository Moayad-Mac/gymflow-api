<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipPlanController extends Controller
{
    public function index () {
        
        if(!Auth::user() || !Auth::user()->Staff){
            return response()->json(['message' => 'unauthorized'], 401);
        }

        $membership_plans = MembershipPlan::all();
        $members = Member::with('User')->get();

        return response()->json(['membership_plans' => $membership_plans, 'members' => $members]);

    }
}
