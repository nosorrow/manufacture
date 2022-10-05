<?php


namespace Core\Bootstrap;

use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Core\Libs\Request;

class Dispatcher
{
    /**
     * @var mixed
     */
    public $frontController;
    /**
     * @var mixed
     */
    public $resolver;
    /**
     * @var
     */
    public $controller;
    /**
     * @var
     */
    public $method;

    /**
     * Dispatcher constructor.
     */
    public function __construct()
    {
        $this->frontController = app(FrontController::class);
        $this->resolver = app(ParameterResolver::class);
        $this->frontController->uriFrontControllerDispatcher();
        $this->controller = $this->frontController->getFullControllerClassName();
        $this->method = $this->frontController->getMethod();

        if (!empty($this->controller)) {
            $this->resolver->setParametersFromUri($this->frontController->getParamsFromUri())
                ->injectedMethodParameters($this->controller, $this->method)
                ->resolve();
        }
		logger('system')->info('loaded: ' . __CLASS__ . " | " . __METHOD__ );

	}

    /**
     * Run app
     */
    public function run()
    {
        $middleware = $this->frontController->getMiddleware();

        if ($middleware) {
            $pipeline = new Pipeline(new Container());

            $pipeline->send(Request::getInstance())
                ->through($middleware)
                ->then(function () {
                    $this->resolver->invoke();
                });

			logger('system')->info('loaded: ' . __CLASS__ . " | " . __METHOD__ );
        } else {
            $this->resolver->invoke();
			logger('system')->info('loaded: ' . __CLASS__ . " | " . __METHOD__ );
        }

    }
}
