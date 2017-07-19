@component('mail::message')
# @lang('messages.emails.approved-proposition.title')

@lang('messages.emails.approved-proposition.description')

@component('mail::panel')
### [{{ $proposition->propositionSort() }}]({{ tenantRoute('proposition', ['id' => $proposition->id()]) }})
{{ $proposition->propositionLong() }}
@endcomponent

## @lang('messages.emails.approved-proposition.share')

@lang('messages.emails.approved-proposition.share-description')

@component('mail::button', ['url' => $shareLinks['facebook']])
@lang('messages.emails.approved-proposition.share-btn')
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
