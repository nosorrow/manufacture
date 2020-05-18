<?php

namespace Core\Bootstrap;

use Core\Libs\Exceptions\RouterExceprion;
use Core\Libs\Uri;
use Core\Libs\Request;

class FrontController
{
    /**
     * @var
     */
    public $dir;
    /**
     * Middleware name
     * @var
     */
    protected $middlewareName;
    /**
     * All callbacks of middlewares
     * @var
     */
    protected $middleware = [];
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Uri
     */
    protected $uri;
    /**
     * @var
     */
    protected $router;
    /**
     * @var array
     */
    protected $route;
    /**
     * @var
     */
    protected $routesInit;
    /**
     * @var
     */
    protected $fullControllerClassName;
    /**
     * @var
     */
    protected $method;
    /**
     * @var array
     */
    protected $params_from_uri = array();
    /**
     * @var MiddlewareDispatch
     */
    private $middlewareDispatch;

    /**
     * FrontController constructor.
     * @param Uri $uri
     * @param Router $router
     */
    public function __construct(
        Uri $uri,
        Router $router,
        Request $request

    )
    {
        $this->uri = $uri;
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function uriFrontControllerDispatcher()
    {
        $httpMethod = $this->request->method();

        $_uri = $this->uri->uriString();

        if (!$_uri) {
            $_uri = '/';
        }

        // параметрите на методите = масив;
        $this->params_from_uri = array();

        $route = $this->router->dispatch($httpMethod, urldecode($_uri));

        // Има ли Middleware ?
        if ($route['middleware']) {
            $this->setRouteMiddlewareName($route['middleware']);
        }

        if (!is_string($route['action']) && is_callable($route['action'])) {
            if (($route['params']) == null) {
                call_user_func($route['action']);
                exit;

            } else {
                call_user_func_array($route['action'], $route['params']);
                exit;
            }

        }

        if (isset($route['params'])) {
            $this->params_from_uri = array_values($route['params']);

        }
        // pull in $_GET
        if (!empty($route['params'])) {
            $this->request->setGet($route['params']);
        }

        // Folder/SubFolder/Controller@Method
        $action_str = $route['action'];

        if (strpos($action_str, '/') !== false) {
            $this->dir = substr($action_str, 0, strrpos($action_str, '/') + 1);
            $act = substr($action_str, strrpos($action_str, '/') + 1);
        } else {
            $act = $action_str;
        }

        if (strpos($act, '@') !== false) {
            $controller = substr($act, 0, strpos($act, '@'));
            $this->method = substr($act, strpos($act, '@') + 1);

        } else {
            $controller = $act;
            $this->method = 'index';
        }

        // Има ли такъв файл ?
        $controller_file = (APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->dir) . ucfirst($controller) . '.php');

        if (!file_exists($controller_file) && !is_readable($controller_file)) {

            throw new \Exception('FrontController say - File not found :  [ ' . $controller_file . ' ] ', 500);
        }

        //Контролерът живее в namespace App\Controllers
        $namespace = 'App\Controllers' . "\\" . str_replace('/', '\\', $this->dir);
        $this->fullControllerClassName = $namespace . ucfirst($controller);

        return $this;
    }

    /**
     * @param $middleware
     */
    public function setRouteMiddlewareName($middleware)
    {
        $this->middlewareName = $middleware;
    }

    /**
     * @return mixed
     */
    public function getFullControllerClassName()
    {
        return $this->fullControllerClassName;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParamsFromUri()
    {
        return (array)($this->params_from_uri);
    }

    /**
     * @return mixed
     * @throws RouterExceprion
     */
    public function getMiddleware()
    {
        $routeMiddlewareNames = $this->getRouteMiddlewareName();
        $middlewares = new MiddlewareDispatch($routeMiddlewareNames);

        return $middlewares->getMiddlewares();
    }

    /**
     * @return mixed
     */
    public function getRouteMiddlewareName()
    {
        return ($this->middlewareName) ?? "";
    }

}
