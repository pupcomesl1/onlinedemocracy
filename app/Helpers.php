<?php

use HipsterJazzbo\Landlord\Facades\Landlord;

function forAllTenants(Closure $func) {
	$ten = \App\Tenant::all();
	foreach ($ten as $tenant) {
		$func($tenant);
	};
}

function tenantRoute($name, $parameters = [], $absolute = true) {
	return route(
		$name,
		array_merge($parameters, [ 'tenant' => 'kirch' ]),
		$absolute
	);
	// return route(
	// 	$name,
	// 	array_merge($parameters, [ 'tenant' => Landlord::getTenants()->first()->prefix ]),
	// 	$absolute
	// );
}

function redirectToUserTenant() {
	if (!Auth::check()) {
		return redirect()->route('guest.home');
	}
	$tenant = Auth::user()->tenant;
	Landlord::addTenant($tenant);
	return redirect('propositions', ['tenant' => $tenant->prefix]);
}