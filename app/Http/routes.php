<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Website routes
Route::get('/', ['as' => 'home', 'uses' => 'MainController@home']);
Route::get('/terms', ['as' => 'terms', 'uses' => 'MainController@terms']);
Route::get('/comment-guidelines', ['as' => 'guidelines', 'uses' => 'MainController@guidelines']);

//Session routes
Route::get('/login', ['as' => 'login', 'uses' => 'SessionController@index']);
Route::post('/login', ['as' => 'authenticate', 'uses' => 'SessionController@login']);
Route::get('/register', ['as' => 'register', 'uses' => 'SessionController@create']);
Route::post('/register', ['as' => 'registrate', 'uses' => 'SessionController@registrate']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'SessionController@logout']);

Route::get('/secretmoderatorlogin', 'SessionController@loginAsTestmod');

//Social Login
Route::get('/login/{provider?}',['uses' => 'SessionController@getSocialAuth','as'   => 'auth.getSocialAuth']);
Route::get('/login/callback/{provider?}',['uses' => 'SessionController@getSocialAuthCallback','as'   => 'auth.getSocialAuthCallback']);

//Msgraph
Route::get('/o365login', ['as' => 'o365login', 'uses' => 'SessionController@msgraphLogin']);

//Profile-related routes
Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
	Route::get('/profile', ['as' => 'profile.main', 'uses' => 'ProfileController@index']);
	Route::post('/profile', ['as' => 'profile.main.update', 'uses' => 'ProfileController@update']);
	Route::get('/profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	Route::post('/profile/password', ['as' => 'profile.password.update', 'uses' => 'ProfileController@password_update']);
	Route::get('/propositions', ['as' => 'profile.propositions', 'uses' => 'ProfileController@propositions']);
	Route::get('/propositions/create', ['as' => 'profile.propositions.create', 'uses' => 'PropositionsController@create']);
	Route::post('/propositions/create', ['as' => 'profile.propositions.store', 'uses' => 'PropositionsController@store']);
	Route::get('/profile/language', ['as' => 'profile.language', 'uses' => 'ProfileController@language']);
	
	Route::get('/profile/language/{code}', ['as' => 'profile.language.set', 'uses' => 'ProfileController@setLanguage']);
});

Route::get('/feedback', ['middleware' => 'auth', 'as' => 'feedback', 'uses' => 'MainController@feedback']);
Route::post('/feedback', ['middleware' => 'auth', 'as' => 'feedback.send', 'uses' => 'MainController@feedback_send']);

//School link routes
Route::get('/profile/link',['middleware' => 'auth','uses' => 'ProfileController@getLinkAuth','as'   => 'getLinkAuth']);
Route::get('/profile/link/callback',['middleware' => 'auth','uses' => 'ProfileController@getLinkAuthCallback','as'   => 'getLinkAuthCallback']);
Route::get('/profile/unlink',['middleware' => 'auth','uses' => 'ProfileController@unlinkGoogle','as'   => 'unlinkGoogle']);

//Propositions routes
Route::get('/home', ['middleware' => 'auth','as' => 'propositions','uses' => 'PropositionsController@index']);
Route::get('/search', ['middleware' => 'auth','as' => 'search','uses' => 'PropositionsController@search']);
Route::get('/archived', ['middleware' => 'auth','as' => 'archived','uses' => 'PropositionsController@archived']);
Route::get('/proposition/{id}', ['as' => 'proposition','uses' => 'PropositionsController@show']);

Route::post('/proposition/update', ['as' => 'proposition.update','uses' => 'PropositionsController@update']);
Route::get('/proposition/delete/{id}', ['as' => 'proposition.delete','uses' => 'PropositionsController@delete']);

Route::post('/proposition/comment', ['middleware' => 'auth', 'as' => 'comment', 'uses' => 'PropositionsController@comment']);
Route::post('/proposition/comment/like', ['middleware' => 'auth', 'as' => 'comment.like', 'uses' => 'PropositionsController@like_comment']);
Route::post('/proposition/comment/remove_like', ['middleware' => 'auth', 'as' => 'comment.remove_like', 'uses' => 'PropositionsController@remove_like_comment']);
Route::get('/proposition/comment/{id}/flag/', ['middleware' => 'auth', 'as' => 'comment.flag', 'uses' => 'PropositionsController@comment_flag']);
Route::get('/proposition/comment/delete/{id}', ['middleware' => 'auth', 'as' => 'comment.delete', 'uses' => 'PropositionsController@delete_comment']);
Route::get('/proposition/comment/undelete/{id}', ['middleware' => 'auth', 'as' => 'comment.undelete', 'uses' => 'PropositionsController@undelete_comment']);
Route::post('/proposition/comment/distinguish', ['middleware' => 'auth', 'as' => 'distinguish', 'uses' => 'PropositionsController@distinguish_comment']);
Route::get('/proposition/{id}/upvote', ['middleware' => 'auth', 'as' => 'upvote', 'uses' => 'PropositionsController@upvote']);
Route::get('/proposition/{id}/downvote', ['middleware' => 'auth', 'as' => 'downvote', 'uses' => 'PropositionsController@downvote']);
Route::get('/proposition/{id}/unvote', ['middleware' => 'auth', 'as' => 'unvote', 'uses' => 'PropositionsController@unvote']);
Route::get('/proposition/{id}/flag/{flag_type}', ['middleware' => 'auth', 'as' => 'flag', 'uses' => 'PropositionsController@flag']);

Route::post('/proposition/{id}/marker/create', ['middleware' => 'auth', 'as' => 'marker.create', 'uses' => 'PropositionsController@create_marker']);
Route::post('/proposition/{id}/marker/edit', ['middleware' => 'auth', 'as' => 'marker.edit', 'uses' => 'PropositionsController@edit_marker']);
Route::get('/proposition/{id}/marker/delete', ['middleware' => 'auth', 'as' => 'marker.delete', 'uses' => 'PropositionsController@delete_marker']);

//Moderator routes
Route::group(['prefix' => 'moderator', 'middleware' => ['auth', 'role:moderator']], function () {
	Route::get('/approval', ['as' => 'moderator.approval', 'uses' => 'ModeratorController@index']);
	Route::get('/flags', ['as' => 'moderator.handle_flags', 'uses' => 'ModeratorController@handle_flags']);
	Route::get('/comment_flags', ['as' => 'moderator.handle_comment_flags', 'uses' => 'ModeratorController@handle_comment_flags']);
	Route::get('/approval/approve/{id}', ['as' => 'moderator.approve', 'uses' => 'ModeratorController@approve']);
	Route::post('/approval/block', ['as' => 'moderator.block', 'uses' => 'ModeratorController@block']);
	Route::post('/comment_flags/dismiss', ['as' => 'moderator.comment_flags.dismiss', 'uses' => 'ModeratorController@dismiss_comment_flags']);
});

// API routes (return JSON data)
Route::group(['prefix' => 'api', 'middleware' => 'auth'], function () {
	Route::get('tag_search', ['as' => 'api.tag_search', 'uses' => 'ApiController@tag_search']);
	Route::get('proposition', ['as' => 'api.proposition', 'uses' => 'ApiController@proposition']);
});

