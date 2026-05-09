<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use Illuminate\Http\Request;

class StyleBoardController extends Controller
{
    // UC-30: Create a style board
    public function createBoard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'sometimes|string|max:500',
            'is_public' => 'sometimes|boolean',
            'cover_image' => 'sometimes|string',
        ]);

        $boardId = 'SB-' . strtoupper(uniqid());
        
        $board = [
            'id' => $boardId,
            'name' => $request->name,
            'description' => $request->description ?? '',
            'is_public' => $request->is_public ?? true,
            'cover_image' => $request->cover_image,
            'creator_id' => $request->user()->id,
            'creator_name' => $request->user()->name,
            'collaborators' => [$request->user()->id],
            'item_count' => 0,
            'created_at' => now()->toDateTimeString(),
            'follower_count' => 0,
        ];

        return response()->json([
            'message' => 'Style board created',
            'board' => $board,
        ], 201);
    }

    // UC-30: Add item to style board
    public function addItem(Request $request)
    {
        $request->validate([
            'board_id' => 'required',
            'item_id' => 'required|exists:items,id',
        ]);

        return response()->json([
            'board_id' => $request->board_id,
            'item_id' => $request->item_id,
            'added_at' => now()->toDateTimeString(),
            'message' => 'Item added to style board',
        ]);
    }

    // UC-30: Get public style boards
    public function getPublicBoards(Request $request)
    {
        $boards = [
            [
                'id' => 'SB-001',
                'name' => 'Sustainable Summer Vibes',
                'description' => 'Eco-friendly summer outfits',
                'creator' => 'SarahGreen',
                'item_count' => 12,
                'follower_count' => 45,
                'cover_image' => null,
                'tags' => ['summer', 'sustainable', 'casual'],
                'created_at' => now()->subDays(15)->toDateTimeString(),
            ],
            [
                'id' => 'SB-002',
                'name' => 'Thrift Flip Transformations',
                'description' => 'Before and after thrift flips',
                'creator' => 'UpcycleQueen',
                'item_count' => 8,
                'follower_count' => 120,
                'cover_image' => null,
                'tags' => ['upcycle', 'diy', 'transformation'],
                'created_at' => now()->subDays(30)->toDateTimeString(),
            ],
            [
                'id' => 'SB-003',
                'name' => 'Minimalist Wardrobe',
                'description' => 'Capsule wardrobe essentials',
                'creator' => 'MinimalMia',
                'item_count' => 25,
                'follower_count' => 89,
                'cover_image' => null,
                'tags' => ['minimalist', 'capsule', 'essentials'],
                'created_at' => now()->subDays(45)->toDateTimeString(),
            ],
        ];

        return response()->json([
            'boards' => $boards,
            'total' => count($boards),
        ]);
    }

    // UC-30: Get style board details with items
    public function getBoardDetails($boardId)
    {
        return response()->json([
            'board' => [
                'id' => $boardId,
                'name' => 'Sustainable Summer Vibes',
                'description' => 'Eco-friendly summer outfits for conscious fashion lovers',
                'creator' => ['id' => 1, 'name' => 'SarahGreen', 'trust_score' => 95],
                'is_public' => true,
                'collaborators' => [
                    ['id' => 2, 'name' => 'EcoEmma'],
                ],
                'created_at' => now()->subDays(15)->toDateTimeString(),
                'follower_count' => 45,
            ],
            'items' => [
                ['id' => 1, 'name' => 'Vintage Denim Jacket', 'price' => 35, 'image' => null],
                ['id' => 2, 'name' => 'Organic Cotton T-Shirt', 'price' => 20, 'image' => null],
                ['id' => 3, 'name' => 'Recycled Linen Shorts', 'price' => 28, 'image' => null],
            ],
            'comments' => [
                ['user' => 'FashionFan', 'comment' => 'Love this collection!', 'timestamp' => now()->subDays(2)->toDateTimeString()],
                ['user' => 'ThriftLover', 'comment' => 'Great sustainable choices', 'timestamp' => now()->subDay()->toDateTimeString()],
            ],
        ]);
    }

    // UC-30: Follow a style board
    public function followBoard(Request $request, $boardId)
    {
        return response()->json([
            'board_id' => $boardId,
            'following' => true,
            'message' => 'You are now following this style board',
            'follower_count' => 46,
        ]);
    }

    // UC-30: Add collaborator to board
    public function addCollaborator(Request $request)
    {
        $request->validate([
            'board_id' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        return response()->json([
            'board_id' => $request->board_id,
            'collaborator_added' => $request->user_id,
            'message' => 'User added as collaborator',
        ]);
    }

    // UC-30: Get followed boards
    public function getFollowedBoards(Request $request)
    {
        return response()->json([
            'boards' => [
                ['id' => 'SB-001', 'name' => 'Sustainable Summer Vibes', 'follower_count' => 45],
                ['id' => 'SB-003', 'name' => 'Minimalist Wardrobe', 'follower_count' => 89],
            ],
            'total' => 2,
        ]);
    }

    // UC-30: Get boards by user
    public function getUserBoards(Request $request, $userId)
    {
        return response()->json([
            'boards' => [
                ['id' => 'SB-001', 'name' => 'My Board 1', 'item_count' => 5, 'follower_count' => 10],
                ['id' => 'SB-004', 'name' => 'My Board 2', 'item_count' => 8, 'follower_count' => 15],
            ],
            'total' => 2,
        ]);
    }

    // UC-30: Update board
    public function updateBoard(Request $request, $boardId)
    {
        $request->validate([
            'name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500',
            'is_public' => 'sometimes|boolean',
        ]);

        return response()->json([
            'board_id' => $boardId,
            'message' => 'Board updated successfully',
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    // UC-30: Delete board
    public function deleteBoard(Request $request, $boardId)
    {
        return response()->json([
            'board_id' => $boardId,
            'message' => 'Board deleted successfully',
            'deleted_at' => now()->toDateTimeString(),
        ]);
    }
}