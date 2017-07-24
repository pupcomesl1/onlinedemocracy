<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;

use App\Proposition;
use \App\Votes;
use \App\User;
use \App\Comments;
use \App\Flags;
use \App\Marker;
use Carbon\Carbon;

class PropositionFactory extends Model {
	
	public function getAllPropositions() {
		return Proposition::all()->orderBy('created_at', 'desc')->get();
	}
	
	public function getAcceptedPropositions() {
		return Proposition::whereStatus(1)->orderBy('created_at', 'desc')->get();
	}
	
	public function getAcceptedPropositionsExeptExpired() {
		return Proposition::whereStatus(1)->where('deadline', '>=', Carbon::today()->toDateString())->orderBy('created_at', 'desc')->get();
	}
	
	public function getAcceptedPropositionsOnlyExpired() {
		return Proposition::whereStatus(1)->where('deadline', '<=', Carbon::today()->toDateString())->orderBy('created_at', 'desc')->get();
	}
	
	public function deleteProposition($id) {
		return Proposition::destroy($id);
	}
	
	public function search($term, $pagination) {
		$query = Proposition::select(['propositions.*'])
        ->join('users', 'users.id', '=', 'propositions.proposer_id')
		->where(function($query) use ($term) {
		    $query->orwhere('propositions.propositionLong', 'LIKE', "%$term%");
			$query->orwhere('propositions.propositionSort', 'LIKE', "%$term%");
    		$query->orWhere(DB::raw("CONCAT(`users`.`firstName`, ' ', `users`.`lastName`)"), 'LIKE', "%$term%");
		})
		->where('propositions.status', '=', 1)
    	->orderBy('propositions.created_at', 'desc')
		->paginate($pagination);
		
		return $query;
	}
	
	public function getQueuedPropositions() {
		return Proposition::whereStatus(2)
            ->orderBy('deadline', 'asc')
            ->get();
	}
	public function getQueuedPropositionsExeptUsers($id) {
		return Proposition::whereStatus(2)
            ->whereNotIn('proposer_id', [$id])
            ->orderBy('deadline', 'asc')
            ->get();
	}
	public function getQueuedPropositionsExceptUsersCount($id) {
		return Proposition::whereStatus(2)
            ->whereNotIn('proposer_id', [$id])
            ->orderBy('deadline', 'asc')
            ->count();
	}
	
	public function getFlaggedPropositionsExeptUsers($id) {
		return Flags::where('status', '<', 3)
            ->whereNotIn('proposer_id', [$id])
            ->groupBy('proposition')
            ->join('propositions', 'flags.proposition', '=', 'propositions.id')
            ->get();
	}
	public function getFlagCount($id) {
		return Flags::where('proposition', [$id])->count();
	}
	public function getFlagTypeCount($id, $type) {
		return Flags::where('proposition', [$id])
            ->where('type', $type)
            ->count();
	}
	public function getGlobalFlagCount($id) {
		return Flags::where('status', '<', 3)
            ->join('propositions', 'flags.proposition', '=', 'propositions.id')
            ->whereNotIn('proposer_id', [$id])
            ->count();
	}
	
	
	public function getPropositionsByUser($userId) {
		return Proposition::whereProposerId($userId)->orderBy('created_at', 'desc')->get();
	}
	
	public function getPropositionsCountByUser($userId) {
		return Proposition::whereProposerId($userId)->count();
	}
	
	public function getProposition($id): ?Proposition {
		return Proposition::find($id);
	}
	
	public function getPropositionsByTagId($tagId) {
		return Proposition::join('propositions_tags', 'propositions.id', '=', 'propositions_tags.proposition_id')
		->join('tags', 'propositions_tags.tag_id', '=', 'tags.id')
		->where('tags.id', '=', $tagId);
	}
	
	public function addTagtoProposition(Tags $tag, Proposition $proposition) {
		DB::table('propositions_tags')->insert([
			'tag_id' => $tag->id(), 
			'proposition_id' => $proposition->id()
		]);
	}
	
	public function getUserVoteStatus($id, $userId) {
		$proposition = Proposition::find($id);
		$user = User::find($userId);
		
		$userHasVoted = false;
		
		if ($user->belongsToSchool() == true) {
			if (Votes::wherePropositionIdAndVoteUser($proposition->id(), $userId)->count() != 0) {
				$userHasVoted = true;
			}
		}
		
		return $userHasVoted;
	}
	
	public function upvote($id, $userId, $schoolEmail) {
		
		return Votes::create([
				"proposition_id" => $id,
				"vote_user" => $userId,
				"vote_value" => 1,
		]);
	}
	
	public function getUpvotes($id) {
		$proposition = Proposition::find($id);
		$upvotes = 0;
	
		$upvotes = Votes::wherePropositionIdAndVoteValue($proposition->id(), 1)->get();
	
		$upvotesSum = 0;
	
		foreach ($upvotes as $upvote) {
			$upvotesSum ++;
		}
	
		return $upvotesSum;
	}
	
	public function downvote($id, $userId) {
	
		return Votes::create([
				"proposition_id" => $id,
				"vote_user" => $userId,
				"vote_value" => 0,
		]);
	}
	
	public function getDownvotes($id) {
		$proposition = Proposition::find($id);
		$downvotes = 0;
		
		$downvotes = Votes::wherePropositionIdAndVoteValue($proposition->id(), 0)->get();
		
		$downvotesSum = 0;
		
		foreach ($downvotes as $downvote) {
			$downvotesSum ++;
		}
		
		return $downvotesSum;
	}

    public function unvote($id, $userId) {
        return Votes::where(['proposition_id' => $id, 'vote_user' => $userId])->delete();
    }
	
	public function getComments($id) {
		return Comments::wherePropositionId($id)->orderBy('created_at', 'desc')->get();
	}
	
	public function getCommentsCount($id) {
		return Comments::wherePropositionId($id)->count();
	}
	
	public function flag($type, $id) {
		return Flags::create([
				"type" => $type,
				"proposition" => $id,
		]);
	}
	
	public function createMarker($type, $body, $id) {
		return Marker::create([
				"marker_id" => $type,
				"marker_text" => $body,
				"proposition_id" => $id,
		]);
	}
	
	public function getMarker($id) {
		return Marker::wherePropositionId($id)->get()->first();
	}
	
}