<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Headers;
use Core\Libs\Logger;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

class ErrorPage extends Controller
{
    public $logger;

    public function __construct()
    {
        parent::__construct();

        $logger = new Logger('system');
        $this->logger = $logger->getLogger();
    }

    public function show($code)
    {
        Headers::setHeaderWithCode($code);

        if (config('template_engine') == 'blade') {
            $file = VIEW_DIR . 'Errors/' . $code . '.blade.php';
            $path = 'Errors.' . $code;
            $page404 = 'Errors.404';

        } else {
            $file = VIEW_DIR . 'Errors/' . $code . '.php';
            $path = 'Errors/' . $code;
            $page404 = 'Errors/404';
        }

        if (!file_exists($file)) {
            $this->view->render($page404);

        } else {
            $this->view->render($path);
        }

		$this->logger->warning("Error page: " . $code , [__CLASS__]);

	}
}