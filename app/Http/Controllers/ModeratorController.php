<?php

namespace App\Http\Controllers;

use App\Badge;
use App\CommentFactory;
use App\Mail\PropositionApproved;
use App\PointsHistoryEntry;
use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Chencha\Share\ShareFacade as Share;

use Mail;

use App\User;
use App\UserFactory;
use App\Proposition;
use App\PropositionFactory;
use App\Votes;
use App\Comments;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ModeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        if (Auth::check()) {
			\App::setLocale(Auth::user()->language());
		}
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->can(['approveOrBlockPropositions', 'handleFlags'])) {
            abort(404);
        }

        $propositionsFactory = new PropositionFactory();


        $viewPropositions = array();
        foreach ($propositionsFactory->getQueuedPropositionsExeptUsers($user->userId()) as $proposition) {
            $viewPropositions[$proposition->id()] = [
                'id' => $proposition->id(),
                'propositionSort' => $proposition->propositionSort(),
                'propositionLong' => $proposition->propositionLong(),
                'proposer' => $proposition->proposerId(),
                'propositionCreationDate' => Carbon::createFromTimestamp(strtotime($proposition->date_created()))->diffForHumans(),
                'userHasVoted' => $propositionsFactory->getUserVoteStatus($proposition->id(), $user->userId()),
                'deadline' => $proposition->deadline(),
                'statusId' => $proposition->status(),
                'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),
            ];
        }

        return view('moderator.approval_new', ['propositions' => $viewPropositions]);
    }


    public function approve(Request $request)
    {
        $id = $request->id;
        $user = Auth::user();
        if (!$user->can('approveOrBlockPropositions')) {
            abort(403, 'Unauthorized action.');
        }

        $propositionsFactory = new PropositionFactory();
        $proposition = $propositionsFactory->getProposition($id);

        $proposition->setStatus(Proposition::ACCEPTED);
        $proposition->save();

        \App::setLocale($proposition->proposer()->language());

        Log::debug($proposition->proposer()->email());

        Mail::to($proposition->proposer()->email())
            ->send(new PropositionApproved($proposition));

        PointsHistoryEntry::add($proposition->proposer->id, 25, 'prop_post', $proposition);
        Badge::tryAward($proposition->proposer->id, 'contributor', $proposition);
        Badge::tryAward($proposition->proposer->id, 'innovator', $proposition);
        Badge::tryAward($proposition->proposer->id, 'inventor', $proposition);

        return redirect()->route('moderator.approval', tenantParams());
    }

    public function block(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('approveOrBlockPropositions')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|max:120',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            abort(403, $validator->errors()->first('reason'));
        } else {
            $id = $request->input('id');
            $reason = $request->input('reason');

            $propositionsFactory = new PropositionFactory();
            $proposition = $propositionsFactory->getProposition($id);

            $proposition->setStatus(Proposition::BLOCKED);
            $proposition->setBlockReason($reason);
            $proposition->save();

            return redirect()->back();
        }

    }

    public function handle_flags()
    {
        $user = Auth::user();
        if (!$user->can('handleFlags')) {
            abort(404);
        }

        $propositionsFactory = new PropositionFactory();

        $viewPropositions = array();
        foreach ($propositionsFactory->getFlaggedPropositionsExeptUsers($user->userId()) as $flag) {

            $proposition = $propositionsFactory->getProposition($flag->id());

            $viewPropositions[$proposition->id()] = [
                'id' => $proposition->id(),
                'propositionSort' => $proposition->propositionSort(),
                'propositionLong' => $proposition->propositionLong(),
                'proposer' => $proposition->proposerId(),
                'propositionCreationDate' => Carbon::createFromTimestamp(strtotime($proposition->date_created()))->diffForHumans(),
                'userHasVoted' => $propositionsFactory->getUserVoteStatus($proposition->id(), $user->userId()),
                'deadline' => $proposition->deadline(),
                'statusId' => $proposition->status(),
                'ending_in' => Carbon::now()->diffInDays(Carbon::createFromTimestamp(strtotime($proposition->deadline())), false),

                'flagsCount' => $propositionsFactory->getFlagCount($proposition->id()),
                'offensiveCount' => $propositionsFactory->getFlagTypeCount($proposition->id(), 1),
                'inappropriateCount' => $propositionsFactory->getFlagTypeCount($proposition->id(), 2),
                'incomprehensibleCount' => $propositionsFactory->getFlagTypeCount($proposition->id(), 3),
            ];
        }

        return view('moderator.flags', ['propositions' => $viewPropositions]);
    }

    public function handle_comment_flags(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('handleFlags')) {
            abort(404);
        }

        $include_dismissed = $request->input('include_dismissed') == '1';

        $propositionsFactory = new PropositionFactory();

        $factory = new \App\CommentFactory();
        $userFactory = new \App\UserFactory();
        $flags = $factory->getFlaggedCommentsExceptUsers($user->id, $include_dismissed);
        $viewComments = [];

        foreach ($flags as $flag) {
            $comment = $flag->comment;
//            $commenter = $userFactory->getUser($comment->commenterId());
            $viewComments[$comment->id] = [
                'id' => $comment->id,
                'body' => $comment->body,
                'commenter' => [
                    'displayName' => $comment->commenter->displayName,
                ],
                'flagCount' => [
                    'total' => $factory->getFlagCountForComment($comment->id, true),
                    'nonDismissed' => $factory->getFlagCountForComment($comment->id, false),
                ],
                'proposition' => [
                    'title' => $comment->proposition->propositionSort,
                    'id' => $comment->proposition->id,
                ]
            ];
        }

        return view('moderator.comment-flags', [
            'displayName' => $user->displayName(),
            'flags' => $viewComments,
            'includeDismissed' => $include_dismissed,
        ]);

    }

    public function dismiss_comment_flags(Request $request) {
        $user = Auth::user();
        if (!$user->can('approveOrBlockPropositions')) {
            abort(403, 'Unauthorized action.');
        }

        $id = $request->input('id');
        $factory = new CommentFactory();
        $factory->dismissCommentFlags($id);

        return redirect()->back();
    }
}
