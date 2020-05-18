<?php

namespace Core\Libs\Support\Facades;

use Core\Libs\Uri as ManufactureUri;

/**
 * Class Url
 * @method static string uriString()
 * @method static string segments()
 * @method static string rawSegments()
 * @method static string segment(int $param)
 * @method static redirect(string $uri)
 * @method static string to($route, array $params = [])
 */
class Uri extends Facade implements \Core\Libs\Interfaces\Facade
{

    public static function getFacade()
    {
       return app(ManufactureUri::class);
    }
}
