<?php

/*
 * dependency injection container =>
 *
$dic = new Dic();

    $connection = new Database\Connection('lesson', 'root', 'pass');
    $dic->setInstance($connection);

    $dic->set('Database\Connection', function () {
        return new Database\Connection('Personal', 'root', 'pass');
    });

    $dic->setFactories('Model', function () use ($dic) {
        return new Model($dic->get('Database\Connection'));
    } );

    $dic->setFactories('Bar', function () use ($dic) {
        return new Bar( $dic->get('Foo'));
        });

     var_dump( $dic->get('Bar') );
 *
 */

namespace Core\Bootstrap;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class DiContainer implements ContainerInterface
{

    /**
     * @var array
     */
    public $class = [];
    /**
     * @var array
     */
    public $registry = [];
    /**
     * Singleton
     * @var array
     */
    public $instances = [];
    /**
     * @var array
     */
    public $factories = [];

    /**
     * Set a Singleton instance
     * @param $key
     * @param callable $resolver
     */
    public function set($key, Callable $resolver)
    {
        $this->registry[$key] = $resolver;

    }

    /**
     * @param $key
     * @param callable $resolver
     */
    public function setFactories($key, Callable $resolver)
    {
        $this->factories[$key] = $resolver;
    }

    /**
     *
     * @param $instance
     * @throws ReflectionException
     */
    public function setInstance($instance)
    {

        $reflection = new ReflectionClass($instance);

        $this->instances[$reflection->getName()] = $instance;

    }

	/**
	 * @param $key
	 * @return mixed
	 * @throws ReflectionException
	 * @throws \Exception
	 */
    public function get($key)
    {
        if (isset($this->factories[$key])) {

            return $this->factories[$key]();
        }

        // Get Singleton
        if (!isset($this->instances[$key])) {

            if (isset($this->registry[$key])) {

                $this->instances[$key] = $this->registry[$key]();

            } else {

                $reflectionClass = new ReflectionClass($key);

                if ($reflectionClass->isInstantiable()) {

                    $constructor = $reflectionClass->getConstructor();

                    if ($constructor) {

                        $parameters = $constructor->getParameters();
                        $constructor_parameters = [];

                        foreach ($parameters as $parameter) {
							// PHP 8 DEPRECATED getClass();
                            /*if ($parameter->getClass()) {
                                $constructor_parameters[] = $this->get($parameter->getClass()->getName());*/

							// php8
							$type = $parameter->getType();

							if(null !== $type && !$type->isBuiltin()){
								$constructor_parameters[] = $this->get($type->getName());
                            } else {
                                $constructor_parameters[] = $parameter->getDefaultValue();
                            }
                        }

                        $this->instances[$key] = $reflectionClass->newInstanceArgs($constructor_parameters);

                    } else {
                        $this->instances[$key] = $reflectionClass->newInstance();

                    }

                } else {
                    $this->instances[$key] = $key::getInstance();

                }

            }
        }
        if (isset($this->instances[$key])) {
            return $this->instances[$key];

        }

		throw new \Exception("DiContainer error: cannot find instance of \{$key\} class", 500);
	}

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        // TODO: Implement has() method.
    }
}
