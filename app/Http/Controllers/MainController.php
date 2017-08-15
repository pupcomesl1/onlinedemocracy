<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\UserFactory;
use App\Proposition;
use App\PropositionFactory;
use Illuminate\Support\Facades\Lang;

class MainController extends Controller
{
	
	public function home() {
		if (Auth::check()) { 
			return redirectToUserTenant();
		} else {
			return view('guest.home');
		}
		
	}
	public function terms() {
		return view('guest.terms');
	}
    public function guidelines() {
        return view('guest.comment-guidelines');
    }

	public function tenant() {
		return redirectToUserTenant();
	}
	
	public function feedback() {

		\App::setLocale(Auth::user()->language());
		 
		return view('feedback');
	}
	
	public function feedback_send(Request $request) {
		
		$validator = Validator::make($request->all(), [
				'feedback' => 'required',
		]);
		 
		if ($validator->fails()) {
			 
			if ($request->ajax()){
				return response()->json($validator->errors(), 404);
			}
			
			return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
			 
		} else {
			
			$user = Auth::user();
			 
			\Mail::send('emails.feedback', ['feedback' => $request->input('feedback'), 'user' => ['displayName' => $user->displayName(), 'email' => $user->email(), 'id' => $user->userId()]], function($message)
			{
				$message->from('dd-feedback@pupilscom-esl1.eu', 'DirectDemocracy')->to(config('app.feedback_email'))->subject('Feedback Submission');
			});
		
			if ($request->ajax()){
				return trans('messages.feedback.thanks');
			}
			
			return redirect()->back()->with('status', trans('messages.feedback.thanks'));
			 
		}
	}

	public function badge($tenant, $name) {
	    $badge = getBadges()[$name];

	    $userCount = User::all()->count();
	    $badgeCount = DB::table('users')
            ->join('badges', 'users.id', '=', 'badges.user_id')
            ->where('badges.type', $name)
            ->count();
	    $percentage = ($badgeCount / $userCount) * 100;

	    return view('badge', ['badge' => $badge, 'percentage' => $percentage, 'count' => $badgeCount, 'key' => $name, 'name' => trans('badges.' . $name . '.name')]);
    }
	
}
