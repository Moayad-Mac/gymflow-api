<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {

            return response()->json(['message' => 'Unauthorized'], 401);

        }elseif ($user->Staff || $user->Trainer) {

            $gymClasses = $user->Staff ? GymClass::with('Trainer.User', 'Gym')->get() : GymClass::with('Trainer.User', 'Gym')->where('trainer_id', $user->Trainer->id)->get();
            $data = [];
            foreach ($gymClasses as $gymClass) {
                array_push($data, [
                    'id' => $gymClass->id,
                    'class_name' => $gymClass->name,
                    'day_of_week' => $gymClass->day_of_week,
                    'starts_at' => Carbon::parse($gymClass->starts_at)->format('H:i:s'),
                    'duration' => $gymClass->duration,
                    'trainer' => $gymClass->Trainer->User->name,
                    'gym' => $gymClass->Gym->name,
                    'capacity' => $gymClass->capacity,
                ]);
            }

            return response()->json(['data' => $data]);

        } elseif ($user->Member) {

            $gymClasses = GymClass::with('Trainer.User', 'Gym')->get();
            $data = [];

            foreach ($gymClasses as $gymClass) {
                array_push($data, [
                    'id' => $gymClass->id,
                    'class_name' => $gymClass->name,
                    'day_of_week' => $gymClass->day_of_week,
                    'starts_at' => Carbon::parse($gymClass->starts_at)->format('H:i:s'),
                    'duration' => $gymClass->duration,
                    'trainer' => $gymClass->Trainer->User->name,
                    'gym' => $gymClass->Gym->name,
                ]);
            }
            return response()->json(['data' => $data]);

        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function show(int $id)
    {
        $user = Auth::user();
        if (!$user) {

            return response()->json(['message' => 'Unauthorized'], 401);

        }elseif ($user->Staff || $user->Trainer) {

            $gymClass = $user->Staff ? GymClass::with('Trainer.User', 'Gym')->findOrFail($id) : GymClass::with('Trainer.User', 'Gym')->where('trainer_id', $user->Trainer->id)->findOrFail($id);

            return response()->json(['data' => [
                'id' => $gymClass->id,
                'class_name' => $gymClass->name,
                'day_of_week' => $gymClass->day_of_week,
                'starts_at' => Carbon::parse($gymClass->starts_at)->format('H:i:s'),
                'duration' => $gymClass->duration,
                'trainer' => $gymClass->Trainer->User->name,
                'gym' => $gymClass->Gym->name,
                'capacity' => $gymClass->capacity,
            ]]);

        } elseif ($user->Member) {

            $gymClass = GymClass::with('Trainer.User', 'Gym')->findOrFail($id);
            
            return response()->json(['data' => [
                'id' => $gymClass->id,
                'class_name' => $gymClass->name,
                'day_of_week' => $gymClass->day_of_week,
                'starts_at' => Carbon::parse($gymClass->starts_at)->format('H:i:s'),
                'duration' => $gymClass->duration,
                'trainer' => $gymClass->Trainer->User->name,
                'gym' => $gymClass->Gym->name,
            ]]);

        } else {

            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function store(Request $request)
    {
        if (!$request->user()->Staff) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'trainer_id' => 'required|exists:trainers,id',
            'gym_id' => 'required|exists:gyms,id',
            'duration' => 'required|integer|min:1',
            'day_of_week' => 'required|integer|between:0,6',
            'starts_at' => 'required|date_format:H:i:s',
            'capacity' => 'required|integer|min:1',
        ]);

        $gymClass = GymClass::create($validatedData);
        return response()->json(['data' => $gymClass], 201);
    }

    public function destroy(int $id)
    {
        if (!Auth::user()->Staff) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $class = GymClass::findOrFail($id);
        $class->delete();

        return response()->json(['data' => ['message' => 'Gym class deleted successfully']  ]);

    }
}
