<?php

namespace Core\Libs\Files;


class JsonResponse implements IResponse
{
    private $obj;

    /**
     * JsonResponse constructor.
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->obj->getResponse());
    }

    /**
     * @return int
     */
    public function countErrors()
    {
        return count($this->obj->getError());
    }
    /**
     * @return string
     */
    public function errors()
    {
        return json_encode($this->obj->getError(), JSON_UNESCAPED_UNICODE);
    }

    public function response()
    {
        return json_encode($this->obj->getResponse(), JSON_UNESCAPED_UNICODE);

    }
}
