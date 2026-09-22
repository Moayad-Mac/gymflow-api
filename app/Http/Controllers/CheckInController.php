<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::user()->Staff){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::find($validatedData['booking_id']);

        if ($booking->status !== 'confirmed' && $booking->status !== 'completed') {
            return response()->json(['message' => 'Only confirmed bookings can be checked in'], 400);
        }

        if (CheckIn::where('booking_id', $booking->id)->exists()) {
            return response()->json(['message' => 'Booking has already been checked in'], 400);
        }

        CheckIn::create([
            'booking_id' => $booking->id,
            'checked_in_at' => now(),
        ]);

        $booking->status = 'completed';
        $booking->save();

        return response()->json(['message' => 'Check-in successful']);
    }
}
