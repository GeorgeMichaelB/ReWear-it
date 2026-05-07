<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Http\Request;

class MaterialCategoryController extends Controller
{
    public function index()
    {
        $categories = MaterialCategory::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fabric_name' => 'required|string|max:255',
            'is_organic' => 'boolean',
            'recycle_tier' => 'integer|min:1|max:5',
        ]);

        $category = MaterialCategory::create($request->all());
        return response()->json($category, 201);
    }

    public function show(MaterialCategory $materialCategory)
    {
        return response()->json($materialCategory->load('items'));
    }

    public function update(Request $request, MaterialCategory $materialCategory)
    {
        $request->validate([
            'fabric_name' => 'sometimes|string|max:255',
            'is_organic' => 'boolean',
            'recycle_tier' => 'integer|min:1|max:5',
        ]);

        $materialCategory->update($request->all());
        return response()->json($materialCategory);
    }

    public function destroy(MaterialCategory $materialCategory)
    {
        $materialCategory->delete();
        return response()->json(['message' => 'Material category deleted']);
    }
}