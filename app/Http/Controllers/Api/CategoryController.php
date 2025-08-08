<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    // Get all categories with count of related serviceProviders
    $categories = \App\Models\Category::withCount('serviceProviders')->get();

    if ($categories->isEmpty()) {
        return response()->json([
            'message' => 'No categories found'
        ], 404);
    }

    return response()->json([
        'categories' => $categories,
        'message' => 'Categories found'
    ]);
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $categories = Category::all();
       if ($categories) {
         return response()->json($categories, 200, $headers);
       }
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
}
