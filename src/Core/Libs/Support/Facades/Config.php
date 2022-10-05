<?php
/**
 * Date: 26.01.2020 г.
 * Time: 18:50
 */

namespace Core\Libs\Support\Facades;

use Core\Libs\AppConfig;
use Core\Libs\Interfaces\Facade as FasadeInterface;

/**
 * Class DB
 * @method static string get(string $key, string $domain)
 * @method static string getConfigFromFile(string $key, string $domain = '')
 * @package Core\Libs\Support\Facades
 */
class Config extends Facade implements FasadeInterface
{

    public const DOMAIN = 'config';

    public static $config;

    public static function getFacade()
    {
        self::$config = AppConfig::getInstance();

        return self::$config;
    }

}
