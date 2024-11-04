<?php

namespace App\Http\Controllers;

use App\Models\UserList;
use App\Models\Comment;
use App\Notifications\NewComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, UserList $list)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = $list->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content']
        ]);

        // Add points if it's not the user's own list
        if ($list->user_id !== auth()->id()) {
            $list->user->addPoints(2);
            // Send notification
            $list->user->notify(new NewComment($comment));
        }

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => [
                    'username' => $comment->user->username,
                    'avatar' => $comment->user->avatar
                ],
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json(['success' => true]);
    }
}