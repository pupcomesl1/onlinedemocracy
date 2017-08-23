@extends('layouts_new.main')

@section('content_base')
  <div class="container-fluid" style="min-height:100%; position:relative;">

    <div class="container-fluid" id="navigation">
      <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topFixedNavbar1">
              <span class="sr-only">{{ Lang::get('messages.navigation.nav_toggle') }}</span><span
                  class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            <a class="navbar-brand" href="{{ tenantRoute('propositions') }}">
              <img src="{{ asset('img/logo.svg') }}" alt="Petitions logo">
              Petitions
            </a>
          </div>

          <div class="collapse navbar-collapse" id="topFixedNavbar1">
            <ul class="nav navbar-nav">
              <li class="@if(Route::current()->getName() == 'propositions') active @endif">
                <a href="{{ tenantRoute('propositions') }}">{{ Lang::get('messages.navigation.home') }}</a>
              </li>
              <li class="@if(Route::current()->getName() == 'archived') active @endif">
                <a href="{{ tenantRoute('archived') }}">{{ Lang::get('messages.navigation.archived') }}</a>
              </li>
              <li class="@if(Route::current()->getName() == 'leaderboard') active @endif leaderboard-button">
                <a href="{{ tenantRoute('leaderboard') }}">Leaderboard</a>
              </li>
            </ul>

            <form class="navbar-form navbar-left" role="search" method="get" action="{{ tenantRoute('search') }}">
              <div class="form-group">
                <input name="q" type="text"
                       @if (isset($_GET["q"]) == true) @if ($_GET["q"] !== null) value="{{ $_GET["q"] }}"
                       @endif @endif class="form-control" placeholder="{{ Lang::get('messages.search.search') }}"
                       autocomplete="off">
              </div>
            </form>

            <ul class="nav navbar-nav navbar-right">
              <li>
                <a href="{{ tenantRoute('profile.propositions.create') }}" class="btn btn-teal"><i
                      class="material-icons" style="font-size: 15px; vertical-align: sub;">create</i><span
                      class="hidden-sm"> @lang('messages.navigation.create_proposition')</span></a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  <img alt="profile picture of {{ $user['displayName'] }}" src="{{ $user['avatar'] }}"
                       class="profile-picture-navbar img-circle">
                  &nbsp;{{ $user['displayName'] }}
                  &nbsp;<small>{{ number_format($user['points'], 0) }}</small>
                  &nbsp;<span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ tenantRoute('profile.propositions') }}">@lang('messages.navigation.propositions')</a>
                  </li>
                  <li><a href="{{ tenantRoute('profile.main') }}">@lang('messages.navigation.profile')</a></li>
                  <li class="divider"></li>
                  <li><a href="{{ tenantRoute('profile.language') }}">@lang('messages.navigation.language')</a></li>
                  <li class="divider"></li>
                  <li><a href="{{ tenantRoute('logout') }}">@lang('messages.navigation.logout')</a></li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
      </nav>
    </div>

    <div style="padding-bottom:40px; padding-top: 90px;">
      <div class="container">
        @if (!Auth::user()->can('postPropositions'))
          <div class="alert alert-info" role="alert" id="link-info" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" data-alert-box="link-info"
                    style="margin-top: -6px;" aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span>
            </button>
            <p>@lang('messages.notifications.not_belongs_to_school', ['school' => tenant()->long_name])</p>
          </div>
        @endif

        <div class="alert alert-info" role="alert" id="lang-info" style="display: none; background-color: #607D8B;">
          <button type="button" class="close" data-dismiss="alert" data-alert-box="link-info" style="margin-top: -6px;"
                  aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span></button>
          @if (Lang::locale() == 'en')
            <p>{{ Lang::get('messages.notifications.available_in_fr') }} <a
                  href="{{ tenantRoute('profile.language.set', ['fr']) }}"
                  class="alert-link">{{ Lang::get('messages.languages.fr') }}</a>!</p>
          @else
            <p>{{ Lang::get('messages.notifications.available_in_en') }} <a
                  href="{{ tenantRoute('profile.language.set', ['en']) }}"
                  class="alert-link">{{ Lang::get('messages.languages.en') }}</a>!</p>
          @endif
        </div>


        @if (isset($modAlerts) == true)

          @if (empty($modAlerts["approval"]) == false)
            <div class="alert alert-warning" role="alert" id="mod-approval" style="display: none;">
              <button type="button" class="close" data-dismiss="alert" data-alert-box="link-info"
                      style="margin-top: -6px;" aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span>
              </button>
              <p><a href="{{ tenantRoute('moderator.approval') }}"
                    class="alert-link">{{ Lang::get('messages.notifications.moderator_approval_queue')  }}</a></p>
            </div>
          @endif
          @if (empty($modAlerts["flag"]) == false)
            <div class="alert alert-warning" role="alert" id="mod-flag" style="display: none;">
              <button type="button" class="close" data-dismiss="alert" data-alert-box="link-info"
                      style="margin-top: -6px;" aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span>
              </button>
              <p><a href="{{ tenantRoute('moderator.handle_flags') }}"
                    class="alert-link">{{ Lang::get('messages.notifications.moderator_flag_queue')  }}</a></p>
            </div>
          @endif

        @endif

      </div>


      @yield('content')
    </div>

    <div class="sidebar">
      @if (Auth::check())
        <h4>Score Leaderboard</h4>
        @component('components.leaderboard', ['data' => $leaderboardData])
        @endcomponent
      @endif
    </div>

    <div id="footer">
      <div class="container">
        <p class="text-center" style="display: none;" id="footer-app-iphone-link"><a href="#">Save on your home
            screen.</a></p>
      </div>
    </div>

    @include('homescreen_link.iphone')

  </div>
  <p class="text-center">
    <small>
      <small class="text-muted">
        @lang('messages.website.footer.1')
        <a href="{{ route('license') }}">@lang('messages.website.footer.2')</a>
        @lang('messages.website.footer.3')
      </small>
    </small>
  </p>
@stop()

@section('cookies')
  <script type="text/javascript" src="{{ asset('js/cookie.js') }}"></script>
  <script>
      jQuery(function ($) {
        @if ($user['belongsToSchool'] == false)
        $('#link-info.alert .close').click(function (e) {
            createCookie('link-info-alert-closed', true, 2);
        });
        @endif

        $('#lang-info.alert .close, #lang-info.alert .alert-link').click(function (e) {
            createCookie('lang-info-alert-closed', true, 365);
        });

          $('#open-app-icon, #close-app-iphone-introduce').click(function (e) {
              createCookie('app-introduce-closed', true, 365);
          });

          $('#mod-approval .close').click(function (e) {
              createCookie('mod-approval-alert', true, 1);
          });
          $('#mod-flag .close').click(function (e) {
              createCookie('mod-flag-alert', true, 1);
          });
      });


      jQuery(function ($) {

          if ((readCookie('link-info-alert-closed') === 'false') || (readCookie('link-info-alert-closed') == null)) {
              $('#link-info.alert').show();
          }
          if ((readCookie('lang-info-alert-closed') === 'false') || (readCookie('lang-info-alert-closed') == null)) {
              $('#lang-info.alert').show();
          }

          if ((readCookie('mod-approval-alert') === 'false') || (readCookie('mod-approval-alert') == null)) {
              $('#mod-approval.alert').show();
          }
          if ((readCookie('mod-flag-alert') === 'false') || (readCookie('mod-flag-alert') == null)) {
              $('#mod-flag.alert').show();
          }
      });
  </script>

  <!-- Floating feedback -->
  <div class="float-btn-feedback" style="position: fixed;bottom: 0;right: 0;text-align: right;">
    <button style="
    display: inline-block;
    background: #607D8B;
    color: #fff;
    padding: 10px 20px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    margin: 0 30px;
    border: none;
	" data-toggle="collapse" data-target="#feedback-floating" aria-expanded="false" aria-controls="collapseExample"><i
          class="material-icons" style="
	    vertical-align: sub;
	">message</i><span> @lang('messages.feedback.feedback')</span></button>
    <div class="collapse" id="feedback-floating">
      <div class="well"
           style="display: inline-block; margin: 0 30px; border-radius: 0; border-top-left-radius: 4px; background: #78909c; color: #fff; min-width: 200px; max-width: 500px; text-align: left;">

        <form class="form-vertical" id="floating-feedback-form" method="POST"
              action="{{ tenantRoute('feedback.send') }}">

          <p>@lang('messages.feedback.reason')</p>

          <div class="alert-populate"></div>

          <div class="form-group @if ($errors->has('feedback')) has-error @endif">
            <textarea class="form-control" name="feedback" style="max-height: 120px;"
                      placeholder="@lang('messages.feedback.placeholder')" required>{{ old('feedback') }}</textarea>
          </div>

          {{ csrf_field() }}

          <div class="form-group">
            <button type="submit" class="btn btn-default">@lang('messages.feedback.submit')</button>
          </div>

        </form>

      </div>
    </div>
  </div>
  <script>
      $(function () {
          $('#feedback-floating').on('show.bs.collapse', function () {
              document.getElementById('floating-feedback-form').reset();
              $(this).find('.alert-populate').html('');
          });

          $('form#floating-feedback-form').submit(function (event) {
              event.preventDefault(); // Prevent the form from submitting via the browser
              var form = $(this);
              $.ajax({
                  type: form.attr('method'),
                  url: form.attr('action'),
                  data: form.serialize()
              }).done(function (data) {
                  form.find('.alert-populate').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" style="margin-top: -6px;" aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span></button> {{ Lang::get("messages.feedback.thanks") }}</div>');
              }).fail(function (data) {
                  form.find('.alert-populate').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" style="margin-top: -6px;" aria-label="Close"><span aria-hidden="true"><i class="material-icons">close</i></span></button> {{ Lang::get("messages.feedback.error") }}</div>');
              });
          });
      });
  </script>
@stop