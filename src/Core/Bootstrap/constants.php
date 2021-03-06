<?php
/**
 * Define Path Constant
 */
define('VERSION', '1.1');
define('APPLICATION_DIR', dirname(__DIR__, 2) .DIRECTORY_SEPARATOR .'App'.DIRECTORY_SEPARATOR);
define('VIEW_DIR', APPLICATION_DIR . 'Views' . DIRECTORY_SEPARATOR);
define('SYSTEM_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__DIR__, 2) .DIRECTORY_SEPARATOR);
define('VENDOR_DIR', ROOT_DIR . 'vendor'. DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', basename(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR);
define('STORAGE_DIR', APPLICATION_DIR . 'storage' . DIRECTORY_SEPARATOR);
define('APP_STORAGE', APPLICATION_DIR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('TEMP_DIR', APPLICATION_DIR . 'storage' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR);
define('LOG_DIR', STORAGE_DIR . 'Logs' . DIRECTORY_SEPARATOR);
define('CONFIG_DIR', APPLICATION_DIR . 'Config' . DIRECTORY_SEPARATOR);
define('LOCALE_DIR', APPLICATION_DIR . 'locale' . DIRECTORY_SEPARATOR);
define('RESOURCES_DIR', APPLICATION_DIR . 'resources' . DIRECTORY_SEPARATOR);
