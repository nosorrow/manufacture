---
title: CONFIG
layout: layout.hbs
---
##### Методът getConfigFromFile
За да вземем настройка от конфигурационен файл използваме статичния метод <code>Config::getConfigFromFile($key, $domain)</code>
или helper <code>config(string $config_key, string domain)</code> , където  

* <b>$key</b> = ключ от конфигурационния масив (може да се използва dot notation)  
* <b>$domain</b> = името на домейна отговаря на файл от директория <code>App/Config</code>

```php
<?php
    use \Core\Libs\Config;
    
    $databasename = Config::getConfigFromFile('connections.mysql.database', 'database');
    
    // or with Getter;
    $databasename = Config::get('connections.mysql.database', 'database');
    // or with helper
    $databasename = config('connections.mysql.database', 'database');
    
    /* От файлът App/Config/database.php ще вземе конфигурацията от масивът
     * [connections][mysql][database] => 'testDatabaseName' 
    */
```
