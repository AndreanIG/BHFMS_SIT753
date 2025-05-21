<?php

namespace App\Http\Middleware;

use App\Models\RentTransaction;
use App\Models\TenantBoarding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantPaymentMiddleware
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
        $invoiceId = $_GET['order'];
        $transaction = RentTransaction::where('invoice_id',$invoiceId)->first();
        if($transaction==null){
            abort(404, 'Not Found');
        }
        if(Auth::user()->user_role_id != 2 || Auth::user()->id != TenantBoarding::find($transaction->tenant_boarding_id)->user_id){
            abort(404, 'Not Found');
        }

        return $next($request);
    }
}
