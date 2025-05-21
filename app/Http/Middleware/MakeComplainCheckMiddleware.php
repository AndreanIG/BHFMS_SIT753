<?php

namespace App\Http\Middleware;

use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MakeComplainCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(TenantBoarding::where('user_id', Auth::user()->id)->where('tenant_status', 'approved')->first()) {
            return $next($request);
        }
        abort(404, 'Not Found');
    }
}
