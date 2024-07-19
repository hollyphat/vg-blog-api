<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    function blog(){
        return $this->belongsTo(Blog::class);
    }

    function comments(){
        return $this->hasMany(Post::class,'parent_id');
    }

    function post(){
        return $this->belongsTo(Post::class,'parent_id');
    }

    function likes(){
        return $this->hasMany(PostLike::class,'post_id');
    }
}
