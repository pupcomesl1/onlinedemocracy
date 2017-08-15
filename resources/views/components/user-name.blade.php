<strong>
  <img class="img-circle text-sized-picture" src="{{ $user['avatar'] }}">
  <a href="{{ tenantRoute('public_profile', ['id' => $user['id']]) }}">{{ $user['displayName'] }}</a>
</strong>
<em>{{ number_format($user['points'], 0) }}</em>
@component('components.badge-counts', $user['badges'])
@endcomponent