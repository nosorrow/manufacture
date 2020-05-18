<?php

namespace App\Middleware;

use Closure;

class IpChecker
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Befor Do ....... */    
        
       $response = $next($request);

       echo "my Ip is 127.0.0.1";

       return $response;

        /* After Do .......*/
    }

}
