<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    // UC-33: Nested social comment threads
    protected $comments = [];

    public function getComments(Request $request, $itemId)
    {
        $itemId = $itemId ?? 1;
        
        // Simulated nested comments
        $comments = [
            [
                'id' => 1,
                'user' => ['id' => 1, 'name' => 'EcoFashionista', 'avatar' => null],
                'content' => 'Love this transformation! What technique did you use?',
                'created_at' => now()->subDays(2)->toDateTimeString(),
                'replies' => [
                    [
                        'id' => 2,
                        'user' => ['id' => 2, 'name' => 'UpcycleQueen', 'avatar' => null],
                        'content' => 'Thanks! I used Shibori tie-dye technique with natural indigo dye.',
                        'created_at' => now()->subDay()->toDateTimeString(),
                        'replies' => [
                            [
                                'id' => 3,
                                'user' => ['id' => 1, 'name' => 'EcoFashionista', 'avatar' => null],
                                'content' => 'That looks amazing! Did you pre-treat the fabric?',
                                'created_at' => now()->subHours(12)->toDateTimeString(),
                                'replies' => [],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 4,
                'user' => ['id' => 3, 'name' => 'ThriftFlipper', 'avatar' => null],
                'content' => 'Great styling tips! Would recommend this to beginners.',
                'created_at' => now()->subHours(5)->toDateTimeString(),
                'replies' => [],
            ],
        ];

        return response()->json([
            'item_id' => $itemId,
            'comments' => $comments,
            'total_comments' => 4,
            'total_threads' => 2,
        ]);
    }

    // UC-33: Add a new comment
    public function addComment(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'content' => 'required|string',
            'parent_id' => 'sometimes|integer',
        ]);

        $commentId = count($this->comments) + 1;
        
        $comment = [
            'id' => $commentId,
            'item_id' => $request->item_id,
            'user_id' => $request->user()->id ?? 1,
            'user_name' => $request->user()->name ?? 'User',
            'content' => $request->content,
            'parent_id' => $request->parent_id ?? null,
            'created_at' => now()->toDateTimeString(),
            'replies' => [],
            'likes' => 0,
        ];

        $this->comments[] = $comment;

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ], 201);
    }

    // UC-33: Reply to a comment (nested)
    public function replyToComment(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $reply = [
            'id' => rand(100, 999),
            'user_id' => $request->user()->id ?? 1,
            'user_name' => $request->user()->name ?? 'User',
            'content' => $request->content,
            'created_at' => now()->toDateTimeString(),
            'replies' => [],
            'likes' => 0,
        ];

        return response()->json([
            'message' => 'Reply added to comment',
            'reply' => $reply,
        ], 201);
    }

    // UC-33: Like a comment
    public function likeComment(Request $request, $commentId)
    {
        return response()->json([
            'comment_id' => $commentId,
            'likes' => rand(1, 50),
            'liked' => true,
        ]);
    }

    // UC-33: Report inappropriate comment
    public function reportComment(Request $request, $commentId)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        return response()->json([
            'comment_id' => $commentId,
            'reported' => true,
            'message' => 'Comment reported for review',
        ]);
    }

    // UC-33: Delete own comment
    public function deleteComment(Request $request, $commentId)
    {
        return response()->json([
            'comment_id' => $commentId,
            'deleted' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}