<a class="thumbnail" href="{{ tenantRoute('proposition', [$proposition['id']]) }}">
  <div class="caption">
    <p>@if (empty($proposition['marker']) == false)
        @if ($proposition['marker']->relationMarkerId() == \App\Marker::SUCCESS)<span class="label label-success label-icon"><i class="material-icons">check</i></span>
        @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::UNDER_DISCUSSION) <span class="label label-info label-icon"><i class="material-icons">speaker_notes</i></span>
        @elseif ($proposition['marker']->relationMarkerId() == \App\Marker::FAILED) <span class="label label-warning label-icon"><i class="material-icons">announcement</i></span>
        @endif
        {{{ $proposition['propositionSort'] }}} @else {{{ $proposition['propositionSort'] }}} @endif</p>
    <small class="text-muted">{{ Lang::choice('messages.proposition.voting.stats.upvotes', $proposition['upvotes'], ['votes' => $proposition['upvotes']]) }} | {{ Lang::choice('messages.proposition.voting.stats.downvotes', $proposition['downvotes'], ['votes' => $proposition['downvotes']]) }} | {{ Lang::choice('messages.proposition.voting.stats.comments', $proposition['comments'], ['comments' => $proposition['comments']]) }}</small>
  </div>
</a>