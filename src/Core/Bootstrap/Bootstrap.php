<?php
declare(strict_types=1);

header('X-Powered-By: PHP Manufacture');
use Core\Bootstrap\Dispatcher;
use Core\Libs\Support\Facades\Config;
use Core\Bootstrap\ExceptionHandler;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use Monolog\Formatter\HtmlFormatter;

include dirname(__DIR__) . '/Bootstrap/constants.php';
include dirname(__DIR__) . '/Bootstrap/includes.php';
include_once ROOT_DIR . 'vendor/autoload.php';
/*
 * Monolog logger и ExceptionHandler
 */
$formatter = new HtmlFormatter();
$logger = new Logger('logger');
$stream = new StreamHandler(LOG_DIR . 'system.html', Logger::DEBUG);
$stream->setFormatter($formatter);
$logger->pushHandler($stream);

/*$log = new Logger('system');
$logger = $log->getLogger();*/

$exceptionHandler = new ExceptionHandler($logger);
$exceptionHandler->run();

/*
 *  --- Define environment ---
 */
date_default_timezone_set(Config::getConfigFromFile('timezone'));

if (Config::getConfigFromFile('environment') === 'production') {

    ini_set('display_errors', '0');

    error_reporting(0);

} elseif (Config::getConfigFromFile('environment') === 'development') {

    ini_set('display_errors', '1');
    ini_set('xdebug.collect_params', '4');

    //  error_reporting(-1);
    //  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    //  error_reporting(E_ERROR | E_WARNING);

} elseif (Config::getConfigFromFile('environment') === 'whoops') {

    ini_set('display_errors', '1');

   // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    $run = new Whoops\Run;
    $exceptionHandler = new Whoops\Handler\PrettyPageHandler;
    $JsonHandler = new Whoops\Handler\JsonResponseHandler;

    $exceptionHandler->setPageTitle('Oops! Something went wrong');
    $run->pushHandler($exceptionHandler);
    $run->register();

} else {
    die(' $conf[\'environment\'] is not configured ! ');
}

$manufacture = app(Dispatcher::class);
$manufacture->run();
