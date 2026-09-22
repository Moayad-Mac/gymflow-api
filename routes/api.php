<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\GymClassController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/gyms', [GymController::class, 'viewGyms']);
Route::get('/gyms-and-classes',[GymController::class, 'viewGymsAndClasses']);



Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index']); 
    Route::delete('/subscriptions/{subscription_id}', [SubscriptionController::class, 'destroy']);

    Route::get('/gym-classes', [GymClassController::class, 'index']);
    Route::get('/gym-classes/{id}', [GymClassController::class, 'show']);
    Route::post('/gym-classes', [GymClassController::class, 'store']);
    Route::delete('/gym-classes/{id}', [GymClassController::class, 'destroy']);

    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/gym-classes/{id}/roster', [BookingController::class, 'classRoster']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

    Route::post('/check-in', [CheckInController::class, 'store']);

    Route::get('/trainers', [TrainerController::class, 'index']);

    Route::get('/members-and-plans', [MembershipPlanController::class, 'index']);

});