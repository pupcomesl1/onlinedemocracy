@extends('layouts_new.authBase')

@section('title', $profile['displayName'])

@section('content')
  <div class="container" id="main">
    <div class="row m-scene">
      <div class="col-md-9 col-md-offset-2">
        <h1>{{ $profile['displayName'] }}</h1>
        <br />
        <img alt="profile picture of {{ $profile['displayName'] }}" src="{{ $profile['avatar'] }}"
             class="img-circle"/>
        <br />
        <small class="text-muted">
          <small>{{ Lang::choice('messages.profile.account.propositionsCount', $profile['propositionsCount'], ['propositions' => $profile['propositionsCount']]) }}</small>
        </small>
        <br />
        <small class="text-muted">
          <small>{{ Lang::choice('messages.profile.account.points', $profile['points'], ['points' => number_format($profile['points'], 0)]) }}</small>
        </small>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 col-md-offset-2">
        <h2>Recent Propositions</h2>
        @foreach($profile['propositions'] as $prop)
          @component('components.proposition', ['proposition' => $prop])
          @endcomponent
        @endforeach
      </div>
    </div>
    <div class="row">
      <div class="col-md-9 col-md-offset-2">
        <h2>Badges</h2>
        <div class="row">
          <div class="col-md-4">
            <h3>Gold</h3>
            @foreach ($profile['badges']['gold'] as $badge)
              @component('components.badge', ['type' => 'gold', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
                @lang('badges.' . $badge->type . '.name')
              @endcomponent
            @endforeach
          </div>
          <div class="col-md-4">
            <h3>Silver</h3>
            @foreach ($profile['badges']['silver'] as $badge)
              @component('components.badge', ['type' => 'silver', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
                @lang('badges.' . $badge->type . '.name')
              @endcomponent
            @endforeach
          </div>
          <div class="col-md-4">
            <h3>Bronze</h3>
            @foreach ($profile['badges']['bronze'] as $badge)
              @component('components.badge', ['type' => 'bronze', 'name' => $badge->type, 'title' => trans('badges.' . $badge->type . '.condition')])
                @lang('badges.' . $badge->type . '.name')
              @endcomponent
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection