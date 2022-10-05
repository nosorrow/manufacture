---
title: Crypt
layout: layout.hbs
---
CRYPT
------
##### Configuration
Преди да започнете работа с манифактурата и за да използвате криптирането  
трябва да зададете 'key' => option във config/config.php  
За да бъде валиден ключът може да използвате terminal command: 
```php
    php cmd key:generate
```

Правилна стойност се генерира от функцията ***crypt_generate_key()*** :
```
[
    'key' => 'FAw/UOnT942TQrHKHxL0NoAJk/Ng1Vtg2ns8+tXI6pA=',
    'cipher' => 'AES-256-CBC'
]
```
генерирана от: 
```php
<?php

$key = base64_encode(random_bytes($cipher === 'AES-128-CBC' ? 16 : 32));

```

##### Crypt facade

static methods:

* encrypt($value, $serialize = true)
* decript($payload, $unserialize = true)
* encryptString($value)
* decryptString($payload)

```php
<?php

use Core\Libs\Support\Facades\Crypt;

$array = [1,2,3,4,5];

$crypt = Crypt::encrypt($array);
var_dump($crypt);

$decript = Crypt::decrypt($crypt);
var_dump($decript);

```

Може да използвате helpers ***encrypt($value)*** и ***decrypt($crypted)***

```php
<?php
    $crypted = encrypt($value);
    
    $decrypted = decrypt($crypted);
```
