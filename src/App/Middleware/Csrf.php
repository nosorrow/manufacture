<?php


namespace App\Middleware;

use Closure;

class Csrf
{
    // method for handler
    /**
     * @param $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        echo "Csrf Middleware {$request->get('id')}<br>";

        $next($request);

    }
}
