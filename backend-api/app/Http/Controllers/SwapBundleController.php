<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class SwapBundleController extends Controller
{
    // UC-15: Create multi-item swap proposal
    public function createProposal(Request $request)
    {
        $request->validate([
            'offer_items' => 'required|array|min:1',
            'request_items' => 'required|array|min:1',
            'recipient_id' => 'required|exists:users,id',
        ]);

        $offerValue = 0;
        $requestValue = 0;

        // Calculate offer value
        foreach ($request->offer_items as $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                $offerValue += $item->price ?? 0;
            }
        }

        // Calculate request value
        foreach ($request->request_items as $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                $requestValue += $item->price ?? 0;
            }
        }

        $proposalId = rand(100000, 999999);

        return response()->json([
            'proposal_id' => $proposalId,
            'offer_items' => $request->offer_items,
            'request_items' => $request->request_items,
            'offer_value' => $offerValue,
            'request_value' => $requestValue,
            'value_difference' => $requestValue - $offerValue,
            'status' => 'pending',
            'created_at' => now()->toDateTimeString(),
            'expires_at' => now()->addDays(3)->toDateTimeString(),
        ]);
    }

    // UC-16: Calculate cash top-up for value discrepancy
    public function calculateTopUp(Request $request)
    {
        $request->validate([
            'offer_value' => 'required|numeric|min:0',
            'request_value' => 'required|numeric|min:0',
        ]);

        $difference = $request->request_value - $request->offer_value;
        
        // Only suggest top-up if significant difference (> $10)
        $threshold = 10;
        
        if ($difference > $threshold) {
            // Suggest cash top-up
            return response()->json([
                'suggested_top_up' => round($difference, 2),
                'offer_value' => $request->offer_value,
                'request_value' => $request->request_value,
                'difference' => round($difference, 2),
                'message' => "Consider adding $" . round($difference, 2) . " to balance the swap",
                'is_fair_swap' => false,
            ]);
        } elseif ($difference < -$threshold) {
            return response()->json([
                'suggested_top_up' => 0,
                'offer_value' => $request->offer_value,
                'request_value' => $request->request_value,
                'difference' => round($difference, 2),
                'message' => "Your offer exceeds the request value by $" . abs(round($difference, 2)),
                'is_fair_swap' => true,
            ]);
        }

        return response()->json([
            'suggested_top_up' => 0,
            'offer_value' => $request->offer_value,
            'request_value' => $request->request_value,
            'difference' => round($difference, 2),
            'message' => 'Fair swap! Values are close.',
            'is_fair_swap' => true,
        ]);
    }

    // UC-17: Add/remove items from bundle iteratively
    public function updateBundle(Request $request)
    {
        $request->validate([
            'bundle_id' => 'required',
            'action' => 'required|in:add,remove',
            'item_id' => 'required|exists:items,id',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        return response()->json([
            'bundle_id' => $request->bundle_id,
            'action' => $request->action,
            'item' => $item,
            'item_value' => $item->price ?? 0,
            'message' => $request->action === 'add' ? 'Item added to bundle' : 'Item removed from bundle',
            'current_bundle_value' => rand(50, 200), // Would track actual value in production
        ]);
    }

    // UC-18: Set auto-accept/decline thresholds
    public function setBargainingThresholds(Request $request)
    {
        $request->validate([
            'auto_accept_threshold' => 'required|numeric|min:0',
            'auto_decline_threshold' => 'required|numeric|min:0',
        ]);

        if ($request->auto_accept_threshold < $request->auto_decline_threshold) {
            return response()->json([
                'error' => 'Auto-accept threshold must be higher than auto-decline threshold'
            ], 400);
        }

        return response()->json([
            'message' => 'Bargaining thresholds set successfully',
            'auto_accept' => $request->auto_accept_threshold,
            'auto_decline' => $request->auto_decline_threshold,
            'current_offer' => null,
            'auto_action' => null,
        ]);
    }

    // Check if offer meets thresholds
    public function checkOfferAgainstThresholds(Request $request)
    {
        $request->validate([
            'offer_amount' => 'required|numeric',
            'auto_accept' => 'required|numeric',
            'auto_decline' => 'required|numeric',
        ]);

        $offer = $request->offer_amount;
        $accept = $request->auto_accept;
        $decline = $request->auto_decline;

        $action = 'pending';
        if ($offer >= $accept) {
            $action = 'auto_accept';
        } elseif ($offer <= $decline) {
            $action = 'auto_decline';
        }

        return response()->json([
            'offer' => $offer,
            'action' => $action,
            'message' => match($action) {
                'auto_accept' => 'Offer automatically accepted!',
                'auto_decline' => 'Offer automatically declined',
                default => 'Offer pending review',
            }
        ]);
    }
}