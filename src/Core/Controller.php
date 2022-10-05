<?php

namespace Core;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Libs\Database\DataBase;
use Core\Libs\Database\MySqlDBQuery;
use Core\Libs\Request;
use Core\Libs\Url;
use Core\Libs\View;

/**
 * Class Controller
 * @method \Core\Libs\Database\MySqlDBQuery table($table)
 * @method \Core\Libs\Database\MySqlDBQuery limit($rows, $offset = 0)
 * @package Core
 */
class Controller
{
    /*use MySqlDBQuery{
        MySqlDBQuery::__construct as private __MySqlDBQueryConstructor;
    }*/

   // use MySqlDBQuery;

    public $view;

    public $request;

    public $url;

    /**
     * Controller constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $this->view = View::getInstance();

        $this->request = Request::getInstance();

        $this->url = Url::getInstance();
    }

}
