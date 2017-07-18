<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
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
		$user = Auth::user();
		$propositionFactory = new PropositionFactory();
		 
		$viewUser = [
				'displayName' => $user->displayName(),
				'firstName' => $user->firstName(),
				'lastName' => $user->lastName(),
				'contactEmail' => $user->contactEmail(),
				'email' => $user->email(),
				'avatar' => $user->avatar(),
				'belongsToSchool' => $user->belongsToSchool(),
				'schoolEmail' => $user->googleEmail(),
				'roles' => $user->roles(),
				'propositionsCount' => $propositionFactory->getPropositionsCountByUser($user->userId()),
		];
		 
		return view('feedback', ['displayName' => $user->displayName(), 'user' => $viewUser]);
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
				$message->from('dd-feedback@pupilscom-esl1.eu', 'DirectDemocracy')->to(env('FEEDBACK_EMAIL'))->subject('Feedback Submission');
			});
		
			if ($request->ajax()){
				return trans('messages.feedback.thanks');
			}
			
			return redirect()->back()->with('status', trans('messages.feedback.thanks'));
			 
		}
	}
	
}
