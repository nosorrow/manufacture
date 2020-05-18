<?php

namespace Core\Libs\Support\Facades;

use Core\Libs\Interfaces\Facade as FasadeInterface;
use Core\Libs\Session as SessionClass;

/**
 * Class Session
 * @package Core\Libs\Support\Facades
 * @method static string store($key, $value)
 * @method static string set($key, $value)
 * @method static string getData($key)
 * @method static string get_all()
 * @method static string all()
 * @method static string has($key)
 * @method static string push($key, $value)
 * @method static string pull($key, $value)
 * @method static string setFlash($key, $value)
 * @method static string getFlash($key)
 * @method static string delete($key)
 * @method static string destroy()
 *
 */
class Session extends Facade implements FasadeInterface
{

    public static function getFacade()
    {
        return SessionClass::getInstance();
    }
}
