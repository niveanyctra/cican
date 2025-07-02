<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'caption',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class)->orderBy('order');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi kolaborator
    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'collaborations');
    }

    // Relasi tagged users
    public function taggedUsers()
    {
        return $this->belongsToMany(User::class, 'post_tags');
    }
}
