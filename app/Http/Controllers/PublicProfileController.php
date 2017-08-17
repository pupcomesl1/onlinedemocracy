<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    function show(Request $request) {
        $id = $request->route('id');
        $user = \Auth::user();
        $userFactory = new \App\UserFactory();
        $profile = $userFactory->getUser($id);
        if ($profile === null) {
            abort(404);
        }

        $propositionFactory = new \App\PropositionFactory();
        $props = $propositionFactory->getApprovedPropositionsByUser($profile->id);
        $propositionsCount = count($props);

        $badges = $profile->badges()->orderBy('created_at', 'desc')->get();

        $viewProfile = [
            'displayName' => $profile->displayName,
            'avatar' => $profile->avatar,
            'points' => $userFactory->getPointsFor($profile),
            'propositionsCount' => $propositionsCount,
            'propositions' => [],
            'badges' => [
                'gold' => $badges->filter(filterGold())->all(),
                'silver' => $badges->filter(filterSilver())->all(),
                'bronze' => $badges->filter(filterBronze())->all(),
            ],
        ];

        if ($props->isNotEmpty()) {
            foreach (array_slice(array_reverse($props->all()), 0, 5) as $prop) {
                $viewProfile['propositions'][] = $propositionFactory->makeForView($prop);
            }
        }

        return view('public_profile', ['display_name' => $user->displayName(), 'profile' => $viewProfile, 'badges' => getBadges()]);
    }
}
