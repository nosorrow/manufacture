<?php

namespace Core\Bootstrap;

/**
 * Class MiddlewareSort
 * @package Core\Bootstrap
 */
class MiddlewareSort
{
    /**
     * @var array
     */
    protected $sortedMiddleware = [];

    /**
     * MiddlewareSort constructor.
     */
    public function __construct($priority, $middlewares)
    {
        $this->sortedMiddleware = $this->middlewareSortPriority($priority, $middlewares);
    }

    /**
     * @param $priority
     * @param $middlewares
     * @return array
     */
    protected function middlewareSortPriority($priority, $middlewares)
    {
        $middlewaresSorted = [];

        foreach ($middlewares as $key => $middleware) {
            if (in_array($middleware, $priority)) {
                $index = array_search($middleware, $priority);
                $middlewaresSorted[$index] = $middleware;
            }
        }
        ksort($middlewaresSorted);
        return array_merge($middlewaresSorted, array_diff($middlewares, $middlewaresSorted));
    }

    /**
     * @return mixed
     */
    public function getSortedMiddleware()
    {
        return $this->sortedMiddleware;
    }


}
