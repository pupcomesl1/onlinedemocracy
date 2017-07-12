@extends('layouts_new.profileBase')

@section('title', Lang::get('messages.moderator.head_title.moderate_props'))

@section('form')
	<div class="toolbox hidden-sm-down hidden-md-down">
		<h4 class="toolbox-title">{{ Lang::get('messages.moderator.criteria.title') }}</h4>
		<ul class="small">
			<li>{{ Lang::get('messages.moderator.criteria.no_offensive_words') }}</li>
			<li>{{ Lang::get('messages.moderator.criteria.no_mentions') }}</li>
		</ul>
	</div>
@if (count($propositions) !== 0)
<div class="list-group">
@foreach ($propositions as $proposition)
  <div class="list-group-item">
  	<span class="pull-right">
  		<span class="label label-info"">{{ Lang::choice('messages.moderator.days_left', $proposition['ending_in'], ['daysleft' => $proposition['ending_in']]) }}</span>
  		<a href="{{ route('moderator.approve', $proposition['id']) }}" class="btn btn-sm btn-success">{{ Lang::get('messages.moderator.approve') }}</a>
		<a class="btn btn-sm btn-danger" data-toggle="collapse" href="#proposition{{$proposition['id']}}" aria-expanded="false" aria-controls="proposition{{$proposition['id']}}">@lang('messages.moderator.block')</a>
  	</span>
    <h4 class="list-group-item-heading">{{$proposition['propositionSort']}}</h4>
    <p class="list-group-item-text">{{$proposition['propositionLong']}}</p>
  </div>
  <div class="list-group-form collapse" id="proposition{{$proposition['id']}}">
	<form class="form-inline" method="post" action="{{ route('moderator.block') }}">
		  <div class="form-group">
		    <input type="text" name="reason" class="form-control input-sm" id="reason" placeholder="@lang('messages.moderator.reason_placeholder')">
		  </div>
		  <input type="hidden" name="id" value="{{$proposition['id']}}">
		  {!! csrf_field() !!}
		  <button type="submit" class="btn btn-danger btn-sm">{{ Lang::get('messages.moderator.block')}}</button>
	</form>
  </div>
@endforeach
</div>
@else
{{ Lang::get('messages.moderator.all_ok') }}
@endif
@stop