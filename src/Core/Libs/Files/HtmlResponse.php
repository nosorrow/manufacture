<?php

namespace Core\Libs\Files;


class HtmlResponse implements IResponse
{
    private $obj;

    /**
     * HtmlResponse constructor.
     * @param $obj
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
        $_e = '';

        $errors = $this->obj->getError();

        if (is_array($errors)){
            foreach ($errors as $error) {
                $_e .= '<p>' . $error . '</p>';
            }
        } else {
            $_e .= '<p>' . $errors . '</p>';
        }

        return $_e;
    }

    /**
     * @return string
     */
    public function response()
    {
        if(count($this->obj->getResponse()) == 0){
            return null;
        }
        $_e = 'Успешно качихте ' . $this->count() . ' файла: <br>';
        $_e .= '<p>';
        foreach ($this->obj->getResponse() as $response) {

            $_e .= $response['file_name'] . ', ';
        }
        $_e = rtrim($_e, ', ');
        $_e .= '</p>';

        return $_e;
    }
}
