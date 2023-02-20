<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificaInvitado
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
        if($request->user()->rol_id == 1 || $request->user()->rol_id == 3)
        return $next($request);
        else
        {
            abort(400,"Debes ser Invitado o Admin");
        }
    }
}
