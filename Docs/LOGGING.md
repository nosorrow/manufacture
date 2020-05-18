---
title: Logging
layout: layout.hbs
---
Logging
-------
Използва Monolog чрез Core\Libs\Logger adapter

Настройките са в App\Config\log.php

```
[
    'system'=>[
		'handler' => Monolog\Handler\StreamHandler::class,
		'formatter' => Monolog\Formatter\HtmlFormatter::class,
		'path' => LOG_DIR . 'system.html',
	]
]	
```
### Използване с класът Logger

```php

<?php

class PostController extends Controller
{
    public $logger;

    public function __construct()
    {
        parent::__construct();

        $logger = new Logger('system');
        $this->logger = $logger->getLogger();
        
    }
    
    public function store()
    {
        // do .......
        // if Ok
        $this->logger->info('The post is stored in database');
        // else 
        $this->logger->error('Data base error ...!');
    }
        
}

```

### Използване с Fasade Log
Дефинирани са следните нива на лога : emergency, alert, critical, error, warning, notice, info and debug 

```php
Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
```
$message ще бъде записан по подразбиране в app/Logs/manufacture.log
```
Log::channel('logMessage', $file)->alert($message);
```
ще запише $message с cannel = logMessage във файл app/Logs/$file

##### пример:
```php
<?php
namespace App\Controllers;

use Core\Controller;
use Core\Libs\Support\Facades\Log;

class TestController extends Controller
{
    public $logger;

    public function __construct()
    {
        parent::__construct();
        
        Log::info('The Facade logging chanel');
        
        // Log wth specific channel
        // the logging file is default -app/Logs/log.log
        Log::channel('infos')->info('The Facade logging');
    }
}
```

 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void log($level, string $message, array $context = [])
 * @package Core\Libs\Utils\Facades
 

## Log Levels

[Monolog in Github](https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md).

Monolog supports the logging levels described by [RFC 5424](http://tools.ietf.org/html/rfc5424).

- **DEBUG** (100): Detailed debug information.

- **INFO** (200): Interesting events. Examples: User logs in, SQL logs.

- **NOTICE** (250): Normal but significant events.

- **WARNING** (300): Exceptional occurrences that are not errors. Examples:
  Use of deprecated APIs, poor use of an API, undesirable things that are not
  necessarily wrong.

- **ERROR** (400): Runtime errors that do not require immediate action but
  should typically be logged and monitored.

- **CRITICAL** (500): Critical conditions. Example: Application component
  unavailable, unexpected exception.

- **ALERT** (550): Action must be taken immediately. Example: Entire website
  down, database unavailable, etc. This should trigger the SMS alerts and wake
  you up.

- **EMERGENCY** (600): Emergency: system is unusable.