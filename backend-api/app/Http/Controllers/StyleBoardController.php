<?php

namespace App\Http\Controllers;

use App\Models\StyleBoard;
use App\Models\Item;
use Illuminate\Http\Request;

class StyleBoardController extends Controller
{
    public function index(Request $request)
    {
        $boards = StyleBoard::with('user')
            ->latest()
            ->paginate(20);
        return response()->json($boards);
    }

    public function myBoards(Request $request)
    {
        $boards = StyleBoard::where('user_id', $request->user()->id)
            ->with('pinnedItems')
            ->latest()
            ->get();
        return response()->json($boards);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = StyleBoard::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'follower_count' => 0,
        ]);

        return response()->json($board, 201);
    }

    public function show(StyleBoard $styleBoard)
    {
        return response()->json($styleBoard->load('user', 'pinnedItems'));
    }

    public function update(Request $request, StyleBoard $styleBoard)
    {
        if ($styleBoard->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate(['title' => 'sometimes|string|max:255']);
        $styleBoard->update($request->only(['title']));
        return response()->json($styleBoard);
    }

    public function destroy(Request $request, StyleBoard $styleBoard)
    {
        if ($styleBoard->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $styleBoard->delete();
        return response()->json(['message' => 'Style board deleted']);
    }

    public function addItem(Request $request, StyleBoard $styleBoard)
    {
        $request->validate(['item_id' => 'required|exists:items,id']);

        if ($styleBoard->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $styleBoard->addPinnedItem(Item::findOrFail($request->item_id));
        return response()->json($styleBoard->load('pinnedItems'));
    }

    public function removeItem(Request $request, StyleBoard $styleBoard, $itemId)
    {
        if ($styleBoard->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $styleBoard->pinnedItems()->detach($itemId);
        return response()->json($styleBoard->load('pinnedItems'));
    }
}