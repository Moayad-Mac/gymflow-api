<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\GymClass;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function viewGyms(){
        $gyms = Gym::all();
        return response()->json($gyms);
    }

    public function viewGymsAndClasses (){
        $gyms_and_classes = Gym::with('GymClasses')->get();
        return response()->json($gyms_and_classes);
    }
}
