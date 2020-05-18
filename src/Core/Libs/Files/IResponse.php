<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.2.2017 г.
 * Time: 19:35 ч.
 */

namespace Core\Libs\Files;


interface IResponse
{
    public function count();

    public function countErrors();

    public function errors();

    public function response();

}
