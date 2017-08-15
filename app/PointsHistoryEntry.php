<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PointsHistoryEntry extends Model
{
    protected $table = 'points_history';
    protected $fillable = ['user_id', 'value', 'cause', 'from'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function add(int $uid, int $value, string $cause, $for = null) {
        PointsHistoryEntry::create([
            'user_id' => $uid,
            'value' => $value,
            'cause' => $cause,
            'for' => $for
        ]);
        Cache::forget('points:' . $uid);
    }

    public static function subtract(int $uid, int $value, string $cause, $for = null) {
        PointsHistoryEntry::create([
            'user_id' => $uid,
            'value' => -$value,
            'cause' => $cause,
            'for' => $for
        ]);
        Cache::forget('points:' . $uid);
    }
}
