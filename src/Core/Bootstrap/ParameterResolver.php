<?php
/*
 * приоритета на аргументите е ;
 *
 * [ DI обекти / аргументи от uri / optional args ]
 *
 * примерно : function booking( \Libs\Session $s, $id = 444, $post = 555, $ab = 666, $bc=111)
 * URI : http://booking-room.dev/booking/888/999
 *  ще върне:
 * [ object $s / $id = 888/ $post = 999 / $ab = 666/ $bc = 111 ]
 *
 */
namespace Core\Bootstrap;

class ParameterResolver
{
    /**
     * @var
     */
    public $class;
    /**
     * @var
     */
    public $method;
    /**
     * @var DiContainer
     */
    public $dic;
    /**
     * инстанциите на Injected params
     * @var array
     */
    public array $di_parameters = [];
    /**
     * Optional params
     * @var array
     */
    public array $optional_parameters = [];
    /**
     * масив с параметрите от URI
     * @var array
     */
    public array $uri_parameters = [];
    /**
     * @var array
     */
    public array $resolved_parameters = [];

    public function __construct(DiContainer $dic)
    {
        // $this->dic = $dic;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParametersFromUri(array $params)
    {
        $this->uri_parameters = $params;

        return $this;
    }

    /**
     * Взима DI и dafault параметрите от метода на обекта
     * @param $classname
     * @param $method
     * @return $this
     * @throws \ReflectionException
     */
    public function injectedMethodParameters($classname, $method)
    {
        $this->method = $method;
        $this->class = $classname;
        $reflectionClass = new \ReflectionClass($classname);
        $_params = $reflectionClass->getMethod($method)->getParameters();

        /*
         * PHP8 DEPRECATED getClass()
         * foreach ($_params as $value) {
            if ($value->getClass() !== null) {
                $this->di_parameters[] = app($value->getClass()->getName());

            } elseif ($value->isDefaultValueAvailable()) {
                $this->optional_parameters[] = $value->getDefaultValue();

            } else {
                $this->optional_parameters[] = 'notoptional';
            }
        }*/

		foreach ($_params as $parameter) {
			$type = $parameter->getType();
			//php8
			if (null !== $type && !$type->isBuiltin()) {
				$this->di_parameters[] = app($type->getName());

			} elseif ($parameter->isDefaultValueAvailable()) {
				$this->optional_parameters[] = $parameter->getDefaultValue();

			} else {
				$this->optional_parameters[] = 'notoptional';
			}
		}

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function resolve()
	{
        $_paramsRepalcement = array_replace(
            $this->optional_parameters,
            $this->uri_parameters
        );

        $this->resolved_parameters = array_merge(
            $this->di_parameters,
            $_paramsRepalcement
        );

        // ако в параметрите има параметър без стойност хвърля Exceptions
        if (in_array('notoptional', $this->resolved_parameters)) {
            throw new \Exception(
                'Incorrectly passed method parameters  [ ' .
                    $this->method .
                    ' ] ' .
                    ' in Class ' .
                    $this->class,
                500
            );
        }
        return $this;
    }

    /**
     * Изиква метода
     * @throws \ReflectionException
     */
    public function invoke()
    {
        $obj = app($this->class);
        call_user_func_array([$obj, $this->method], $this->resolved_parameters);
    }
}
