<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UserFactory extends Model {
	
	public function getUser($userId) {
		return Cache::driver('array')->remember('user:' . $userId, 1, function() use ($userId) {
		    return User::find($userId);
        });
	}
	
	public function findUserByEmailOrFail($userEmail) {
		if (User::where('email', '=', $userEmail)->count() == 1) {
			return User::whereRaw('email = ?', [$userEmail])->get();
		} else {
			return false;
		}
	}
	
	public function schoolEmailIsTaken($email) {
		if (User::where('googleEmail', '=', $email)->count() == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function unlinkGoogleAccount($userId) {
		$user = User::find($userId);
		
		$user->setGoogleId(null);
		$user->setGoogleEmail(null);
    					
		$user->setBelongsToSchool(false);
    				
    	$user->save();
    	
    	return true;
	}
	
	public function createUser($firstName, $lastName, $email, $avatar_url, $password) {
		return User::create([
				"firstName" => $firstName,
				"lastName" => $lastName,
				"avatar" => $avatar_url,
				"email" => $email,
				"password" => \Hash::make($password),
		]);
	}

	public function getPointsForId($userId) {
	    return Cache::remember('points:' . $userId, 60, function() use ($userId) {
	        return PointsHistoryEntry::where('user_id', $userId)->orderBy('id', 'asc')->sum('value');
        });


//	    $value = Redis::get('points:' . $userId);
//	    if ($value == 0) {
//	        // Recalculate the points
//            $value = PointsHistoryEntry::where('user_id', $userId)->orderBy('id', 'asc')->sum('value');
//            Redis::set('points:' . $userId, $value);
//        }
//        return $value;
    }

    public function getPointsFor($user) {
	    return $this->getPointsForId($user->id);
    }

    public function resetPointsCacheFor($user) {
        $this->resetPointsCacheForId($user->id);
    }

    public function resetPointsCacheForId($userId) {
	    Redis::del('points:' . $userId);
    }

    public function getBadges($userId) {
	    return Cache::remember('badgesFor:' . $userId, 1, function() use ($userId) {
	       return Badge::where('user_id', $userId)->get();
        });
    }

    public function buildLeaderboard() {
        $data = Cache::remember('leaderboard', 1, function() {
            return DB::table('points_history')
                ->join('users', 'points_history.user_id', '=', 'users.id')
                ->selectRaw('SUM(value) AS total, user_id, displayName')
                ->groupBy(['user_id'])
                ->orderBy('total', 'desc')
                ->get();
        });

        $data->transform(function($row) {
            $user = $this->getUser($row->user_id);
            $badges = $this->getBadges($user->id);
            return array_merge((array)$row, ['badges' => [
                'gold' => $badges->filter(filterGold())->count(),
                'silver' => $badges->filter(filterSilver())->count(),
                'bronze' => $badges->filter(filterBronze())->count(),
            ]]);
        });

        return $data;
    }
	
}