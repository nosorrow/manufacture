<?php

namespace Core\Libs;

use Exception;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * Logger constructor.
     * @param string $channel
     * @param string $path
     * @param string $formatter
     */
<<<<<<< HEAD
    public function __construct($channel = 'log', $path = 'system.log', $formatter = '')
    {
        $config = include CONFIG_DIR . 'log.php';

        if (isset ($config[$channel])) {
            $handler = $config[$channel]['handler'];
            $path = $config[$channel]['path'];
            $formatter = $config[$channel]['formatter'];

=======
    public function __construct(
        $channel = 'log',
        $path = 'system.log',
        $formatter = ''
    ) {
        $config = include CONFIG_DIR . 'log.php';

        if (isset($config[$channel])) {
            $handler = $config[$channel]['handler'];
            $path = $config[$channel]['path'];
            $formatter = $config[$channel]['formatter'];
>>>>>>> 484e080ed6891c1aa5fb6fa85c1bb5617847c4a8
        } else {
            $handler = "Monolog\Handler\StreamHandler";
            $path = LOG_DIR . $path;
        }

        $logger = new MonologLogger($channel);
        $stream = new $handler($path, MonologLogger::DEBUG);

        if (isset($formatter) && $formatter) {
            $formatter = new $formatter();
            $stream->setFormatter($formatter);
        }

        $logger->pushHandler($stream);

        $this->logger = $logger;
<<<<<<< HEAD

=======
>>>>>>> 484e080ed6891c1aa5fb6fa85c1bb5617847c4a8
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
<<<<<<< HEAD

=======
>>>>>>> 484e080ed6891c1aa5fb6fa85c1bb5617847c4a8
}
