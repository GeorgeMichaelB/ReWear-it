<?php

namespace App\Http\Controllers;

use App\Models\SaleOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SaleOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = SaleOrder::with(['transaction', 'item', 'escrowService'])
            ->whereHas('transaction', function ($query) use ($request) {
                $query->where('buyer_id', $request->user()->id)
                    ->orWhere('seller_id', $request->user()->id);
            })
            ->latest()
            ->paginate(20);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'item_id' => 'required|exists:items,id',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        
        if ($transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = SaleOrder::create([
            'transaction_id' => $request->transaction_id,
            'item_id' => $request->item_id,
            'total_amount' => $request->total_amount,
            'platform_fee' => $request->total_amount * 0.10,
        ]);

        return response()->json($order->load('transaction', 'item', 'escrowService'), 201);
    }

    public function show(SaleOrder $saleOrder, Request $request)
    {
        if ($saleOrder->transaction->buyer_id !== $request->user()->id && 
            $saleOrder->transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($saleOrder->load('transaction', 'item', 'escrowService'));
    }

    public function updateTracking(Request $request, SaleOrder $saleOrder)
    {
        $request->validate(['tracking_number' => 'required|string']);
        
        if ($saleOrder->transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $saleOrder->update(['tracking_number' => $request->tracking_number]);
        return response()->json($saleOrder);
    }

    public function calculateFee(SaleOrder $saleOrder)
    {
        return response()->json(['dynamic_fee' => $saleOrder->calculateDynamicFee()]);
    }

    public function bundleDiscount(SaleOrder $saleOrder)
    {
        return response()->json(['discounted_price' => $saleOrder->applyBundleDiscount()]);
    }
}