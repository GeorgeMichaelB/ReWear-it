<?php

namespace App\Http\Controllers;

use App\Models\SwapAgreement;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SwapAgreementController extends Controller
{
    public function index(Request $request)
    {
        $agreements = SwapAgreement::with(['transaction', 'partyA', 'partyB', 'items'])
            ->where('party_a_id', $request->user()->id)
            ->orWhere('party_b_id', $request->user()->id)
            ->latest()
            ->paginate(20);
        return response()->json($agreements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'party_b_id' => 'required|exists:users,id',
            'cash_top_up_amount' => 'nullable|numeric|min:0',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);

        $agreement = SwapAgreement::create([
            'transaction_id' => $request->transaction_id,
            'party_a_id' => $request->user()->id,
            'party_b_id' => $request->party_b_id,
            'cash_top_up_amount' => $request->cash_top_up_amount ?? 0,
            'status' => 'pending',
        ]);

        return response()->json($agreement->load('transaction', 'partyA', 'partyB'), 201);
    }

    public function show(SwapAgreement $swapAgreement, Request $request)
    {
        if ($swapAgreement->party_a_id !== $request->user()->id && 
            $swapAgreement->party_b_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($swapAgreement->load('transaction', 'partyA', 'partyB', 'items'));
    }

    public function sign(Request $request, SwapAgreement $swapAgreement)
    {
        $isPartyA = $swapAgreement->party_a_id === $request->user()->id;
        $isPartyB = $swapAgreement->party_b_id === $request->user()->id;

        if (!$isPartyA && !$isPartyB) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($isPartyA) {
            $swapAgreement->update(['party_a_signed' => true]);
        } else {
            $swapAgreement->update(['party_b_signed' => true]);
        }

        if ($swapAgreement->party_a_signed && $swapAgreement->party_b_signed) {
            $swapAgreement->lockAgreement();
        }

        return response()->json($swapAgreement);
    }

    public function valueBalancer(SwapAgreement $swapAgreement)
    {
        return response()->json(['suggested_balance' => $swapAgreement->suggestValueBalancer()]);
    }

    public function lock(SwapAgreement $swapAgreement, Request $request)
    {
        if ($swapAgreement->party_a_id !== $request->user()->id && 
            $swapAgreement->party_b_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $swapAgreement->lockAgreement();
        return response()->json($swapAgreement);
    }

    public function addItem(Request $request, SwapAgreement $swapAgreement)
    {
        $request->validate(['item_id' => 'required|exists:items,id']);

        if ($swapAgreement->party_a_id !== $request->user()->id && 
            $swapAgreement->party_b_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $swapAgreement->items()->attach($request->item_id);
        return response()->json($swapAgreement->load('items'));
    }
}