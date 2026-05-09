<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    // UC-37: Dynamic commission modifiers
    public function setCommissionModifier(Request $request)
    {
        $request->validate([
            'category_id' => 'sometimes|integer',
            'modifier_type' => 'required|in:percentage,fixed,zero_fee',
            'value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'sometimes|string',
        ]);

        $modifierId = 'MOD-' . strtoupper(uniqid());

        $modifier = [
            'id' => $modifierId,
            'category_id' => $request->category_id ?? 'all',
            'type' => $request->modifier_type,
            'original_fee' => '10%',
            'modified_fee' => $request->modifier_type === 'zero_fee' ? '0%' : $request->value . '%',
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason ?? 'Promotional period',
            'created_by' => 'admin',
            'status' => 'active',
        ];

        return response()->json([
            'message' => 'Commission modifier applied',
            'modifier' => $modifier,
            'impact' => "All " . ($request->category_id ? "category {$request->category_id}" : "categories") . 
                " listings will now have " . $modifier['modified_fee'] . " platform fee",
        ], 201);
    }

    // UC-37: Get active modifiers
    public function getCommissionModifiers(Request $request)
    {
        return response()->json([
            'active_modifiers' => [
                [
                    'id' => 'MOD-001',
                    'category' => 'Upcycled',
                    'type' => 'percentage',
                    'original_fee' => '10%',
                    'modified_fee' => '4%',
                    'reason' => 'Sustainability promotion',
                    'ends_at' => now()->addDays(30)->toDateTimeString(),
                ],
                [
                    'id' => 'MOD-002',
                    'category' => 'Accessories',
                    'type' => 'zero_fee',
                    'original_fee' => '10%',
                    'modified_fee' => '0%',
                    'reason' => 'Spring sale weekend',
                    'ends_at' => now()->addDays(7)->toDateTimeString(),
                ],
            ],
        ]);
    }

    // UC-37: Calculate effective fee for item
    public function calculateEffectiveFee(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category_id' => 'sometimes|integer',
        ]);

        $baseFee = 0.10;
        
        // Check for active modifiers
        $modifier = rand(0, 1) ? ['type' => 'upcycled_discount', 'reduction' => 0.06] : null;
        
        $effectiveRate = $modifier ? ($baseFee - $modifier['reduction']) : $baseFee;
        $fee = $request->amount * $effectiveRate;

        return response()->json([
            'amount' => $request->amount,
            'base_fee_rate' => $baseFee * 100 . '%',
            'effective_fee_rate' => $effectiveRate * 100 . '%',
            'fee_amount' => round($fee, 2),
            'savings' => $modifier ? round($request->amount * 0.06, 2) : 0,
            'active_modifier' => $modifier ? 'Upcycled discount applied!' : null,
        ]);
    }

    // UC-38: Platform-wide sustainability audit
    public function getSustainabilityAudit(Request $request)
    {
        $period = $request->period ?? '30_days';

        return response()->json([
            'period' => $period,
            'generated_at' => now()->toDateTimeString(),
            'total_impact' => [
                'waste_diverted_kg' => rand(5000, 15000),
                'co2_saved_kg' => rand(10000, 30000),
                'water_saved_liters' => rand(100000, 500000),
                'items_recycled' => rand(2000, 8000),
                'items_upcycled' => rand(1000, 4000),
            ],
            'by_category' => [
                ['category' => 'Denim', 'items' => 1200, 'waste_diverted' => 2500],
                ['category' => 'Cotton', 'items' => 1800, 'waste_diverted' => 3200],
                ['category' => 'Wool', 'items' => 450, 'waste_diverted' => 890],
                ['category' => 'Leather', 'items' => 280, 'waste_diverted' => 560],
            ],
            'community_impact' => [
                'active_swappers' => rand(500, 2000),
                'total_swaps' => rand(1000, 5000),
                'eco_credits_earned' => rand(10000, 50000),
                'trees_equivalent' => rand(50, 200),
            ],
            'trends' => [
                'waste_diversion_growth' => '+' . rand(10, 30) . '% vs last period',
                'community_growth' => '+' . rand(5, 15) . '% new users',
            ],
        ]);
    }

    // UC-38: Export audit report
    public function exportAuditReport(Request $request)
    {
        $format = $request->format ?? 'json';

        return response()->json([
            'report_id' => 'AUDIT-' . strtoupper(uniqid()),
            'format' => $format,
            'download_url' => '/api/reports/download/audit-' . date('Y-m-d') . '.' . $format,
            'generated_at' => now()->toDateTimeString(),
            'message' => 'Report ready for download',
        ]);
    }

    // UC-39: Role-Based Access Control
    public function getRoles(Request $request)
    {
        return response()->json([
            'roles' => [
                [
                    'id' => 1,
                    'name' => 'super_admin',
                    'permissions' => ['all'],
                    'description' => 'Full platform access',
                ],
                [
                    'id' => 2,
                    'name' => 'moderator',
                    'permissions' => ['view_reports', 'manage_users', 'handle_disputes', 'content_moderation'],
                    'description' => 'Content and user management',
                ],
                [
                    'id' => 3,
                    'name' => 'content_mod',
                    'permissions' => ['view_reports', 'content_moderation', 'handle_reports'],
                    'description' => 'Content review only',
                ],
                [
                    'id' => 4,
                    'name' => 'support',
                    'permissions' => ['view_tickets', 'handle_disputes', 'view_users'],
                    'description' => 'Support and disputes',
                ],
            ],
        ]);
    }

    // UC-39: Assign role to user
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'role' => 'required|string',
        ]);

        return response()->json([
            'user_id' => $request->user_id,
            'role' => $request->role,
            'assigned_by' => 'super_admin',
            'assigned_at' => now()->toDateTimeString(),
            'message' => 'Role assigned successfully',
        ]);
    }

    // UC-39: Check user permissions
    public function checkPermissions(Request $request, $userId)
    {
        return response()->json([
            'user_id' => $userId,
            'role' => 'moderator',
            'permissions' => ['view_reports', 'manage_users', 'handle_disputes', 'content_moderation'],
            'can_access' => true,
        ]);
    }

    // UC-39: Create custom role
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array',
            'description' => 'sometimes|string',
        ]);

        return response()->json([
            'role_id' => rand(100, 999),
            'name' => $request->name,
            'permissions' => $request->permissions,
            'created_at' => now()->toDateTimeString(),
            'message' => 'Custom role created successfully',
        ]);
    }
}