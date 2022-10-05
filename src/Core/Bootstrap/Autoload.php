<?php

namespace Core\Bootstrap;

/*
 * Autoload на класовете
 *
 */
foreach (glob(APPLICATION_DIR . 'Config/*.php') as $config_file) {

    include_once $config_file;
}

foreach (glob(APPLICATION_DIR . 'Functions/*.php') as $functions) {

    include_once $functions;
}
foreach (glob(SYSTEM_DIR . 'Helpers/*.php') as $helpers) {

    include_once $helpers;
}

include_once ROOT_DIR . 'vendor/autoload.php';

class Autoload
{

    /**
     * Autoload constructor.
     * @param bool $loader
     */

    public function __construct($loader = false)
    {
        if ($loader === true) {

            include_once ROOT_DIR . 'vendor/autoload.php';

        } else {

            spl_autoload_register( array($this, 'loadClass') );
        }

    }

    public function loadClass($class)
    {
        $_dir = [
            SYSTEM_DIR,
            APPLICATION_DIR,
            ROOT_DIR,
            VENDOR_DIR,
        ];

        foreach ($_dir as $dir) {

            $_raw_file = ( $dir . $class . '.php');

            $file = str_replace('\\', DIRECTORY_SEPARATOR, $_raw_file);

            if (file_exists($file)) {

                $file_to_include = $file;
            }
        }

        if ($file_to_include != '' && is_file( $file_to_include )) {

            include_once $file_to_include;

        } else {
            //trigger_error( 'Грешка в [ Autoload.php ] : Не е намерен файл { ' . $file. ' }', E_USER_ERROR );
            throw new \Exception('Грешка в [ Autoload.php ] : Не е намерен файл { ' . $file. ' }' , 500);
        }
    }
}
