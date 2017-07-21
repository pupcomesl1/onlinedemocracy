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
use Chencha\Share\ShareFacade as Share;

use App\User;
use App\UserFactory;
use App\Proposition;
use App\PropositionFactory;
use App\TagsFactory;
use App\Votes;
use App\Comments;
use App\CommentFactory;
use App\Flags;
use App\Marker;
use App\Tags;
use App\Like;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PropositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    	];

    	$propositionFactory = new PropositionFactory();
    	$viewPropositions = array();
    	$endingSoonPropositions = array();
    	$votedPropositions = array();



    	foreach ($propositionFactory->getAcceptedPropositionsExeptExpired() as $proposition) {

    		$userHasVoted = $propositionFactory->getUserVoteStatus($proposition->id(), $user->userId());
    		$daysLeft = Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false);

    		if (($daysLeft <= 5) AND ($daysLeft >= 0)) {
    			//Ending soon (5 days left)
    			$endingSoonPropositions[$proposition->id()] = [
    					'id' => $proposition->id(),
    					'propositionSort' => $proposition->propositionSort(),
    					'proposer' => $proposition->proposerId(),
    					'propositionCreationDate' => $proposition->date_created(),
    					'userHasVoted' => $userHasVoted,
    					'deadline' => $proposition->deadline(),
    					'statusId' => $proposition->status(),
    					'ending_in' => $daysLeft,
    					'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    					'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    					'comments' => $propositionFactory->getCommentsCount($proposition->id()),
    					'marker' => $propositionFactory->getMarker($proposition->id()),
    			];

    		} elseif (($userHasVoted == true) AND ($daysLeft > 0)) {
    			//Voted propositions
    			$votedPropositions[$proposition->id()] = [
    					'id' => $proposition->id(),
    					'propositionSort' => $proposition->propositionSort(),
    					'proposer' => $proposition->proposerId(),
    					'propositionCreationDate' => $proposition->date_created(),
    					'userHasVoted' => $userHasVoted,
    					'deadline' => $proposition->deadline(),
    					'statusId' => $proposition->status(),
    					'ending_in' => $daysLeft,
    					'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    					'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    					'comments' => $propositionFactory->getCommentsCount($proposition->id()),
    					'marker' => $propositionFactory->getMarker($proposition->id()),
    			];

    		} else {
    			$viewPropositions[$proposition->id()] = [
    					'id' => $proposition->id(),
    					'propositionSort' => $proposition->propositionSort(),
    					'proposer' => $proposition->proposerId(),
    					'propositionCreationDate' => $proposition->date_created(),
    					'userHasVoted' => $userHasVoted,
    					'deadline' => $proposition->deadline(),
    					'statusId' => $proposition->status(),
    					'ending_in' => $daysLeft,
    					'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    					'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    					'comments' => $propositionFactory->getCommentsCount($proposition->id()),
    					'marker' => $propositionFactory->getMarker($proposition->id()),
    			];
    		}




    	}


		$modAlerts = array(
			"approval" => $user->can('approveOrBlockPropositions') AND $propositionFactory->getQueuedPropositionsExceptUsersCount($user->userId()) > 0,
			"flag" => $user->can('handleFlags') AND $propositionFactory->getGlobalFlagCount($user->userId()) > 0
		);

    	return view('propositions_new', ['displayName' => $user->displayName(), 'user' => $viewUser, 'propositions' => $viewPropositions, 'endingSoonPropositions' => $endingSoonPropositions, 'votedPropositions' => $votedPropositions, 'modAlerts' => $modAlerts]);
    }

    public function archived()
    {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    	];

    	$propositionFactory = new PropositionFactory();
    	$expiredPropositions = array();

    	foreach ($propositionFactory->getAcceptedPropositionsOnlyExpired() as $proposition) {

    		$expiredPropositions[$proposition->id()] = [
    				'id' => $proposition->id(),
    				'propositionSort' => $proposition->propositionSort(),
    				'proposer' => $proposition->proposerId(),
    				'propositionCreationDate' => $proposition->date_created(),
    				'deadline' => $proposition->deadline(),
    				'statusId' => $proposition->status(),
    				'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),
    				'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    				'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    				'comments' => $propositionFactory->getCommentsCount($proposition->id()),
    				'marker' => $propositionFactory->getMarker($proposition->id()),
    		];

    	}

    	return view('archived', ['displayName' => $user->displayName(), 'user' => $viewUser, 'expiredPropositions' => $expiredPropositions]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if (!$user->can('postPropositions')) {
            abort(403, 'Unauthorised action.');
        }

    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    	];

    	return view('create_proposition_new', ['displayName' => $user->displayName(), 'user' => $viewUser]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$validator = Validator::make($request->all(), [
    			'proposition' => 'required|max:140',
    			'proposition_description' => 'min:10',
    			'deadline' => 'required|between:1,3',
    	]);

    	if ($validator->fails()) {

    		return redirect()->back()->withInput($request->all())->withErrors($validator->errors());

    	} else {

    		if ($user->belongsToSchool() == true && $user->can('postPropositions')) {

    			$deadlineId = $request->input('deadline');	// Deadlines: 1=2weeks, 2=1month, 3=2months

    			switch ($deadlineId) {
    				case 1:
    					$deadline = Carbon::now()->addWeeks(2)->toDateTimeString();
    					break;
    				case 2:
    					$deadline = Carbon::now()->addMonth()->toDateTimeString();
    					break;
    				case 3:
    					$deadline = Carbon::now()->addMonths(2)->toDateTimeString();
    					break;
    			}

    			$proposition = Proposition::create([
    					"proposer_id" => $user->userId(),
    					"propositionSort" => $request->input('proposition'),
    					"propositionLong" => $request->input('proposition_description'),
    					"deadline" => $deadline,
    			]);

    			// Register new tags
    			preg_match_all("/#([a-zA-Z0-9_]+)/", $request->input('proposition') . " " . $request->input('proposition_description'), $matches);
    			foreach(array_unique($matches[1]) as $tagString)
    			{
    				$tag = with(new TagsFactory())->findOrCreate($tagString);
    				$proposition->addTag($tag);
    			}

    			return redirect()->route('profile.propositions', tenantParams())->with('status', trans('messages.profile.create_proposition.success'));

    		} else {
    			abort(403, trans('messages.unauthorized'));
    		}

    	}
    }


    public function update(Request $request)
    {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$propositionFactory = new PropositionFactory();

    	$proposition = $propositionFactory->getProposition($request->get('id'));

    	if ($proposition->status() == 2) {

    		$validator = Validator::make($request->all(), [
    				'proposition' => 'required|max:140',
    				'description' => 'min:10',
    		]);

    		if ($validator->fails()) {

    			return $validator->errors();

    		} else {

    			if ($user->belongsToSchool() == true) {

    				// Register new tags
    				preg_match_all("/#([a-zA-Z0-9_]+)/", $request->input('proposition') . " " . $request->input('description'), $matches);
    				foreach(array_unique($matches[1]) as $tagString)
    				{
    					$tag = with(new TagsFactory())->findOrCreate($tagString);
    					$proposition->addTag($tag);
    				}

    				$proposition->setPropositionSort($request->input('proposition'));
    				$proposition->setPropositionLong($request->input('description'));

    				$proposition->save();

    				return 'success';

    			} else {
    				abort(403, trans('messages.unauthorized'));
    			}

    		}

    	} else {
    		return trans('messages.unauthorized');
    	}
    }

    public function delete($tenant, $id)
    {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$proposition = with(new PropositionFactory())->getProposition($id);

    	if (
            (
                ($proposition !== null)
                and ($proposition->proposerId() == $user->userId())
                and (($proposition->status() == 2)
                or ($proposition->status() == 3)
                or (Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false) < 0))
            )
            and $user->can('deleteOwnPropositions')
        ) {

    			if (with(new PropositionFactory())->deleteProposition($id) == true) {

    				return redirect()->route('profile.propositions', tenantParams())->with('status', trans('messages.profile.propositions.success_deleting'));
    			} else {

    				return redirect()->route('profile.propositions', tenantParams())->with('error', trans('messages.profile.propositions.error_deleting'));
    			}

    	} else {
    		abort(403);
    	}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tenant, $id)
    {

    	$propositionFactory = new PropositionFactory();
    	$userFactory = new UserFactory();
        $user = Auth::user();

    	$proposition = $propositionFactory->getProposition($id);

    	if ($proposition == null) {
    		abort(404);
    	}

    	if ($proposition->status() !== Proposition::ACCEPTED) {
    		abort(404);
    	}

    	$proposer = $userFactory->getUser($proposition->proposerId());

    	$viewProposition = [
    			'id' => $proposition->id(),
    			'proposer' => [
    					'id' => $proposition->proposerId(),
    					'displayName' => $proposer->displayName,
    					'avatar' => $proposer->avatar(),
    			],
    			'propositionSort' => $proposition->propositionSort(),
    			'propositionLong' => $proposition->propositionLong(),
    			'date_created' => Carbon::createFromTimestamp(strtotime($proposition->date_created()))->diffForHumans(),
    			'deadline' => $proposition->deadline(),
    			'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),
    			'marker' => $propositionFactory->getMarker($proposition->id()),
    	];

    	$viewVotes = [
    			'upvotes' => $propositionFactory->getUpvotes($id),
    			'downvotes' => $propositionFactory->getDownvotes($id),
    	];

    	$viewShareLinks = Share::load(route('proposition', tenantParams([$viewProposition['id']])), $viewProposition['propositionSort'])->services();

    	$viewComments = array();

        foreach ($propositionFactory->getComments($id) as $comment) {

    		$commentUser = $userFactory->getUser($comment->commenterId());

    		if ($user and $user->can('distinguishAllComments')) {
    		    $canDistinguish = \App\Role::where('name', '!=', 'user')->get();
            } else if ($user and $user->can('distinguishSameRoleComments')) {
    		    $canDistinguish = $user->roles()->get()->intersect($commentUser->roles)->where('name', '!=', 'user')->all();
            } else {
    		    $canDistinguish = null;
            }

    		$viewComments[$comment->id()] = [
    				'id' => $comment->id(),
    				'commentBody' => $comment->body(),
    				'commenter' => [
    						'id' => $commentUser->userId(),
    						'displayName' => $commentUser->displayName,
    						'avatar' => $commentUser->avatar(),
                            'userCanDelete' => $commentUser->can('deleteOwnComments'),
    				],
    				'likes' => $comment->likes(),
    				'people_likes' => null,
    				'userHasLiked' => null,
    				'date_created' => Carbon::createFromTimestamp(strtotime($comment->created_at()))->diffForHumans(),
                    'modDeleted' => $comment->deletedBy() !== null,
                    'distinguish' => \App\Role::find($comment->distinguish()),
                    'userCanDistinguish' => $canDistinguish,
                    'userCanFlag' =>
                        $user
                        and $user->can('flag')
                        and $user->id != $comment['commenter']['id']
                        and \App\CommentFlag::where(
                            [
                                ['flagger', '=', $user->id],
                                ['comment_id', '=', $comment->id]
                            ])->get()->isEmpty(),
    		];

    		if (Auth::check()) {
    			$viewComments[$comment->id()]['userHasLiked'] = with(new CommentFactory())->userHasLiked($comment, $user);
    			$viewComments[$comment->id()]['people_likes'] = with(new CommentFactory())->getLikesByComment($comment);
    		}

    	}

    	$viewProposition['commentsCount'] = count($viewComments);


    	$viewTags = array();
    	foreach (with(new TagsFactory())->getTagsById($proposition->id()) as $tag) {
    		$viewTags[$tag->id()] = $tag->content();
    	}

    	if (Auth::check()) {
			\App::setLocale($user->language());
	    	$user = $user;
	    	$viewUser = ['userId' => $user->userId(), 'displayName' => $user->displayName(),'firstName' => $user->firstName(),'lastName' => $user->lastName(),'contactEmail' => $user->contactEmail(),'email' => $user->email(),'avatar' => $user->avatar(),'belongsToSchool' => $user->belongsToSchool(),'schoolEmail' => $user->googleEmail(),];

	    	$viewVotes = [
	    			'upvotes' => $propositionFactory->getUpvotes($id),
	    			'downvotes' => $propositionFactory->getDownvotes($id),
	    			'userHasVoted' => $propositionFactory->getUserVoteStatus($id, $user->userId()),
	    	];

	    	return view('proposition_new', ['displayName' => $user->displayName(), 'user' => $viewUser, 'proposition' => $viewProposition, 'votes' => $viewVotes, 'comments' => $viewComments,'shareLinks' => $viewShareLinks, 'tags' => $viewTags]);
    	} else {
    		return view('proposition_public', ['proposition' => $viewProposition, 'votes' => $viewVotes, 'comments' => $viewComments,'shareLinks' => $viewShareLinks]);
    	}
    }

    public function comment(Request $request) {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if (!$user->can('comment')) {
            abort(403, 'Unauthorised action.');
        }

    	$validator = Validator::make($request->all(), [
    			'commentBody' => 'required',
    			'id' => 'required',
    	]);

    	if ($validator->fails()) {

    		abort(403, $validator->errors()->first('commentBody'));

    	} else {
    	    \Log::debug('Inside else statement: ' . $user->userId() . ', ' . $request->input('id'));
    	    $factory = new CommentFactory();
    	    $nc = $factory->createComment($user->userId(), $request->input('id'), $request->input('commentBody'));

    	    \Log::debug($nc);

            return redirect()->route('proposition', tenantParams(['id' => $request->input('id')]));
    	}
    }

    public function delete_comment($tenant, $id) {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$commentsFactory = new commentFactory();
    	$comment = $commentsFactory->getComment($id);

    	if ($comment->commenterId() == $user->userId() && $user->can('deleteOwnComments')) {
    		$commentsFactory->deleteComment($id);
    		return redirect()->back();
    	} else if ($user->can('deleteComments')) {
            $commentsFactory->moderatorDeleteComment($user->id, $id);
            return redirect()->back();
        } else {
    		abort(403, trans('messages.unauthorized'));
    	}
    }

    public function undelete_comment($tenant, $id) {
        \App::setLocale(Auth::user()->language());
        $user = Auth::user();

        $commentsFactory = new commentFactory();

        if ($user->can('deleteComments')) {
            $commentsFactory->moderatorUndeleteComment($id);
            return redirect()->back();
        } else {
            abort(403, trans('messages.unauthorized'));
        }
    }

    public function like_comment(Request $request) {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$validator = Validator::make($request->all(), [
    			'id' => 'required',
    	]);



    	if ($validator->fails()) {
    		abort(403, $validator->errors());
    	} else {

    		$commentsFactory = new CommentFactory();

    		$comment = $commentsFactory->getComment($request->input('id'));

    		return $commentsFactory->likeComment($user, $comment);
    	}
    }

    public function distinguish_comment(Request $request) {
        $comment = Comments::find($request->input('comment_id'));
        if ($request->input('distinguish') === 'nonenullnilch') {
            $distinguish = null;
        } else {
            $distinguish = \App\Role::where('name', '=', $request->input('distinguish'))->first();
        }
        $user = Auth::user();
        $userFactory = new UserFactory();
        $commenter = $userFactory->getUser($comment->commenterId());

        if (!(
            $user->can('distinguishAllComments')
            || (
                $user->can('distinguishSameRoleComments')
                && (
                    $distinguish == null
                    ? $user->roles()->get()->intersect($commenter->roles)->isNotEmpty()
                    : $user->roles()->get()->intersect($commenter->roles)->contains($distinguish)
                )
            )
        )) {
            return abort(403, 'Unauthorised Action.');
        } else {
            $commentsFactory = new CommentFactory();
            if ($distinguish == null) {
                $commentsFactory->undistinguishComment($comment);
            } else {
                $commentsFactory->distinguishComment($comment, $distinguish);
            }
            return redirect()->back();
        }
    }

    public function remove_like_comment(Request $request) {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	$validator = Validator::make($request->all(), [
    			'id' => 'required',
    	]);

    	if ($validator->fails()) {
    		abort(403, $validator->errors());
    	} else {

	    	$commentsFactory = new CommentFactory();

	    	$comment = $commentsFactory->getComment($request->input('id'));
	    	$like = $commentsFactory->findLikeByUserAndComment($user, $comment);

    		return $commentsFactory->removeLike($like);
    	}
    }

    public function comment_flag($tenant, $id) {
        \App::setLocale(Auth::user()->language());
        $factory = new CommentFactory();
        $comment = $factory->getComment($id);
        if ($comment == null) {
            return abort(404);
        }
        $factory->flagComment($comment, Auth::user());
        return redirect()->back()->with('status', trans('messages.proposition.comments.flagged'));
    }


    public function upvote($tenant, $id) {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if ($user->belongsToSchool() == true) {
	    	$propositionFactory = new PropositionFactory();

            if (
                Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($propositionFactory->getProposition($id)->deadline())), false) <= 0
                or (!$user->can('vote'))
            ) {
	    		abort(403, trans('messages.unauthorized'));
	    	}


	    	if ($propositionFactory->getUserVoteStatus($id, $user->userId()) == false) {

	    		$propositionFactory->upvote($id, $user->userId(), $user->googleEmail());

	    		return redirect()->route('proposition', tenantParams(['id' => $id]));
	    	} else {
	    		abort(403, trans('messages.unauthorized'));
	    	}
    	} else {
    		abort(403, trans('messages.unauthorized'));
    	}
    }

    public function downvote($tenant, $id) {
		\App::setLocale(Auth::user()->language());
    	$user = Auth::user();
    	$propositionFactory = new PropositionFactory();

    	if (
    	    Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($propositionFactory->getProposition($id)->deadline())), false) <= 0
            or (!$user->can('vote'))
        ) {
    		abort(403, trans('messages.unauthorized'));
    	}

    	if ($propositionFactory->getUserVoteStatus($id, $user->userId()) == false) {

    		$propositionFactory->downvote($id, $user->userId());

    		return redirect()->route('proposition', tenantParams(['id' => $id]));
    	} else {
    		abort(403, trans('messages.unauthorized'));
    	}
    }

    public function unvote($tenant, $id) {
        \App::setLocale(Auth::user()->language());
        $user = Auth::user();
        $propositionFactory = new PropositionFactory();
		\Debugbar::info(
			Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($propositionFactory->getProposition($id)->deadline())), false) <= 0
            or (!$user->can('vote'))
		);

        if (
            Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($propositionFactory->getProposition($id)->deadline())), false) <= 0
            or (!$user->can('vote'))
        ) {
            abort(403, trans('messages.unauthorized') . ' (1)');
        }

		\Debugbar::info([$id, $propositionFactory->getUserVoteStatus($id, $user->userId())]);
        if ($propositionFactory->getUserVoteStatus($id, $user->userId())) {

            $propositionFactory->unvote($id, $user->userId());

            return redirect()->route('proposition', tenantParams(['id' => $id]));
        } else {
            abort(403, trans('messages.unauthorized') . ' (2)');
        }
    }

    /**
     * Flag a proposition as offensive/incomprehensible.
     *
     * @param  int  $id
     * @param  int  $flag_type
     * @return \Illuminate\Http\Response
     */
    public function flag($tenant, $id, $flag_type) {
		\App::setLocale(Auth::user()->language());
        if ($flag_type == 1 OR $flag_type == 3) {
        	with(new PropositionFactory())->flag($flag_type, $id);
        	return redirect()->back()->with('status', trans('messages.proposition.flagged'));
        } else {
        	return abort(404);
        }
    }

    /**
     * Create mark for proposition.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_marker($tenant, $id, Request $request) {

    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if ($user->can('setPropositionMarkers')) {
	    	$validator = Validator::make($request->all(), [
	    			'type' => 'required|min:1|max:3',
	    			'message' => 'max:240',
	    	]);

	    	if ($validator->fails()) {

				return $validator->errors();

	    	} else {

	    		with(new PropositionFactory())->createMarker($request->input('type'), $request->input('message'), $id);
				return 'success';

	    	}
    	} else {
    		return trans('messages.unauthorized');
    	}

    }

    public function edit_marker($tenant, $id, Request $request) {

    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if ($user->can('setPropositionMarkers')) {
	    	$validator = Validator::make($request->all(), [
	    			'type' => 'required|min:1|max:3',
	    			'message' => 'max:240',
	    	]);

	    	if ($validator->fails()) {

	    		return $validator->errors();

	    	} else {

	    		$marker = with(new PropositionFactory)->getMarker($id);
	    		$marker->setRelationMarkerId($request->input('type'));
	    		$marker->setMarkerText($request->input('message'));
	    		$marker->save();

	    		return 'success';

	    	}
	    } else {
	   		return trans('messages.unauthorized');
	   	}

    }

    public function delete_marker($tenant, $id) {

    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();

    	if ($user->can('setPropositionMarkers')) {

	    	$marker = with(new PropositionFactory)->getMarker($id);
	    	$marker->delete();

	    	return redirect()->back();

	    } else {
	    	abort(403, trans('messages.unauthorized'));
	    }
    }

    public function search(Request $request)
    {
    	\App::setLocale(Auth::user()->language());
    	$user = Auth::user();
    	$viewUser = [
    			'displayName' => $user->displayName(),
    			'firstName' => $user->firstName(),
    			'lastName' => $user->lastName(),
    			'contactEmail' => $user->contactEmail(),
    			'email' => $user->email(),
    			'avatar' => $user->avatar(),
    			'belongsToSchool' => $user->belongsToSchool(),
    			'schoolEmail' => $user->googleEmail(),
    	];

    	$results = array();
    	$pages = null;

    	if (empty($request->input('q')) == false) {
    		$term = $request->input('q');

    		$propositionFactory = new PropositionFactory();

    		$proposition_results = $propositionFactory->search($term, 5);

    		$pages = $proposition_results->lastPage();

    		foreach ($proposition_results->items() as $proposition) {

    			$proposer = with(new userFactory)->getUser($proposition->proposerId());

    			$results[$proposition->id()] = [
    					'id' => $proposition->id(),
    					'propositionSort' => $proposition->propositionSort(),
    					'proposer' => [
    							'id' => $proposition->proposerId(),
    							'displayName' => $proposer->displayName(),
    							'avatar' => $proposer->avatar(),
    					],
    					'propositionCreationDate' => $proposition->date_created(),
    					'deadline' => $proposition->deadline(),
    					'statusId' => $proposition->status(),
    					'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),
    					'upvotes' => $propositionFactory->getUpvotes($proposition->id()),
    					'downvotes' => $propositionFactory->getDownvotes($proposition->id()),
    					'comments' => $propositionFactory->getCommentsCount($proposition->id()),
    					'marker' => $propositionFactory->getMarker($proposition->id()),
    			];
    		}
    	}

    	return view('search', ['displayName' => $user->displayName(), 'user' => $viewUser, 'results' => $results, 'pages' => $pages]);
    }

}
