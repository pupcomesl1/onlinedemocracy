@extends('layouts_new.guestBase')

@section('title', 'Down for maintenance')

@section('content')

	<style>
	html, body {
		height: 100%;
	}

	body {
		margin: 0;
		padding: 0;
		width: 100%;
		display: table;
	}

	.container {
		text-align: center;
		display: table-cell;
		vertical-align: middle;
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
		<h1 class="text-center">Be right back.</h1>
		<p class="lead text-center">We're updating our code.</p>
		<p>As you read this, a script is running on our servers, updating the site to the latest version.</p>
		<p>We should be back online in less than a minute, and with new features and bug fixes!</p>
		
	</div>

@stop