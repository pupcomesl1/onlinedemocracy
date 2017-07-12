@extends('layouts_new.authBase')

@section('content')
  <style>
    @media (max-width: 767px) {
      body {
        padding-top: 50px;
      }
    }
  </style>

  <nav class="visible-xs navbar navbar-default navbar-secondary navbar-fixed-top " role="navigation">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('profile.main') }}"><img alt="profile picture of {{ $user['displayName'] }}"
                                                                        src="{{ $user['avatar'] }}"
                                                                        class="profile-picture-navbar img-circle"/> {{ $user['displayName'] }}
        </a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <div class="container">
          <ul class="nav navbar-nav">
            <li><a href="#" disabled="disabled"><strong>{{ Lang::get('messages.profile.menu.account') }}</strong></a>
            </li>
            <li class="@if(Route::current()->getName() == 'profile.main') active @endif"><a
                  href="{{ route('profile.main') }}">{{ Lang::get('messages.profile.menu.overview') }}</a></li>
            <li><a href="{{ route('profile.language') }}">{{ Lang::get('messages.profile.menu.language') }}</a></li>
            <li class="@if(Route::current()->getName() == 'profile.propositions') active @endif"><a
                  href="{{ route('profile.propositions') }}">{{ Lang::get('messages.profile.menu.propositions') }}</a>
            </li>

            @permission(['approveOrBlockPropositions', 'handleFlags'])
            <li><a href="#" disabled="disabled"><strong>{{ Lang::get('messages.moderator.menu.title') }}</strong></a>
            </li>
            @endpermission
            @permission('approveOrBlockPropositions')
            <li class="@if(Route::current()->getName() == 'moderator.approval') active @endif"><a
                  href="{{ route('moderator.approval') }}">{{ Lang::get('messages.moderator.menu.for_approval') }}</a>
            </li>
            @endpermission
            @permission('handleFlags')
            <li class="@if(Route::current()->getName() == 'moderator.handle_flags') active @endif"><a
                  href="{{ route('moderator.handle_flags') }}">{{ Lang::get('messages.moderator.menu.handle_flags') }}</a>
            </li>
            @endpermission

            <li><a href="#" disabled="disabled"><strong>{{ Lang::get('messages.profile.menu.contribute') }}</strong></a>
            </li>
            <li><a href="https://github.com/pupcomesl1/onlinedemocracy"
                   target="_blank">{{ Lang::get('messages.profile.menu.github') }} <i
                    class="fa fa-external-link"></i></a></li>
            <li class="@if(Route::current()->getName() == 'feedback') active @endif"><a
                  href="{{ route('feedback') }}">{{ Lang::get('messages.profile.menu.feedback') }}</a></li>

          </ul>
        </div>
      </div><!-- /.navbar-collapse --></div>
  </nav>

  <div class="container" id="account">
    <div class="row m-scene">

      <div class="col-md-3 text-center hidden-xs" id="profile_navigation">
        <img alt="profile picture of {{ $user['displayName'] }}" src="{{ $user['avatar'] }}"
             class="profile-picture img-circle"/>
            <br/>
            <small class="text-muted">
              <small>{{ Lang::choice('messages.profile.account.propositionsCount', $user['propositionsCount'], ['propositions' => $user['propositionsCount']]) }}</small>
            </small>
          </p>
          <br/>

          <!--             <div class="list-group account-settings"> -->
          <!--               <p class="list-group-item" data-toggle="collapse" data-target="#demo"><strong>Get started</strong></p> -->
          <!--               <a href="#" class="list-group-item" style="text-decoration: line-through;">Link your school account</a>-->
          <!--               <a href="#" class="list-group-item" style="text-decoration: line-through;">Set up your account</a>-->
          <!--               <a href="#" class="list-group-item">Make your first proposition</a> -->
          <!--             </div> -->

          <div class="list-group account-settings">
            <p class="list-group-item"><strong>{{ Lang::get('messages.profile.menu.account') }}</strong></p>
            <a href="{{ route('profile.main') }}"
               class="list-group-item @if(Route::current()->getName() == 'profile.main') active @endif">{{ Lang::get('messages.profile.menu.overview') }}</a>
            <a href="{{ route('profile.language') }}"
               class="list-group-item">{{ Lang::get('messages.profile.menu.language') }}</a>
            <a href="{{ route('profile.propositions') }}"
               class="list-group-item @if(Route::current()->getName() == 'profile.propositions') active @endif">{{ Lang::get('messages.profile.menu.propositions') }}</a>
          </div>

          @permission(['approveOrBlockPropositions', 'handleFlags'])
          <div class="list-group account-settings">
            <p class="list-group-item"><strong>{{ Lang::get('messages.moderator.menu.title') }}</strong></p>

            @permission('approveOrBlockPropositions')
            <a href="{{ route('moderator.approval') }}"
               class="list-group-item @if(Route::current()->getName() == 'moderator.approval') active @endif">{{ Lang::get('messages.moderator.menu.for_approval') }}</a>
            @endpermission
            @permission('handleFlags')
            <a href="{{ route('moderator.handle_flags') }}"
               class="list-group-item @if(Route::current()->getName() == 'moderator.handle_flags') active @endif">{{ Lang::get('messages.moderator.menu.handle_flags') }}</a>
            @endpermission
          </div>
          @endpermission

          <div class="list-group account-settings">
            <p class="list-group-item"><strong>{{ Lang::get('messages.profile.menu.contribute') }}</strong></p>
          <!--               <a href="#" class="list-group-item">{{ Lang::get('messages.profile.menu.translate') }} <i class="fa fa-external-link"></i></a> -->
            <a href="https://github.com/pupcomesl1/onlinedemocracy" target="_blank"
               class="list-group-item">{{ Lang::get('messages.profile.menu.github') }} <i
                  class="fa fa-external-link"></i></a>
            <a href="{{ route('feedback') }}"
               class="list-group-item @if(Route::current()->getName() == 'feedback') active @endif">{{ Lang::get('messages.profile.menu.feedback') }}</a>
          </div>

      </div>

      <div class="col-md-9" id="account_form">

        @if (session('status'))
          <div class="form-group form-group-sm">
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          </div>
        @endif

        @if (session('error'))
          <div class="form-group form-group-sm">
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
          </div>
        @endif

        @yield('form')
      </div>
    </div>
  </div>
@stop