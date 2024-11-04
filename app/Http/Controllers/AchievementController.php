<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    private $ninjaAchievements = [
        [
            'name' => 'Ninja Apprentice',
            'description' => 'Your first list - your training begins!',
            'icon' => '🥷',
            'required_lists' => 1,
            'required_points' => 10
        ],
        [
            'name' => 'Ninja Scout',
            'description' => 'Five lists - your skills are growing!',
            'icon' => '⚔️',
            'required_lists' => 5,
            'required_points' => 50
        ],
        [
            'name' => 'Ninja Warrior',
            'description' => 'Ten lists - mastering the art of lists!',
            'icon' => '🌟',
            'required_lists' => 10,
            'required_points' => 100
        ],
        [
            'name' => 'Shadow Master',
            'description' => 'Twenty lists - moving in silence!',
            'icon' => '📜',
            'required_lists' => 20,
            'required_points' => 200
        ],
        [
            'name' => 'Elite Ninja',
            'description' => 'Fifty lists - true wisdom achieved!',
            'icon' => '🎭',
            'required_lists' => 50,
            'required_points' => 500
        ],
        [
            'name' => 'List Ninja Legend',
            'description' => 'A hundred lists - you are the legend!',
            'icon' => '⚡',
            'required_lists' => 100,
            'required_points' => 1000
        ]
    ];

    public function checkAchievements(User $user)
    {
        $listCount = $user->lists()->count();
        $unlockedAchievements = [];

        foreach ($this->ninjaAchievements as $achievementData) {
            if ($listCount >= $achievementData['required_lists'] && 
                $user->points >= $achievementData['required_points']) {
                
                $achievement = Achievement::firstOrCreate(
                    ['name' => $achievementData['name']],
                    $achievementData
                );

                if (!$user->achievements->contains($achievement->id)) {
                    $user->achievements()->attach($achievement->id, [
                        'unlocked_at' => now()
                    ]);
                    $unlockedAchievements[] = $achievement;
                }
            }
        }

        if (count($unlockedAchievements) > 0) {
            return response()->json([
                'success' => true,
                'message' => 'New achievements unlocked!',
                'achievements' => $unlockedAchievements
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'No new achievements'
        ]);
    }

    public function index(User $user)
    {
        $achievements = $user->achievements()
            ->orderBy('required_points')
            ->get();

        $nextAchievements = Achievement::whereNotIn('id', $achievements->pluck('id'))
            ->where('required_points', '>', $user->points)
            ->orderBy('required_points')
            ->get();

        return view('achievements.index', compact('achievements', 'nextAchievements', 'user'));
    }

    public function setupAchievements()
    {
        foreach ($this->ninjaAchievements as $achievement) {
            Achievement::firstOrCreate(
                ['name' => $achievement['name']],
                $achievement
            );
        }

        return redirect()->back()->with('success', 'Achievement system initialized!');
    }
}