<?php

namespace App\Http\Middleware;

use Closure;
use App\Business;
use App\InvoiceType;

class CheckInvoiceType
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
        if (isset($request->route()->parameters('business')['business']) &&
            isset($request->route()->parameters('invoiceType')['invoiceType'])) {
            $business = Business::find($request->route()->parameters('business')['business']);
            $invoiceType = InvoiceType::find($request->route()->parameters('invoiceType')['invoiceType']);
            if ($business != null && $invoiceType != null) {
                if ($business->user->plan->bType == $invoiceType->bType) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
