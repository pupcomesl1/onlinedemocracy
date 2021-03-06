<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentFlag extends Model
{
    protected $fillable = ['flagger', 'comment_id'];

    function comment() {
        return $this->belongsTo('App\Comments');
    }

    function flagger() {
        return $this->belongsTo('App\User');
    }
}
