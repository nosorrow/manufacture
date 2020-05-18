---
title: Helpers
layout: layout.hbs
---
## Helpers

##### System
* app
* isClosure
* config

##### Array helpers & Data manipulations
* array_collapse
* array_has
* array_only
* array_pluck
* array_where
* array_random
* data_get
* data_set
* tap
* Arrays::first()
* Arrays::last()
* collect
* See all laravel array helpers

##### URL / Redirect / Routing
* site_url
* assets_url
* route
* redirect

##### Request Helpers
* request_post
* request_get
* set_cookie
* get_cookie
* xss_clean

##### Validation Helpers
* oldd alias oldValue
* has_error
* validation_error

##### Form Helpers
* csrf alias csrf_field
* method_field
##### Render View  Helpers
* setLayout
* view
* esc = escape html
* resc = recursive escape html
* escd = htmlspecialchars_decode
* partial
##### Directory helpers
* rrmdir

##### Crypt
* api_key // generate base64 key;
* passwordHash

##### Html
* alert
* highlight_code

##### i18n Localization
* get_locale_lang
* tr_
* tn_

##### Session
* sessionData
* flash
* session_push
* session_pull
* session_delete

##### String
* create_slug

##### Text
* word_limit
* highlight_code

##### URL
* url

will return instance of: Core\Libs\Url::class

### Description
array_has - Проверява дали стойнстта съществува използвайки "dot" notation.

array_pluck - Извлича всички стойности за даден ключ от масив 
            (function retrieves all of the values for a given key from an array)
```

$array = [
          ['developer' => ['id' => 1, 'name' => 'Taylor']],
           ['developer' => ['id' => 2, 'name' => 'Abigail']],
        ];

       $names = array_pluck($array, 'developer.name', 'developer.id');

     /*  array:2 [▼
         1 => "Taylor"
         2 => "Abigail"
       ] */

```

array_where - Филтрира масива използвайки callback . (Filter the array using the given callback.)

```

        $filtered = array_where($a, function ($value, $key){
                  return $value > 20;
            });

```
