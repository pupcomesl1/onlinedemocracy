<?php

namespace App;

use CommentFlags;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use \App\User;
use \App\Comments;
use \App\Like;
use Illuminate\Database\Query\JoinClause;
use PhpParser\Comment;

class CommentFactory extends Model {
	
	public function getComment($id) {
		return Comments::find($id);
	}
	
	public function deleteComment($id) {
		return Comments::destroy($id);
	}

	public function moderatorDeleteComment($user, $id) {
	    $comment = Comments::find($id);
	    $comment->deleted_by = $user;
	    return $comment->save();
    }

    public function moderatorUndeleteComment($id) {
        $comment = Comments::find($id);
        $comment->deleted_by = null;
        return $comment->save();
    }
	
	public function createComment($userId, $id, $body) {
		
		return Comments::create([
				"commenter_id" => $userId,
				"proposition_id" => $id,
				"body" => $body,
		]);
	}
	
	public function findLikeById($likeId) {
		return Like::find($likeId);
	}
	
	public function getLikesByComment(Comments $comment) {
		return Like::where('comment_id','=',$comment->id())->orderBy('updated_at', 'DESC')->get();
	}
	
	public function findLikeByUserAndComment(User $user, Comments $comment) {
		return Like::where('comment_id','=',$comment->id())->where('user_id','=',$user->userId())->get()->first();
	}
	
	public function getNumberOfLikes(Comments $comment) {
		return Like::where('comment_id','=',$comment->id())->count();
	}
	
	public function userHasLiked(Comments $comment, User $user) {
		return Like::where('comment_id','=',$comment->id())->where('user_id','=',$user->userId())->count();
	}
	
	// Like comment
	public function likeComment(User $user, Comments $comment) {
		return Like::firstOrCreate([
				"user_id" => $user->userId(),
				"comment_id" => $comment->id(),
		]);
	}
	
	// Remove like
	public function removeLike(Like $like) {
		return Like::destroy($like->likeId());
	}

	public function distinguishComment(Comments $comment, Role $distinguish) {
	    $comment->distinguish = $distinguish->id;
	    return $comment->save();
    }

    public function undistinguishComment(Comments $comment) {
	    $comment->distinguish = null;
	    return $comment->save();
    }

    public function flagComment(Comments $comment, User $user) {
	    return $comment->flags()->create([
	        'flagger' => $user->id
        ]);
    }

    public function getFlaggedCommentsExceptUsers($uid, $include_dimissed = false) {
	    if ($include_dimissed) {
	        $base = CommentFlag::where('flagger', '!=', $uid);
        } else {
	        $base = CommentFlag::where('dismissed', '!=', true)
                ->where('flagger', '!=', $uid);
        }
	    return $base
            ->groupBy('comment_id')
            ->join('comments', function(JoinClause $join) use ($uid) {
                $join->on('comment_flags.comment_id', '=', 'comments.id')
                     ->where('comments.commenter_id', '!=', $uid)
                     ->where('comments.deleted_by', '=', null);
            })
            ->get();
    }

    public function getFlagCountForComment($id, $includeDismissed = false) {
	    if ($includeDismissed) {
            return CommentFlag::where('comment_id', '=', $id)->count();
        } else {
            return CommentFlag::where([['dismissed', '!=', true], ['comment_id', '=', $id]])->count();
        }
    }

    public function dismissCommentFlags($id) {
	    return CommentFlag::where('comment_id', $id)
            ->update(['dismissed' => true]);
    }
	
}