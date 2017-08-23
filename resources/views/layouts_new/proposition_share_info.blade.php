<meta property="fb:app_id" content="{{ config('app.fb_client_id') }}" />
<meta property="og:url" content="{{ tenantRoute('proposition', [$proposition['id']]) }}" />
<meta property="og:title" content="{{ $proposition['propositionSort'] }}" />
@if (empty($proposition['propositionLong']) == false)
<meta property="og:description" content="{{ $proposition['propositionLong'] }}" />
@else
<meta property="og:description" content="You have finally got the power to change things! Take part in your school's decision making" />
@endif
<meta property="og:image" content="{{ asset('img/logo.png') }}" />
<meta property="og:site_name" content="Petitions.Online">