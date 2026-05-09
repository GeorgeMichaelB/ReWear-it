<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;

class GeospatialController extends Controller
{
    // UC-21: Find nearby users for in-person swaps
    public function findNearbyUsers(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_km' => 'sometimes|numeric|min:1|max:50',
        ]);

        $radius = $request->radius_km ?? 10; // default 10km
        
        // Simulate user locations (in production, would use actual coordinates)
        $allUsers = User::where('id', '!=', $request->user()->id)->get();
        
        $nearbyUsers = [];
        
        foreach ($allUsers as $user) {
            // Simulate distance calculation
            $userLat = $request->latitude + (rand(-100, 100) / 1000);
            $userLng = $request->longitude + (rand(-100, 100) / 1000);
            
            $distance = $this->calculateDistance(
                $request->latitude, $request->longitude,
                $userLat, $userLng
            );
            
            if ($distance <= $radius) {
                $nearbyUsers[] = [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'trust_score' => $user->trust_score,
                    ],
                    'distance_km' => round($distance, 2),
                    'available_items' => Item::where('seller_id', $user->id)
                        ->where('status', 'available')->count(),
                ];
            }
        }
        
        // Sort by distance
        usort($nearbyUsers, function($a, $b) {
            return $a['distance_km'] <=> $b['distance_km'];
        });
        
        // Calculate emissions saved vs shipping
        $shippingEmissionsPerKm = 0.21; // kg CO2 per km
        $totalSaved = array_sum(array_map(function($u) use ($shippingEmissionsPerKm) {
            return $u['distance_km'] * $shippingEmissionsPerKm;
        }, $nearbyUsers));
        
        return response()->json([
            'nearby_users' => $nearbyUsers,
            'total_nearby' => count($nearbyUsers),
            'search_radius_km' => $radius,
            'emissions_saved_kg' => round($totalSaved * 2, 2), // round trip
            'carbon_impact' => $totalSaved > 0 ? 'Positive - In-person swaps save shipping emissions!' : 'N/A',
        ]);
    }

    // UC-21: Find nearby items for in-person swap
    public function findNearbyItems(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius_km' => 'sometimes|numeric',
        ]);

        // Get all available items and calculate distances
        $items = Item::where('status', 'available')
            ->where('seller_id', '!=', $request->user()->id)
            ->with('category')
            ->get();
        
        $nearbyItems = [];
        
        foreach ($items as $item) {
            // Simulate seller location
            $sellerLat = $request->latitude + (rand(-100, 100) / 1000);
            $sellerLng = $request->longitude + (rand(-100, 100) / 1000);
            
            $distance = $this->calculateDistance(
                $request->latitude, $request->longitude,
                $sellerLat, $sellerLng
            );
            
            if ($distance <= ($request->radius_km ?? 10)) {
                $nearbyItems[] = [
                    'item' => $item,
                    'distance_km' => round($distance, 2),
                ];
            }
        }
        
        usort($nearbyItems, fn($a, $b) => $a['distance_km'] <=> $b['distance_km']);
        
        return response()->json([
            'items' => $nearbyItems,
            'count' => count($nearbyItems),
        ]);
    }

    // Haversine formula for distance calculation
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    // UC-21: Get user's location settings
    public function getLocationSettings(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'location_enabled' => $user->latitude !== null,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
            'city' => $user->city ?? 'Not set',
        ]);
    }

    // UC-21: Set location
    public function setLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'city' => 'sometimes|string',
        ]);

        $user = $request->user();
        $user->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
        ]);

        return response()->json(['message' => 'Location updated successfully']);
    }
}