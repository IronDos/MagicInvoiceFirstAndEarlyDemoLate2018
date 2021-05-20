<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Business;
use App\User;

class CheckBusinessAndUser
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
        if ($business != null) {
            if (Auth::id() == $business->user->id || Auth::user()->IsAdmin()) {
                return $next($request);
            }
        }
        return redirect('/');
    }
}
