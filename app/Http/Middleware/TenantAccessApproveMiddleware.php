<?php

namespace App\Http\Middleware;

use App\Models\Boarding;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAccessApproveMiddleware
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
            $tenantBoarding = TenantBoarding::where('id', $request->id)->first() ?? null;
            
            if($tenantBoarding == null ){
                abort(404, 'Not Found');
            }else{
                $ownerId = Boarding::where('id',$tenantBoarding->boarding_id)->first()->ownerBoardings()->first()->id;
                $tenantStatus = $tenantBoarding->tenant_status;
                    if($ownerId == Auth::user()->id && $tenantStatus == 'pending'){
                        return $next($request);
                        
                    }
            }

        //Manager
        }else if (Auth::user()->user_role_id == 4) {
            $tenantBoarding = TenantBoarding::where('id', $request->id)->first() ?? null;
            if($tenantBoarding == null ){
                abort(404, 'Not Found');
            }else{
                $managerId = Boarding::where('id',$tenantBoarding->boarding_id)->first()->managerBoardings()->first()->id;
                $tenantStatus = $tenantBoarding->tenant_status;
                    if($managerId == Auth::user()->id && $tenantStatus == 'pending'){
                        return $next($request);
                        
                    }
            }
                    
        }

        abort(404, 'Not Found');
    }
}
