<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustBeLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            return $next($request);
        }
        return redirect('/login')->with('failure', 'You Must Logged In');
    }
}