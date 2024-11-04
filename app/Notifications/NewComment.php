<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewComment extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'comment',
            'user_id' => $this->comment->user_id,
            'username' => $this->comment->user->username,
            'list_id' => $this->comment->list_id,
            'list_title' => $this->comment->list->title,
            'comment' => \Str::limit($this->comment->content, 50),
            'created_at' => now()->diffForHumans(),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment',
            'user_id' => $this->comment->user_id,
            'username' => $this->comment->user->username,
            'list_id' => $this->comment->list_id,
            'list_title' => $this->comment->list->title,
            'comment' => \Str::limit($this->comment->content, 50),
        ];
    }
}