<?php

namespace App\Http\Middleware;

use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardingAccessReadMiddleware
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
        //Admin
        if (Auth::user()->user_role_id == 1) {
            return $next($request);
        //Owner   
        }else if (Auth::user()->user_role_id == 3) {
            $ownerId = OwnerBoarding::where('boarding_id', $request->id)->first()->user_id ?? -1;
            if($ownerId != Auth::user()->id || $ownerId == -1){
                abort(404, 'Not Found');
            }else{
                return $next($request);
            }
        //Tenant
        }else if (Auth::user()->user_role_id == 2) {
            $tenantId = TenantBoarding::where('boarding_id', $request->id)->first()->user_id ?? -1;
            if($tenantId != Auth::user()->id || $tenantId == -1){
                abort(404, 'Not Found');
            }else{
                return $next($request);
            }
        //Manager
        }else if (Auth::user()->user_role_id == 4) {
            $managerId = ManagerBoarding::where('boarding_id', $request->id)->first()->user_id ?? -1;
            if($managerId != Auth::user()->id || $managerId == -1){
                abort(404, 'Not Found');
            }else{
                return $next($request);
            }
        }

        abort(404, 'Not Found');
    }
}
