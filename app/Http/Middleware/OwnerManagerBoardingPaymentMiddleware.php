<?php

namespace App\Http\Middleware;

use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OwnerManagerBoardingPaymentMiddleware
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
        try {
            $boardingId = null;

            if(isset($_GET['tenantBoarding'])){
                $boardingId = TenantBoarding::find($_GET['tenantBoarding'])->boarding_id;
            }
            if(isset($_GET['boarding']) || $boardingId !== null) {
                
                $boardingId = $boardingId === null ? $_GET['boarding'] : $boardingId;
                if (Auth::user()->user_role_id == 3 && OwnerBoarding::where('boarding_id',$boardingId)
                ->where('user_id',Auth::user()->id)
                ->where('owner_status','approved')->first() != null) { //case if owner
                    return $next($request);
                }
        
                if(Auth::user()->user_role_id == 4 && 
                (ManagerBoarding::where('boarding_id',$boardingId)
                ->where('user_id',Auth::user()->id)->first() != null || 
                OwnerBoarding::where('boarding_id',$_GET['boarding'])
                ->value('owner_status') == 'approved')) { //case if manager
                    return $next($request);
                }
            }


        } catch (Throwable $e) {
            abort(500, 'Error Occurred');
        }
        abort(404, 'Not Found');
        
    }
}
