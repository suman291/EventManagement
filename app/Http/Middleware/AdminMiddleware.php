<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'You must be logged in to view this page.');
        }

        // 2. Check if the authenticated user has the 'is_admin' attribute set to true
        if (auth()->user()->is_admin) {
            return $next($request);
        }
        return redirect()->route('login')->with('error', 'Access denied. You are not an administrator.');
    }
}
