@extends('layouts_new.guestBase')

@section('title', 'An online voting platform for schools')

@section('content')
  <div class="section container text">
    <h1>Migration complete!</h1>

    <p>Thank you for migrating your account!</p>

    <p>From now on, use your Office 365 account to sign in to Petitions.</p>

    <p>As a thank you, we've awarded a
      @component('components.badge', ['type' => 'bronze', 'name' => 'veteran', 'title' => trans('badges.veteran.condition')])
        @lang('badges.veteran.name')
      @endcomponent
      badge to your account.
    </p>

    <p>Enjoy Petitions!</p>

    <a href="{{ tenantRoute('propositions') }}" class="btn btn-primary">@lang('messages.migration.finish')</a>
  </div>
@stop

@section('footer_scripts')
  <script>
      (function (i, s, o, g, r, a, m) {
          i['GoogleAnalyticsObject'] = r;
          i[r] = i[r] || function () {
              (i[r].q = i[r].q || []).push(arguments)
          }, i[r].l = 1 * new Date();
          a = s.createElement(o),
              m = s.getElementsByTagName(o)[0];
          a.async = 1;
          a.src = g;
          m.parentNode.insertBefore(a, m)
      })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

      ga('create', 'UA-70133844-9', 'auto');
      ga('send', 'pageview');

  </script>
@stop