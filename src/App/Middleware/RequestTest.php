<?php

namespace App\Middleware;

use Closure;

class RequestTest
{    /**
     * @param $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        echo "RequestTest Middleware ID: " . $request->id . " | ";

        $next($request);

        /* After Do .......*/
    }

}
