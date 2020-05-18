---
title: Request
layout: layout.hbs
---
Request
-----
##### Basic usage

```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Request;

class PostController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    
    public function login(Request $request)
    {
        $user = $request->post('user');
        $pass = $request->post('password');
        
        // .... validate user & pass
    }

}
```
##### Request methods  

поддържани са следните http методи 'GET', 'POST','PUT', 'PATCH', 'DELETE'  

```php
<?php

$request->input($key);
$request->post($key);
$request->get($key);
$request->put($key);
$request->patch($key);
$request->delete($key);
$request->cookie($name);
$request->delete_cookie($name);
$request->set_cookie($name, $value = '', $expire = 3600, $path = '/', $domain = '', $secure = false, $httponly = true);
$request->method();

?>
```

PUT, PATCH, DELETE методи които не се поддържат от браузъра  използвайте hidden input:

 ```html
<input type="hidden" name="_method" value="PUT"> 
 ```
 
или helper -> method_field($method)  

```php
<?= method_field('PUT')?>

<!-- <input type="hidden" name="_method" value="PUT"> -->
```

##### Нормализация на входящите данни

```php
<?php
$request->input($key, 'htmlspecialchars|strip_interval|');
```
налични филтри:

```Textfile
string | int | float |double | bool | trim | addslashes | htmlspecialchars |
htmlentities | strip_tags | strip_interval |
html_entity_decode | urlencode | xss
```
