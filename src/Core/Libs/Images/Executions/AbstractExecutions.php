<?php

namespace Core\Libs\Images\Executions;

use Core\Libs\Images\Image as Image;

abstract class AbstractExecutions
{
    public $arguments;

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    abstract public function execute(Image $image);
}
