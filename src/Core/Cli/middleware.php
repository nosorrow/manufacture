<?php
include_once dirname(__DIR__) . '/Bootstrap/constants.php';

function middleware($path)
{
    $countVariablesCreated = extract($path, EXTR_SKIP);
    if ($countVariablesCreated != count($path)) {
        die('Extraction failed: scope modification attempted');
    }
    $dir = APPLICATION_DIR . 'Middleware' . DIRECTORY_SEPARATOR .$namespace;

    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        print (sprintf('Directory "%s" was not created', $dir));
        die;
    }

    $middleware = APPLICATION_DIR . 'Middleware' . DIRECTORY_SEPARATOR . ucfirst($filePath) . '.php';
    $middlewareNamespace = ($namespace == '') ? 'namespace App\Middleware;':"namespace App\Middleware\\$namespace;";

    $f =<<<CONTR
<?php

{$middlewareNamespace}

use Closure;

class $className
{
CONTR;

    $f.= <<<'CONTR'
    /**
     * @param $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        /* Befor Do ....... */    
        
        $next($request);

        /* After Do .......*/
    }

}
CONTR;

    if (file_put_contents($middleware, $f)){
        echo "\033[32m" . 'Middleware - ' . $middleware . ' - is created';
    } else {
        die('Middleware not created');
    }
}
