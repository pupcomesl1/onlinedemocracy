<table class="leaderboard">
  @foreach($data as $row)
    @if($loop->index === 10)

      <tr class="skip"><td colspan="3"><hr /></td></tr>

    @elseif($loop->index < 10 || $row['user_id'] == Auth::user()->id)

      <tr class="{{ $row['user_id'] == Auth::user()->id ? 'highlight' : '' }}">
        <td class="rownum" colspan="2">{{ $loop->iteration }}.</td>
        <td>{{ number_format($row['total'], 0) }}</td>
        <td>
          <a href="{{ tenantRoute('public_profile', ['id' => $row['user_id']]) }}" style="color: inherit">
            {{ $row['displayName'] }}
          </a>
        </td>
        <td>
          @component('components.badge-counts', $row['badges'])
          @endcomponent
        </td>
      </tr>
    @endif
  @endforeach
</table>
