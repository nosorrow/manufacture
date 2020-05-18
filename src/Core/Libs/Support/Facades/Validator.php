<?php

namespace Core\Libs\Support\Facades;
/**
 * Class Validator
 * @method static \Core\Libs\Validator make(string $field, string $fieldname, array $rules)
 * @method static \Core\Libs\Validator for(array $data)
 * @method static run()
 * @package Core\Libs\Support\Facades
 */
class Validator extends Facade implements \Core\Libs\Interfaces\Facade
{
    /**
     * @method static for($data)
     * @return \Core\Libs\Validator mixed
     */
    public static function getFacade()
    {
        return app(\Core\Libs\Validator::class);
    }
}
