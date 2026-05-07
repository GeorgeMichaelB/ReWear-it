<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Item;
use App\Models\TransformationLog;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function index()
    {
        $creators = Creator::with('seller.user')
            ->where('pro_upcycle_badge', true)
            ->latest()
            ->paginate(20);
        return response()->json($creators);
    }

    public function me(Request $request)
    {
        $creator = $request->user()->seller?->creator;
        
        if (!$creator) {
            return response()->json(['message' => 'You are not a creator'], 404);
        }

        return response()->json($creator->load('seller.user', 'transformationLogs'));
    }

    public function store(Request $request)
    {
        $seller = $request->user()->seller;

        if (!$seller) {
            return response()->json(['message' => 'You must be a seller first'], 400);
        }

        if ($seller->creator) {
            return response()->json(['message' => 'You are already a creator'], 400);
        }

        $creator = Creator::create([
            'seller_id' => $seller->id,
            'pro_upcycle_badge' => false,
            'total_waste_diverted' => 0,
        ]);

        return response()->json($creator, 201);
    }

    public function show(Creator $creator)
    {
        return response()->json($creator->load('seller.user', 'transformationLogs', 'seller.products'));
    }

    public function logTransformation(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
        ]);

        $creator = $request->user()->seller?->creator;
        
        if (!$creator) {
            return response()->json(['message' => 'You are not a creator'], 403);
        }

        $log = $creator->logTransformation(Item::findOrFail($request->item_id));
        
        return response()->json($log->load('item', 'creator'), 201);
    }

    public function bulkListing(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.category_id' => 'required|exists:categories,id',
        ]);

        $creator = $request->user()->seller?->creator;
        
        if (!$creator) {
            return response()->json(['message' => 'You are not a creator'], 403);
        }

        $creator->createBulkListing($request->items);
        
        return response()->json(['message' => 'Bulk listing created']);
    }

    public function updateBadge(Request $request, Creator $creator)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate(['pro_upcycle_badge' => 'boolean']);
        
        $creator->update(['pro_upcycle_badge' => $request->pro_upcycle_badge]);
        return response()->json($creator);
    }
}