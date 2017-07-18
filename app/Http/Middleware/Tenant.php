<?php

namespace App\Http\Middleware;

use Closure;
use HipsterJazzbo\Landlord\Facades\Landlord;

class Tenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tenant = \App\Tenant::where('prefix', $request->params('tenant'))->first();
        if ($tenant == null) {
            return abort(404);
        }
        if (Auth::check() && !(Auth::user()->tenant_id == $tenant->id)) {
            return redirect()->route('propositions', ['tenant' => $tenant->prefix]);
        }
        Landlord::addTenant($tenant);
        return $next($request);
    }
}
