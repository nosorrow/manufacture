<?php

namespace Core\Libs;


class Response
{
    public static function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');

        return (json_encode($data, JSON_UNESCAPED_UNICODE));
    }

}
