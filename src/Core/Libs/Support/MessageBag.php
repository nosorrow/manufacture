<?php

namespace Core\Libs\Support;

class MessageBag
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * MessageBag constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param array $messages
     */
    public function set(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * for dump
     * @return array
     */
    public function get($key = null)
    {
        if ($key === null) {
            return $this->messages;
        } else {
            return Arr::get($this->messages, $key);
           // return isset($this->messages[$key]) ? $this->messages[$key] : null;
        }
    }
    /**
     *
     * @return array|null
     */
    public function all()
    {
        return ($this->messages)? Arr::collapse($this->messages):null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->messages[$key][] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->messages[$key];
    }

    /**
     *  Get first message wth key in bag
     * @param $key
     * @return mixed
     */
    public function first($key)
    {
        $message = $this->get($key);

        if ($message) {
            return reset($this->messages[$key]);
        } else {
            return null;
        }
    }

    /**
     * Get the number of messages in the message bag.
     *
     * @return int
     */
    public function count()
    {
        return count($this->messages, COUNT_RECURSIVE) - count($this->messages);
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ! $this->any();
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return $this->any();
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function any()
    {
        return $this->count() > 0;
    }

    /**
     * Determine if messages exist for all of the given keys.
     *
     * @param  array|string  $key
     * @return bool
     */
    public function has($key = null)
    {

        if ($this->isEmpty()) {
            return false;
        }

        if (is_null($key)) {
            return $this->any();
        }

        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $key) {
            if ($this->first($key) == null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->messages, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return serialize($this);
    }
}
