<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Business;
use App\Product;

class CheckProductAndBusiness
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
        $product = Product::find($request->route()->parameters('product')['product']);
        if ($business != null && $product != null) {
            if (Auth::id() == $business->user->id || Auth::user()->IsAdmin()) {
                if ($product->business->id == $business->id) {
                    return $next($request);
                }
            }
        }
        return redirect('/');
    }
}
