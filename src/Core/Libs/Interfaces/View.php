<?php
/**
 * Created by PhpStorm.
 * User: plamenorama
 * Date: 22.2.2019 г.
 * Time: 20:50
 */

namespace Core\Libs\Interfaces;


interface View
{
    public function render($name, $data = []);

}
