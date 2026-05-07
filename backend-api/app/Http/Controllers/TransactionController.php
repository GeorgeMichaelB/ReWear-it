<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['buyer', 'seller', 'saleOrder'])
            ->where('buyer_id', $request->user()->id)
            ->orWhere('seller_id', $request->user()->id)
            ->latest()
            ->paginate(20);
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        if ($item->seller_id === $request->user()->id) {
            return response()->json(['message' => 'Cannot buy your own item'], 400);
        }

        $transaction = Transaction::create([
            'buyer_id' => $request->user()->id,
            'seller_id' => $item->seller_id,
            'status' => 'pending',
        ]);

        $item->updateStatus('reserved');

        return response()->json($transaction->load('buyer', 'seller'), 201);
    }

    public function show(Transaction $transaction, Request $request)
    {
        if ($transaction->buyer_id !== $request->user()->id && $transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($transaction->load('buyer', 'seller', 'saleOrder', 'disputes', 'reviews'));
    }

    public function cancel(Transaction $transaction, Request $request)
    {
        if ($transaction->buyer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction->cancelTransaction();
        
        if ($transaction->saleOrder) {
            $transaction->saleOrder->item->updateStatus('available');
        }

        return response()->json($transaction);
    }

    public function complete(Transaction $transaction, Request $request)
    {
        if ($transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction->completeTransaction();
        
        if ($transaction->saleOrder) {
            $transaction->saleOrder->item->updateStatus('sold');
        }

        return response()->json($transaction);
    }
}