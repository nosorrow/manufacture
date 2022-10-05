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
     *
     * @return array|null
     */
    public function all()
    {
        return ($this->messages) ? Arr::collapse($this->messages) : null;
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
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->messages[$key][] = $value;
    }

	public function unset($key): void
	{
		unset($this->messages[$key]);
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
     * Get the number of messages in the message bag.
     *
     * @return int
     */
    public function count()
    {
        return count($this->messages, COUNT_RECURSIVE) - count($this->messages);
    }

    /**
     * Determine if messages exist for all of the given keys.
     *
     * @param array|string $key
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
            if ($this->first($key) === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the message bag has any messages.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return !$this->any();
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
        }

        return null;
    }

    /**
     * for dump
     * @return array
     */
    public function get($key = null)
    {
        if ($key === null) {
            return $this->messages;
        }

        return Arr::get($this->messages, $key);
        // return isset($this->messages[$key]) ? $this->messages[$key] : null;
    }

    /**
     * Convert the object to its JSON representation.
     * @return string
     * @throws \JsonException
     */
    public function toJson()
    {
        return json_encode($this->messages, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

	public function toArray(): array
	{
		return $this->messages;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return serialize($this);
    }
}
