<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        switch ($guard) {
            case 'asisten':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('asisten');
                }

                break;
            case 'praktikan':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('praktikan');
                }

                break;
            case 'all':
                if (Auth::guard('praktikan')->check()) {
                    return redirect()->route('praktikan');
                }

                if (Auth::guard('asisten')->check()) {
                    return redirect()->route('asisten');
                }

                break;
        }

        return $next($request);
    }
}
