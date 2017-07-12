@extends('layouts_new.profileBase')

@section('title', $displayName)

@section('form')

<form class="form-horizontal" id="account_form" method="POST" action="{{ route('profile.main.update') }}">

	<div class="form-group form-group-sm @if ($errors->has('first') or $errors->has('last')) has-error @endif">
		<label for="name" class="col-sm-2 control-label">{{ Lang::get('messages.profile.account.name') }}</label>
		<div class="col-sm-10">
			<div class="row">
				<span class="col-sm-12 col-xs-12">
					<input type="text" class="form-control" id="displayName" name="displayName" placeholder="Display name" value="{{ old('displayName') ? old('displayName') : $user['displayName'] }}">
					@if ($errors->has('displayName')) <small class="text-danger">{{ $errors->first('displayName') }}</small>@endif
				</span>
			</div>
				  
		</div>
	</div>
              
	<div class="form-group form-group-sm @if ($errors->has('email')) has-error @endif">
		<label for="email" class="col-sm-2 control-label">{{ Lang::get('messages.profile.account.email') }}</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="email" placeholder="{{ Lang::get('messages.profile.account.email') }}" value="{{ old('email') ? old('email') : $user['email'] }}" disabled>
		</div>
	</div>
              
	<br/>

	<div class="form-group form-group-sm @if ($errors->has('language')) has-error @endif @if (isset($highlight['lang']) == true) focused @endif">
		<label for="inputPassword3" class="col-sm-2 control-label">{{ Lang::get('messages.profile.account.language') }}</label>
		<div class="col-sm-10">
			<select class="form-control" name="lang">
				<option value="en" @if ($user['lang'] == 'en') selected @endif >@lang('messages.languages.en')</option>
				<option value="fr" @if ($user['lang'] == 'fr') selected @endif >@lang('messages.languages.fr')</option>
			</select>
		</div>
	</div>
              
	<br/>
	
    {{ csrf_field() }}
       
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary">{{ Lang::get('messages.profile.account.save') }}</button>
		</div>
	</div>
</form>
@stop