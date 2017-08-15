@extends('layouts_new.authBase')

@section('title', 'Propositions')

@section('content')
<div class="container" id="main">

	@if ((count($endingSoonPropositions) == 0) AND (count($propositions) == 0) AND (count($votedPropositions) == 0))
	<h3 class="propositions-section">{{ Lang::get('messages.propositions.no_active') }}</h3>
	<p class="lead text-center">{{ Lang::get('messages.propositions.no_active_desc') }}</p></br>
	<p class="text-center"><a href="{{ tenantRoute('profile.propositions.create') }}" class="btn btn-lg btn-teal"><i class="glyphicon glyphicon-pencil"></i> @lang('messages.navigation.create_proposition')</a></p>
	@endif

	@if (count($endingSoonPropositions) !== 0)
	<h3 class="propositions-section">{{ Lang::get('messages.propositions.ending_soon') }}</h3>
	<div class="pinBoot" id="expiring">
		@foreach($endingSoonPropositions as $proposition)
			@component('components.proposition', ['proposition' => $proposition])
			@endcomponent
		@endforeach
	</div>
	@endif
	
    @if (count($propositions) !== 0)
    <h3 class="propositions-section" >{{ Lang::get('messages.propositions.new_propositions') }}</h3>
	<div class="pinBoot" id="recent">
		@foreach($propositions as $proposition)
			@component('components.proposition', ['proposition' => $proposition])
			@endcomponent
		@endforeach
	</div>
	@endif
	
	@if (count($votedPropositions) !== 0)
	<h3 class="propositions-section">{{ Lang::get('messages.propositions.voted_propositions') }}</h3>
	<div class="pinBoot" id="voted">
		@foreach($votedPropositions as $proposition)
			@component('components.proposition', ['proposition' => $proposition])
			@endcomponent
		@endforeach
	</div>
	@endif
	
</div>
@stop