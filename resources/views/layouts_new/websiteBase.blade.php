@extends('layouts_new.main')

@section('header_scripts')
<style>
	body {
		background: #3e3f3a;
	}
	.navbar .btn {
		color: #eee !important;
		padding: 10px;
		margin: 10px;
	}
	.navbar .btn-primary.btn:hover, .navbar .btn-primary.btn:active, .navbar .btn-primary.btn:focus {
		color: #fff !important;
		background: #4283C5 !important;
	}
	
	.navbar .btn-success.btn:hover, .navbar .btn-success.btn:active, .navbar .btn-success.btn:focus {
		color: #fff !important;
		background: #79a736 !important;
	}
	.background-header {
		background: #3e3f3a;
		display: block;
		width: 100%;
		padding-top: 90px;
		padding-bottom: 10px;
		color: #eee;
	}
	.background-header {
		overflow:visible;
		overflow-x: hidden;
	}
	.img-header {
		height: 500px;
		margin-left: -80px;
	}
	.padding-top {
		padding-top: 7%;
		padding-right: 50px;
	}
	.btn-secondary {
		background: #72736E;
	}
	.btn-secondary:hover, .btn-secondary:active, .btn-secondary:focus {
		background: #A0A09F !important;
	}
	.background-header .justify.hidden-xs {
		padding-bottom: 20px;
	}
		
	.background-header h1 {
		color: white;
		font-size: 50px;
	}
	.background-header .btn {
		margin-top: 10px;
	}
	.btn-lg {
		font-size: 16px;
	}
	.section {
		background: #F1F1EF;
	}
	.section .container {
		padding-top: 50px;
		padding-bottom: 30px;
	}
	.panel-body h2 {
		margin-top: 0;
	}
	.footer {
		padding-top: 20px;
		padding-bottom: 30px;
	}
	.footer select {
		background: #3E3F3A;
		border: none;
	}
	.footer select:focus, .footer select:active, .footer select:hover {
		outline: none;
	}
	.panel img {
		display: block;
		width: 60%;
		margin: auto;
		padding-bottom: 30px;
	}
	.section.terms h2 {
		padding-top: 20px;
		font-size: 25px;
	}
	.section.terms p {
		text-align:justify;
	}
	.section.terms .container {
	    padding-top: 80px;
    }
    
    /* Landscape phones and portrait tablets */
	@media (max-width: 767px) {
		.background-header h1 {
			text-align: center;
    		font-size: 45px;
		}
		.background-header .lead {
		    text-align: center;
		    font-size: 20px;
		    margin-top: 15px;
		}
		.padding-top {
			padding-right: 15px;
		}
		.background-header .justify.visible-xs {
			padding-top: 10px;
			padding-bottom: 20px;
		}
	}
	
	/* Portrait phones and smaller */
	@media (max-width: 480px) {
		
	}
</style>
@stop

@section('content_base')
	<div class="container-fluid" id="navigation">
    	<nav class="navbar navbar-default navbar-fixed-top">
      		<div class="container">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topFixedNavbar1"><span class="sr-only">{{ Lang::get('messages.navigation.nav_toggle') }}</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
	          <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset('img/logo.svg') }}" alt="DirectDemocracy logo">DirectDemocracy</a></div>
	        <!-- Collect the nav links, forms, and other content for toggling -->
	        <div class="collapse navbar-collapse" id="topFixedNavbar1">
	          
	          <ul class="nav navbar-nav navbar-right">
	          @if (Auth::check())
	            <li><a href="{{ route('propositions', ['tenant' => Auth::user()->tenant->prefix]) }}" class="btn btn-success">@lang('messages.website.return')</a></li>
	          @else
				<li><a href="{{ route('o365login') }}" class="btn btn-primary">@lang('messages.session.login.use_msgraph')</a></li>
	          @endif
	          </ul>
	        </div>
	        <!-- /.navbar-collapse -->
	      </div>
	      	<!-- /.container-fluid -->
    	</nav>
  	</div>

	@yield('content')
	<div class="footer">
  	<div class="container">
        <p><small class="text-muted"><a href="{{ route('terms') }}" class="text-muted">@lang('messages.website.terms')</a> | <a href="https://github.com/pupcomesl1/onlinedemocracy" target="_blank" class="text-muted">GitHub</a></small></p>
        <p><small class="text-muted">@lang('messages.website.footer')</small></p>
	</div>
  </div>
@stop()