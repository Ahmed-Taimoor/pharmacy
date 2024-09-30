<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\CredentialEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // User Registration (Customer)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $password = Str::random(8);

        try {
            Mail::to($request->email)->send(new CredentialEmail($request->name, $request->email, $password));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
            $user->assignRole('customer');

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => "Credentials sent via Email."
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // User Registration (Pharmacy)
    public function registerPharmacy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $password = Str::random(8);

        try {
            Mail::to($request->email)->send(new CredentialEmail($request->name, $request->email, $password));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
            $user->assignRole('pharmacy');

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => "Credentials sent via Email."
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // User Registration (Service Provider)
    public function registerServiceProvider(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $password = Str::random(8);

        try {
            Mail::to($request->email)->send(new CredentialEmail($request->name, $request->email, $password));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
            $user->assignRole('service_provider');

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => "Credentials sent via Email."
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'error' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // User Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tokens Revoked',
        ]);
    }
}
