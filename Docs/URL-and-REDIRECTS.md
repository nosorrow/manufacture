---
title: Url & redirects
layout: layout.hbs
---
Route helpers & redirects
-----
##### Генериране на URLs
След като сме създали име на маршрута в <code>route.php</code> може да използваме това име  
за генериране на URL адреси или редиректване с помоща на функцията <code> route() и route_url()</code>  

* function route($routeName, $params = [], $request_method = null);
* function route_url($routeName, $params = [], $request_method = null)

```php
<?php 
// маршрут с име в route.php
//Router::get('page/{id}',['ErrorPage@show','name' => 'error']);

$uri = route('error', [404]); //ще върне URI "page/404"
$url = site_url($uri); // https://site.com/page/404

echo route_url('error', ['id'=>404]); //  https://site.com/page/404
```

##### Пример с подаване на  аргументи в масив  

```php
<?php 
// маршрут с име в route.php
Router::get('user/{id}/comment/{commentId}', ['Controller@method', 'name'=>'user_comments']);

// ще подреди правилно аргументите и ще върне "user/10/comment/25"
$uri = route('user_comments', ['id'=>10, 'commentId'=>25]);

Router::get('user/{id}/comment/{commentId?}', ['Controller@method', 'name'=>'user_comments']);
// ще подреди правилно аргументите и ще върне "user/10/comment" тъй като има опционален параметър
$uri = route('user_comments', ['id'=>10]);
```

##### Redirect helper

##### Използваме helper redirect()
 
```php
 <?php 
// Router::get('user/{id}/comment/{commentId}', ['Controller@method', 'name'=>'user_comments']);
// Router::any('signup', ['RegistrationController@signupForm', 'signup']);

$uri = route('user_comments', ['id'=>10, 'commentId'=>25]);
redirect()->to($uri);
redirect($uri);

// или използваме на route name
redirect()->route('user_comments', ['id'=>10, 'commentId'=>25]);
// редирект към външен url адрес
redirect()->away('https://google.com');
```

##### Използваме redirect() с flash message

```php
 <?php 
redirect()->to('uri')->with('success', 'All is Ok!');
```
Пример на показване на флаш съобщението във view с blade template:  

<?php
$str = <<<'EOF'
<pre class=" language-php"><code class=" language-php">
@if</span>($msg = flash</span>('success'))
  {{ $msg }}
@endif;
</code></pre>
EOF;
echo ($str);
?>
