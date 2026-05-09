<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MentorshipController extends Controller
{
    // UC-35: Mentor-mentee pairing
    protected $mentorships = [];

    public function requestMentor(Request $request)
    {
        $request->validate([
            'skill_interest' => 'required|string',
            'experience_level' => 'required|in:beginner,intermediate,advanced',
            'goals' => 'sometimes|array',
        ]);

        $requestId = 'MTR-' . strtoupper(uniqid());

        $mentorship = [
            'id' => $requestId,
            'mentee_id' => $request->user()->id ?? 1,
            'skill_interest' => $request->skill_interest,
            'experience_level' => $request->experience_level,
            'status' => 'pending_matching',
            'created_at' => now()->toDateTimeString(),
            'goals' => $request->goals ?? [],
        ];

        return response()->json([
            'message' => 'Mentorship request submitted',
            'request' => $mentorship,
            'matching_info' => 'We will pair you with an experienced creator in your area of interest',
        ], 201);
    }

    // UC-35: Apply to be a mentor
    public function applyAsMentor(Request $request)
    {
        $request->validate([
            'expertise' => 'required|array',
            'years_experience' => 'required|integer',
            'specialties' => 'sometimes|array',
        ]);

        return response()->json([
            'message' => 'Mentor application submitted',
            'application_status' => 'under_review',
            'next_steps' => 'Our team will review your application within 3-5 business days',
        ]);
    }

    // UC-35: Get mentor recommendations
    public function getMentorRecommendations(Request $request)
    {
        $skill = $request->skill ?? 'upcycling';
        
        $mentors = [
            [
                'id' => 1,
                'name' => 'UpcycleQueen',
                'avatar' => null,
                'expertise' => ['upcycling', 'tie-dye', 'embroidery'],
                'years_experience' => 8,
                'rating' => 4.9,
                'mentees_helped' => 45,
                'specialties' => ['denim transformation', 'natural dyes'],
                'availability' => 'Weekends',
            ],
            [
                'id' => 2,
                'name' => 'EcoStitcher',
                'avatar' => null,
                'expertise' => ['patchwork', 'mending', 'visible mending'],
                'years_experience' => 5,
                'rating' => 4.7,
                'mentees_helped' => 28,
                'specialties' => ['Sashiko', 'boro'],
                'availability' => 'Evenings',
            ],
            [
                'id' => 3,
                'name' => 'SustainableStyle',
                'avatar' => null,
                'expertise' => ['refashion', 'tailoring', ' alterations'],
                'years_experience' => 10,
                'rating' => 5.0,
                'mentees_helped' => 120,
                'specialties' => ['formal wear', 'streetwear'],
                'availability' => 'Mornings',
            ],
        ];

        return response()->json([
            'skill' => $skill,
            'mentors' => $mentors,
            'total_available' => count($mentors),
        ]);
    }

    // UC-35: Match with mentor
    public function requestMatch(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|integer',
        ]);

        $matchId = 'MATCH-' . strtoupper(uniqid());

        return response()->json([
            'match_id' => $matchId,
            'mentor_id' => $request->mentor_id,
            'status' => 'pending_approval',
            'message' => 'Match request sent to mentor',
        ]);
    }

    // UC-35: Accept/Decline match
    public function respondToMatch(Request $request, $matchId)
    {
        $request->validate([
            'action' => 'required|in:accept,decline',
        ]);

        return response()->json([
            'match_id' => $matchId,
            'action' => $request->action,
            'message' => $request->action === 'accept' 
                ? 'Match accepted! You can now start chatting.'
                : 'Match declined.',
            'next_steps' => $request->action === 'accept' 
                ? ['Schedule intro call', 'Set goals', 'Begin learning']
                : [],
        ]);
    }

    // UC-35: Get active mentorships
    public function getActiveMentorships(Request $request)
    {
        return response()->json([
            'active_mentorships' => [
                [
                    'id' => 'MTR-001',
                    'mentor' => ['id' => 1, 'name' => 'UpcycleQueen', 'expertise' => ['upcycling']],
                    'mentee' => ['id' => 2, 'name' => 'NewCreator'],
                    'skill' => 'denim transformation',
                    'start_date' => now()->subWeeks(2)->toDateTimeString(),
                    'status' => 'active',
                    'sessions_completed' => 4,
                ],
            ],
        ]);
    }

    // UC-35: Schedule mentorship session
    public function scheduleSession(Request $request)
    {
        $request->validate([
            'mentorship_id' => 'required',
            'date' => 'required|date',
            'topic' => 'required|string',
        ]);

        return response()->json([
            'session_id' => 'SES-' . strtoupper(uniqid()),
            'mentorship_id' => $request->mentorship_id,
            'scheduled_for' => $request->date,
            'topic' => $request->topic,
            'status' => 'scheduled',
            'reminder' => 'You will receive a reminder 1 hour before the session',
        ]);
    }
}