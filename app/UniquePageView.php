<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UniquePageView extends Model
{
    protected $fillable = ['proposition_id', 'referrer', 'ip'];

    function proposition() {
        return $this->belongsTo('App\Proposition');
    }
}
