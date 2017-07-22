<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use HipsterJazzbo\Landlord\BelongsToTenants;


class Comments extends Model implements AuthenticatableContract
{
    use Authenticatable, BelongsToTenants;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'commenter_id', 'proposition_id', 'body', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';

    public function commenter() {
        return $this->belongsTo('App\User');
    }

    public function proposition() {
        return $this->belongsTo('App\Proposition');
    }
    
    public function id () {
    	return $this->attributes['id'];
    }
    
    public function commenterId () {
    	return $this->attributes['commenter_id'];
    }
    
    public function propositionId() {
    	return $this->attributes['proposition_id'];
    }
    
    public function body () {
    	return $this->attributes['body'];
    }
    
    public function likes () {
		return with(new CommentFactory())->getNumberOfLikes($this);
    }
    
    public function created_at () {
    	return $this->attributes['created_at'];
    }

    public function deletedBy() {
        return $this->attributes['deleted_by'];
    }

    public function distinguish() {
        return $this->attributes['distinguish'];
    }

    public function flags() {
        return $this->hasMany('App\CommentFlag', 'comment_id', 'id');
    }
    
}
