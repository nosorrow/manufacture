<?php

namespace Core\Bootstrap;

use \App\Register;
use Core\Libs\Exceptions\RouterExceprion;

/**
 * Class MiddlewareDispatch
 * @package Core\Bootstrap
 */
class MiddlewareDispatch
{
    /**
     * @var array
     */
    public $middlewares;
    /**
     * @var
     */
    protected $middleware;
    /**
     * @var \App\Register
     */
    private $register;
    /**
     * @var
     */
    private $routeMiddlewareNames;

    /**
     * MiddlewareDispatch constructor.
     * @throws RouterExceprion
     */
    public function __construct($routeMiddlewareNames)
    {
        $this->register = new Register();
        $this->routeMiddlewareNames = $routeMiddlewareNames;
        $this->middlewares = $this->middleware();
    }

    /**
     * @return array
     * @throws RouterExceprion
     */
    protected function middleware()
    {
        $globalMiddleware = $this->getGlobalMiddlewares();
        $routeMiddlewares = $this->getRouteMiddlewares();

        if (!empty($globalMiddleware)) {
            $this->middleware = array_merge($globalMiddleware, $routeMiddlewares);
        } else {
            $this->middleware = $routeMiddlewares;
        }

        return $this->middleware;

    }

    /**
     * @return array
     */
    protected function getGlobalMiddlewares()
    {
        if (!empty($this->register->middleware)) {

            foreach ($this->register->middleware as $middleware) {
                $globalMiddleware[] = $middleware;
            }
            return $globalMiddleware;
        }
        return [];
    }

    /**
     * @return array|mixed
     * @throws RouterExceprion
     */
    protected function getRouteMiddlewares()
    {
        $middlewares = [];

        if ($this->routeMiddlewareNames) {
            $this->routeMiddlewareNames = is_array($this->routeMiddlewareNames) ?
                $this->routeMiddlewareNames : [$this->routeMiddlewareNames];

            foreach ($this->routeMiddlewareNames as $name) {
                // има ли дефиниран route middleware  в маршрута ?
                if (isset($this->register->routeMiddleware[$name])) {
                    $middlewares[] = $this->register->routeMiddleware[$name];

                } // има ли го в групата от middleware
                elseif (isset($this->register->groupMiddleware[$name]) &&
                    is_array($this->register->groupMiddleware[$name]) &&
                    !empty($this->register->groupMiddleware[$name])

                ) {
                    foreach ($this->register->groupMiddleware[$name] as $group) {
                        $middlewares[] = $group;
                    }

                } else {
                    throw new RouterExceprion(
                        sprintf('Middleware name or Group: {%s} is not registered Class', $name), 500
                    );

                }

            }
        }

        // Ако има приоритети в Register.php сортира $middlewares
        $priority = $this->register->middlewarePriority;
        if (!empty($priority)) {
            $middlewareSort = new MiddlewareSort($priority, $middlewares);
            $middlewares = $middlewareSort->getSortedMiddleware();
        }
        return $middlewares ?? [];
    }

    /**
     * @return
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }


}
