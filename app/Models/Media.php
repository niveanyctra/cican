<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'post_id',
        'file_url',
        'type',
        'order',
        'thumbnail_url',
    ];
}
