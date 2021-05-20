<?php

namespace App\Http\Middleware;

use Closure;

class CheckReceipt
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
        if (isset($request->route()->parameters('receipt')['receipt'])) {
            $receipt = Receipt::find($request->route()->parameters('receipt')['receipt']);
            if ($receipt!=null)
            {
                if ($receipt->business->id == $request->route()->parameters('business')['business']) {
                    return $next($request);
                }
                
            }
        }

        return redirect('/');
        
    }
}
