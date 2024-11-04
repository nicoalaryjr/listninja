<?php

namespace App\Notifications;

use App\Models\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewLike extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'like',
            'user_id' => $this->like->user_id,
            'username' => $this->like->user->username,
            'list_id' => $this->like->list_id,
            'list_title' => $this->like->list->title,
            'created_at' => now()->diffForHumans(),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'like',
            'user_id' => $this->like->user_id,
            'username' => $this->like->user->username,
            'list_id' => $this->like->list_id,
            'list_title' => $this->like->list->title,
        ];
    }
}