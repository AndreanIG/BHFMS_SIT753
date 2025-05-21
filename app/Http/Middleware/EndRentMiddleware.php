<?php

namespace App\Http\Middleware;

use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndRentMiddleware
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
        if (Auth::user()->user_role_id == 2) {
            $tenantBoarding = TenantBoarding::where('id', $request->id)->first() ?? null;
            if($tenantBoarding->user_id == Auth::user()->id && $tenantBoarding->tenant_status == 'approved' && $tenantBoarding->end_date == null){
                return $next($request);
            }
        }
        abort(404, 'Not Found');
    }
}
