<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Validation\Rule;
class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceproviders = ServiceProvider::all();
        return response()->json($serviceproviders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


   public function updateProfile(Request $request)
{
    $provider = $request->user();
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('service_providers')->ignore($provider->id)],
        'phone' => ['required', 'string', Rule::unique('service_providers')->ignore($provider->id)],
        'bio' => 'required|string|max:1000',
        'hourly_rate' => 'required|numeric|min:0',
        'location' => 'required|string|max:255',
        'is_available' => 'boolean',
        'current_password' => 'nullable|string|required_with:new_password',
        'new_password' => 'nullable|string|min:8|confirmed',
        'avatar' => 'nullable|image|max:2048',
    ]);

    // Handle password change
    if ($request->filled('current_password')) {
        if (!Hash::check($request->current_password, $provider->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }
        $provider->password = Hash::make($request->new_password);
    }

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        // Delete old avatar if exists
        if ($provider->avatar) {
            Storage::disk('public')->delete($provider->avatar);
        }
        
        $path = $request->file('avatar')->store('avatars', 'public');
        $provider->avatar = $path;
    }

    // Update profile fields
    $provider->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'bio' => $validated['bio'],
        'hourly_rate' => $validated['hourly_rate'],
        'location' => $validated['location'],
        'is_available' => $validated['is_available'] ?? $provider->is_available,
    ]);

    // Return updated provider data with avatar URL
    return response()->json([
        'message' => 'Profile updated successfully',
        'provider' => [
            'id' => $provider->id,
            'name' => $provider->name,
            'email' => $provider->email,
            'phone' => $provider->phone,
            'bio' => $provider->bio,
            'hourly_rate' => $provider->hourly_rate,
            'location' => $provider->location,
            'is_available' => $provider->is_available,
            'avatar_url' => $provider->avatar ? Storage::url($provider->avatar) : null,
            'completion_percentage' => $this->calculateProfileCompletion($provider),
        ]
    ]);
}

private function calculateProfileCompletion($provider)
{
    $requiredFields = [
        $provider->name,
        $provider->email,
        $provider->phone,
        $provider->bio,
        $provider->hourly_rate,
        $provider->location
    ];
    
    $completed = count(array_filter($requiredFields));
    return round(($completed / count($requiredFields)) * 100);
}
}
