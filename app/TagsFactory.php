<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use App\Proposition;
use \App\Votes;
use \App\User;
use \App\Comments;
use \App\Flags;
use \App\Marker;
use \App\Tags;

class TagsFactory {
	
	public function findOrCreate($tagString) {
		return Tags::firstOrCreate(['content' => $tagString]);
	}
	
	
	public function getTagByString($tagString) {
		return Tags::where('content', '=', $tagString)->first();
	}
	
	public function getAllTags() {
		return Tags::orderBy('created_at', 'desc')->get();
	}
	
	public function searchTag($term) {
		if (isset($term)) {
			$results = Tags::where('content', 'LIKE', "%$term%")->get();
		} else {
			$results = $this->getAllTags();
		}
		return $results;
	}
	
	public function getTagsById($id) {
		 return Tags::join('propositions_tags', 'propositions_tags.tag_id', '=', 'tags.id')
		 	->join('propositions', 'propositions.id', '=', 'propositions_tags.proposition_id')
			->where('propositions.id', '=', $id)->get();
	}
	
	public function getPropositionsByTagId($tagId) {
		return Proposition::join('propositions_tags', 'propositions_tags.proposition_id', '=', 'propositions.id')
		->join('tags', 'tags.id', '=', 'propositions_tags.tag_id')
		->where('tags.id', '=', $tagId)->get();
	}
	
	public function getAproovedPropositionsByTagId($tagId) {
		return Proposition::join('propositions_tags', 'propositions_tags.proposition_id', '=', 'propositions.id')
		->join('tags', 'tags.id', '=', 'propositions_tags.tag_id')
		->where('tags.id', '=', $tagId)->where('propositions.status', '=', 1)->get();
	}
	
	public function getFlaggedPropositionsExeptUsers($id) {
		return Flags::where('status', '<', 3)->whereNotIn('proposer_id', [$id])->groupBy('proposition')->join('propositions', 'flags.proposition', '=', 'propositions.id')->get();
	}
	public function getFlagCount($id) {
		return Flags::where('proposition', [$id])->count();
	}
	public function getFlagTypeCount($id, $type) {
		return Flags::where('proposition', [$id])->where('type', $type)->count();
	}
	
	
	public function getPropositionsByUser($userId) {
		return Proposition::whereProposerId($userId)->orderBy('created_at', 'desc')->get();
	}
	
	public function getPropositionsCountByUser($userId) {
		return Proposition::whereProposerId($userId)->count();
	}
	
	//Shouldn't be here move to PropositionFactory
	public function getProposition($id) {
		return Proposition::find($id);
	}
	
	public function getUserVoteStatus($id, $userId) {
		$proposition = Proposition::find($id);
		$user = User::find($userId);
		
		$userHasVoted = false;
		
		if ($user->belongsToSchool() == true) {
			if (Votes::whereIdAndVoteSchoolEmail($proposition->id(), $user->googleEmail())->count() == 1) {
				$userHasVoted = true;
			}
		}
		
		return $userHasVoted;
	}
	
	public function getUpvotes($id) {
		$proposition = Proposition::find($id);
		$upvotes = 0;
	
		$upvotes = Votes::whereIdAndVoteValue($proposition->id(), 1)->get();
	
		$upvotesSum = 0;
	
		foreach ($upvotes as $upvote) {
			$upvotesSum ++;
		}
	
		return $upvotesSum;
	}
	
	public function getDownvotes($id) {
		$proposition = Proposition::find($id);
		$downvotes = 0;
		
		$downvotes = Votes::whereIdAndVoteValue($proposition->id(), 0)->get();
		
		$downvotesSum = 0;
		
		foreach ($downvotes as $downvote) {
			$downvotesSum ++;
		}
		
		return $downvotesSum;
	}
	
	public function getComments($id) {
		return Comments::whereId($id)->orderBy('created_at', 'desc')->get();
	}
	
	public function getCommentsCount($id) {
		return Comments::whereId($id)->count();
	}
	
	public function getMarker($id) {
		return Marker::whereId($id)->get()->first();
	}
	
}