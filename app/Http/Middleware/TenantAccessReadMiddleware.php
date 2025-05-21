<?php

namespace App\Http\Middleware;

use App\Models\Boarding;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAccessReadMiddleware
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
            $tenantBoarding = TenantBoarding::where('id', $request->id)->first()->boarding_id ?? -1;
            
            if($tenantBoarding == -1 ){
                abort(404, 'Not Found');
            }else{
                $owner_id = Boarding::where('id',$tenantBoarding)->first()->ownerBoardings()->first()->id;
                    if($owner_id != Auth::user()->id){
                        abort(404, 'Not Found');
                    }else{
                        return $next($request);
                    }
            }

        //Manager
        }else if (Auth::user()->user_role_id == 4) {
            $tenantBoarding = TenantBoarding::where('id', $request->id)->first()->boarding_id ?? -1;
            if($tenantBoarding == -1 ){
                abort(404, 'Not Found');
            }else{
                $managerId = Boarding::where('id',$tenantBoarding)->first()->managerBoardings()->first()->id;
                    if($managerId != Auth::user()->id){
                        abort(404, 'Not Found');
                    }else{
                        return $next($request);
                    }
            }
                    
        }

        abort(404, 'Not Found');
    }
}
