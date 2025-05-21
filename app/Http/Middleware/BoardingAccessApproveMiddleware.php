<?php

namespace App\Http\Middleware;

use App\Models\OwnerBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardingAccessApproveMiddleware
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
        //Approve, decline, update, banned
        //Admin
        if (Auth::user()->user_role_id == 1) {
            return $next($request);
            
        //Owner
        }else if (Auth::user()->user_role_id == 3) {
            $ownerBoarding = OwnerBoarding::where('boarding_id', $request->id)->first() ?? null;
            if($ownerBoarding->user_id == Auth::user()->id && $ownerBoarding->owner_status != 'declined'){
                return $next($request);
            }
        }
        abort(404, 'Not Found');
    }
}
