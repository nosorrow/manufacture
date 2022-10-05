<?php

include_once dirname(__DIR__) . '/Bootstrap/constants.php';
include_once dirname(__DIR__) . '/Bootstrap/includes.php';
include_once realpath(dirname(__DIR__) . '/..') .DIRECTORY_SEPARATOR.'vendor/autoload.php';

function generate(){
 //   echo crypt_generate_key();

    $key = crypt_generate_key();
    $file = file_get_contents(CONFIG_DIR . 'config.php');

    $pattern = "#(\'key\'(?:\s*)=>(?:\s*)[^\s]+)#";

    if(file_put_contents(CONFIG_DIR . 'config.php',
            preg_replace($pattern, "'key' => '" . $key . "', ", $file))){

        echo "\033[32m" . 'Successfully create key: ' . "\e[39m" . PHP_EOL;
        echo "\033[32m" . $key . "\e[39m";
    }

}
