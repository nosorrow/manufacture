<?php

namespace App\Middleware;

use Closure;
use Core\Libs\Support\Facades\Crypt;

class EncryptCookies
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $response = $next($request);

        echo "EncryptCookies Middleware <br>";
        // var_dump(sessionData('manufacture'));
        //$c = Crypt::encrypt($request->cookie('manufacture'));
      //  $request->set_cookie('manufacture', $c);
        var_dump($request->cookie('manufacture'));

        return $response;
    }

}
