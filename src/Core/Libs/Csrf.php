<?php

namespace Core\Libs;

use Core\Libs\Session;

class Csrf
{
    /**
     * @var Session
     */
    public $session;
    /**
     * @var
     */
    public $request;
    /**
     * @var mixed|null
     */
    public $csrf_old_token = null;

    /**
     * @var null
     */
    public $csrf_token = null;

    /**
     * Csfr constructor.
     * @param \Core\Libs\Request $request
     * @throws \Exception
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->session = Session::getInstance();
        if ($this->session->getData('csrf_token')) {
            $this->csrf_old_token = $this->session->getData('csrf_token');
        }
    }

    /**
     * @return string
     */
    protected function setToken()
    {
        return md5(bin2hex(random_bytes(5)));
    }

    /**
     * @return void
     */
    public function csrf_field()
    {
        $this->csrf_token = $this->setToken();
        $this->session->store('csrf_token', $this->csrf_token);
        echo "<input type='hidden' name='csrf_token' id='csrf_token' value='" .
            $this->csrf_token .
            "' />" .
            PHP_EOL;
    }

    /**
     * @return bool
     */
    public function csrf_validate()
    {
        if ($this->request->post('csrf_token') === '') {
            return false;
        }

        if (
            $this->request->method() === 'POST' &&
            $this->request->post('csrf_token') === $this->csrf_old_token
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     * @throws \Exception
     */
    public static function validate()
    {
        return app(__CLASS__)->csrf_validate();
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        throw new \BadMethodCallException(
            "Bad Csrf method {'$name'} " . implode(', ', $arguments)
        );
    }
}
