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
     *
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

        /*$request = urldecode(htmlentities($_SERVER['REQUEST_URI']));

        $request = trim($request, '/');

        $normalize = new UrlNormalizer($request);

        $request = $normalize->normalize();

        $this->script_path = $_SERVER['SCRIPT_NAME'];

        if (!empty($request)) {

            $this->removeIndex();

            // ако се извиква от пр. http://localhost/MyScripts/booking-room/public/search
            $_uri = trim(substr($request, strlen($this->script_path)), '/');

            // Ако има заявка от GET : http://booking-room.dev/booking?bar=baz
            //искам да върне само -> booking
            if (strpos($_uri, '?') !== false) {
                $this->uri = strstr($_uri, '?', true);

            } else {
                $this->uri = $_uri;
            }
        }*/
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
        $segments[0] = NULL;

        $_rs = $this->rawSegments();

        $arange = array_merge($segments, $_rs);

        unset($arange[0]);

        $this->segments = $arange;

        return $this->segments;
    }

    /**
     * @param null
     * @return string
     */
    public function segment($param = null)
    {
        $this->segments();

        return $this->segments[$param];
    }

    /**
     * @param $routename
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function route($routename, array $params = [], $http_method = null)
    {
        $this->route = $this->router->route($routename, $params, $http_method)->route;

        return $this;
    }

    /**
     * $this->route('routename', [param, param-1])->redirect();
     *
     * @param null $uri
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
