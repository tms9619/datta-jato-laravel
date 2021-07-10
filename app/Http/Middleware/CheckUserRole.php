<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
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
        if(!Auth::check()){
            return redirect('login');
        }

        if(Auth::user()->role == "cliente"){
            return redirect('user_cliente');
        }

        if(Auth::user()->role == "operador"){
            return redirect('user_operador');
        }

        return $next($request);
    }
}
