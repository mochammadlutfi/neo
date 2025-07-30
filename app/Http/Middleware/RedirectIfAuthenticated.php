<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard == "admin" && Auth::guard($guard)->check()) {
                return redirect()->route('admin.beranda');
            }
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                // Don't redirect if already on verification pages
                if ($request->routeIs(['verification.notice', 'verification.verify', 'verification.send'])) {
                    return $next($request);
                }
                // Check if user is authenticated but not verified
                if ($user && !$user->hasVerifiedEmail()) {
                    return redirect()->route('verification.notice');
                }
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
