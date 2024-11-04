<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserList extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'type',
        'size',
        'category',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ListItem::class, 'list_id')->orderBy('position');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'list_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'list_id');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}