<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidarStatus
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
        if($request->user()->status == 1)
        return $next($request);
        else
        {
            abort(400,"Debes de estar activo");
        }
    }
}
