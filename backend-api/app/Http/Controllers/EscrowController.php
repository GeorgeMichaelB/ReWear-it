<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EscrowController extends Controller
{
    // UC-22: Hold funds in virtual vault
    protected $vault = []; // In-memory simulation

    public function createEscrow(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'item_id' => 'required|exists:items,id',
            'buyer_id' => 'required|exists:users,id',
            'seller_id' => 'required|exists:users,id',
        ]);

        $escrowId = 'ESC-' . strtoupper(uniqid());
        
        $escrow = [
            'id' => $escrowId,
            'amount' => $request->amount,
            'currency' => 'USD',
            'item_id' => $request->item_id,
            'buyer_id' => $request->buyer_id,
            'seller_id' => $request->seller_id,
            'status' => 'held',
            'created_at' => now()->toDateTimeString(),
            'released_at' => null,
            'held_in' => 'Platform Escrow Vault',
        ];

        $this->vault[$escrowId] = $escrow;

        return response()->json([
            'message' => 'Funds held in escrow vault',
            'escrow' => $escrow,
            'vault_balance' => array_sum(array_column($this->vault, 'amount')),
        ], 201);
    }

    // UC-22: Verify and release funds
    public function verifyAndRelease(Request $request, $escrowId)
    {
        if (!isset($this->vault[$escrowId])) {
            return response()->json(['message' => 'Escrow not found'], 404);
        }

        $escrow = $this->vault[$escrowId];
        
        if ($escrow['status'] !== 'held') {
            return response()->json(['message' => 'Escrow already processed'], 400);
        }

        // Verify item received (simulation)
        $verified = $request->input('verified', true);
        
        if ($verified) {
            $this->vault[$escrowId]['status'] = 'released';
            $this->vault[$escrowId]['released_at'] = now()->toDateTimeString();
            
            // Calculate platform fee
            $fee = $this->calculatePlatformFee($escrow['amount'], null);
            $netAmount = $escrow['amount'] - $fee;

            return response()->json([
                'message' => 'Funds released to seller',
                'escrow' => $this->vault[$escrowId],
                'amount_released' => $netAmount,
                'platform_fee' => $fee,
                'transaction_complete' => true,
            ]);
        } else {
            $this->vault[$escrowId]['status'] = 'disputed';
            return response()->json([
                'message' => 'Item verification failed - funds held for dispute',
                'escrow' => $this->vault[$escrowId],
            ]);
        }
    }

    // UC-22: Release to seller after dispute resolution
    public function releaseAfterDispute(Request $request, $escrowId)
    {
        $request->validate([
            'resolution' => 'required|in:buyer_favor,seller_favor,split',
        ]);

        if (!isset($this->vault[$escrowId])) {
            return response()->json(['message' => 'Escrow not found'], 404);
        }

        $escrow = $this->vault[$escrowId];
        
        switch ($request->resolution) {
            case 'buyer_favor':
                // Refund buyer (no release)
                $this->vault[$escrowId]['status'] = 'refunded';
                $message = 'Full refund issued to buyer';
                break;
            case 'seller_favor':
                // Release to seller
                $this->vault[$escrowId]['status'] = 'released';
                $this->vault[$escrowId]['released_at'] = now()->toDateTimeString();
                $message = 'Funds released to seller after dispute resolution';
                break;
            case 'split':
                // 50/50 split
                $half = $escrow['amount'] / 2;
                $this->vault[$escrowId]['status'] = 'released';
                $this->vault[$escrowId]['released_at'] = now()->toDateTimeString();
                $this->vault[$escrowId]['buyer_refund'] = $half;
                $this->vault[$escrowId]['seller_payout'] = $half;
                $message = 'Funds split 50/50 between buyer and seller';
                break;
        }

        return response()->json([
            'message' => $message,
            'escrow' => $this->vault[$escrowId],
        ]);
    }

    // UC-27: Schedule payout to seller
    public function schedulePayout(Request $request)
    {
        $request->validate([
            'escrow_id' => 'required',
            'payout_schedule' => 'required|in:immediate,next_business_day,weekly',
        ]);

        $escrow = $this->vault[$request->escrow_id] ?? null;
        
        if (!$escrow || $escrow['status'] !== 'released') {
            return response()->json(['message' => 'No released escrow to payout'], 400);
        }

        $fee = $this->calculatePlatformFee($escrow['amount'], null);
        $netAmount = $escrow['amount'] - $fee;

        return response()->json([
            'escrow_id' => $request->escrow_id,
            'gross_amount' => $escrow['amount'],
            'platform_fee' => $fee,
            'net_payout' => $netAmount,
            'payout_schedule' => $request->payout_schedule,
            'scheduled_date' => match($request->payout_schedule) {
                'immediate' => now()->toDateTimeString(),
                'next_business_day' => now()->addDay()->toDateTimeString(),
                'weekly' => now()->addDays(7)->toDateTimeString(),
            },
            'status' => 'scheduled',
        ]);
    }

    // UC-23: Dynamic platform fees based on item type
    public function calculatePlatformFee($amount, $itemType = null)
    {
        // Base fee: 10%
        $baseFee = 0.10;
        
        // Discounted fee for upcycled items
        $upcycledDiscount = 0.04; // 4% instead of 10%
        
        // Simulated item type check (would check item.is_upcycled in production)
        $isUpcycled = $itemType === 'upcycled' || $itemType === '100_upcycled';
        
        $feeRate = $isUpcycled ? (1 - $upcycledDiscount) : $baseFee;
        
        return round($amount * $feeRate, 2);
    }

    // UC-28: Currency conversion
    public function convertCurrency(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from_currency' => 'required|string|size:3',
            'to_currency' => 'required|string|size:3',
        ]);

        $rates = [
            'USD' => 1.00,
            'EUR' => 0.92,
            'GBP' => 0.79,
            'EGP' => 30.90,
            'CAD' => 1.36,
            'AUD' => 1.53,
        ];

        $fromRate = $rates[$request->from_currency] ?? 1;
        $toRate = $rates[$request->to_currency] ?? 1;
        
        $converted = ($request->amount / $fromRate) * $toRate;

        return response()->json([
            'original_amount' => $request->amount,
            'original_currency' => $request->from_currency,
            'converted_amount' => round($converted, 2),
            'converted_currency' => $request->to_currency,
            'exchange_rate' => round($toRate / $fromRate, 4),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}