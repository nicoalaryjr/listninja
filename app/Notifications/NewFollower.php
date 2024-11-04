<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewFollower extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => 'follow',
            'user_id' => $this->follower->id,
            'username' => $this->follower->username,
            'created_at' => now()->diffForHumans(),
            'follower_avatar' => $this->follower->avatar 
                ? Storage::url($this->follower->avatar) 
                : 'https://ui-avatars.com/api/?name='.urlencode($this->follower->username),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'follow',
            'user_id' => $this->follower->id,
            'username' => $this->follower->username,
        ];
    }
}