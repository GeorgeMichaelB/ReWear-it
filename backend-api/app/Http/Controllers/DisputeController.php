<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function index(Request $request)
    {
        $disputes = Dispute::with(['transaction', 'reporter'])
            ->whereHas('transaction', function ($query) use ($request) {
                $query->where('buyer_id', $request->user()->id)
                    ->orWhere('seller_id', $request->user()->id);
            })
            ->latest()
            ->paginate(20);
        return response()->json($disputes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'reason' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        if ($transaction->buyer_id !== $request->user()->id && $transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dispute = Dispute::create([
            'transaction_id' => $request->transaction_id,
            'reporter_id' => $request->user()->id,
            'reason' => $request->reason,
            'resolution_status' => 'open',
        ]);

        return response()->json($dispute->load('transaction', 'reporter'), 201);
    }

    public function show(Dispute $dispute, Request $request)
    {
        if ($dispute->transaction->buyer_id !== $request->user()->id && 
            $dispute->transaction->seller_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($dispute->load('transaction', 'reporter'));
    }

    public function uploadEvidence(Request $request, Dispute $dispute)
    {
        $request->validate(['photo_url' => 'required|url']);
        
        if ($dispute->reporter_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $dispute->uploadEvidence($request->photo_url);
        return response()->json($dispute);
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate(['verdict' => 'required|string']);

        $dispute->closeDispute($request->verdict);
        return response()->json($dispute);
    }
}