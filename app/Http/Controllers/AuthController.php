<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
			'gym_id' => 'required|exists:gyms,id|integer'
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
		
		$user->Member()->create(['gym_id' =>  $validatedData['gym_id']]);

        $userRole = [
            'role' => 'member',
            'gym_id' => $validatedData['gym_id'],
            'role_id' => $user->Member->id
        ];
		
		$token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token, 'userRole' => $userRole], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $credentials['email'])->FirstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $userRole =[];

        $userRole['role'] = $user->Member ? 'member':($user->Trainer ? 'trainer' : ($user->Staff ? 'staff' : null));
        $userRole['role_id'] = $user->Member ? $user->Member->id:($user->Trainer ? $user->Trainer->id : ($user->Staff ? $user->Staff->id : null));
        $userRole['gym_id'] = $user->Member ? $user->Member->gym_id:($user->Trainer ? $user->Trainer->gym_id : ($user->Staff ? $user->Staff->gym_id : null));

        return response()->json(['message' => 'Login successful', 'user' => $user, 'token' => $token ,'userRole' => $userRole], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
