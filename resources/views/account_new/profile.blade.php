@extends('layouts_new.profileBase')

@section('title', $displayName)

@section('form')

<form class="form-horizontal" id="account_form" method="POST" action="{{ tenantRoute('profile.main.update') }}">

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

<div class="row">
	<div class="col-md-9 col-md-offset-2">
		<h2>Badges</h2>
		<div class="row">
			<div class="col-md-4">
				<h3>Gold</h3>
				@foreach ($user['badges']['gold'] as $badge)
					@component('components.badge', ['type' => 'gold', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
						@lang('badges.' . $badge->type . '.name')
					@endcomponent
				@endforeach
			</div>
			<div class="col-md-4">
				<h3>Silver</h3>
				@foreach ($user['badges']['silver'] as $badge)
					@component('components.badge', ['type' => 'silver', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
						@lang('badges.' . $badge->type . '.name')
					@endcomponent
				@endforeach
			</div>
			<div class="col-md-4">
				<h3>Bronze</h3>
				@foreach ($user['badges']['bronze'] as $badge)
					@component('components.badge', ['type' => 'bronze', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
						@lang('badges.' . $badge->type . '.name')
					@endcomponent
				@endforeach
			</div>
		</div>
	</div>
</div>
@stop