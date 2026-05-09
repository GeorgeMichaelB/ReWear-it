<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CareInstructionController extends Controller
{
    // UC-14: Generate care instructions based on material composition
    public function generateCareInstructions(Request $request)
    {
        $request->validate([
            'materials' => 'required|array',
            'condition' => 'sometimes|string',
        ]);

        $materials = $request->materials;
        $condition = $request->condition ?? 'good';

        $materialCare = [
            'cotton' => [
                'washing' => 'Machine wash cold with like colors',
                'drying' => 'Tumble dry low or hang dry',
                'ironing' => 'Iron on medium heat if needed',
                'bleaching' => 'Do not bleach',
                'special' => 'May shrink slightly. Wash before wearing.',
            ],
            'polyester' => [
                'washing' => 'Machine wash warm',
                'drying' => 'Tumble dry low',
                'ironing' => 'Iron low heat if needed',
                'bleaching' => 'Do not bleach',
                'special' => 'Wrinkle-resistant, quick drying.',
            ],
            'wool' => [
                'washing' => 'Hand wash cold or dry clean',
                'drying' => 'Lay flat to dry',
                'ironing' => 'Iron on wool setting with cloth',
                'bleaching' => 'Never bleach',
                'special' => 'Resistant to odors. Air regularly.',
            ],
            'linen' => [
                'washing' => 'Machine wash gentle',
                'drying' => 'Hang dry, iron while damp',
                'ironing' => 'Iron while damp for best results',
                'bleaching' => 'Do not bleach',
                'special' => 'Gets softer with washing. Natural wrinkles are characteristic.',
            ],
            'silk' => [
                'washing' => 'Hand wash cold or dry clean only',
                'drying' => 'Lay flat to dry, away from direct sunlight',
                'ironing' => 'Iron on low heat, inside out',
                'bleaching' => 'Never bleach',
                'special' => 'Delicate fabric. Store flat.',
            ],
            'denim' => [
                'washing' => 'Machine wash cold inside out',
                'drying' => 'Hang or tumble dry low',
                'ironing' => 'Iron as needed',
                'bleaching' => 'Do not bleach',
                'special' => 'May fade naturally. Wash less for longevity.',
            ],
            'leather' => [
                'washing' => 'Wipe clean with damp cloth',
                'drying' => 'Air dry away from heat',
                'ironing' => 'Do not iron',
                'bleaching' => 'N/A',
                'special' => 'Condition regularly. Store in breathable bag.',
            ],
        ];

        // Aggregate care instructions
        $instructions = [
            'general' => [],
            'washing' => [],
            'drying' => [],
            'ironing' => [],
            'bleaching' => [],
            'special' => [],
        ];

        foreach ($materials as $material) {
            $materialKey = strtolower(trim($material));
            if (isset($materialCare[$materialKey])) {
                $care = $materialCare[$materialKey];
                $instructions['washing'][] = $care['washing'];
                $instructions['drying'][] = $care['drying'];
                $instructions['ironing'][] = $care['ironing'];
                $instructions['bleaching'][] = $care['bleaching'];
                $instructions['special'][] = $care['special'];
            }
        }

        // Deduplicate and simplify
        $instructions['washing'] = array_unique($instructions['washing']);
        $instructions['drying'] = array_unique($instructions['drying']);
        $instructions['ironing'] = array_unique($instructions['ironing']);
        $instructions['bleaching'] = array_unique($instructions['bleaching']);
        $instructions['special'] = array_unique($instructions['special']);

        // Material-specific warnings
        $warnings = [];
        if (in_array('silk', array_map('strtolower', $materials))) {
            $warnings[] = 'Contains silk - avoid contact with perfumes/oils';
        }
        if (in_array('wool', array_map('strtolower', $materials))) {
            $warnings[] = 'Contains wool - store with cedar to prevent moths';
        }
        if ($condition === 'worn' || $condition === 'fair') {
            $warnings[] = 'Item is in fair condition - handle with extra care';
        }

        return response()->json([
            'materials' => $materials,
            'condition' => $condition,
            'care_instructions' => [
                'washing' => implode(' / ', $instructions['washing']),
                'drying' => implode(' / ', $instructions['drying']),
                'ironing' => implode(' / ', $instructions['ironing']),
                'bleaching' => implode(' / ', $instructions['bleaching']),
                'special' => implode(' / ', $instructions['special']),
            ],
            'warnings' => $warnings,
            'eco_tip' => 'Wash in cold water to reduce energy consumption by up to 90%!',
            'generated_at' => now()->toDateTimeString(),
        ]);
    }
}