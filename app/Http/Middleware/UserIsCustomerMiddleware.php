<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIsCustomerMiddleware
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
        if($request->user()->customer == 1) {
            return $next($request);
        } else {
            return redirect()->back()->with('error', "Vous n'avez pas accès à cette interface");
        }
    }
}
