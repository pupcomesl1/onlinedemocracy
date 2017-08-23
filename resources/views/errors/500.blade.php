@extends('layouts_new.guestBase')

@section('title', '500 - Internal Server Error')

@php
  $images = [
    'http://dailypicksandflicks.com/wp-content/uploads/2012/06/i-can-fix-it-cat-trust-me-im-an-engineer.jpg',
    'https://bighugelabs.com/output/lolcat55b875caeb638ae0f3c6a2e8e30592a591981656.jpg',
    'https://scontent.cdninstagram.com/hphotos-xaf1/t51.2885-15/e15/11296629_1655470038017896_122128900_n.jpg',
    'https://s-media-cache-ak0.pinimg.com/736x/c8/26/25/c8262522fba28be1d6f5261028c7b0d2--online-pet-supplies-pets-online.jpg',
    'https://stackoverflow.blog/wp-content/uploads/2017/02/error-lolcat-problemz.jpg'
  ];
$image = $images[array_rand($images, 1)];
@endphp

@section('content')

  <style>
    html, body {
      height: 100%;
    }

    body {
      margin: 0;
      padding: 0;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
    }

    .content {
      text-align: center;
      display: inline-block;
    }

    .logo {
      width: 100px;
      height: 100px;
      margin-bottom: 50px;
    }

    .cat {
      max-height: 300px;
      margin-bottom: 20px;
    }
  </style>

  <div class="container">

    <img class="logo" src="{{ asset('img/logo.svg') }}" alt="DirectDemocracy logo">
    <h1 class="text-center"><strong>{{ $code }}</strong> - {{ $summary }}</h1>

    <small>{{ $detail }}</small>

    <img src="{{ $image }}" alt="Cat." class="img-responsive cat">

    <p>We're sorry. An error occurred on our servers.</p>

    <p>It's not you, it's us. <b>This is our fault, not yours.</b></p>

    <p>Our systems have automatically logged detailed information about the error and our developers are on the
      case.
      @unless(empty($id))
        If you'd like to get in touch with them about it, please include the tracking code: <code>{{ $id }}</code>.
      @endunless
    </p>

  @unless(empty($id))
    <!-- Sentry JS SDK 2.1.+ required -->
      <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

      <script>
          Raven.showReportDialog({
              eventId: '{{ $id }}',

              // use the public DSN (dont include your secret!)
              dsn: 'https://62d56f48a0784229aa370188bfc23f9c@sentry.io/204561'
          });
      </script>
    @endunless

    <p>Thank you for using DirectDemocracy!</p>

  </div>

@stop