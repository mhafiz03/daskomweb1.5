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
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        // If no guard is specified, check all guards
        if ($guard === null) {
            if (Auth::guard('praktikan')->check()) {
                return redirect()->route('praktikan.dashboard');
            }

            if (Auth::guard('asisten')->check()) {
                return redirect()->route('asisten.dashboard');
            }
        } else {
            switch ($guard) {
                case 'asisten':
                    if (Auth::guard($guard)->check()) {
                        return redirect()->route('asisten.dashboard');
                    }
                    break;

                case 'praktikan':
                    if (Auth::guard($guard)->check()) {
                        return redirect()->route('praktikan.dashboard');
                    }
                    break;

                case 'all':
                    if (Auth::guard('praktikan')->check()) {
                        return redirect()->route('praktikan.dashboard');
                    }

                    if (Auth::guard('asisten')->check()) {
                        return redirect()->route('asisten.dashboard');
                    }
                    break;
            }
        }

        return $next($request);
    }
}
