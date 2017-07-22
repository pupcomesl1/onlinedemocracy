<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HipsterJazzbo\Landlord\BelongsToTenants;

class CommentFlag extends Model
{
    use BelongsToTenants;
    protected $fillable = ['flagger', 'comment_id'];

    function comment() {
        return $this->belongsTo('App\Comments');
    }

    function flagger() {
        return $this->belongsTo('App\User');
    }
}
