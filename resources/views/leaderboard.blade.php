@extends('layouts_new.authBase')

@section('title', 'Leaderboard')

@section('content')
  <div class="container leaderboard" id="main">
    <div class="row">
      <div class="col-md-8 col-md-offset-3">
        <h3>Score Leaderboard</h3>
      </div>
    </div>
  </div>
        @component('components.leaderboard', ['data' => $data])
        @endcomponent
@stop