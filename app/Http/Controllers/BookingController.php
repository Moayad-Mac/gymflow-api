<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\GymClass;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->Member){ 

            $bookings = $user->Member->Bookings()->with('GymClass')->get();
            return response()->json($bookings);

        } elseif ($user->Staff) {

            $bookings = Booking::with('GymClass', 'Member.User')->get();
            return response()->json($bookings);

        } else {
            return response()->json(['message' => 'Unauthorized'], 400);
        }
    }

    public function store (Request $request)
    {
        if (!Auth::user()->Member && !Auth::user()->Staff) {
            return response()->json(['message' => 'Only members or staff can book classes'], 403);
        }

        $validatedData = $request->validate([
            'gym_class_id' => 'required|exists:gym_classes,id',
            'class_date' => 'required|date',
        ]);

        $member_id = Auth::user()->Member ? Auth::user()->Member->id : (Auth::user()->staff ? $request->member_id : null);
        
        if (!$member_id) {
            return response()->json(['message' => 'missing member_id'], 401);
        }

        $member = Member::find($member_id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        if (!$member->hasActiveSubscription()) {
            return response()->json(['message' => 'Member does not have an active subscription'], 403);
        }

        if (Carbon::parse($request->class_date)->isPast()) {
            return response()->json(['message' => 'Cannot book a class in the past'], 400);
        }

        $gym_class = GymClass::find($validatedData['gym_class_id']);

        if ($gym_class->day_of_week !== Carbon::parse($validatedData['class_date'])->dayOfWeek)
        {
            return response()->json(['message' => 'Class date does not match the scheduled day of the week for this class'], 400);
        }

        if (Booking::where('gym_class_id', $validatedData['gym_class_id'])
            ->where('class_date', $validatedData['class_date'])
            ->where('status', 'confirmed')
            ->count() >= $gym_class->capacity) {
            return response()->json(['message' => 'Class is already full'], 400);
        }

        if (Booking::where('member_id', $member_id)
            ->where('gym_class_id', $validatedData['gym_class_id'])
            ->where('class_date', $validatedData['class_date'])
            ->where('status', 'confirmed')
            ->exists()) {
            return response()->json(['message' => 'Member has already booked this class on this date'], 400);
        }

        $booking = Booking::create([
            'member_id' => $member_id,
            'gym_class_id' => $validatedData['gym_class_id'],
            'status' => 'confirmed',
            'booked_at' => now(),
            'class_date' => $validatedData['class_date'],
        ]);

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    public function destroy(int $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if (Auth::user()->Staff || (Auth::user()->Member && Auth::user()->Member->id === $booking->member_id)) {

            if ($booking->status !== 'confirmed') {
                return response()->json(['message' => 'Only confirmed bookings can be cancelled'], 400);
            }


            if (Carbon::parse($booking->class_date)->isPast()) {
                return response()->json(['message' => 'Cannot cancel a booking for a class that has already occurred'], 400);
            }

            $classDateTime = Carbon::parse($booking->class_date->format('Y-m-d') . ' ' . $booking->GymClass->starts_at);

            if (Carbon::now()->diffInHours($classDateTime) < 24) {
                return response()->json(['message' => 'Cannot cancel a booking within 24 hours of the class'], 400);
            }
 
            $booking->status = 'cancelled';
            $booking->save();

            return response()->json(['message' => 'Booking cancelled successfully']);
            
        }

        return response()->json(['message' => 'Unauthorized'], 403);      
    }

    public function classRoster(int $id)
    {
        $gym_class = GymClass::find($id);

        if (!$gym_class) {
            return response()->json(['message' => 'Gym class not found'], 404);
        }

        if (Auth::user()->Trainer && $gym_class->trainer_id === Auth::user()->Trainer->id) {
            
            $data = GymClass::with(['Gym', 'Bookings' => function ($query) {
                $query->where('status', 'confirmed');
            }, 'Bookings.Member.user'])->find($id);

            return response()->json($data);       
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
        
}