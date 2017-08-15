<?php

use HipsterJazzbo\Landlord\Facades\Landlord;

require_once 'BadgeHelpers.php';

function forAllTenants(Closure $func) {
	$ten = \App\Tenant::all();
	foreach ($ten as $tenant) {
		$func($tenant);
	};
}

function tenantRoute($name, $parameters = [], $absolute = true) {
	// return route(
	// 	$name,
	// 	array_merge($parameters, [ 'tenant' => 'kirch' ]),
	// 	$absolute
	// );
	if (is_array($parameters)) {
		return route(
			$name,
			tenantParams($parameters),
			$absolute
		);
	} else {
		return route(
			$name,
			tenantParams(['id' => $parameters]),
			$absolute
		);
	}
}

function redirectToUserTenant() {
	if (!Auth::check()) {
		return redirect()->route('guest.home');
	}
	$tenant = userTenant();
	Landlord::addTenant($tenant);
	return redirect()->route('propositions', ['tenant' => $tenant->prefix]);
}

function tenantId() {
	return Landlord::getTenants()->first();
}

function tenant() {
	$id = tenantId();
	return \Cache::remember('tenant-' . $id, 60, function() use ($id) {
		return \App\Tenant::findOrFail($id);
	});
}

function userTenant() {
	return Auth::user()->tenant;
}

function tenantParams($params = []) {
	return array_merge($params, ['tenant' => tenant()->prefix]);
}
