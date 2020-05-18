<?php

namespace Core\Libs\Validator;

use Core\Libs\Validator;

/**
 * Trait ValidatesRequests
 * @package Core\Libs\Validator
 */
trait ValidatesRequests
{
    public function validation()
    {
        $validator = app(Validator::class);

        return $validator->for($this->input());

    }
}
