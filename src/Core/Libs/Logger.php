<?php

namespace Core\Libs;

use Psr\Log\LoggerInterface;
use \Monolog\Logger as MonologLogger;

class Logger
{
	/**
	 * @var LoggerInterface
	 */
	public $logger;

	/**
	 * Logger constructor.
	 * @param LoggerInterface $logger
	 * @throws \Exception
	 */
	public function __construct($channel = 'log', $path = 'system.log', $formatter = '')
	{
		$config = include(CONFIG_DIR . 'log.php');

		if (isset ($config[$channel])){
			$handler = $config[$channel]['handler'];
			$path = $config[$channel]['path'];
			$formatter = $config[$channel]['formatter'];

		} else {
			$handler = "Monolog\Handler\StreamHandler";
			$path = LOG_DIR . $path;
		}

		$logger = new MonologLogger($channel);
		$stream = new $handler($path, MonologLogger::DEBUG);

		if(isset($formatter) && $formatter){
			$formatter = new $formatter();
			$stream->setFormatter($formatter);
		}

		$logger->pushHandler($stream);

		$this->logger = $logger;

	}

	/**
	 * @return LoggerInterface
	 */
	public function getLogger(): LoggerInterface
	{
		return $this->logger;
	}

}
