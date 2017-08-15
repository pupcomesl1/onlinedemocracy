<?php

namespace App\Http\ViewComposers;

use App\PropositionFactory;
use App\UserFactory;
use Illuminate\View\View;

class AuthBaseComposer {

    protected $userFactory;
    protected $propositionFactory;

    public function __construct()
    {
        $this->userFactory = new UserFactory();
        $this->propositionFactory = new PropositionFactory();
    }

    public function compose(View $view) {
        if (\Auth::check()) {
            $user = \Auth::user();

            $factory = new UserFactory();
            $data = $factory->buildLeaderboard();

            $with = [
                'user' => [
                    'id' => $user->id,
                    'displayName' => $user->displayName(),
                    'email' => $user->email(),
                    'avatar' => $user->avatar(),
                    'belongsToSchool' => $user->belongsToSchool(),
                    'points' => $this->userFactory->getPointsFor($user),
                    'propositionsCount' => $this->propositionFactory->getPropositionsCountByUser($user->userId()),
                    'lang' => $user->language(),
                ],
                'displayName' => $user->displayName(),
                'leaderboardData' => $data
            ];
            $view->with(array_merge($with, $view->getData()));
        }
    }
}