<?php

namespace Core\Libs;

use BadMethodCallException;
use Core\Libs\Files\Upload;
use Core\Libs\Support\ParameterBag;
use Core\Libs\Validator\ValidatesRequests;
use RuntimeException;

/**
 * Class Request
 * @package Libs
 */
class Request
{
    use ValidatesRequests;

    private static $instance = null;

    public $get = [];

    public $post = [];

    public $json = [];

    public $implicit_methods = ['POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public $cookie = [];

    public $input;

    public $method;

    public $session;

    public $file;

    public $parameterBag;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        $parameters = ['post' => $_POST];

        $this->session = Session::getInstance();

        $this->post = !empty($_POST) ? $_POST : null;

        if (!empty($_FILES)) {
            // TODO - Зaщо post е NULL ???
            $post = $this->post ?? [];
            $this->post = array_merge($post, $_FILES);
        }

        $this->get = !empty($_GET) ? $_GET : null;

        $this->cookie = !empty($_COOKIE) ? $_COOKIE : null;

        $this->file = new Upload();

        $this->httpMethodInit();

        $this->parameterBag = new ParameterBag($parameters);

    }

    /**
     * за PUT PATCH DELETE
     * методи които не се поддържат от браузъра се използва:
     * <input type="hidden" name="_method" value="DELETE">
     * helpers -> method_field()
     */
    private function httpMethodInit()
    {
        if ($this->post('_method')) {

            $this->method = strtoupper($this->post('_method'));

        } else {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

    }

    /**
     * post
     *
     * @param null $index
     * @param null $normalize
     * @return mixed
     */
    public function post($index = null, $normalize = null)
    {
        return $this->getInputData($this->post, $index, $normalize);
    }

    /**
     * @param $array
     * @param $key
     * @param $normalize_rules
     * @return array|string
     */
    protected function getInputData($array, $key, $normalize_rules)
    {
        if (strpos($key, '.')) {
            $input_data = trimValues(data_get($array, $key));

        } elseif ($key === null) {
            $input_data = trimValues($array);

        } elseif (isset($array[$key])) {
            $input_data = trimValues($array[$key]);

        } else {
            $input_data = "";
        }

        if ($normalize_rules) {
            data_normalize($input_data, $normalize_rules);
        }

        return $input_data;
    }

    /**
     * @return Request|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $name
     * @param null $normalize
     * @return mixed
     */
    public function input($name = null, $normalize = null)
    {
        if (in_array($this->method(), $this->implicit_methods)) {
            $input = 'post';

        } else {
            $input = 'get';
        }

        if ($name !== null) {
            $this->input[$name] = $this->{$input}($name, $normalize);
            return $this->input[$name];

        }

        $this->input = $this->{$input}($normalize);
        return $this->input;

    }

    /**
     * @return mixed
     */
    public function method()
    {
        return strtoupper($this->method);
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function json($key = null, $assoc = true)
    {
        $stream = file_get_contents('php://input');

        $this->json = json_decode($stream, $assoc);

        if ($key === null) {
            return $this->json;
        }

        return data_get($this->json, $key);
    }

    public function postAll($normalize = null)
    {
        $post = trimValues($this->post);

        if ($normalize !== null) {
            data_normalize($post, $normalize);
        }

        return $post;
    }

    /**
     * пълни Get -> параметрите на
     * методите на контролерите
     * @param array $data
     */
    public function setGet(array $data)
    {
        if (isset($this->get)) {
            $this->get = array_merge($data, $this->get);

        } else {
            $this->get = $data;
        }
    }

    /**
     * $_GET
     * @param null $index
     * @param null $normalize
     * @return array|bool|float|int|null|string
     */
    public function get($index = null, $normalize = null)
    {
        return $this->getInputData($this->get, $index, $normalize);
    }

    public function getAll($normalize = null)
    {
        $get = trimValues($this->get);

        if ($normalize !== null) {
            data_normalize($get, $normalize);
        }

        return $get;
    }

    /**
     * cookie
     *
     * @param $name
     * @return mixed
     */
    public function cookie($name)
    {
        return isset($_COOKIE[$name]) ? trim(htmlspecialchars(XssSecure::xss_clean($_COOKIE[$name]))) : "";
        //   return $cookie;
    }

    /**
     * @param $name
     */
    public function delete_cookie($name)
    {
        setcookie($name, 0, time() - 17200);
    }

    /**
     * @return Upload
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * @param $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool|false $secure
     * @param bool|true $httponly
     */
    public function set_cookie($name, $value = '', $expire = 3600, $path = '/', $domain = '',
                               $secure = false, $httponly = true)
    {
        /*
        * $cookie = array(
            'name'   => 'The Cookie Name',
            'value'  => 'The Value',
            'expire' => '86500',
            'domain' => '.some-domain.com',
            'path'   => '/',
            'prefix' => 'myprefix_',
            'secure' => TRUE
            );
        */
        if (is_array($name)) {
            $countVariablesCreated = extract($name, EXTR_OVERWRITE);
            if ($countVariablesCreated !== count($name)) {
                throw new RuntimeException('Extraction failed: scope modification attempted');
            }
        }

        setcookie((string)$name, $value, time() + $expire, $path, $domain, $secure, $httponly);

    }

    /**
     * @param $httpMethod
     */
    public function setMethod($httpMethod)
    {
        $this->method = $httpMethod;
    }

    /**
     * за PUT PATCH DELETE
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($this->method(), $this->implicit_methods)) {

            return $this->post($arguments[0], $arguments[1]);

        }

        throw new BadMethodCallException("Bad request method: [{$name}] ", 501);
    }

}
