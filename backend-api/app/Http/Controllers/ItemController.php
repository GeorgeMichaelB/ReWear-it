<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['seller', 'category', 'materialCategory'])->where('status', 'available');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        $items = $query->latest()->paginate(20);
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'material_category_id' => 'nullable|exists:material_categories,id',
            'carbon_savings' => 'nullable|numeric',
        ]);

        $item = Item::create([
            'seller_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'status' => 'available',
            'carbon_savings' => $request->carbon_savings ?? 0.0,
            'material_category_id' => $request->material_category_id,
        ]);

        return response()->json($item->load('seller', 'category'), 201);
    }

    public function show(Item $item)
    {
        return response()->json($item->load('seller', 'category', 'materialCategory', 'transformationLogs'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'condition' => 'sometimes|string',
            'status' => 'sometimes|string',
            'carbon_savings' => 'sometimes|numeric',
        ]);

        $item->update($request->only(['title', 'description', 'price', 'condition', 'status', 'carbon_savings']));

        return response()->json($item);
    }

    public function destroy(Request $request, Item $item)
    {
        if ($item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function updateStatus(Request $request, Item $item)
    {
        $request->validate(['status' => 'required|string']);
        $item->updateStatus($request->status);
        return response()->json($item);
    }

    public function carbonSavings(Item $item)
    {
        return response()->json(['carbon_savings' => $item->calculateCarbonSavings()]);
    }

    public function myProducts(Request $request)
    {
        $items = Item::with('category', 'materialCategory')
            ->where('seller_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($items);
    }
}