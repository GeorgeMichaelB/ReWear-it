<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    // UC-24: Generate tracking number
    public function generateTracking(Request $request)
    {
        $request->validate([
            'from_postal' => 'required|string',
            'to_postal' => 'required|string',
            'weight_kg' => 'required|numeric|min:0.1',
            'dimensions' => 'sometimes|array', // length, width, height
        ]);

        // Generate unique tracking number
        $trackingNumber = 'RW' . strtoupper(uniqid()) . rand(100, 999);
        
        // Generate shipping label URL (simulated)
        $labelUrl = url('/api/shipping/labels/' . $trackingNumber);
        
        // Calculate estimated delivery
        $baseDays = 3;
        $distanceFactor = rand(1, 3);
        $estimatedDays = $baseDays + $distanceFactor;
        
        // Calculate shipping cost
        $baseRate = 5.00;
        $weightRate = $request->weight_kg * 1.50;
        $distanceRate = $distanceFactor * 2.00;
        $shippingCost = $baseRate + $weightRate + $distanceRate;

        return response()->json([
            'tracking_number' => $trackingNumber,
            'label_url' => $labelUrl,
            'carrier' => 'EcoShip',
            'service_type' => 'Standard',
            'estimated_delivery_days' => $estimatedDays,
            'estimated_delivery_date' => now()->addDays($estimatedDays)->toDateString(),
            'shipping_cost' => round($shippingCost, 2),
            'weight' => $request->weight_kg . ' kg',
            'status' => 'label_generated',
            'carbon_neutral' => true,
            'environmental_offset' => round($request->weight_kg * 0.5, 2) . ' kg CO2',
        ]);
    }

    // UC-24: Get tracking status
    public function getTrackingStatus($trackingNumber)
    {
        $events = [
            ['status' => 'Label Created', 'location' => 'Origin Facility', 'timestamp' => now()->subDays(2)->toDateTimeString()],
            ['status' => 'Picked Up', 'location' => 'Local Depot', 'timestamp' => now()->subDays(1)->toDateTimeString()],
            ['status' => 'In Transit', 'location' => 'Regional Hub', 'timestamp' => now()->toDateTimeString()],
        ];
        
        return response()->json([
            'tracking_number' => $trackingNumber,
            'status' => 'in_transit',
            'current_location' => 'Regional Hub',
            'events' => $events,
            'estimated_delivery' => now()->addDays(1)->toDateString(),
        ]);
    }

    // UC-25: Reverse logistics - initiate return
    public function initiateReturn(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'reason' => 'required|string',
            'item_condition' => 'required|string',
        ]);

        $returnId = 'RET-' . strtoupper(uniqid());

        return response()->json([
            'return_id' => $returnId,
            'order_id' => $request->order_id,
            'reason' => $request->reason,
            'status' => 'return_initiated',
            'shipping_label' => url('/api/shipping/return-labels/' . $returnId),
            'instructions' => [
                '1. Pack item securely',
                '2. Attach return label',
                '3. Drop at nearest collection point',
                '4. Keep proof of postage',
            ],
            'timeline' => [
                'return_requested' => now()->toDateTimeString(),
                'label_sent' => now()->toDateTimeString(),
                'item_received' => null,
                'refund_processed' => null,
            ],
        ]);
    }

    // UC-25: Process return after receiving
    public function processReturn(Request $request, $returnId)
    {
        $request->validate([
            'item_condition' => 'required|string',
            'refund_eligible' => 'required|boolean',
        ]);

        $refundAmount = $request->refund_eligible 
            ? rand(20, 100) // Would calculate from original amount
            : 0;

        return response()->json([
            'return_id' => $returnId,
            'status' => $request->refund_eligible ? 'refund_approved' : 'refund_declined',
            'item_received' => true,
            'item_condition' => $request->item_condition,
            'refund_amount' => $refundAmount,
            'refund_method' => 'original_payment_method',
            'refund_timeline' => '3-5 business days',
        ]);
    }

    // UC-26: Bundle discount calculation
    public function calculateBundleDiscount(Request $request)
    {
        $request->validate([
            'item_prices' => 'required|array',
            'seller_id' => 'required|integer',
        ]);

        $itemCount = count($request->item_prices);
        $total = array_sum($request->item_prices);

        // Tiered discounts
        $discounts = [
            1 => 0,      // 0% for 1 item
            2 => 0.05,   // 5%
            3 => 0.10,   // 10%
            4 => 0.15,   // 15%
            5 => 0.20,    // 20%
        ];

        $discountRate = $discounts[$itemCount] ?? 0.20;
        $discountAmount = $total * $discountRate;
        $finalTotal = $total - $discountAmount;

        return response()->json([
            'item_count' => $itemCount,
            'original_total' => $total,
            'discount_rate' => $discountRate * 100 . '%',
            'discount_amount' => round($discountAmount, 2),
            'final_total' => round($finalTotal, 2),
            'savings' => round($discountAmount, 2),
            'message' => "You've saved $" . round($discountAmount, 2) . " with the $itemCount-item bundle discount!",
        ]);
    }
}