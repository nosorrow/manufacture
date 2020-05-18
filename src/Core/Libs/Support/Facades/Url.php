<?php
/**
 * Date: 7.4.2019 г.
 * Time: 17:22
 */

namespace Core\Libs\Support\Facades;

use Core\Libs\Url as ManufactureUrl;

/**
 * Class Url
 * @method static string getSiteUrl(string $uri)
 * @method static string current()
 * @method static string full()
 * @method static previous()
 * @method static string request()
 * @method static string path()
 * @method static string host()
 * @method static string scheme()
 * @method static string query()
 * @method static string isValidUrl(string $poth)
 * @method static string refresh(string $delay)

 */
class Url extends Facade implements \Core\Libs\Interfaces\Facade
{

    public static function getFacade()
    {
       return app(ManufactureUrl::class);
    }
}
