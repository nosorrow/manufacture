<?php

namespace Core\Libs;

/*
 * Употреба
 *
 * $message = new Libs\Message();
 *
 * $error = $message->get('ErrorFile')->line('line');
 *
 */

use Exception;

class Message
{
	public $dir = null;

	public $_msg = array();


	/**
	 * Message constructor.
	 */
	public function __construct()
	{
		$this->dir = get_locale_lang();
	}

	/**
	 * @param $file
	 * @return $this
	 * @throws Exception
	 */
	public function get($file = null)
	{
		if (!$file) {
			$path = RESOURCES_DIR . 'messages' . DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR . 'messages' . '.php';

		} else {
			$file = dot_notation_dir($file);
			$path = RESOURCES_DIR . 'messages' . DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR . $file . '.php';
			$locale_lang = $this->dir;

			if (strpos($locale_lang, '_') === false) {
				/* Да връща пълен ISO 639 ISO 3166 формат (ll_CC) ако lang е само e двубуквен езиков код (lang=en).
				  за системните директории */
				$country = $locale_lang === 'en' ? 'us' : $locale_lang;
				$locale_lang .= '_' . strtoupper($country);

				$locale_lang = strtolower($locale_lang);
			}

			$sys_path = SYSTEM_DIR . 'Messages' . DIRECTORY_SEPARATOR . $locale_lang . DIRECTORY_SEPARATOR . $file . '.php';

		}

//        if (realpath($path) &&
//            is_readable($path) &&
//            realpath($sys_path) &&
//            is_readable($sys_path)
//
//        )
		if (file_exists($path)) {
			$message = include $path;

		} else {
			throw new Exception('Language file not found: [ ' . $path . ' ]', 404);
		}

		$this->_msg = $message;

		return $this;
	}

	/**
	 * @param $line
	 * @return mixed
	 */
	public function line($line)
	{
		return $this->_msg[$line] ?? $line;
	}

	private function getPath($file)
	{

	}
}
