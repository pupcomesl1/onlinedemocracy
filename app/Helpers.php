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
    if (!config('app.multitenant')) {
        return route($name, $parameters, $absolute);
    }
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
    if (!config('app.multitenant')) {
        return redirect()->route('propositions');
    }
	if (!Auth::check()) {
		return redirect()->route('guest.home');
	}
	$tenant = userTenant();
	Landlord::addTenant($tenant);
	return redirect()->route('propositions', ['tenant' => $tenant->prefix]);
}

function tenantId() {
    if (!config('app.multitenant')) {
        return 0;
    }
	return Landlord::getTenants()->first();
}

function tenant() {
    if (!config('app.multitenant')) {
        return null;
    }
	$id = tenantId();
	return \Cache::remember('tenant-' . $id, 60, function() use ($id) {
		return \App\Tenant::find($id);
	});
}

function userTenant() {
    if (!config('app.multitenant')) {
        return null;
    }
	return Auth::user()->tenant;
}

function tenantParams($params = []) {
    if (!config('app.multitenant')) {
        return $params;
    }
    $tenant = tenant();
	return array_merge($params, ['tenant' => $tenant ? $tenant->prefix : '']);
}
