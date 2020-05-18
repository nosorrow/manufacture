<?php

include_once dirname(__DIR__) . '/Bootstrap/constants.php';
include_once dirname(__DIR__) . '/Bootstrap/includes.php';
include_once realpath(dirname(__DIR__) . '/..') .DIRECTORY_SEPARATOR.'vendor/autoload.php';

function clear($dir=''){
    switch ($dir){
        case 'views':
            $path = APP_STORAGE . 'views';

            if(!erdir($path)){
                trigger_error('Delete Error', E_USER_ERROR);
            } else {
                echo "Delete complete successfully";
            }
            break;

        case 'sessions':
            $path = TEMP_DIR;

            if(!erdir($path)){
                trigger_error('Delete Error', E_USER_ERROR);
            } else {
                echo "Delete complete successfully";
            }
            break;
    }

}
