<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    public function handle(Request $request, closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        return redirect('/')->with('error','Anda tidak memiliki akses ke halaman ini.');
    }
}
