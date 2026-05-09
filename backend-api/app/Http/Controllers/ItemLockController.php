<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ItemLockController extends Controller
{
    // UC-11: Lock item when transaction begins
    public function lockItem(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        
        // Check if item is already locked
        if ($item->status === 'locked') {
            return response()->json([
                'message' => 'Item is currently locked by another transaction',
                'locked_by' => $item->locked_by_transaction_id
            ], 409);
        }

        $transactionType = $request->input('type', 'swap'); // 'swap' or 'sale'
        
        // Create pending transaction
        $transaction = Transaction::create([
            'buyer_id' => $request->user()->id,
            'seller_id' => $item->seller_id,
            'item_id' => $item->id,
            'type' => $transactionType,
            'status' => 'pending',
            'expires_at' => now()->addHours(24), // 24 hour lock
        ]);

        // Lock the item
        $item->update([
            'status' => 'locked',
            'locked_by_transaction_id' => $transaction->id,
        ]);

        return response()->json([
            'message' => 'Item locked successfully',
            'transaction' => $transaction,
            'expires_at' => $transaction->expires_at
        ]);
    }

    // UC-11: Unlock item (transaction cancelled or completed)
    public function unlockItem($id)
    {
        $item = Item::findOrFail($id);
        
        if ($item->status !== 'locked') {
            return response()->json(['message' => 'Item is not locked'], 400);
        }

        // Update transaction status
        Transaction::where('id', $item->locked_by_transaction_id)
            ->update(['status' => 'cancelled']);

        // Unlock item
        $item->update([
            'status' => 'available',
            'locked_by_transaction_id' => null,
        ]);

        return response()->json(['message' => 'Item unlocked successfully']);
    }

    // UC-20: Auto-cancel pending offers after inactivity
    public function autoCancelExpiredTransactions()
    {
        $expired = Transaction::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $transaction) {
            // Unlock associated item
            if ($transaction->item_id) {
                Item::where('id', $transaction->item_id)->update([
                    'status' => 'available',
                    'locked_by_transaction_id' => null,
                ]);
            }
            
            // Mark transaction as expired
            $transaction->update(['status' => 'expired']);
        }

        return response()->json([
            'message' => 'Expired transactions cleaned up',
            'count' => $expired->count()
        ]);
    }

    // UC-12: Check item for prohibited content
    public function validateItem(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $prohibitedKeywords = [
            'fake', 'replica', 'knockoff', 'replicaa', 'counterfeit',
            'authentic fake', 'designer inspired', 'unbranded logo'
        ];

        $content = strtolower($request->title . ' ' . ($request->description ?? ''));
        
        $violations = [];
        foreach ($prohibitedKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                $violations[] = "Prohibited keyword detected: $keyword";
            }
        }

        // Check misleading upcycle claims
        $upcycleIndicators = ['handmade', 'handcrafted', 'unique', 'one of a kind'];
        $hasUpcycleClaim = false;
        foreach ($upcycleIndicators as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $hasUpcycleClaim = true;
            }
        }

        return response()->json([
            'is_valid' => count($violations) === 0,
            'violations' => $violations,
            'warnings' => $hasUpcycleClaim ? ['Verify upcycle claims are accurate'] : [],
            'recommendation' => count($violations) > 0 ? 'REJECT' : ($hasUpcycleClaim ? 'REVIEW' : 'APPROVE')
        ]);
    }

    // UC-19: Lock item after swap agreement signed
    public function lockItemForAgreement($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->item) {
            $transaction->item->update([
                'status' => 'agreement_locked',
                'locked_by_transaction_id' => $transactionId,
            ]);
        }

        return response()->json(['message' => 'Item locked for swap agreement']);
    }
}