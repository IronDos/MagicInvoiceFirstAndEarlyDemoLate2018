<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Business;
use App\Invoice;

class CheckInvoiceAndBusiness
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $business = Business::find($request->route()->parameters('business')['business']);
        $invoice = Invoice::find($request->route()->parameters('invoice')['invoice']);
        if ($business != null && $invoice != null) {
            if (Auth::id() == $business->user->id || Auth::user()->IsAdmin()) {
                if ($invoice->business->id == $business->id) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
