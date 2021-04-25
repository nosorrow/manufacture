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
     * Push in end of array using dot notation
     *
     * @param $array
     * @param $key * in dot notation
     * @param null $data data to push
     */
    public static function push(&$array, $key, $data = null)
    {
        $_array = (array)(self::get($array, $key, []));

        $_array[] = $data;

        self::set($array, $key, $_array);
    }

    /**
     * @override laravel arr:dot helper
     * @param array $array
     * @param string $prepend
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                foreach (static::dot($value, "$prepend$key.") as $k => $v) {
                    $result[$k] = $v;
                }
            } else {
                $result["$prepend$key"] = $value;
            }
        }
        return $result;
    }

    /**
     * @return mixed
     * $data[] = array('volume' => 98, 'edition' => 2);
     * $data[] = array('volume' => 86, 'edition' => 6);
     * $data[] = array('volume' => 67, 'edition' => 7);
     *
     * $sorted = Arr:orderby($data, 'volume', SORT_DESC, 'edition', SORT_ASC);
     */
    public static function orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        array_multisort(...$args);
        return array_pop($args);
    }

}
