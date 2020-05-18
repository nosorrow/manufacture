---
title: Router
layout: layout.hbs
---
## Router
-----

##### Как да започнем
 
Добавяме маршрутите (routes) в <code>App/routes.php</code> 
Методите на класът Router са get, post, head, put, delete и отговарят на
съответните Http методи. Всеки метод получава два аргумента:
* съответния маршрут от вида - <code>"маршрут", "маршрут/{аргумент}", "маршрут/{аргумент?}"</code>
* Масив с елменти: <code>['class@action', 'name'=>'route-name']</code> като 
елемента 'name' е незадължителен !  

```php
 Router::get('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::post('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::head('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::put('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::patch('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::delete('route/{id}', ['class@action', 'name'=>'route-name']);
 Router::get('post/{id}/view', ['class@action', 'name'=>'route-name']);
```

##### Именуване на маршрутите (routes) 
Не е задължително , но е препоръчително да се дават имена на маршрути с цел по-лесно
поддържане на проекта ако се наложи промяна в URL адресите. Пример:  

Създаваме маршрут
```php
Router::get('programing/posts', ['PostsController@postsPrograming', 'name'=>'computer_programing']);
```
Създаваме хиперлинк
```php
<a href="<?= site_url('programing/posts') ?>">Всички статии</а>
```
В горния пример <code>site_url</code> ще генерира Url от вида <code>http://mysite.com/programing/posts</code>
Ако се наложи промяна на този адрес примерно на <code>http://mysite.com/programing/posts</code> то ще трябва да намерим 
в кода всички адреси генерирани от <code>site_url</code> и да ги променим ръчно , което е твърде неудобно!
Решението е да използваме функцията <code>route_url(string $route_name)</code> , която получава като аргумент  името на маршрута.

```php
<a href="<?= route_url('computer_programing') ?>">Всички статии</а>
```

##### Генериране на  URI стринг от Named Route 
Ако посоченият маршрут дефинира параметри, можете да предадете масив с параметри
като втори аргумент към метода 'route' на класът Router. 
```php
// Примерен маршрут
Router::get('route/{lang}/post/{slug}', ['Class@action', 'name'=>'lang-fr']);
// генерира URI стринг: route/fr/post/your-post-slug
$route = $router->route('lang-fr',['lang'=>'fr', 'slug'=>'your-post-slug'])->route;
//със http метод 
$route = $router->route('lang-fr',['lang'=>'fr', 'slug'=>'your-post-slug'], 'GET')->route;
```
Горният пример показва как работи методът <code>Roter::route(string $route_name, array $params)</code>  
В Manufacture използваме helpers за генериране на Uri виж <b>Route helpers & redirects</b>  

Ако искаме да спрем сайта, примерно за профлактика:

```php

    Router::site_down(function(){
        echo 'Сайта е в ремонт!';
     // or  view('maintenance');
        exit;
    });
```

##### Маршрути с повече от един Http метод (Multiple HTTP verbs)

Поддържаните Http методи са <code>'GET', 'POST', 'HEAD', 'PUT', 'PATCH', 'DELETE'</code>

```php
// създава марсшрут за post и get
Router::methods(['post', 'get'],'route/{id}', ['class@action', 'name'=>'route-name']);
// създава марсшрут за всички Http методи
Router::any('route/{id}', ['class@action', 'name'=>'route-name']);
```

##### Callback
```php
 Router::get('route/{slug}/{id}', [function($slug, $id){
    echo $id . ' from ' . $slug;
  }, 'name'=>'callback']);
```

##### Опционални параметри (Optional Parameters)
```php
// Съвпада с  /route/user-post/55 или /route/user-post
 Router::get('route/{slug}/{id?}', ['class@action', 'name'=>'route-name']);
```
##### Регулярни изрази (Regular Expression) - Syntax {param:[regex]}
```php
Router::get('route/{slug}/edit/{post:[a-z]}/{id:[0-9]}', ['class@action', 'name'=>'route-name']);
Router::get('route/{lang:(en|bg)}/{post}/{id:\d+}', ['class@action', 'name'=>'route-name']);

// Съвпада: /route/my-post-name.pdf
Router::get('route/{slug:^\w+((?:\.pdf))$}', ['class@action', 'name'=>'route-name']);
 
// Може да използваме (format=html) вместо регулярен израз | Съвпада: /route/my-post-name.html or /route/my-post-name
Router::get('route/{slug:format=html}, ['class@action', 'name'=>'route-name']);
```

### Как работи рутерът (How Dispatch routes in Front Controller)
```php
include_once 'Router.php';
include_once 'routes.php';

$router = new Router();
$route = $router->dispatch('post/55');

/*
$route now is array like

Array
(
    "action" => "controller@action"
    "name" => "route_name"
    "params" => Array
        (
            "id" => 55
        )
)
*/

```
