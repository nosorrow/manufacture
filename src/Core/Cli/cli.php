<?php
/**
 * Use terminal command: php cmd
 *
 * clear:views
 * clear:sessions
 * make:controller      Create a new controller class
 * make:model           Create a new model class
 * make:middleware
 * key:generate
 */
include_once 'create.php';
include_once 'clear.php';
include_once 'key.php';

if ($argc < 2) {
    trigger_error(sprintf("\e[31m" . "Too few arguments to CLI, %d passed. ", $argc - 1), E_USER_ERROR);
}

// "make:controller"
if (strpos($argv[1], ':') !== false) {
    // array = ['make', 'controller]
    $commands = explode(':', $argv[1]);
} else {
    // string
    $commands = $argv[1];
}

if (is_array($commands) && isset($argv[2])) {
    switch ($commands[0]) {
        case 'make':
            // make:controller TestController
            create($commands[1], $argv[2]);
            break;
    }

} elseif (is_array($commands)) {
    // clear:view
    switch ($commands[0]) {
        case 'clear':
            // clear:views
            // clear:sessions
            clear($commands[1]);
            break;

        case 'key':
            // key:generate
            $commands[1]();
            break;

    }
}
