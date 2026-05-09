<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class DigitalClosetController extends Controller
{
    // UC-7: Get user's digital closet (unlisted items)
    public function getCloset(Request $request)
    {
        // Items user owns but hasn't listed (status: 'closet')
        $closet = Item::where('seller_id', $request->user()->id)
            ->where('status', 'closet')
            ->get();

        return response()->json([
            'closet_items' => $closet,
            'count' => $closet->count()
        ]);
    }

    // Add item to closet (not listed yet)
    public function addToCloset(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'condition' => 'sometimes|string',
            'size' => 'nullable|string',
            'material' => 'nullable|string',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $item = Item::create([
            'seller_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'condition' => $request->condition ?? 'good',
            'price' => 0,
            'status' => 'closet',
            'category_id' => $request->category_id,
            'carbon_savings' => 0,
        ]);

        // Add metadata
        if ($request->size) {
            $item->update(['size' => $request->size]);
        }
        if ($request->material) {
            $item->update(['material' => $request->material]);
        }

        return response()->json([
            'message' => 'Item added to digital closet',
            'item' => $item
        ], 201);
    }

    // Move item from closet to marketplace
    public function listItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $item = Item::find($request->item_id);
        
        if ($item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->update([
            'status' => 'available',
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Item listed on marketplace',
            'item' => $item
        ]);
    }

    // Remove from closet (delete)
    public function removeFromCloset(Request $request, $id)
    {
        $item = Item::find($id);
        
        if (!$item || $item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item removed from closet']);
    }

    // Create swap invitation for closet item
    public function createSwapInvite(Request $request, $id)
    {
        $item = Item::find($id);
        
        if (!$item || $item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'message' => 'Swap invitation created',
            'invitation_id' => rand(1000, 9999),
            'item' => $item,
            'invite_link' => url('/swap-invite/' . $item->id),
        ]);
    }
}