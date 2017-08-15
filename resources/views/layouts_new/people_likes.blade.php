<div class="list-group scrollable">
  @foreach ($likes as $like)
  <a href="{{ tenantRoute('public_profile', ['id' => $like->user()->id]) }}" class="list-group-item">
    <img class="img-circle text-sized-picture" src="{{ $like->user()->avatar() }}"> {{ $like->user()->displayName }}
  </a>
  @endforeach
</div>