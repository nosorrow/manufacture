<?php


namespace Core\Libs\Support;

use Illuminate\Support\Arr as LaravelArr;
/**
 * From Laravel.com
 * Illuminate\Support\Arr
 * Class Arrays
 * @package Core\Libs\Utils
 */
class Arr extends LaravelArr
{
    /**
     * Push in end of array usind dot notation
     *
     * @param $array
     * @param $key Key in dot notation
     * @param null $data data to push
     */
    public static function push(&$array, $key, $data = null)
    {
        $_array = (array)(self::get($array, $key, []));

        $_array[] = $data;

        self::set($array, $key, $_array);
    }
}
