---
title: Url Class
layout: layout.hbs
---
Url Generator
-----

##### URL Class
Целта на класа е да генерира правилни Url стрингове. 

```php
<?php
namespace App\Controllers;

use Core\Controller;
use Core\Libs\Url;

class TestUrlController extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }
    
    public function UrlExample(Url $url)
    {
        
        // Взима настоящия URL без query string
        echo $url->current();
        // Взима настоящия URL с query string
        echo $url->full();
        // Взима  URL от предишния request
        echo $url->previous();
        // генерира базови URL + зададен път
        echo $url->getSiteUrl();
        echo $url->getSiteUrl("post/45");
        // Взима  URL от предишния request
        dump($url->getReferer());
     
        dump($url->request()->path());
        
        dump($url->request()->query());      
    }
    
}
```

##### Url Helpers:  
* url(string $uri = null)
* site_url(string $uri)
* assets_url(string $uri)

```php
<?php 

// https://site.com/post/2
echo site_url('post/2'); // псевдоним на url()

// https://site.com/assets/css/bootstrap
echo assets_url('css/bootstrap');
//return true
dump(valid_url($url));

// https://site.com/post/2
echo url('post/2');

echo url()->current();
echo url()->full();
echo url()->previous();
echo url()->request()->path();
echo url()->request()->query();
```

##### Url Facade:  

```php
<?php
use Core\Libs\Support\Facades\Url;

class TestUrl
{
    public function getUrl()
    {
       echo Url::current();
       echo Url::full();
       echo Url::previous();
       echo Url::request()->path();
       echo Url::request()->query();
    }
}
```
