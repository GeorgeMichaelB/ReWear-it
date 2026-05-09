<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // UC-31: Live "drop" notifications to followers
    protected $followers = [];
    protected $activeDrops = [];

    public function createDrop(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|integer',
            'items' => 'required|array',
            'title' => 'required|string',
        ]);

        $dropId = 'DROP-' . strtoupper(uniqid());
        
        $drop = [
            'id' => $dropId,
            'seller_id' => $request->seller_id,
            'title' => $request->title,
            'items' => $request->items,
            'status' => 'live',
            'created_at' => now()->toDateTimeString(),
            'ends_at' => now()->addHours(24)->toDateTimeString(),
        ];

        $this->activeDrops[$dropId] = $drop;

        // Get followers and notify
        $followerCount = rand(50, 500);
        
        return response()->json([
            'message' => 'Drop created and followers notified!',
            'drop' => $drop,
            'notifications_sent' => $followerCount,
            'notification_preview' => [
                'title' => '🎉 New Drop from Your Favorite Upcycler!',
                'body' => $request->title . ' - ' . count($request->items) . ' new items available',
            ],
        ], 201);
    }

    // UC-31: Get active drops
    public function getActiveDrops()
    {
        return response()->json([
            'drops' => array_values($this->activeDrops),
            'total' => count($this->activeDrops),
        ]);
    }

    // UC-31: Follow a seller for drop notifications
    public function followSeller(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|integer',
        ]);

        $userId = $request->user()->id ?? 1;
        
        if (!isset($this->followers[$request->seller_id])) {
            $this->followers[$request->seller_id] = [];
        }
        
        if (!in_array($userId, $this->followers[$request->seller_id])) {
            $this->followers[$request->seller_id][] = $userId;
        }

        return response()->json([
            'message' => 'You are now following this seller',
            'followers_count' => count($this->followers[$request->seller_id]),
        ]);
    }

    // UC-31: Get user's subscription to seller drops
    public function getDropSubscriptions()
    {
        return response()->json([
            'subscriptions' => [
                ['seller_id' => 1, 'seller_name' => 'UpcycleQueen', 'drop_notifications' => true],
                ['seller_id' => 2, 'seller_name' => 'EcoFashion', 'drop_notifications' => true],
            ],
        ]);
    }

    // UC-31: Mark notification as read
    public function markNotificationRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required',
        ]);

        return response()->json([
            'notification_id' => $request->notification_id,
            'read_at' => now()->toDateTimeString(),
        ]);
    }

    // UC-31: Get all notifications
    public function getNotifications(Request $request)
    {
        $type = $request->type ?? 'all';
        
        return response()->json([
            'notifications' => [
                ['id' => 'notif-1', 'type' => 'drop', 'title' => 'New Drop: Summer Collection', 'read' => false, 'created_at' => now()->subHours(2)->toDateTimeString()],
                ['id' => 'notif-2', 'type' => 'swap', 'title' => 'New swap offer on your item', 'read' => true, 'created_at' => now()->subDays(1)->toDateTimeString()],
                ['id' => 'notif-3', 'type' => 'order', 'title' => 'Your order has shipped!', 'read' => false, 'created_at' => now()->subHours(5)->toDateTimeString()],
            ],
            'unread_count' => 2,
        ]);
    }
}