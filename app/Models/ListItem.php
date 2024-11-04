<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    protected $fillable = [
        'list_id',
        'title',
        'description',
        'external_id',
        'position'
    ];

    public function list()
    {
        return $this->belongsTo(UserList::class, 'list_id');
    }
}