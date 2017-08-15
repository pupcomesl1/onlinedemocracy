<?php

namespace App\Http\Controllers;

use App\User;
use App\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index() {
        $factory = new UserFactory();
        $data = $factory->buildLeaderboard();

        return view('leaderboard', ['data' => $data]);
    }
}
