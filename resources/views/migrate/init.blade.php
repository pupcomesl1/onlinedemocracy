@extends('layouts_new.guestBase')

@section('title', 'An online voting platform for schools')

@section('content')
  <div class="section container text">
    <h1>Please migrate your account to Office 365</h1>

    <p>If you registered with Facebook, click the button below to sign in. If you used your email, enter your email
      and
      password below.</p>

    <a href="{{ route('migrate.facebook') }}"
       class="btn btn-primary">{{Lang::get('messages.session.login.use_fb_login')}}</a>

    <form action="{{ route('migrate.email') }}" method="post">

      <br />

      @if(!empty($errors))
        @foreach($errors as $error)
          <div class="alert alert-danger">{{ Lang::get('messages.session.errors.' . $error) }}</div>
        @endforeach
      @endif

      <div class="form-group">
        <label>
          @lang('messages.session.login.email')
          <input type="email" class="form-control" name="email">
        </label>
      </div>

      <div class="form-group">
        <label>
          @lang('messages.session.login.password')
          <input type="password" class="form-control" name="password">
        </label>
      </div>

      <button type="submit" class="btn btn-primary">@lang('messages.session.login.submit')</button>

      {{ csrf_field() }}
    </form>
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