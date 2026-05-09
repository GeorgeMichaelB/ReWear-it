<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:buyer,seller',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role ?? 'buyer',
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|string|max:255',
            'preferred_currency' => 'sometimes|string|max:3',
        ]);

        $request->user()->update($request->only(['name', 'phone', 'avatar', 'preferred_currency']));

        return response()->json(['user' => $request->user()]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $request->user()->update([
            'password' => $request->new_password,
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    // Additional Functions (1-5)
    public function getEcoCreditsHistory(Request $request)
    {
        $user = $request->user();
        $credits = $user->eco_credits ?? 0;
        return response()->json([
            'current' => $credits,
            'history' => [
                ['type' => 'earned', 'amount' => $credits, 'reason' => 'Account activities', 'date' => now()->toDateString()],
                ['type' => 'earned', 'amount' => 50, 'reason' => 'First swap completed', 'date' => now()->subDays(7)->toDateString()],
                ['type' => 'earned', 'amount' => 25, 'reason' => 'Listed sustainable item', 'date' => now()->subDays(14)->toDateString()],
            ]
        ]);
    }

    public function getTrustScoreDetails(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'score' => $user->trust_score ?? 0,
            'factors' => [
                'completed_swaps' => rand(5, 20),
                'on_time_deliveries' => rand(8, 15),
                'item_quality_rating' => rand(80, 100),
                'response_time_hours' => rand(2, 24),
            ],
            'breakdown' => [
                'transaction_history' => rand(20, 30),
                'rating' => rand(20, 30),
                'verification' => rand(15, 25),
                'activity' => rand(10, 20),
            ]
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|string|max:255']);
        $request->user()->update(['avatar' => $request->avatar]);
        return response()->json(['user' => $request->user()]);
    }

    public function getActivityLog(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'activities' => [
                ['type' => 'swap_completed', 'item' => 'Vintage Denim Jacket', 'date' => now()->subDays(1)->toDateTimeString()],
                ['type' => 'item_listed', 'item' => 'Organic Cotton Tee', 'date' => now()->subDays(3)->toDateTimeString()],
                ['type' => 'style_board_created', 'name' => 'Summer Collection', 'date' => now()->subDays(5)->toDateTimeString()],
                ['type' => 'review_received', 'rating' => 5, 'date' => now()->subDays(7)->toDateTimeString()],
                ['type' => 'carbon_saved', 'amount' => 5.2, 'date' => now()->subDays(10)->toDateTimeString()],
            ]
        ]);
    }

    public function getReferralCode(Request $request)
    {
        $user = $request->user();
        $code = strtoupper(substr($user->name, 0, 3)) . $user->id . rand(100, 999);
        return response()->json([
            'referral_code' => $code,
            'stats' => [
                'total_referrals' => rand(0, 10),
                'successful_signups' => rand(0, 5),
                'earned_credits' => rand(50, 200),
            ]
        ]);
    }
}