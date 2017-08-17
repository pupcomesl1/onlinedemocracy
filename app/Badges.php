<?php

use App\Badge;
use App\Marker;
use App\Proposition;
use App\UniquePageView;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

$userFactory = new \App\UserFactory();
$propositionFactory = new \App\PropositionFactory();

return [
    'initiate' => [
        'type' => 'bronze'
    ],

    'veteran' => [
        'type' => 'bronze',
        'once' => true
    ],

    'contributor' => [
        'type' => 'bronze',
        'check' => function(int $uid) use ($propositionFactory) {
            return count($propositionFactory->getApprovedPropositionsByUser($uid)) == 1;
        }
    ],
    'innovator' => [
        'type' => 'silver',
        'check' => function(int $uid) use ($propositionFactory) {
            return count($propositionFactory->getApprovedPropositionsByUser($uid)) == 5;
        }
    ],
    'inventor' => [
        'type' => 'gold',
        'check' => function(int $uid) use ($propositionFactory) {
            return count($propositionFactory->getApprovedPropositionsByUser($uid)) == 15;
        }
    ],

    'popular_proposition' => [
        'type' => 'bronze',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where([['proposer_id', $uid], ['views', '>=', 30]])->get();
            if ($props->isNotEmpty()) {
                foreach ($props as $prop) {
                    if (Badge::where([
                        ['from_type', 'App\Proposition'],
                        ['from_id', $prop->id],
                        ['type', 'popular_proposition']
                    ])->get()->isEmpty()) {
                        Badge::award($uid, 'popular_proposition', $prop);
                    }
                }
            }
        }
    ],
    'notable_proposition' => [
        'type' => 'silver',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where([['proposer_id', $uid], ['views', '>=', 75]])->get();
            if ($props->isNotEmpty()) {
                foreach ($props as $prop) {
                    if (Badge::where([
                        ['from_type', 'App\Proposition'],
                        ['from_id', $prop->id],
                        ['type', 'notable_proposition']
                    ])->get()->isEmpty()) {
                        Badge::award($uid, 'notable_proposition', $prop);
                    }
                }
            }
        }
    ],
    'famous_proposition' => [
        'type' => 'gold',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where([['proposer_id', $uid], ['views', '>=', 150]])->get();
            if ($props->isNotEmpty()) {
                foreach ($props as $prop) {
                    if (Badge::where([
                        ['from_type', 'App\Proposition'],
                        ['from_id', $prop->id],
                        ['type', 'famous_proposition']
                    ])->get()->isEmpty()) {
                        Badge::award($uid, 'famous_proposition', $prop);
                    }
                }
            }
        }
    ],

    'tumbleweed' => [
        'type' => 'bronze',
        'once' => true,
        'scheduledTask' => function(int $uid) {

        }
    ],

    'good_idea' => [
        'type' => 'silver',
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            $marker = $propositionFactory->getMarker($prop->id);
            return $marker->relationMarkerId() == Marker::UNDER_DISCUSSION;
        }
    ],
    'great_idea' => [
        'type' => 'gold',
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            $marker = $propositionFactory->getMarker($prop->id);
            return $marker->relationMarkerId() == Marker::SUCCESS;
        }
    ],

    'tenacious' => [
        'type' => 'bronze',
        'once' => true,
        'scheduledTask' => function(int $uid) {
            $matching = Proposition::where([['proposer_id', $uid], ['created_at', '<', Carbon::now()->subWeek()]])
                ->get()
                ->filter(function(Proposition $prop) {
                    return $prop->votes->isEmpty() && $prop->comments->isEmpty();
                });
            if (count($matching) >= 3) {
                Badge::award($uid, 'tenacious');
            }
        }
    ],
    'tireless' => [
        'type' => 'silver',
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            $marker = $propositionFactory->getMarker($prop->id);
            return $marker->relationMarkerId() == Marker::UNDER_DISCUSSION
                && $prop->votes->isEmpty()
                && $prop->comments->isEmpty();
        }
    ],
    'resolute' => [
        'type' => 'gold',
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            $marker = $propositionFactory->getMarker($prop->id);
            return $marker->relationMarkerId() == Marker::SUCCESS
                && $prop->votes->isEmpty()
                && $prop->comments->isEmpty();
        }
    ],

    'publicist' => [
        'type' => 'bronze',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where('proposer_id', $uid);
            foreach ($props as $prop) {
                if (Badge::where([
                    ['from_type', 'App\Proposition'],
                    ['from_id', $prop->id],
                    ['type', 'publicist']
                ])->get()->isEmpty()) {
                    $uniques = UniquePageView::where('proposition_id', $prop->id)->count();
                    if ($uniques >= 25) {
                        Badge::award($uid, 'publicist', $prop);
                    }
                }
            }
        }
    ],
    'socialite' => [
        'type' => 'silver',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where('proposer_id', $uid)->get();
            foreach ($props as $prop) {
                if (Badge::where([
                    ['from_type', 'App\Proposition'],
                    ['from_id', $prop->id],
                    ['type', 'socialite']
                ])->get()->isEmpty()) {
                    $uniques = UniquePageView::where('proposition_id', $prop->id)->count();
                    if ($uniques >= 40) {
                        Badge::award($uid, 'socialite', $prop);
                    }
                }
            }
        }
    ],
    'activist' => [
        'type' => 'gold',
        'scheduledTask' => function(int $uid) {
            $props = Proposition::where('proposer_id', $uid)->get();
            foreach ($props as $prop) {
                if (Badge::where([
                    ['from_type', 'App\Proposition'],
                    ['from_id', $prop->id],
                    ['type', 'activist']
                ])->get()->isEmpty()) {
                    $uniques = UniquePageView::where('proposition_id', $prop->id)->count();
                    if ($uniques >= 100) {
                        Badge::award($uid, 'activist', $prop);
                    }
                }
            }
        }
    ],

    'supported' => [
        'type' => 'bronze',
        'once' => true,
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            return $propositionFactory->getUpvotes($prop->id) === 1;
        }
    ],
    'encouraged' => [
        'type' => 'silver',
        'once' => true,
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            return $propositionFactory->getUpvotes($prop->id) === 3;
        }
    ],
    'empowered' => [
        'type' => 'gold',
        'once' => true,
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            return $propositionFactory->getUpvotes($prop->id) === 10;
        }
    ],

    'well_received' => [
        'type' => 'silver',
        'once' => true,
        'check' => function(int $uid, $prop) use ($propositionFactory) {
            return $propositionFactory->getUpvotes($prop->id) === 7 && $propositionFactory->getDownvotes($prop->id) === 0;
        }
    ],

    'enthusiast' => [
        'type' => 'silver',
        'once' => true,
        'scheduledTask' => function(int $uid) {
            $key = 'pageviews:' . $uid;
            $count = Redis::scard($key);
            $user = User::find($uid);
            if ($count >= 3) {
                $user->increment('fanatic_count');
                if ($user->fanatic_count == 30) {
                    Badge::award($uid, 'enthusiast');
                }
            } else {
                $user->update(['fanatic_count', 0]);
            }
            Redis::del($key);
        }
    ],
    'fanatic' => [
        'type' => 'gold',
        'once' => true,
        'scheduledTask' => function(int $uid) {
            $user = User::find($uid);
            if ($user->fanatic_count == 100) {
                Badge::award($uid, 'fanatic');
            }
        }
    ],

    'getting_the_hang_of_it' => [
        'type' => 'bronze',
        'once' => true,
        'scheduledTask' => function(int $uid) use ($userFactory) {
            if ($userFactory->getPointsForId($uid) >= 50) {
                Badge::tryAward($uid, 'getting_the_hang_of_it');
            }
        }
    ],
    'really_getting_into_it' => [
        'type' => 'silver',
        'once' => true,
        'scheduledTask' => function(int $uid) use ($userFactory) {
            if ($userFactory->getPointsForId($uid) >= 150) {
                Badge::tryAward($uid, 'really_getting_into_it');
            }
        }
    ],
    'totally_into_it' => [
        'type' => 'gold',
        'once' => true,
        'scheduledTask' => function(int $uid) use ($userFactory) {
            if ($userFactory->getPointsForId($uid) >= 500) {
                Badge::tryAward($uid, 'totally_into_it');
            }
        }
    ],

    'helpful' => [
        'type' => 'silver'
    ],
    'supportive' => [
        'type' => 'gold'
    ],

    'helped_make_this_thing' => [
        'type' => 'silver'
    ],
    'literally_made_this_thing' => [
        'type' => 'gold'
    ]

];