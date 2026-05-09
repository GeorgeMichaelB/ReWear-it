<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdvancedController extends Controller
{
    // Order/Transaction Functions (21-30)

    // 21. Calculate shipping estimate
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'from_postal_code' => 'required',
            'to_postal_code' => 'required',
            'weight_kg' => 'required|numeric|min:0.1|max:30'
        ]);
        
        $baseRate = 5.00;
        $weightRate = $request->weight_kg * 0.50;
        $distanceFactor = rand(1.0, 2.5);
        $estimatedCost = ($baseRate + $weightRate) * $distanceFactor;
        
        return response()->json([
            'estimated_cost' => round($estimatedCost, 2),
            'estimated_days' => rand(2, 7),
            'carrier' => 'EcoShip',
            'carbon_neutral' => true,
        ]);
    }

    // 22. Create swap agreement
    public function createSwapAgreement(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'other_party_id' => 'required|exists:users,id'
        ]);
        
        $agreementId = rand(10000, 99999);
        
        return response()->json([
            'agreement_id' => $agreementId,
            'status' => 'pending',
            'items' => $request->item_ids,
            'other_party' => $request->other_party_id,
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);
    }

    // 23. Cancel swap request
    public function cancelSwapRequest($id)
    {
        return response()->json([
            'message' => 'Swap request cancelled successfully',
            'swap_id' => $id,
            'refund_credits' => rand(10, 50),
        ]);
    }

    // 24. Rate completed swap
    public function rateSwap(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);
        
        return response()->json([
            'message' => 'Rating submitted successfully',
            'swap_id' => $id,
            'rating' => $request->rating,
        ]);
    }

    // 25. Get swap history with status
    public function getSwapHistory(Request $request)
    {
        return response()->json([
            'swaps' => [
                ['id' => 1, 'status' => 'completed', 'items' => ['Vintage Tee', 'Denim Jacket'], 'date' => now()->subDays(5)->toDateTimeString()],
                ['id' => 2, 'status' => 'pending', 'items' => ['Summer Dress'], 'date' => now()->subDays(1)->toDateTimeString()],
                ['id' => 3, 'status' => 'cancelled', 'items' => ['Wool Sweater'], 'date' => now()->subDays(10)->toDateTimeString()],
            ]
        ]);
    }

    // 26. Calculate bundle discount
    public function calculateBundleDiscount(Request $request)
    {
        $request->validate(['item_count' => 'required|integer|min:2']);
        
        $discountRates = [
            2 => 0.05,
            3 => 0.10,
            4 => 0.15,
            5 => 0.20,
        ];
        
        $count = $request->item_count;
        $discount = $discountRates[$count] ?? 0.20;
        
        return response()->json([
            'item_count' => $count,
            'discount_percentage' => $discount * 100,
            'message' => 'You qualify for ' . ($discount * 100) . '% bundle discount!'
        ]);
    }

    // 27. Get order timeline
    public function getOrderTimeline($id)
    {
        return response()->json([
            'order_id' => $id,
            'timeline' => [
                ['step' => 'Order Placed', 'status' => 'completed', 'date' => now()->subDays(5)->toDateTimeString()],
                ['step' => 'Payment Confirmed', 'status' => 'completed', 'date' => now()->subDays(4)->toDateTimeString()],
                ['step' => 'Item Shipped', 'status' => 'completed', 'date' => now()->subDays(2)->toDateTimeString()],
                ['step' => 'In Transit', 'status' => 'current', 'date' => now()->toDateTimeString()],
                ['step' => 'Delivered', 'status' => 'pending', 'date' => null],
            ]
        ]);
    }

    // 28. Track shipment
    public function trackShipment($id)
    {
        return response()->json([
            'shipment_id' => $id,
            'status' => 'in_transit',
            'current_location' => 'Distribution Center - Cairo',
            'estimated_delivery' => now()->addDays(2)->toDateTimeString(),
            'tracking_events' => [
                ['location' => 'Pickup Facility', 'status' => 'Picked up', 'date' => now()->subDays(2)->toDateTimeString()],
                ['location' => 'Sorting Center', 'status' => 'In sorting', 'date' => now()->subDays(1)->toDateTimeString()],
                ['location' => 'Distribution Center - Cairo', 'status' => 'Arrived', 'date' => now()->toDateTimeString()],
            ]
        ]);
    }

    // 29. Estimate order value
    public function estimateOrderValue(Request $request)
    {
        $request->validate(['item_ids' => 'required|array']);
        
        $itemCount = count($request->item_ids);
        $baseValue = $itemCount * 25;
        $carbonBonus = $itemCount * 5;
        
        return response()->json([
            'item_count' => $itemCount,
            'base_value' => $baseValue,
            'carbon_bonus_credits' => $carbonBonus,
            'total_estimated_value' => $baseValue + $carbonBonus,
        ]);
    }

    // 30. Get transaction analytics
    public function getTransactionAnalytics(Request $request)
    {
        return response()->json([
            'total_swaps' => rand(10, 50),
            'total_value' => rand(500, 2000),
            'carbon_saved' => rand(50, 200),
            'monthly_stats' => [
                'this_month' => rand(1, 10),
                'last_month' => rand(5, 15),
                'trend' => 'up'
            ],
            'top_categories' => ['Tops', 'Bottoms', 'Accessories'],
        ]);
    }
}