<?php
if (version_compare(PHP_VERSION, '7.0') < 0) {
    die('php version is less than the required 7.0 or more');

}

// if src is outside  public dir
//$autoloadPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/src/Core/Bootstrap/Bootstrap.php';
//$autoloadPath =  (dirname(__DIR__) . '/..') . '/src/Core/Bootstrap/Bootstrap.php';

//PHP7 only
//$autoloadPath = dirname(__DIR__, 2) . '/src/Core/Bootstrap/Bootstrap.php';

$bootstrapPath = dirname(__DIR__) . '/src/Core/Bootstrap/Bootstrap.php';

if (file_exists($bootstrapPath)) {
    include_once $bootstrapPath;

} else {
    die('{ Bootstrap.php } not Found in path: ' . $bootstrapPath);
}
