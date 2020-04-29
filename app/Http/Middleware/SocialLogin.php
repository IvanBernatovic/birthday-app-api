<?php

namespace App\Http\Middleware;

use Closure;

class SocialLogin
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
        if (!in_array($request->service, ['google', 'facebook'])) {
            return abort(404, 'Driver not found');
        }

        return $next($request);
    }
}
