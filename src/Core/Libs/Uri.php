<?php

namespace Core\Libs;

use Core\Bootstrap\Router;
use Core\Libs\Support\Url\UrlParser;

class Uri
{
    /**
     * @var
     */
    public $request;
    /**
     * @var
     */
    public $script_path;
    /*
     * стринг със сегментите от URI;
     */
    public $uri;
    /**
     * @var array
     */
    public $rawSegments = array();
    /*
     * Масив със подредените сегменти
     * започват с индекс 1
     */
    public $segments = array();
    /**
     * @var
     */
    public $route;
    /**
     * @var Router
     */
    public $router;
    /**
     * Uri constructor.
     * @param Router $router
     * @throws \Exception
     */
    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->uri = UrlParser::getUri();

    }
    /**
     * @return string
     */
    public function uriString()
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function rawSegments()
    {
        // -- връща масив със сегментите от URL --
        $this->rawSegments = explode("/", $this->uri);
        //array key start with 0;
        return $this->rawSegments;
    }


    /**
     *
     * @return array
     */
    public function segments()
    {
        $segments[0] = null;
        $_rs = $this->rawSegments();
        $arrange = array_merge($segments, $_rs);
        unset($arrange[0]);
        $this->segments = $arrange;

        return $this->segments;
    }

    /**
     * @param null
     * @return string
     */
    public function segment($param = null)
    {
        $segments = $this->segments();
        return $segments[$param] ?? '';

    }

	/**
	 * @param $routename
	 * @param array $params
	 * @param null $http_method
	 * @return $this
	 * @throws Exceptions\RouterExceprion
	 */
    public function route($routename, array $params = [], $http_method = null)
    {
        $this->route = $this->router->route($routename, $params, $http_method)->route;

        return $this;
    }

    /**
     * $this->route('routename', [param, param-1])->redirect();
     * @param null $uri
     * @throws \ReflectionException
     */
    public function redirect($uri = null)
    {
        if ($uri === null) {
            $uri = $this->route;
        }

        header("location:" . site_url($uri));
    }

    /**
     * @param $route
     * @param array $params
     * @throws \Exception
     */
    public function to($route, array $params = [])
    {
        $this->route($route, $params);
        $this->redirect();
    }
}
