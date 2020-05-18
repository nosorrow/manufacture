<?php
/**
 * Date: 1.4.2019 г.
 * Time: 13:48
 */

namespace Core\Libs\Support\Facades;

use Core\Libs\Logger;

/**
 * Class Log
 *
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @package Core\Libs\Utils\Facades
 */
class Log extends Facade implements \Core\Libs\Interfaces\Facade
{
    public static function getFacade()
    {
        $log = new Logger('log');
        $logger = $log->getLogger();
        return $logger;
    }

    public static function channel($name, $path = 'manufacture.log', $formatter ='')
    {
        $log = new Logger($name, $path, $formatter);
        $logger = $log->getLogger();
        return $logger;
    }
}
