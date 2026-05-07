<?php

namespace App\Http\Controllers;

use App\Models\EscrowService;
use App\Models\SaleOrder;
use Illuminate\Http\Request;

class EscrowServiceController extends Controller
{
    public function index(Request $request)
    {
        $escrows = EscrowService::with('saleOrder')
            ->whereHas('saleOrder.transaction', function ($query) use ($request) {
                $query->where('buyer_id', $request->user()->id)
                    ->orWhere('seller_id', $request->user()->id);
            })
            ->latest()
            ->paginate(20);
        return response()->json($escrows);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_order_id' => 'required|exists:sale_orders,id',
            'held_amount' => 'required|numeric|min:0',
        ]);

        $saleOrder = SaleOrder::findOrFail($request->sale_order_id);
        
        if ($saleOrder->transaction->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Only buyer can create escrow'], 403);
        }

        $escrow = EscrowService::create([
            'sale_order_id' => $request->sale_order_id,
            'held_amount' => $request->held_amount,
            'status' => 'pending',
        ]);

        return response()->json($escrow->load('saleOrder'), 201);
    }

    public function show(EscrowService $escrowService, Request $request)
    {
        if ($escrowService->saleOrder->transaction->buyer_id !== $request->user()->id && 
            $escrowService->saleOrder->transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($escrowService->load('saleOrder'));
    }

    public function lockFunds(Request $request, EscrowService $escrowService)
    {
        $request->validate(['amount' => 'required|numeric|min:0']);
        
        $escrowService->lockFunds($request->amount);
        return response()->json($escrowService);
    }

    public function release(Request $request, EscrowService $escrowService)
    {
        if ($escrowService->saleOrder->transaction->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Only buyer can release funds'], 403);
        }

        $sellerId = $escrowService->saleOrder->transaction->seller_id;
        $escrowService->releaseToSeller($sellerId);
        return response()->json($escrowService);
    }

    public function refund(Request $request, EscrowService $escrowService)
    {
        if ($escrowService->saleOrder->transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Only seller can initiate refund'], 403);
        }

        $buyerId = $escrowService->saleOrder->transaction->buyer_id;
        $escrowService->refundToBuyer($buyerId);
        return response()->json($escrowService);
    }
}