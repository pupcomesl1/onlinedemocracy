@extends('layouts_new.profileBase')

@section('title', Lang::get('messages.moderator.head_title.handle_comment_flags'))

@section('form')
	<p>@lang('messages.moderator.comment_flags.explanation')<a href="{{ route('guidelines') }}">@lang('messages.proposition.comments.guidelines_2')</a>.</p>

	<label for="include_dismissed">Include Dismissed Flags</label>
	<input id="include_dismissed" type="checkbox" {{ $includeDismissed ? 'checked' : '' }}>

<div class="panel-group" id="comments" role="tablist" aria-multiselectable="true" aria-expanded="true">
@if (!empty($flags))
@foreach ($flags as $comment)
<div class="panel panel-default">
	<div class="panel-heading" role="tab" id="heading{{ $comment['id'] }}">
		
		<span class="label label-danger pull-right" style="line-height: 18px;">{{ Lang::choice('messages.moderator.flags.count', $comment['flagCount']['total'], ['flags' => $comment['flagCount']['total']]) }}</span>
		
		<a class="panel-title" role="button" data-toggle="collapse" data-parent="#propositions" href="#collapse{{ $comment['id'] }}" aria-controls="collapse{{ $comment['id'] }}">{{ str_limit($comment['proposition']['title'], $limit=50, $end='&hellip;') }}&mdash;{{ str_limit($comment['body'], $limit=50, $end='&hellip;') }}</a>
	</div>
	       
	<div id="collapse{{ $comment['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $comment['id'] }}">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-10">
					<blockquote style="font-size: 15px;">{{ $comment['body'] }}</blockquote>
					<p class="text-muted">By {{ $comment['commenter']['displayName'] }}</p>
					
					<p class="text-muted">
						<span class="label label-warning">{{ Lang::choice('messages.moderator.flags.count', $comment['flagCount']['total']['total'], ['flags' => $comment['flagCount']['total']]) }}</span>
						<span class="label label-info">{{ Lang::choice('messages.moderator.flags.countNonDismissed', $comment['flagCount']['nonDismissed'], ['flags' => $comment['flagCount']['nonDismissed']]) }}</span>
					</p>

					<p>
						<a class="btn btn-sm btn-danger" data-toggle="collapse" href="{{ route('comment.delete', $comment['id']) }}" aria-expanded="false" aria-controls="proposition{{$comment['id']}}">@lang('messages.proposition.comments.delete')</a>
						<form style="display: inline" method="post" action="{{ route('moderator.comment_flags.dismiss') }}">
							<input type="hidden" name="id" value="{{ $comment['id'] }}">
							{!! csrf_field() !!}
							<input type="submit" class="btn btn-sm btn-primary" value="{{ Lang::choice('messages.moderator.flags.dismiss', $comment['flagCount']['total']['total']) }}">
						</form>
					</p>

					
				</div>
			</div>
		</div>
	</div>
</div>
@endforeach
@else
	@lang('messages.moderator.all_ok')
@endif

@stop

@section('footer_scripts')
	<script>
		$(document).ready(function() {
		   $('#include_dismissed').change(function() {
				var checked = $(this).attr('checked');
				if (checked) {
            window.location.search = window.location.search.replace(/[?&]include_dismissed=1/, '');
				} else {
				    if (window.location.search.indexOf('?') !== -1) {
				        window.location.search = window.location.search + '&include_dismissed=1';
						} else {
                window.location.search = '?include_dismissed=1';
						}
				}
			 });
		});
	</script>
@stop