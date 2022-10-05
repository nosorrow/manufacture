<?php

namespace Core\Libs\Support\Facades;
use Core\Libs\Request;
/**
 * Class Validator
 * @method static \Core\Libs\Validator make(string $field, string $fieldname, array $rules)
 * @method static \Core\Libs\Validator ruleFor(string $field, string $fieldname, array $rules)
 * @method static \Core\Libs\Validator for(array|Request $data)
 * @method static \Core\Libs\Validator set_error(string $field, string $msg)
 * @method static \Core\Libs\Validator toJson()
 * @method static \Core\Libs\Validator messageBag()

 * @method static run()
 * @package Core\Libs\Support\Facades
 */
class Validator extends Facade implements \Core\Libs\Interfaces\Facade
{
    /**
     * @method static for($data)
     * @return \Core\Libs\Validator mixed
     */
    public static function getFacade(): \Core\Libs\Validator
	{
        return app(\Core\Libs\Validator::class);
    }
}
