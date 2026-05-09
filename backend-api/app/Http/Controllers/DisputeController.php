<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DisputeController extends Controller
{
    // UC-29: Create dispute case
    public function createDispute(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'dispute_type' => 'required|in:not_received,not_as_described,damaged,other',
            'description' => 'required|string',
            'evidence_photos' => 'sometimes|array',
        ]);

        $disputeId = 'DSP-' . strtoupper(uniqid());

        $dispute = [
            'id' => $disputeId,
            'order_id' => $request->order_id,
            'type' => $request->dispute_type,
            'description' => $request->description,
            'status' => 'pending_review',
            'created_at' => now()->toDateTimeString(),
            'priority' => 'medium',
            'evidence' => $request->evidence_photos ?? [],
            'chat_logs_attached' => false,
            'admin_assigned' => null,
        ];

        return response()->json([
            'message' => 'Dispute case created',
            'dispute' => $dispute,
            'next_steps' => [
                'Admin will review within 48 hours',
                'Chat logs will be requested if needed',
                'Both parties will be notified of resolution',
            ],
        ], 201);
    }

    // UC-29: Get dispute details with chat logs and photos
    public function getDisputeDetails($disputeId)
    {
        // Simulated dispute data with chat logs
        return response()->json([
            'dispute' => [
                'id' => $disputeId,
                'order_id' => 'ORD-12345',
                'type' => 'not_as_described',
                'description' => 'Item arrived with different color than shown',
                'status' => 'under_review',
                'created_at' => now()->subDays(2)->toDateTimeString(),
                'buyer_evidence' => [
                    'photos' => ['dispute_photo_1.jpg', 'dispute_photo_2.jpg'],
                    'description' => 'The item is clearly navy blue, not black as listed',
                ],
                'seller_response' => [
                    'response' => 'Item was photographed in natural light',
                    'timestamp' => now()->subDay()->toDateTimeString(),
                ],
            ],
            'chat_logs' => [
                ['sender' => 'buyer', 'message' => 'Hi, the item arrived but color is wrong', 'timestamp' => now()->subDays(3)->toDateTimeString()],
                ['sender' => 'seller', 'message' => 'I\'m sorry to hear that. Can you send a photo?', 'timestamp' => now()->subDays(3)->toDateTimeString()],
                ['sender' => 'buyer', 'message' => 'Here is the photo. It\'s clearly navy, not black.', 'timestamp' => now()->subDays(2)->toDateTimeString()],
                ['sender' => 'seller', 'message' => 'I understand. Let me check with shipping.', 'timestamp' => now()->subDay()->toDateTimeString()],
            ],
            'admin_actions' => [
                'requested_chat_logs' => true,
                'requested_photos' => true,
                'contacted_seller' => true,
            ],
            'resolution_options' => [
                'full_refund',
                'partial_refund',
                'return_for_refund',
                'keep_item_partial_refund',
            ],
        ]);
    }

    // UC-29: Admin resolve dispute
    public function resolveDispute(Request $request, $disputeId)
    {
        $request->validate([
            'resolution' => 'required|in:buyer_wins,seller_wins,partial_refund,return_required',
            'refund_amount' => 'sometimes|numeric',
            'reason' => 'required|string',
        ]);

        $resolution = match($request->resolution) {
            'buyer_wins' => 'Full refund to buyer - seller responsible for return shipping',
            'seller_wins' => 'No refund - item to be returned to buyer',
            'partial_refund' => 'Partial refund of $' . ($request->refund_amount ?? 0) . ' to buyer',
            'return_required' => 'Buyer returns item, seller issues full refund upon receipt',
        };

        return response()->json([
            'dispute_id' => $disputeId,
            'status' => 'resolved',
            'resolution' => $resolution,
            'admin_decision' => $request->reason,
            'resolved_by' => 'Admin User',
            'resolved_at' => now()->toDateTimeString(),
            'funds_action' => match($request->resolution) {
                'buyer_wins' => 'Refunded from escrow to buyer',
                'partial_refund' => 'Partial refund to buyer, remainder to seller',
                default => 'Held pending return',
            },
        ]);
    }

    // UC-29: Get all disputes (admin view)
    public function getAllDisputes(Request $request)
    {
        $status = $request->status ?? 'all';
        
        // Simulated disputes
        $disputes = [
            ['id' => 'DSP-001', 'order_id' => 'ORD-12345', 'type' => 'not_as_described', 'status' => 'pending_review', 'created_at' => now()->subDays(2)->toDateTimeString()],
            ['id' => 'DSP-002', 'order_id' => 'ORD-12346', 'type' => 'not_received', 'status' => 'under_review', 'created_at' => now()->subDays(5)->toDateTimeString()],
            ['id' => 'DSP-003', 'order_id' => 'ORD-12347', 'type' => 'damaged', 'status' => 'resolved', 'created_at' => now()->subDays(10)->toDateTimeString()],
        ];

        return response()->json([
            'disputes' => $disputes,
            'total' => count($disputes),
            'by_status' => [
                'pending_review' => 1,
                'under_review' => 1,
                'resolved' => 1,
            ],
        ]);
    }

    // UC-29: Attach chat logs to dispute
    public function attachChatLogs(Request $request, $disputeId)
    {
        $request->validate([
            'chat_logs' => 'required|array',
        ]);

        return response()->json([
            'dispute_id' => $disputeId,
            'chat_logs_attached' => true,
            'message' => 'Chat logs successfully attached to dispute case',
        ]);
    }
}