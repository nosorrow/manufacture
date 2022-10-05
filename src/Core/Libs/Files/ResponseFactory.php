<?php

namespace Core\Libs\Files;

use Core\Bootstrap\DiContainer;

class ResponseFactory
{
    public $dic;

    public function __construct(){
        $this->dic = new DiContainer();
    }

    public static function makeResponse($obj, $class = null)
    {
        $class = ucfirst(strtolower($class));
        $_class = ($class == null || $class == 'Response') ?
            'Core\Libs\Files\Response' :
            'Core\Libs\Files\\' . $class . 'Response';

        return new $_class($obj);
    }

}
