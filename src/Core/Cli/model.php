<?php
include_once dirname(__DIR__) . '/Bootstrap/constants.php';

function model($path)
{
    $countVariablesCreated = extract($path, EXTR_SKIP);
    if ($countVariablesCreated != count($path)) {
        die('Extraction failed: scope modification attempted');
    }
    $dir = APPLICATION_DIR . 'Models' . DIRECTORY_SEPARATOR .$namespace;

    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        print (sprintf('Directory "%s" was not created', $dir));
        die;
    }

    $model = APPLICATION_DIR . 'Models' . DIRECTORY_SEPARATOR . ucfirst($filePath) . '.php';
    $modelNamespace = ($namespace == '') ? 'namespace App\Models;':"namespace App\Models\\$namespace;";
    $f =<<<CONTR
<?php

{$modelNamespace}

use Core\Model;

class $className extends Model
{

    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

}
CONTR;

    if (file_put_contents($model, $f)){
        echo 'Model - ' . $model . ' - is created';
    } else {
        die('Model not created');
    }
}
