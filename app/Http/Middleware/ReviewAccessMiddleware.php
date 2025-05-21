<?php

namespace App\Http\Middleware;

use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewAccessMiddleware
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
        $userId = TenantBoarding::where('boarding_id', $request->id ? $request->id : $request->boardingId)->where('user_id', Auth::user()->id)->where('tenant_status', 'checkout')->first()->user_id ?? -1;

        if ($userId == -1) {
            abort(404, 'Not Found');
        }
        return $next($request);
    }
}
