<?php

namespace App\Http\Middleware;

use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadPaymentMiddleware
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
        if(!isset($_GET['boarding']) || $_GET['boarding'] === 'null') {
            return $next($request);
        }
        $boardingId = $_GET['boarding'];
        if($boardingId!=null){
            if(Auth::user()->user_role_id == 3 && OwnerBoarding::where('boarding_id',$boardingId)
            ->where('user_id',Auth::user()->id)
            ->first() == null) { //Case for Owner
                abort(404, 'Not Found');
            }
    
            if(Auth::user()->user_role_id == 4 && ManagerBoarding::where('boarding_id',$boardingId)
            ->where('user_id',Auth::user()->id)
            ->first() == null) { //Case for Manager
                abort(404, 'Not Found');
            }
        }
        return $next($request);
    }
}
