<?php


namespace App\Middleware;

use Closure;

class Auth
{
    /**
     * @param $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        echo "Auth Middleware<br>";

        $next($request);

    }
}
