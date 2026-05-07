<?php

namespace App\Http\Controllers;

use App\Models\TransformationLog;
use App\Models\Item;
use Illuminate\Http\Request;

class TransformationLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = TransformationLog::with(['item', 'creator'])
            ->latest()
            ->paginate(20);
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'before_image_url' => 'nullable|url',
            'after_image_url' => 'nullable|url',
            'modifications_made' => 'nullable|array',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        $creator = $request->user()->seller?->creator;
        
        if (!$creator) {
            return response()->json(['message' => 'Only creators can log transformations'], 403);
        }

        $log = TransformationLog::create([
            'item_id' => $request->item_id,
            'creator_id' => $creator->id,
            'before_image_url' => $request->before_image_url,
            'after_image_url' => $request->after_image_url,
            'modifications_made' => $request->modifications_made ?? [],
        ]);

        return response()->json($log->load('item', 'creator'), 201);
    }

    public function show(TransformationLog $transformationLog)
    {
        return response()->json($transformationLog->load('item', 'creator'));
    }

    public function careInstructions(TransformationLog $transformationLog)
    {
        return response()->json(['care_instructions' => $transformationLog->generateCareInstructions()]);
    }
}