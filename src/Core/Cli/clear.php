<?php

include_once dirname(__DIR__) . '/Bootstrap/constants.php';
include_once dirname(__DIR__) . '/Bootstrap/includes.php';
include_once realpath(dirname(__DIR__) . '/..') .DIRECTORY_SEPARATOR.'vendor/autoload.php';

function clear($dir=''){
    switch ($dir){
        case 'views':
            $path = APP_STORAGE . 'views';

            if(!erdir($path)){
                trigger_error("\e[31m" . 'Delete Error' . "\e[39m", E_USER_ERROR);
            } else {
                echo "\033[32m" . "Delete complete successfully" . "\e[39m";
            }
            break;

        case 'sessions':
            $path = TEMP_DIR;

            if(!erdir($path)){
                trigger_error("\e[31m" . 'Delete Error'. "\e[39m", E_USER_ERROR);
            } else {
                echo "\e[32m" . "Delete complete successfully" . "\e[39m";
            }
            break;
    }

}
