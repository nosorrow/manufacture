<?php

namespace App\Middleware;

use Closure;
/* Enable CORS policy
 * https://github.com/websanova/laravel-api-demo/blob/master/app/Http/Middleware/Cors.php
 * https://github.com/axios/axios/issues/569
 *
*/
class Cors
{    /**
     * @param $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        /* Befor Do ....... */
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
        header('Access-Control-Expose-Headers: Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        $next($request);

        /* After Do .......*/
    }

}
