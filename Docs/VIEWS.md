---
title: Views
layout: layout.hbs
---
### VIEWS

##### Manufacture native php view engine

```php
<?php 
class DashboardController extends Controller
{
    public function login()
    {
        $data['title'] = "The site title";
        return view('admin/form', $data);
    }
}
```

Using dot notation in View name

```php
<?php
class DashboardController extends Controller
{
    
    public function DashboardLogin()
    {
        $data['title'] = "The site title";
        return view('admin.form', $data);
    }
}
```

Template file:

```php

    <h1><?= $title ?></h1>
    <!-- Or -->
    <h1><?= $this->title ?></h1>

```

Escape variable with helpers
```php

    <h1><?= esc($title) ?></h1>
    <h1><?= esc($title, 'xss|strip_interval') ?></h1>

```

Escape variable 
```php

    <h1><?= $this->e($title) ?></h1>
    <h1><?= $this->e($title, 'xss|strip_interval') ?></h1>

```

##### Blade tempalte
In config.php
change 'template_engine' = 'blade'

See laravel.com how to use blade template [blade doc](https://laravel.com/docs/5.8/blade)

How make blade directive:

```php
<?php

    View::blade()->directive('datetime', function ($expression) {
          return "<?php echo with({$expression})->format('Y-m-D h:i:s'); ?>";
        });

```
in tempalte file use

```php

<?php $obj = new DateTime() ;?>

@datetime($obj)

```