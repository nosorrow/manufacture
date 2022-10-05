<?php
/*
 * set_exception_handler
 */
namespace Core\Bootstrap;

use Core\Libs\Support\Facades\Config;

use Core\Libs\Headers;

/**
 * Class ExceptionHandler
 * @package Core\Bootstrap
 */
class ExceptionHandler
{
    /**
     * Monolog logger
     * @var
     */
    public $logger;

    /**
     * ExceptionHandler constructor.
     * @param $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function run()
    {
        set_exception_handler(array($this, '_exception'));
    }

    public function _exception($e)
    {
        if (Config::getConfigFromFile('environment') === 'development') {

//            if($e->getCode() === 404){
//                return redirect()->to('page/404');
//            }
//
            try {
                $msg = Headers::setHeaderWithCode($e->getCode());
                echo '<h2>{ ' . $e->getCode() . ' }</h2>';
                echo '<h4>{ ' . $msg . ' }</h4>';
                echo "<b>Exceptions Message: </b><span style = \"color:#FF4031; font-size: 18px;\">", $e->getMessage() . "</span> <br><strong>Line: </strong>"
                    . $e->getLine() . ' => ' . $e->getFile() . "<br><strong>Trace: </strong>" . $e->getTraceAsString();
                exit;
            } catch (\Exception $e) {
                echo '<h1>' . $e->getCode() . '</h1>';
            }

        } elseif (Config::getConfigFromFile('environment') === 'production') {

            // Всичко което не е 404
            $code = ($e->getCode() === 404) ? $e->getCode() : 500;

            try {
                if (Config::getConfigFromFile('logger') === true) {
                    $remote = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $log =
                          "Code : " . $e->getCode() . PHP_EOL
                        . " Remote host : " . $remote . PHP_EOL
                        . " USER_AGENT: " . $user_agent . PHP_EOL
                        . " Exceptions Message: " . $e->getMessage() . PHP_EOL
                        . " Line: " . $e->getLine() . PHP_EOL
                        . " Trace: " . $e->getTraceAsString();
                    if($code === 404){
                        $this->logger->error($log);

                    } else {
                        $this->logger->critical($log);
                        $mail = config('critical-email');

                        if ($mail) {
                            mail($mail,'Critical error', $log);

                        }
                    }
                }

            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            Headers::setHeaderWithCode($code);
            $path = (config('template_engine') == 'blade') ? 'Errors.'.$code : 'Errors/' . $code;

            return view($path);
        }

    }

}
