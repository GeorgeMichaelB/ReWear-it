<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class BulkListingController extends Controller
{
    // UC-13: Apply global attributes to multiple items
    public function applyBulkAttributes(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'attributes' => 'required|array',
        ]);

        $updatedCount = 0;
        $errors = [];

        foreach ($request->item_ids as $itemId) {
            $item = Item::find($itemId);
            
            if (!$item) {
                $errors[] = "Item $itemId not found";
                continue;
            }

            if ($item->seller_id !== $request->user()->id) {
                $errors[] = "Item $itemId not owned by user";
                continue;
            }

            // Apply attributes
            $attributes = $request->attributes;
            $updateData = [];

            if (isset($attributes['condition'])) {
                $updateData['condition'] = $attributes['condition'];
            }
            if (isset($attributes['category_id'])) {
                $updateData['category_id'] = $attributes['category_id'];
            }
            if (isset($attributes['carbon_savings'])) {
                $updateData['carbon_savings'] = $attributes['carbon_savings'];
            }
            if (isset($attributes['status'])) {
                $updateData['status'] = $attributes['status'];
            }

            if (!empty($updateData)) {
                $item->update($updateData);
                $updatedCount++;
            }
        }

        return response()->json([
            'message' => 'Bulk update completed',
            'updated' => $updatedCount,
            'errors' => $errors,
            'items_updated' => $request->item_ids,
        ]);
    }

    // UC-13: Get items eligible for bulk operations
    public function getMyItemsForBulk(Request $request)
    {
        $items = Item::where('seller_id', $request->user()->id)
            ->whereIn('status', ['available', 'closet'])
            ->get(['id', 'title', 'status', 'condition', 'category_id', 'price']);

        // Group by status for bulk operations
        $grouped = [
            'available' => $items->where('status', 'available')->values(),
            'closet' => $items->where('status', 'closet')->values(),
        ];

        return response()->json([
            'items' => $items,
            'grouped' => $grouped,
            'total' => $items->count(),
            'by_status' => [
                'available' => $items->where('status', 'available')->count(),
                'closet' => $items->where('status', 'closet')->count(),
            ],
        ]);
    }

    // UC-13: Bulk create similar items
    public function bulkCreateSimilar(Request $request)
    {
        $request->validate([
            'base_title' => 'required|string',
            'variations' => 'required|array',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'condition' => 'sometimes|string',
        ]);

        $createdItems = [];

        foreach ($request->variations as $index => $variation) {
            $title = $request->base_title . ' - ' . ($variation['name'] ?? 'Variant ' . ($index + 1));
            $price = $variation['price'] ?? $request->base_price;
            $size = $variation['size'] ?? null;

            $item = Item::create([
                'seller_id' => $request->user()->id,
                'category_id' => $request->category_id,
                'title' => $title,
                'description' => $variation['description'] ?? '',
                'price' => $price,
                'condition' => $request->condition ?? 'good',
                'status' => 'available',
                'size' => $size,
                'carbon_savings' => $request->carbon_savings ?? 0,
            ]);

            $createdItems[] = $item;
        }

        return response()->json([
            'message' => 'Bulk creation completed',
            'created_count' => count($createdItems),
            'items' => $createdItems,
        ], 201);
    }
}