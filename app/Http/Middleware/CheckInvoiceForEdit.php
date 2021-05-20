<?php

namespace App\Http\Middleware;

use Closure;
use App\Invoice;

class CheckInvoiceForEdit
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
        if (isset($request->route()->parameters('invoice')['invoice'])) {
                $invoice = Invoice::find($request->route()->parameters('invoice')['invoice']);
                if ($invoice != null) {
                    if ($invoice->business->id == $request->route()->parameters('business')['business'] &&
                        $invoice->invoiceStatus->status == 'Draft') {
                            return $next($request);
                    }            
                }
        }
        return redirect('/');
    }
}
