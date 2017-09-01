@extends('layouts_new.guestBase')

@section('title', 'An online voting platform for schools')

@section('content')
  <div class="section container text">
    <h1>Account Migration - Error</h1>

    @if (str_contains($error, 'fuckup'))
    <p>We're sorry, an error has occurred. Please try migrating again, and if it doesn't work, contact us at <code>contact@directdemocracy.online</code>.</p>
    @else
      @if ($error === 'account_already_migrated')
        It seems like your account has already been migrated.
      @endif
    @endif

    <p>Error code: <code>{{ $error }}</code></p>

    <a href="{{ route('migrate.init') }}" class="btn btn-primary">@lang('messages.migration.try_again')</a>
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