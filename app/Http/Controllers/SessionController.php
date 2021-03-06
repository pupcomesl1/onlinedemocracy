<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Socialite;

use App\User;
use App\UserFactory;
use Illuminate\Database\Eloquent\Model;

class SessionController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Show login screen
    	return view('session_new.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // Show sigup screen
    	return view('session_new.register');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $userFactory = new UserFactory();
        $user = $userFactory->getUser($userId);
        

        $userFactory = new UserFactory();
        return $userFactory->getUser(1);
    }
    
    public function registrate(Request $request) {
    	
    	$validator = Validator::make($request->all(), [
    			'first_name' => 'required',
    			'last_name' => 'required',
    			'email' => 'required|email|unique:users,email',
    			'password' => 'required|min:5|confirmed',
    	]);
    	
    	if ($validator->fails()) {
    		return redirect()->back()->withInput($request->except('password', 'password_confirm'))->withErrors($validator->errors());
    	} else {
    		
    		with(new UserFactory())->createUser($request->input("first_name"), $request->input("last_name"), $request->input("email"), null, $request->input("password"));
    		
    		return redirect()->route('login');
    	}
    	
    }
    
    public function login(Request $request) {
    	
    	$validator = Validator::make($request->all(), [
	        'email' => 'required|email',
	        'password' => 'required|min:5',
		]);
    	
    	if ($validator->fails()) {
    		
    		return redirect()->back()->withInput($request->except('password'))->withErrors($validator->errors());
    		
    	} else {
    		
    		$email = $request->input('email');
    		$password = $request->input('password');
    		$remember = ($request->input('remember')) ? true : false;
    		
    		if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
			    
    			$user = Auth::user();
    			return redirect()->intended('/');

			} else {
				return redirect()->back()->withInput($request->except('password'))->withErrors([
    				'password' => trans('messages.session.login.wrong_pass'),
    			]);
			}
    		
    	}
    }
    
    public function logout()
    {
    	Auth::logout();
    	return redirect()->route('home');
    }


    public function getSocialAuth($provider=null)
    {
    	if(!config("services.$provider")) abort('404'); //just to handle providers that doesn't exist

        return Socialite::driver($provider)->redirect();
    }

    public function msgraphLogin()
    {
        return Socialite::driver('graph')->scopes(['User.Read'])->redirect();
    }
    
    public function getSocialAuthCallback($provider=null)
    {

	    switch ($provider) {
            case 'graph':
                if ($graphUser = Socialite::driver($provider)->user()){

                        $user = User::where('msgraphId', '=', $graphUser->id)->first();
                        if ($user === null) {
                            $user = new User;
                            $user->setMsgraphId($graphUser->id);
                            $user->setDisplayName($graphUser->givenName . ' ' . $graphUser->surname);
                            $user->setfirstName($graphUser->givenName);
                            $user->setlastName($graphUser->surname);
                            $user->setEmail($graphUser->email);
                            if (preg_match('/.+\(LUX-S[1-7][A-Z]+\)/', $graphUser->displayName)) {
                                $user->setBelongsToSchool(true);
                                $user->attachRole(Role::find(1));
                            }
                            $user->save();
                        }

                        Auth::login($user, true);
                        return redirect()->intended('/');

                    } else {
                        return redirect()->home()->withErrors(['login' => trans('messages.profile.account.school_link_messages.not_valid_name')]);
                    }
                break;
		        
		    default:
		    	return redirect()->route('login')->withErrors([
		    			'social' => trans('messages.session.login.error'),
		    	]);
		}
    		
    	
    }
    
    public function landing()
    {
    	if (Auth::check()) {
    		return redirect()->route('propositions');
    	} else {
    		return redirect()->route('login');
    	}
    }

    public function loginAsTestmod() {
        if (\App::environment('development')) {
            $user = User::find(0);
            Auth::login($user, true);
            return redirect()->intended('/');
        } else {
            return abort(403, 'Unauthorised action.');
        }
    }
    
}
