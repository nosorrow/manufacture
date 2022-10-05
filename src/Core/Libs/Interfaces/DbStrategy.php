<?php
/**
 * Date: 3.4.2019 г.
 * Time: 12:53
 */

namespace Core\Libs\Interfaces;

interface DbStrategy
{
    public function get();

    public function connection($connection);
}
