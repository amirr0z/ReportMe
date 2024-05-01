<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * register new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|unique:users,email|max:255'
        ]);
        $user = User::create($validated);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
    }

    /**
     * login user
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
            'email' => 'required|string|email'
        ]);
        if (!Auth::attempt($validated))
            return response()->json(['message' => 'The provided credentials are incorrect'], 400);
        $user = User::where('email', $validated['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['message' => 'User loged in successfully',  'token' => $token], 200);
    }

    /**
     * update user
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:6',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $request->user()->id,
        ]);
        $request->user()->update($validated);
        return response()->json(['message' => 'User profile successfully updated', 'user' => $request->user()], 200);
    }

    /**
     * get user
     */
    public function index(Request $request)
    {
        return response()->json(['message' => 'successful', 'user' => $request->user()], 200);
    }
}
