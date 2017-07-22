<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Mail\Message;

use App\User;
use App\UserFactory;
use App\Proposition;
use App\PropositionFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProfileController extends Controller
{
	
	public function __construct(Socialite $socialite){
	    $this->socialite = $socialite;
	    if (Auth::check()) {
	        App::setLocale(Auth::user()->language());
	    }
	}
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = Auth::user();
    	$propositionFactory = new PropositionFactory();
    	$propositionsCount = $propositionFactory->getPropositionsCountByUser($user->userId());
    	
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    			'lang' => $user->language(),
    			'propositionsCount' => $propositionsCount,
    	];

		$highlight = array();
		$highlight[session('highlight')] = true;
    	
    	return view('account_new.profile', ['displayName' => $user->displayName(), 'user' => $viewUser, 'highlight' => $highlight]);
    }

    public function language()
    {
    	return redirect()->route('profile.main', tenantParams())->with('highlight', 'lang');
    }
    
    public function setLanguage($code) 
    {
    	$user = Auth::user();
    	$user->setLanguage($code);
    	$user->save();
    	
    	return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    			'displayName' => 'required',
    			'contact' => 'email',
    			'lang' => 'required',
    	]);
    	 
    	if ($validator->fails()) {
    		 
    		return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
    		 
    	} else {

            $displayName = $request->input('displayName');
    		$contactemail = $request->input('contact');
    		$lang = $request->input('lang');
    	
    		$user = Auth::user();
    	
    		$user->setDisplayName($displayName);
    		$user->setContactEmail($contactemail);
    		$user->setLanguage($lang);
    	
    		$user->save();
    	
    		return redirect()->back();
    		 
    	}
    }
    
    public function propositions() {
    	$user = Auth::user();
	Carbon::setLocale($user->language());
    	$propositionFactory = new PropositionFactory();
    	$propositionsCount = $propositionFactory->getPropositionsCountByUser($user->userId());
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    			'propositionsCount' => $propositionsCount,
    	];
    	
    	$propositionFactory = new PropositionFactory();
    	$userPropositions = $propositionFactory->getPropositionsByUser($user->userId());
    	
    	$viewPropositions = array();
    	
    	foreach ($userPropositions as $proposition) {
    	
    		$viewPropositions[$proposition->id()] = [
    				'id' => $proposition->id(),
    				'propositionSort' => $proposition->propositionSort(),
    				'propositionLong' => $proposition->propositionLong(),
    				'propositionCreationDate' => Carbon::createFromTimestamp(strtotime($proposition->date_created()))->diffForHumans(),
    				'userHasVoted' => $propositionFactory->getUserVoteStatus($proposition->id(), $user->userId()),
    				'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    				'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    				'commentsCount' => $propositionFactory->getCommentsCount($proposition->id()),
    				'deadline' => $proposition->deadline(),
    				'statusId' => $proposition->status(),
    				'blockReason' => $proposition->blockReason(),
    				'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),
    				'marker' => $propositionFactory->getMarker($proposition->id()),
    		];
    	}
    	
    	return view('account_new.propositions', ['displayName' => $user->displayName(), 'user' => $viewUser, 'propositions' => $viewPropositions]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function getLinkAuth()
    {
    	return $this->socialite->with('google')->redirect();
    }
    
    public function getLinkAuthCallback()
    {
    	if($socialUser = $this->socialite->with('google')->user()){
    			
    		if (isset($socialUser->user['domain'])) {
    
    			$domain = $socialUser->user['domain'];
    			 
    			if ($domain === "eursc-mamer.lu") {
    
    				$id = $socialUser->id;
    				$email = $socialUser->email;
    				
    				$userFactory = new UserFactory();
    				
    				if ($userFactory->schoolEmailIsTaken($email) == true) {
    					return redirect()->route('profile.main')->withErrors(['linkState' => trans('messages.profile.account.school_link_messages.already_linked')]);
    				} else {
    					$user = Auth::user();
    						
    					$user->setGoogleId($id);
    					$user->setGoogleEmail($email);
    						
    					$user->setBelongsToSchool(true);
    						
    					$user->save();

    					return redirect()->route('profile.main');
    				}
    				
    			} else {
    				return redirect()->route('profile.main')->withErrors(['linkState' => trans('messages.profile.account.school_link_messages.not_valid_email')]);
    			}
    
    		} else {
    			return redirect()->route('profile.main')->withErrors(['linkState' => trans('messages.profile.account.school_link_messages.not_valid_email')]);
    		}
    	} else {
    		return redirect()->route('profile.main')->withErrors(['linkState' => trans('messages.profile.account.school_link_messages.error')]);
    	}
    }
    
    public function unlinkGoogle()
    {
    	$user = Auth::user();
    	$userFactory = new UserFactory();
    	
    	$userFactory->unlinkGoogleAccount($user->userId());
    	
    	return redirect()->route('profile.main')->withErrors(['linkState' => trans('messages.profile.account.school_link_messages.unlinked')]);
    }
    
    public function password()
    {
    	$user = Auth::user();
    	$propositionFactory = new PropositionFactory();
    	$propositionsCount = $propositionFactory->getPropositionsCountByUser($user->userId());
    	 
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    			'propositionsCount' => $propositionsCount,
    	];
    	 
    	return view('account_new.password', ['displayName' => $user->displayName(), 'user' => $viewUser]);
    }
    
    public function password_update(Request $request)
    {
    	$user = Auth::user();
    	$propositionFactory = new PropositionFactory();
    	
    	$validator = Validator::make($request->all(), [
    			'old_password' => 'required',
    			'new_password' => 'required|confirmed|min:5',
    	]);
    	
    	if ($validator->fails()) {
    		 
    		return redirect()->back()->withErrors($validator->errors());
    		 
    	} else {
    	
    		$user = Auth::user();
    		
    		$old_password = $request->input('old_password');
    		$new_password = $request->input('new_password');
    		if (Auth::attempt(['email' => $user->email(), 'password' => $old_password])) {
    			
    			$user->setPassword(\Hash::make($new_password));
    			$user->save();
    			 
    			
    		} else {
    			return redirect()->back()->withErrors([
    					'old_password' => trans('messages.profile.password.wrong'),
    			]);
    		}
    		
    		return redirect()->back()->with('status', trans('messages.profile.password.updated'));
    		 
    	}
    
    }
    
}
