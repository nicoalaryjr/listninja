<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'required_points',
        'required_lists',
        'badge_emoji'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withTimestamp('unlocked_at');
    }
}