<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Business;
use App\Customer;

class CheckCustomerAndBusiness
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
        $customer = Customer::find($request->route()->parameters('customer')['customer']);
        if ($business != null && $customer != null) {
            if (Auth::id() == $business->user->id || Auth::user()->IsAdmin()) {
                if ($customer->business->id == $business->id) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
