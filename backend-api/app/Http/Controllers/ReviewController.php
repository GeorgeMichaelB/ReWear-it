<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['reviewer', 'reviewee', 'transaction'])
            ->where('reviewee_id', $request->user()->id)
            ->latest()
            ->paginate(20);
        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'eco_friendliness_score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        
        $revieweeId = $transaction->buyer_id === $request->user()->id 
            ? $transaction->seller_id 
            : $transaction->buyer_id;

        $existingReview = Review::where('transaction_id', $request->transaction_id)
            ->where('reviewer_id', $request->user()->id)
            ->first();

        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this transaction'], 400);
        }

        $review = Review::create([
            'transaction_id' => $request->transaction_id,
            'reviewer_id' => $request->user()->id,
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
            'eco_friendliness_score' => $request->eco_friendliness_score,
            'comment' => $request->comment,
        ]);

        return response()->json($review->load('reviewer', 'reviewee', 'transaction'), 201);
    }

    public function show(Review $review, Request $request)
    {
        return response()->json($review->load('reviewer', 'reviewee', 'transaction'));
    }
}