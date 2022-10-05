---
title: Xlsx2Mysql Iporter
layout: doc_import.hbs
---
###### <b>Конфигуриране връзка към базата данни</b>

Намира се във файла <code>config.php</code>
```php
<?php
return [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'secret',
    'dbname' => 'testdb',

];
```
Примерната база данни е във файла<code>heavy_metal.sql</code>  

###### <b>Четене и импорт на данни от xlsx към Mysql</b>

Съдържанието на примерен файл за импортиране <code>xls2mysql.php</code>

```php
<?php
    use Importer\Importer;
    
    require_once 'src/vendor/autoload.php';
    $config = include_once 'config.php';
    
    $importer = new Importer($config);
    
    $importer->file = 'Files/test.xls';
    $importer->table = 'heavy_metal';
    
    $importer->fields = [
        'category', 'steel', 'steel_size', 'qty'
    ];
    
    $importer->import();
    
    $result = $importer->result;
    
    print_r($result);
    
    /*
     * $result е масив , който може да се използва за 
     * системни съобщния информиращи потребителя
     * Array
       (
           [time] => 0.24
           [read_rows] => 461
           [imported_rows] => 461
           [not_imported] => 0
           [error] => 0
       )
     */
    
    echo "Успешно са импортирани {$result['imported_rows']} от {$result['read_rows']} прочетени !";
    
```

###### Свойствата на класа Importer
След като създадем инстанция на класът <code>$importer = new Importer($config)</code> имаме достъп до следните
свойства (променливи), на които трябва да дадм стойности     
    
* <code>$importer->file</code> = Укажете пътя до файла, от който ще се четат данните за прехвърляне
* <code>$importer->table</code> = Укажете таблицата от DB , в която ще се записва
* <code>$importer->fields</code> = Масив с имената на полетата на Mysql таблицата  

Масивът в <code>$importer->fields</code> може да не съдържа всички полета (fields) на в таблицата, 
тоест може да импортирате само това което желаете - примерно 
<code>$importer->fields = ['category', 'steel']; </code> това ще импортира в DB само стойностите на колоните 
(A) и (B) от <code>xlsx</code> файлът.  
<b>ВАЖНО!</b> Подреждането на имената на полетата в масива е от особено значение! В този случай данните от
колоните в екселовата таблица се импортират в следната последователност :  

<code>[col A] => category | [col B] => steel | [col C] => steel_size | [col D] => qty</code>

```php
<?php
$importer->file = 'Files/test.xls';
$importer->table = 'heavy_metal';
$importer->fields = [
    'category', 'steel', 'steel_size', 'qty'
];
```

###### \# Забележка!
Най - добре работи с файлове <code>xlsx</code> (MS Excel 10 +);
Чете и <code>xls</code> файлове на по ниски версии на ексел но е препоръчително и в двата случая клетките
да бъдат форматирани като <code>General</code> - десен бутон => format cells => General  

###### \# Сервизно триене и нулиране на таблицата в Базата данни!
Класът <code>Importer</code> използва SQL <code>(DELETE FROM table)</code> клауза за триене на таблицата преди всеки нов 
импорт на данни. Двете заявки DELETE И INSERT се изпълняват като транзакция (MySql Transaction), тоест ако нещо се счупи 
се извършва така наречения <code>rollback</code> или казано процес на възстановяване на база данни към 
предишно състояние. <code>DELETE</code> обаче не нулира PRIMARY KEY на таблицата ! Това се постига с <code>TRUNCATE</code>  
чрез методът <code>Importer::truncate()</code>. Връща 0 при успешно изтриване или грешка (PDOException)  
  
Съдържанието на примерен файл:

```php
<?php
use Importer\Importer;

require_once 'src/vendor/autoload.php';
$config = include_once 'config.php';

$importer = new Importer($config);
$importer->file = 'Files/test.xls';
$importer->table = 'heavy_metal';

// Забърсва таблицата и нулира PRIMARY KEY
if($importer->truncate() == 0){
    echo 'Успешно изтриване на данните';
}
```

##### Използване на Helper -> xls2mysql
Може да изполвате и функцията <code>xls2mysql(array config, string path-to-file, string dbtablename , array fields)</code>

```php
<?php

require_once 'src/vendor/autoload.php';
$config = include_once 'config.php';

$file = 'XlsFiles/test.xls';
$table = 'heavy_metal';
$fields = [
    'category', 'steel', 'steel_size', 'qty'
];

$result = xls2mysql($config, $file, $table, $fields);

echo "Успешно са импортирани {$result['imported_rows']} от {$result['read_rows']} прочетени !";
```