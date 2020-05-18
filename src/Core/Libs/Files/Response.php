<?php

namespace Core\Libs\Files;


class Response implements IResponse
{
    public $obj;

    public $response = [];
    /**
     * Response constructor.
     * @param $obj
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    public function count()
    {
        return count($this->obj->getResponse());
    }

    public function errors()
    {
        return $this->obj->getError();
    }

    public function response()
    {
       $this->response = $this->obj->getResponse();

        return $this->response;
    }

    public function countErrors()
    {
        return count($this->obj->getError());
    }
}
