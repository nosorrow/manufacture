---
title: Команди от конзолата (Command line)
layout: layout.hbs
---

Команди от конзолата (Command line)
-----

##### Създаване на Контролер (Controller)

```php
php cmd make:controller SendMailController
```

Ще създаде файл <code>SendMailController.php</code> в директория <code>src/App/Controllers</code>

##### Създаване на Контролер (Controller)

```php
php cmd make:model <modelname>
```
Ще създаде файл <code>modelname.php</code> в директория <code>src/App/Models</code>

##### Трие кеша и файловете на сесиите

Темплейт системата Blade създава кеш файлове в директория <code>src/App/storage/app/views</code>, които може да
изтрием от конзолата с команда:

```php
php cmd clear:views
```

Файловете на сесиите от директория <code>src/App/storage/tmp</code>


```php
php cmd clear:sessions
```

##### Нов криптиращ ключ

Генерира нов валиден криптиращ ключ и го записва в <code>config.php</code>

```php
php cmd key:generate
```
