<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerController extends Controller
{
    public function index (){
        $user = Auth::user();

        if(!$user || !$user->staff){
            return response()->json(['message' => 'unauthorized'], 401);
        }

        $trainers = Trainer::with('User')->get();
        return response()->json($trainers);
    }
}
