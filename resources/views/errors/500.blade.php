@extends('layouts_new.guestBase')

@section('title', '500 - Internal Server Error')

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
    img {
      width: 100px;
      height: 100px;
      margin-bottom: 50px;
    }
  </style>

  <div class="container">

    <img src="{{ asset('img/logo.svg') }}" alt="DirectDemocracy logo">
    <h1 class="text-center"><strong>500</strong> - Internal Server Error.</h1>

    <p>We're sorry. An error occurred on our servers.</p>

    <p>It's not you, it's us. <b>This is our fault, not yours.</b></p>

    <p>Our systems have automatically logged detailed information about the error and our developers are on the case.</p>

    <p>If you'd like to give us some additional information about what you did, feel free to let us know at <code>contact{{'@'}}{{ $prefix }}.directdemocracy.online</code>.</p>

    <p>Otherwise, just go back and continue browsing. Thank you for using DirectDemocracy!</p>

  </div>

@stop