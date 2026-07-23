<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
        return redirect('/login');
    }

    if (trim(Auth::user()->role) !== trim($role)) { // Ditambah trim() untuk keselamatan
            return abort(403, 'Unauthorized action.');
        }

    return $next($request);
    }
}
