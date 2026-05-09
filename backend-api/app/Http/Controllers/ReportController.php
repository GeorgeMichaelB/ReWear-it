<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    // UC-34: Multi-stage reporting with shadow-bans
    protected $reports = [];

    public function createReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:counterfeit,harassment,prohibited,scam,other',
            'target_type' => 'required|in:item,user,comment,message',
            'target_id' => 'required|integer',
            'evidence' => 'sometimes|array',
            'description' => 'required|string',
        ]);

        $reportId = 'RPT-' . strtoupper(uniqid());
        
        $report = [
            'id' => $reportId,
            'type' => $request->report_type,
            'target_type' => $request->target_type,
            'target_id' => $request->target_id,
            'description' => $request->description,
            'evidence' => $request->evidence ?? [],
            'status' => 'submitted',
            'severity' => $this->calculateSeverity($request->report_type),
            'created_at' => now()->toDateTimeString(),
            'stage' => 1,
        ];

        $this->reports[$reportId] = $report;

        return response()->json([
            'message' => 'Report submitted successfully',
            'report' => $report,
            'next_steps' => 'Report will be reviewed within 24-48 hours',
        ], 201);
    }

    // UC-34: Calculate report severity
    private function calculateSeverity($type)
    {
        return match($type) {
            'counterfeit' => 'critical',
            'harassment' => 'high',
            'scam' => 'high',
            'prohibited' => 'medium',
            'other' => 'low',
        };
    }

    // UC-34: Auto-escalate based on severity
    public function escalateReport(Request $request, $reportId)
    {
        $report = $this->reports[$reportId] ?? null;
        
        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        $report['stage'] = $report['stage'] + 1;
        $report['status'] = match($report['stage']) {
            2 => 'under_review',
            3 => 'investigating',
            4 => 'action_taken',
            default => 'completed',
        };

        return response()->json([
            'report' => $report,
            'stage' => $report['stage'],
            'actions_taken' => match($report['stage']) {
                2 => ['Evidence reviewed', 'Reporter notified'],
                3 => ['Content flagged', 'User notified', 'Investigation started'],
                4 => ['Content removed', 'User warned', 'Shadow-ban applied if severe'],
            },
        ]);
    }

    // UC-34: Apply shadow-ban
    public function applyShadowBan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'reason' => 'required|string',
            'duration' => 'sometimes|integer',
        ]);

        $shadowBan = [
            'user_id' => $request->user_id,
            'type' => 'shadow_ban',
            'reason' => $request->reason,
            'applied_at' => now()->toDateTimeString(),
            'expires_at' => $request->duration 
                ? now()->addDays($request->duration)->toDateTimeString() 
                : null,
            'visible_only_to_user' => true,
            'effects' => [
                'listings_hidden_from_search',
                'messages_not_delivered',
                'comments_hidden_from_others',
                'profile_not_searchable',
            ],
        ];

        return response()->json([
            'message' => 'Shadow ban applied successfully',
            'shadow_ban' => $shadowBan,
            'admin_note' => 'User will not know they are banned - content appears normal to them',
        ]);
    }

    // UC-34: Remove shadow-ban
    public function removeShadowBan(Request $request, $userId)
    {
        return response()->json([
            'user_id' => $userId,
            'shadow_ban_removed' => true,
            'message' => 'Shadow ban removed - user privileges restored',
        ]);
    }

    // UC-34: Get all reports (admin)
    public function getAllReports(Request $request)
    {
        $status = $request->status ?? 'all';
        
        return response()->json([
            'reports' => [
                ['id' => 'RPT-001', 'type' => 'counterfeit', 'severity' => 'critical', 'status' => 'investigating', 'created_at' => now()->subDays(2)->toDateTimeString()],
                ['id' => 'RPT-002', 'type' => 'harassment', 'severity' => 'high', 'status' => 'under_review', 'created_at' => now()->subDays(1)->toDateTimeString()],
                ['id' => 'RPT-003', 'type' => 'prohibited', 'severity' => 'medium', 'status' => 'resolved', 'created_at' => now()->subDays(5)->toDateTimeString()],
            ],
            'total' => 3,
            'by_status' => ['investigating' => 1, 'under_review' => 1, 'resolved' => 1],
        ]);
    }

    // UC-34: Get report details
    public function getReportDetails($reportId)
    {
        return response()->json([
            'report' => [
                'id' => $reportId,
                'type' => 'counterfeit',
                'target_type' => 'item',
                'target_id' => 123,
                'description' => 'Item appears to be counterfeit designer goods',
                'evidence' => ['photo1.jpg', 'photo2.jpg'],
                'status' => 'investigating',
                'stage' => 2,
                'reporter_id' => 45,
                'created_at' => now()->subDays(2)->toDateTimeString(),
            ],
            'timeline' => [
                ['stage' => 1, 'status' => 'submitted', 'timestamp' => now()->subDays(2)->toDateTimeString()],
                ['stage' => 2, 'status' => 'under_review', 'timestamp' => now()->subDays(1)->toDateTimeString()],
            ],
        ]);
    }
}