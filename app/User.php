<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable, EntrustUserTrait, BelongsToTenants;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMINISTRATOR = 3;

    public function getAuthIdentifier()
    {
        return $this->attributes['id'];
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'email', 'password', 'firstName', 'lastName', 'contactEmail', 'avatar', 'facebookId', 'googleId', 'roleId', 'languageCode', 'googleEmail', 'belongsToSchool'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public function userId () {
    	return $this->attributes['id'];
    }
    
    public function firstName () {
    	return $this->attributes['firstName'];
    }
    
    public function setfirstName($firstName) {
    	return $this->attributes['firstName'] = $firstName;
    }
    
    public function lastName () {
    	return $this->attributes['lastName'];
    }
    
    public function setlastName($lastName) {
    	return $this->attributes['lastName'] = $lastName;
    }

    public function contactEmail () {
    	return $this->attributes['contactEmail'];
    }
    
    public function setcontactEmail($contactEmail) {
    	return $this->attributes['contactEmail'] = $contactEmail;
    }
    
    public function avatar () {
    	if ($this->attributes['avatar'] == null) {
    		return "https://www.gravatar.com/avatar/".md5( strtolower( trim( $this->email() ) ) )."?d=identicon&s=500";
    	} else {
    		return $this->attributes['avatar'];
    	}
    }
    
    public function setAvatar($avatar) {
    	return $this->attributes['avatar'] = $avatar;
    }

    public function msgraphId() {
        return $this->attributes['msgraphId'];
    }

    public function setMsgraphId($msgraphId) {
        return $this->attributes['msgraphId'] = $msgraphId;
    }

    public function displayName() {
        return $this->attributes['displayName'];
    }

    public function setDisplayName($displayName) {
        return $this->attributes['displayName'] = $displayName;
    }
    
    public function email () {
    	return $this->attributes['email'];
    }
    
    public function setEmail($email) {
    	return $this->attributes['email'] = $email;
    }
    
    public function setPassword ($hashedPassword) {
    	return $this->attributes['password'] = $hashedPassword;
    }

    public function googleId () {
    	return $this->attributes['facebookId'];
    }
    
    public function setGoogleId($googleId) {
    	return $this->attributes['googleId'] = $googleId;
    }
    
    public function googleEmail () {
    	return $this->attributes['googleEmail'];
    }
    
    public function setGoogleEmail($googleEmail) {
    	return $this->attributes['googleEmail'] = $googleEmail;
    }
    
    public function belongsToSchool () {
    	if ($this->attributes['belongsToSchool'] == 1) {
    		return true;
    	} else {
    		return false;
    	}
    }
    
    public function setBelongsToSchool($belongsToSchool) {
    	if ($belongsToSchool == true) {
    		return $this->attributes['belongsToSchool'] = 1;
    	} else {
	    	return $this->attributes['belongsToSchool'] = 0;
    	}
    }
    
    public function language() {
    	return $this->attributes['languageCode'];
    }
    public function setLanguage($langCode) {
    	return $this->attributes['languageCode'] = $langCode;
    }

    public function comments() {
        return $this->hasMany('App\Comments');
    }

    public function commentFlags() {
        return $this->hasMany('App\CommentFlag');
    }
    
}
