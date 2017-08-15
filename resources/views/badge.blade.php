@extends('layouts_new.authBase')

@section('title', $name . ' - An online voting platform for schools')

@section('content')
  <div class="container">
    <h1>{{ $name }}</h1>
    @component('components.badge', ['type' => $badge['type'], 'name' => $key, 'title' => trans('badges.' . $key . '.condition')])
      {{ $name }}
    @endcomponent
    <br />
    <b>{{ trans('badges.' . $key . '.condition') }}</b>
    <br />
    {{ number_format($count, 0) }} users ({{ number_format($percentage, 0) }}% of all users) have this badge.
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