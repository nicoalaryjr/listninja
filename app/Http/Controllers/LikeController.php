<?php

namespace App\Http\Controllers;

use App\Models\UserList;
use App\Notifications\NewLike;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(UserList $list)
    {
        $user = auth()->user();
        
        if ($list->likes()->where('user_id', $user->id)->exists()) {
            $list->likes()->where('user_id', $user->id)->delete();
            $isLiked = false;
        } else {
            $list->likes()->create(['user_id' => $user->id]);
            $isLiked = true;
            
            // Add points if it's not the user's own list
            if ($list->user_id !== $user->id) {
                $list->user->addPoints(5);
                // Send notification
                $list->user->notify(new NewLike($list->likes()->latest()->first()));
            }
        }

        return response()->json([
            'success' => true,
            'isLiked' => $isLiked,
            'likesCount' => $list->likes()->count()
        ]);
    }
}