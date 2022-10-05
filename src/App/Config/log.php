<?php

return [
	'system'=>[
		'handler' => Monolog\Handler\StreamHandler::class,
		'formatter' => Monolog\Formatter\HtmlFormatter::class,
		'path' => LOG_DIR . 'system.html',
	],

    'log'=>[
        'handler' => Monolog\Handler\StreamHandler::class,
        'formatter' => false,
        'path' => LOG_DIR . 'manufacture.log',
    ],
];
