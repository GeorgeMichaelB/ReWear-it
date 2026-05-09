<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SellerBadgeController extends Controller
{
    // UC-1: Get/Update seller badges
    public function getBadges(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'is_pro_upcycler' => $user->is_pro_upcycler ?? false,
            'eco_verified' => $user->eco_verified ?? false,
            'badges' => $user->pro_upcycler_badges ?? [],
            'available_badges' => [
                'pro_upcycler', 'eco_verified', 'speedster', 'top_seller', 
                'carbon_saver', 'zero_waste', 'creative_transformer'
            ]
        ]);
    }

    public function updateBadges(Request $request)
    {
        $request->validate([
            'badges' => 'required|array',
        ]);

        $user = $request->user();
        $user->update([
            'pro_upcycler_badges' => $request->badges,
            'is_pro_upcycler' => in_array('pro_upcycler', $request->badges),
        ]);

        return response()->json([
            'message' => 'Badges updated successfully',
            'badges' => $user->pro_upcycler_badges
        ]);
    }

    public function verifyEco(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        // Simulate verification
        $user = $request->user();
        $user->update(['eco_verified' => true]);

        return response()->json([
            'message' => 'Eco-verification successful!',
            'eco_verified' => true
        ]);
    }

    // UC-6: Calculate trust score
    public function getTrustScore(Request $request)
    {
        $user = $request->user();
        
        // Simulated trust score calculation
        $baseScore = $user->trust_score ?? 50;
        
        // Factors
        $descriptionAccuracy = rand(70, 100);
        $shippingSpeed = rand(60, 100);
        $swapCompletion = rand(75, 100);
        
        // Calculate new score
        $newScore = ($descriptionAccuracy * 0.3) + ($shippingSpeed * 0.3) + ($swapCompletion * 0.4);
        $newScore = ($baseScore * 0.5) + ($newScore * 0.5);

        return response()->json([
            'current_score' => round($newScore, 1),
            'factors' => [
                'description_accuracy' => $descriptionAccuracy,
                'shipping_speed' => $shippingSpeed,
                'swap_completion_rate' => $swapCompletion
            ],
            'level' => $newScore >= 80 ? 'Excellent' : ($newScore >= 60 ? 'Good' : 'Fair'),
            'breakdown' => [
                'transaction_history' => round($newScore * 0.25, 1),
                'rating' => round($newScore * 0.25, 1),
                'verification' => round($newScore * 0.25, 1),
                'activity' => round($newScore * 0.25, 1),
            ]
        ]);
    }

    public function updateTrustScore(Request $request)
    {
        $request->validate([
            'description_accuracy' => 'required|integer|min:0|max:100',
            'shipping_speed' => 'required|integer|min:0|max:100',
            'swap_completion_rate' => 'required|integer|min:0|max:100',
        ]);

        $score = ($request->description_accuracy * 0.3) + 
                 ($request->shipping_speed * 0.3) + 
                 ($request->swap_completion_rate * 0.4);

        $user = $request->user();
        $user->update(['trust_score' => round($score, 1)]);

        return response()->json([
            'message' => 'Trust score updated',
            'new_score' => $user->trust_score
        ]);
    }
}