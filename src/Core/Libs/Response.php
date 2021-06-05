<?php

namespace Core\Libs;

class Response
{
    public static function json($data, $code=200)
    {
        header('Content-Type: application/json; charset=utf-8');
        Headers::setHeaderWithCode($code);
        return (json_encode($data, JSON_UNESCAPED_UNICODE));
    }

}
