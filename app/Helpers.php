<?php

function forAllTenants(Closure $func) {
	$ten = \App\Tenant::all();
	foreach ($ten as $tenant) {
		$func($tenant);
	};
}
