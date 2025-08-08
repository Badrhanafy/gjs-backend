<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:service_providers',
            'email' => 'nullable|email|unique:service_providers',
            'password' => 'required|string|min:8',
            'category_id' => 'required|exists:categories,id',
            'rate' => 'required|numeric',
            'location' => 'required|string',
        ]);

        $provider = ServiceProvider::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'category_id' => $request->category_id,
            'hourly_rate' => $request->rate,
            'location' => $request->location,
        ]);

        return response()->json([
            'token' => $provider->createToken('gjs-token')->plainTextToken,
            'provider' => $provider
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $provider = ServiceProvider::where('phone', $request->phone)->first();

        if (!$provider || !Hash::check($request->password, $provider->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $provider->createToken('gjs-token')->plainTextToken,
            'provider' => $provider
        ]);
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the current access token
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    ////////////// normal (user) clients

    public function registerClient(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                
            ]);
            return response()->json([
                'message' => 'Client registration successful',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

}