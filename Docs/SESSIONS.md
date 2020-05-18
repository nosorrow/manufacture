---
title: Sessions
layout: layout.hbs
---
Sessions
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
        // .... validate user & pass
        $request->session->store('auth', 'loged');
    }
    
    public function adminArea(Request $request)
    {
        $auth = $request->session->getData('auth');
        
        // $auth === 'logged'
        // ... do somthing
    }
}
    
```
##### Session methods

```php
<?php
$this->session->store($key, $value);
$this->session->set($key, $value); // alias of session->srore(...);
$this->session->getData($key);
$this->session->get_all();
$this->session->all(); // alias of getAll();
$this->session->has($key);
$this->session->push($key, $value);
$this->session->pull($key, $value);
$this->session->setFlash($key, $value);
$this->session->getFlash($key);
$this->session->delete($key);
$this->session->destroy();

```
##### Sesion helpers

```php
<?php
/* 
 * Ako $name е масив или имаме стойност в value
 * то ще слагаме сеиия ($nama=>$value),
 * иначе вземам сесия с име $name;
 */
sessionData($key, $data);
// връща сесия или всички сесии
$session = sessionData($key);
$session = sessionData();
// pull push
session_push($key, $value);
session_pull($key, $value);

// Ако съществува връща true
session_has($key);

// връща флаш сесия
$error = flash($name);
session_delete($key);
```

##### Session helper свързани с валидация на данни и View
* errors() - връща обект със съобещния от валидатора.
```php
<?= $errors->first('email') ?>
```

##### Показваме грешките във View

Всички грешки от валидирането на данни се записват във флаш сесия!

```php
<?php
    if ($errors->has()) {
        foreach ($errors->all() as $error) {
            echo '<li>' . $error . '</li>';
        }
    }; 
```
##### old($str) value helper
Функцията връща старите попълнени от потребителя данни в полето (input) ако валидацията е неуспешна.
```php
    <input type='text' name='email' value="<?= old('email') ?>"
```
