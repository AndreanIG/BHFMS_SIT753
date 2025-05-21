<?php

namespace App\Http\Middleware;

use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\RentTransaction;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadSpecificPaymentMiddleware
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
        $invoiceId = json_decode($request->getContent())->invoice_id;
        if($invoiceId==null) {
            abort(404, 'Not Found');
        }
        $transaction = RentTransaction::where('invoice_id',$invoiceId)->first();
        
        if($transaction == null){
            abort(404, 'Not Found');
        }

        $tenantBoarding = TenantBoarding::find($transaction->tenant_boarding_id);
        if(Auth::user()->user_role_id == 1){
            return $next($request);
        }
        if(Auth::user()->user_role_id == 2 && 
        Auth::user()->id == $tenantBoarding->user_id){ //Case for tenant
            return $next($request);
        }

        if(Auth::user()->user_role_id == 3 && OwnerBoarding::where('boarding_id',$tenantBoarding->boarding_id)
        ->where('user_id',Auth::user()->id)
        ->first() != null) { //Case for Owner
            return $next($request);
        }

        if(Auth::user()->user_role_id == 4 && ManagerBoarding::where('boarding_id',$tenantBoarding->boarding_id)
        ->where('user_id',Auth::user()->id)
        ->first() != null) { //Case for Manager
            return $next($request);
        }
        
        abort(404, 'Not Found');
    }
}
