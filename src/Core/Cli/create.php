<?php
include_once 'controller.php';
include_once 'model.php';
include_once 'middleware.php';

/**
 * @param $class
 * @param $name
 */
function create($class, $name)
{
//    $e = explode(':', $class);
//    list($class, $name) = $e;
    $path = _parse($name);

 //   var_dump($path);die;

    switch ($class) {
        case 'controller':
            controller($path);
            break;
        case 'model':
            model($path);
            break;
        case 'middleware':
            middleware($path);
            break;
        default:
            die("\e[31m" . 'Cannot find command ' . $class. "\e[39m");
    }
}

function _parse($path)
{
    $filePath = ucfirst($path);
    $namespace = ucfirst(substr($filePath, 0, strrpos($filePath, '/')));
    $namespace = str_replace('/', '\\', $namespace);
    $namespace = trim($namespace, '\\');

    if (strrpos($filePath, '/')){
        $className = ucfirst(substr($filePath,strrpos($filePath, '/')+1));
    } else {
        $className = ucfirst($filePath);

    }

    return compact('filePath', 'namespace', 'className');

}
