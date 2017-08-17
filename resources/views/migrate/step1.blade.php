@extends('layouts_new.guestBase')

@section('title', 'An online voting platform for schools')

@section('content')
  <div class="section container text">
    <h1>Account Migration</h1>

    <p>Thank you, {{ $firstName }}. Next, please sign in to your school's Office 365 account</p>

    <a href="{{ route('migrate.o365') }}" class="btn btn-primary">@lang('messages.session.login.use_msgraph')</a>
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

      ga('create', 'UA-72457439-1', 'auto');
      ga('send', 'pageview');

  </script>
@stop