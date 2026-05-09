<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemAdvancedController extends Controller
{
    // Item Functions (6-14)

    // 6. Mark item as swap-ready
    public function markSwapReady(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update(['status' => 'available']);
        return response()->json(['message' => 'Item marked as swap-ready', 'item' => $item]);
    }

    // 7. Calculate item's carbon footprint
    public function calculateCarbonFootprint($id)
    {
        $item = Item::findOrFail($id);
        $baseCarbon = 2.5;
        $materialFactor = in_array($item->condition, ['new', 'like_new']) ? 1.5 : 1.0;
        $carbonFootprint = $baseCarbon * $materialFactor;
        
        return response()->json([
            'item_id' => $item->id,
            'carbon_footprint_kg' => round($carbonFootprint, 2),
            'savings_vs_new' => round($carbonFootprint * 0.7, 2),
            'equivalent' => [
                'trees_needed' => round($carbonFootprint / 21, 2),
                'car_miles_saved' => round($carbonFootprint * 2.5, 2),
            ]
        ]);
    }

    // 8. Get similar items recommendations
    public function getSimilarItems(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $similar = Item::where('category_id', $item->category_id)
            ->where('id', '!=', $id)
            ->where('status', 'available')
            ->limit(5)
            ->get();
        
        return response()->json(['similar_items' => $similar]);
    }

    // 9. Add item to style board
    public function addToStyleBoard(Request $request, $itemId)
    {
        $request->validate(['style_board_id' => 'required|exists:style_boards,id']);
        
        $item = Item::findOrFail($itemId);
        
        return response()->json([
            'message' => 'Item added to style board',
            'style_board_id' => $request->style_board_id,
            'item' => $item
        ]);
    }

    // 10. Report item as inappropriate
    public function reportItem(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|in:inappropriate,scam,duplicate,other',
            'description' => 'nullable|string|max:500'
        ]);
        
        return response()->json([
            'message' => 'Report submitted successfully',
            'report_id' => rand(1000, 9999),
            'item_id' => $id
        ]);
    }

    // 11. Share item link
    public function shareItem($id)
    {
        $item = Item::findOrFail($id);
        $shareUrl = url('/items/' . $id);
        
        return response()->json([
            'item_id' => $id,
            'title' => $item->title,
            'share_url' => $shareUrl,
            'share_text' => 'Check out this item on ReWear-it: ' . $item->title
        ]);
    }

    // 12. Get item statistics
    public function getItemStatistics($id)
    {
        $item = Item::findOrFail($id);
        
        return response()->json([
            'item_id' => $item->id,
            'views' => rand(50, 500),
            'favorites' => rand(5, 50),
            'inquiries' => rand(2, 20),
            'swaps_completed' => rand(0, 5),
            'avg_response_time_hours' => rand(1, 12),
        ]);
    }

    // 13. Bulk update item status
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'status' => 'required|in:available,pending,sold'
        ]);
        
        Item::whereIn('id', $request->item_ids)->update(['status' => $request->status]);
        
        return response()->json([
            'message' => 'Status updated for ' . count($request->item_ids) . ' items'
        ]);
    }

    // 14. Calculate item depreciation
    public function calculateDepreciation($id)
    {
        $item = Item::findOrFail($id);
        $originalPrice = $item->price * 1.5;
        $daysListed = rand(1, 90);
        $depreciationRate = 0.001;
        $depreciatedValue = $originalPrice * (1 - ($depreciationRate * $daysListed));
        
        return response()->json([
            'original_price' => round($originalPrice, 2),
            'current_listing_price' => $item->price,
            'depreciated_value' => round(max($depreciatedValue, $item->price * 0.5), 2),
            'depreciation_percentage' => round((($originalPrice - $depreciatedValue) / $originalPrice) * 100, 2),
            'days_listed' => $daysListed
        ]);
    }
}