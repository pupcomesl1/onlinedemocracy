<?php
$update = strpos($exception->getMessage(), 'codeUpdate') !== false;
if (!$update) {
    $cause = $exception->getMessage();
    $eta = $exception->willBeAvailableAt;
}
?>

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
		
		<img src="{{ asset('img/logo.svg') }}" alt="Petitions logo">
		<h1 class="text-center">Be right back.</h1>
		@if ($update)
			<p class="lead text-center">We're updating our code.</p>
			<p>As you read this, a script is running on our servers, updating the site to the latest version.</p>
			<p>We should be back online in less than a minute, and with new features and bug fixes!</p>
		@else
			<p class="lead text-center">We're running some important maintenance.</p>
			<p>We're sorry about this, but there's something we need to do to our servers which we can't do while the website is up.</p>
			<p>We're working as fast as we can, and will be back very soon!</p>
			<p><b>What's up:</b> {{ $cause }}</p>
			@if ($eta->gt(\Carbon\Carbon::now()))
				<p><b>ETA:</b> about {{ $eta->diffForHumans(null, false) }}</p>
			@else
				<p><b>ETA:</b> right about now, actually...</p>
			@endif
		@endif
		
	</div>

@stop