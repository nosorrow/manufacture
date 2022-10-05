<?php
/**
 * Created by PhpStorm.
 * User: plamenorama
 * Date: 27.2.2019 г.
 * Time: 19:22
 */

namespace Core\Libs\Support;


use Traversable;

class ParameterBag implements \IteratorAggregate, \Countable
{
    /**
     * Parameter storage.
     */
    protected $parameters;
    /**
     * @param array $parameters An array of parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        // TODO: Implement count() method.
    }
}
