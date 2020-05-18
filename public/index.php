<?php

if ((version_compare(PHP_VERSION, '7.0') >= 0)) {

    // ako src е извън public директорията на сървъра.

    //$autoloadPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/src/Core/Bootstrap/Bootstrap.php';
    //$autoloadPath =  (dirname(__DIR__) . '/..') . '/src/Core/Bootstrap/Bootstrap.php';

    //PHP7 only
    //$autoloadPath = dirname(__DIR__, 2) . '/src/Core/Bootstrap/Bootstrap.php';

    $autoloadPath = dirname(__DIR__) . '/src/Core/Bootstrap/Bootstrap.php';

    if (file_exists($autoloadPath)) {

        include_once $autoloadPath;

    } else {

        die('{ Bootstrap.php } not Found in path: ' . $autoloadPath);
    }
} else {

    die('php version is less than the required > 7.0');
}