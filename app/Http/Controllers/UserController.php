<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->plainTextToken;

            // return response()->json(['token' => $token], 200);
            return redirect('/home')->with('success', 'Zalogowano!');
        } else {
            // return response()->json(['error' => 'Invalid credentials'], 401);
            return redirect('/login')->with('error', 'Nieprawidłowe dane logowania');
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        // return response()->json(['token' => $token], 201);
        return redirect('/home')->with('success', 'Witaj!');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();
            Auth::guard('web')->logout();
        }

        // return response()->json(['message' => 'Logged out successfully'], 200);
        return redirect('/')->with('success', 'Wylogowano pomyślnie');
    }
}
