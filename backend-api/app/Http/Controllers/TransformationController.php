<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class TransformationController extends Controller
{
    // UC-8: Get transformation details for an item
    public function getTransformation($id)
    {
        $item = Item::findOrFail($id);

        // Return transformation data (simulated if not set)
        return response()->json([
            'item_id' => $item->id,
            'has_transformation' => $item->transformation_log_id != null || $item->before_state != null,
            'before_state' => $item->before_state ?? [
                'condition' => 'worn',
                'description' => 'Old worn item',
                'image' => null
            ],
            'after_state' => [
                'condition' => $item->condition,
                'description' => $item->description,
                'image' => null
            ],
            'modifications' => $item->modifications ?? [
                'hem_shortened',
                'dye_applied',
                'patches_added',
                'embroidery_added',
                'buttons_replaced'
            ]
        ]);
    }

    // Create/update transformation details
    public function saveTransformation(Request $request, $id)
    {
        $request->validate([
            'before_condition' => 'required|string',
            'before_description' => 'nullable|string',
            'modifications' => 'required|array',
        ]);

        $item = Item::findOrFail($id);

        if ($item->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->update([
            'before_state' => json_encode([
                'condition' => $request->before_condition,
                'description' => $request->before_description,
            ]),
            'modifications' => json_encode($request->modifications),
        ]);

        return response()->json([
            'message' => 'Transformation details saved',
            'item' => $item
        ]);
    }

    // UC-2: Calculate CO2 and water savings
    public function calculateImpact(Request $request)
    {
        $request->validate([
            'material_type' => 'required|string',
            'weight_kg' => 'required|numeric|min:0.1',
            'original_condition' => 'required|string',
        ]);

        $materialFactors = [
            'cotton' => ['co2' => 10.0, 'water' => 10000],
            'polyester' => ['co2' => 15.0, 'water' => 2000],
            'wool' => ['co2' => 20.0, 'water' => 15000],
            'linen' => ['co2' => 5.0, 'water' => 8000],
            'silk' => ['co2' => 12.0, 'water' => 12000],
            'denim' => ['co2' => 18.0, 'water' => 11000],
        ];

        $factor = $materialFactors[$request->material_type] ?? $materialFactors['cotton'];
        
        // Calculate savings (vs buying new)
        $co2Saved = $factor['co2'] * $request->weight_kg * 0.7;
        $waterSaved = $factor['water'] * $request->weight_kg * 0.5;

        // Impact based on original condition
        $conditionMultiplier = $request->original_condition === 'worn' ? 1.5 : 
                              ($request->original_condition === 'fair' ? 1.2 : 1.0);

        return response()->json([
            'material' => $request->material_type,
            'weight_kg' => $request->weight_kg,
            'impact' => [
                'co2_saved_kg' => round($co2Saved * $conditionMultiplier, 2),
                'water_saved_liters' => round($waterSaved * $conditionMultiplier, 0),
                'carbon_footprint_reduction_percent' => round(($co2Saved / ($factor['co2'] * $request->weight_kg)) * 100, 1),
            ],
            'equivalents' => [
                'trees_needed_for_same_absorption' => round($co2Saved / 21, 2),
                'car_miles_not_driven' => round($co2Saved / 0.4, 1),
                'smartphone_charges_saved' => round($co2Saved * 10, 0),
            ]
        ]);
    }
}