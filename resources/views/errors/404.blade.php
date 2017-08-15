@extends('layouts_new.guestBase')

@section('title', '404 - Not found')

@section('content')

	<style>
	html, body {
		height: 100%;
	}

	body {
		margin: 0;
		padding: 0;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.container {
	}

	.content {
		text-align: center;
		display: inline-block;
	}
	img {
		width: 100px;
		height: 100px;
		margin-bottom: 50px;
	}
	</style>
	
	<div class="container">
		
		<img src="{{ asset('img/logo.svg') }}" alt="DirectDemocracy logo">
		<h1 class="text-center"><strong>404</strong> - Not found.</h1>
		
		
	</div>

@stop