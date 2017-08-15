<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['user_id', 'type', 'from'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function from() {
        return $this->morphTo();
    }

    public static function tryAward(int $uid, string $type, $from = null) {
        $badge = getBadges()[$type];

        $once = @$badge['once'];
        $check = @$badge['check'];

        if ($check != null && !$check($uid, $from)) {
            \Log::debug('Refused to award badge ' . $type . ' to ' . $uid . ' because it violated the check.');
            return;
        }

        $has = Badge::where([['type', $type], ['user_id', $uid]])->get()->isNotEmpty();

        if ($once && $has) {
            \Log::debug('Refused to award badge ' . $type . ' to ' . $uid . ' because the user already has it.');
            return;
        }

        Badge::award($uid, $type, $from);
    }

    public static function award(int $uid, string $type, $from = null) {
        $badge = new Badge();
        $badge->user_id = $uid;
        $badge->type = $type;
        if (get_class($from) === 'App\Proposition') {
            $from->badges()->save($badge);
        } else {
            $badge->saveOrFail();
        }
    }
}
