<?php

namespace App\Http\Middleware;

use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerComplainAccessMiddleware
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
        $ownerId = OwnerBoarding::where('boarding_id', $request->id)->first()->user_id ?? -1;
        $managerId = ManagerBoarding::where('boarding_id', $request->id)->first()->user_id ?? -1;
        if(!Auth::user()->id){ //not logged in
            abort(404, 'Not Found');
        }
        if (Auth::user()->user_role_id == 3 && (Auth::user()->id !== $ownerId || $ownerId == -1)) { //case if owner
            abort(404, 'Not Found');
        }

        if(Auth::user()->user_role_id == 4 && (Auth::user()->id !== $managerId || $managerId == -1)) { //case if manager
            abort(404, 'Not Found');
        }
        return $next($request);
    }
}
