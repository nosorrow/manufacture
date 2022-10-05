<?php

namespace Core\Libs;

use BadMethodCallException;
use Core\Libs\Session;
use Exception;

class Csrf
{
	/**
	 * @var Session
	 */
	public $session;
	/**
	 * @var
	 */
	public $request;
	/**
	 * @var mixed|null
	 */
	public $csrf_old_token = null;

	/**
	 * @var null
	 */
	public $csrf_token = null;

	/**
	 * Csfr constructor.
	 * @param Request $request
	 * @throws Exception
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->session = Session::getInstance();
		if ($this->session->getData('csrf_token')) {
			$this->csrf_old_token = $this->session->getData('csrf_token');
		}
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return bool
	 * @throws Exception
	 */
	public static function validate()
	{
		return app(__CLASS__)->csrf_validate();
	}

	/**
	 * @return void
	 */
	public function csrf_field()
	{
		$this->csrf_token = $this->setToken();
		$this->session->store('csrf_token', $this->csrf_token);
		echo "<input type='hidden' name='csrf_token' id='csrf_token' value='" .
			$this->csrf_token .
			"' />" .
			PHP_EOL;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	protected function setToken()
	{
		return md5(bin2hex(random_bytes(5)));
	}

	/**
	 * @return bool
	 */
	public function csrf_validate(): bool
	{
		/*if ($this->request->post('csrf_token') === '') {
			return false;
		}

		if ($this->request->json('csrf_token') === '') {
			return false;
		}*/

		if ($this->request->post('csrf_token') !== '') {
			$csrf_token = $this->request->post('csrf_token');

		} elseif ($this->request->json('csrf_token') !== "") {
			$csrf_token = $this->request->json('csrf_token');

		} else {
			return false;

		}

		/*if (
			$this->request->method() === 'POST' &&
			$this->request->post('csrf_token') === $this->csrf_old_token) {
			return true;
		}*/

		return $this->request->method() === 'POST' &&
			$csrf_token === $this->csrf_old_token;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @throws Exception
	 */
	public function __call($name, $arguments)
	{
		throw new BadMethodCallException(
			"Bad Csrf method {'$name'} " . implode(', ', $arguments)
		);
	}
}
