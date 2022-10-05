<?php
include_once dirname(__DIR__) . '/Bootstrap/constants.php';

function controller($path)
{
    $countVariablesCreated = extract($path, EXTR_SKIP);
    if ($countVariablesCreated != count($path)) {
       die('Extraction failed: scope modification attempted');
    }

    $dir = APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR . $namespace;

    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        print (sprintf('Directory "%s" was not created', $dir));
        die;
    }

    $fileName = APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($filePath) . '.php';

    $controllerNamespace = ($namespace == '') ? 'namespace App\Controllers;' : "namespace App\Controllers\\$namespace;";

    $f = <<<"CONTR"
<?php

{$controllerNamespace}

defined('APPLICATION_DIR') OR exit('No direct Access here!');

use Core\Controller;
use Core\Libs\{Request, Response, Csrf, Validator};
use Core\Libs\Support\Facades\{Url, Crypt, DB, Log, Config, Validator as ValidatorFacade};

class $className extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

}
CONTR;

    if (file_put_contents($fileName, $f)) {
        echo  "\033[32m" . 'The controller:: ' . $fileName . ':: was created Successfully'. "\e[39m";
    } else {
        die("\e[31m" . 'FAIL: The controller was not created'. "\e[39m");
    }
}
