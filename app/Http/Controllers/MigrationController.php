<?php

namespace App\Http\Controllers;

use App\Badge;
use App\Role;
use App\User;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class MigrationController extends Controller
{
    public function init() {
        return response()->view('migrate.init');
    }

    public function email(Request $request) {
        $user = User::where('email', $request->input('email'))->first();
        if (!isset($user) or !Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with(['errors' => ['emailOrPassword']]);
        } else {
            app('session')->put('migrate_user_id', $user->id);
            Session::save();
            return response()->view('migrate.step1', ['firstName' => $user->firstName]);
        }
    }

    public function facebook() {
        return Socialite::driver('facebook')->scopes(['public_profile', 'email'])->redirectUrl(route('migrate.facebook.redirect'))->redirect();
    }

    public function facebookRedirect(Request $request) {
        if ($fbUser = Socialite::driver('facebook')->user()) {
            $user = User::first([
                'email' => $fbUser->email,
            ]);
            if (!isset($user)) {
                return view('migrate.error', ['error' => 'socialite_fuckup.facebook_no_user']);
            }
            app('session')->put('migrate_user_id', $user->id);
            Session::save();
            return response()->view('migrate.step1', ['firstName' => $user->firstName]);
        } else {
            return view('migrate.error', ['error' => 'socialite_fuckup.facebook']);
        }
    }


    public function o365() {
        return Socialite::driver('graph')->scopes(['User.Read'])->redirectUrl(env('GRAPH_REDIRECT_URI_MIGRATE'))->redirect();
    }

    public function finish() {
        if ($graphUser = Socialite::driver('graph')->redirectUrl(env('GRAPH_REDIRECT_URI_MIGRATE'))->stateless()->user()) {

            if (User::where('msgraphId', $graphUser->id)->count() !== 0) {
                return view('migrate.error', ['error' => 'account_already_migrated']);
            }

            $user = User::findOrFail(session('migrate_user_id'));

            if (isset($user->msgraphId)) {
                return view('migrate.error', ['error' => 'account_already_migrated']);
            }

            $tenant = null;
            if (preg_match('/.+\([A-Z]{3}-S[1-7][A-Z]+\)/', $graphUser->displayName)) {
                if (config('app.multitenant')) {
                    if (preg_match('/.+\(LUX-/', $graphUser->displayName)) {
                        $tenant = \App\Tenant::where('prefix', 'kirch')->first();
                    } else if (preg_match('/.+\(MAM-/', $graphUser->displayName)) {
                        $tenant = \App\Tenant::where('prefix', 'mam')->first();
                    }
                    Landlord::addTenant($tenant);
                }

            } else {
                return abort(500, 'No tenant. This isn\'t supposed to happen.');
            }
            $user->setMsgraphId($graphUser->id);
            $user->setDisplayName($graphUser->givenName . ' ' . $graphUser->surname);
            $user->setfirstName($graphUser->givenName);
            $user->setlastName($graphUser->surname);
            if (!isset($user->avatar)) {
                $user->setAvatar('https://www.gravatar.com/avatar/' . md5(strtolower(trim($graphUser->email))). '?d=identicon');
            }
            $user->setBelongsToSchool(true);
            $user->attachRole(Role::find(1));
            if (config('app.multitenant')) {
                $user->tenant_id = $tenant->id;
            }
            $user->save();
            Badge::tryAward($user->id, "veteran");

            Auth::login($user);

            return view('migrate.finish');
        } else {
            return view('migrate.error', ['error' => 'socialite_fuckup.graph']);
        }
    }
}
