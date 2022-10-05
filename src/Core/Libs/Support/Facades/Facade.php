<?php

namespace Core\Libs\Support\Facades;

class Facade
{
    public static function __callStatic($name, $arguments)
    {
        $class  = static::getFacade();

        if (! $class) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $class->{$name}(...$arguments);
    }

}
