@extends('layouts_new.guestBase')

@section('title', Lang::get('messages.session.sign_up.sign_up'))


@section('content')

	<div class="container" id="login">
		
		<div class="row" style="margin-top:110px">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default">
					<div class="panel-heading"><h1 class="panel-title"><strong><center><a href="{{ route('home') }}">DirectDemocracy.online</a></center></strong></h1></div>
					<div class="panel-body">
						<form role="form" action="{{ route('registrate') }}" method="POST">
							<div class="form-group @if ($errors->has('first_name')) has-error @endif @if ($errors->has('last_name')) has-error @endif">
								@if ($errors->has('last_name')) <small class="text-danger pull-right"> {{ $errors->first('last_name') }}</small>@endif
								@if ($errors->has('first_name')) <small class="text-danger pull-right">{{ $errors->first('first_name') }} </small>@endif
								<label for="first_name">@lang('messages.session.sign_up.name')</label>
								<div class="row">
									<div class="col-md-6"><input type="text" name="first_name" class="form-control" id="first_name" placeholder="@lang('messages.session.sign_up.first_placeholder')" value="{{ old('first_name') }}"></div>
									<div class="col-md-6"><input type="text" name="last_name" class="col-md-6 form-control" id="last_name" placeholder="@lang('messages.session.sign_up.last_placeholder')" value="{{ old('last_name') }}"></div>
								</div>
							</div>
							
							<div class="form-group @if ($errors->has('email')) has-error @endif @if ($errors->has('password_confirmation')) has-error @endif">
								@if ($errors->has('email')) <small class="text-danger pull-right">{{ $errors->first('email') }}</small>@endif
								<label for="email">@lang('messages.session.sign_up.email')</label>
								<input type="email" name="email" class="form-control" id="email" placeholder="@lang('messages.session.sign_up.email_placeholder')" value="{{ old('email') }}">
							</div>
							
							<div class="form-group @if ($errors->has('password')) has-error @endif">
								@if ($errors->has('password_confirmation')) <small class="text-danger pull-right">{{ $errors->first('password_confirmation') }}</small>@endif
								@if ($errors->has('password')) <small class="text-danger pull-right">{{ $errors->first('password') }}</small>@endif
								<label for="password">@lang('messages.session.sign_up.password')</label>
								<div class="row">
								
									<div class="col-md-6"><input type="password" name="password" class="form-control" id="password" placeholder="@lang('messages.session.sign_up.password_placeholder')" value="{{ old('password') }}"></div>
									<div class="col-md-6"><input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="@lang('messages.session.sign_up.password_confirm')" value="{{ old('password_confirmation') }}"></div>
								</div>
								
							</div>
							
							{!! csrf_field() !!}
							<p class="text-center"><small class="text-muted">@lang('messages.session.sign_up.accept_terms')}} <a href="{{ route('terms') }}" target="_blank">{{Lang::get('messages.website.terms')</a></small></p>
							
							
							<button type="submit" class="btn btn-default btn-block">@lang('messages.session.sign_up.sign_up')</button>
							<a href="{{ route('auth.getSocialAuth', ['provider' => 'facebook']) }}" class="btn btn-primary btn-block">@lang('messages.session.sign_up.use_fb')</a>
							
							<a class="btn btn-block btn-link" href="{{ route('login') }}">@lang('messages.session.return_to_login')</a>
						</form>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	
	<div id="background-particles"></div>
@stop

@section('footer_scripts')
<script src="{{ asset('js/jquery.particleground.min.js') }}"></script>
<script>
$('#background-particles').particleground({
		directionX: 'center',
		directionY: 'up',
		minSpeedX: 0.1,
        maxSpeedX: 0.1,
        minSpeedY: 0.1,
        maxSpeedY: 0.1,
        density: 10000,
        proximity: 100,
		lineWidth: 0.4,
		particleRadius: 3.5,
		parallax: false,
});
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72457439-1', 'auto');
  ga('send', 'pageview');

</script>
@stop